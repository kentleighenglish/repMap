<?php

namespace RepMap\Services;

use RepMap\Services\ParliamentApi;
use RepMap\Services\ONSApi;

use Illuminate\Support\Facades\DB;
use RepMap\EloquentModels\County;
use RepMap\EloquentModels\Constituency;
use RepMap\EloquentModels\Member;
use RepMap\EloquentModels\Party;

class SyncService {

	public $parliament;
	public $ons;

	public $regions = [
		'S14' => 'S15000001',
		'N06' => 'N07000001',
		'W07' => 'W08000001'
	];

	public function __construct(ParliamentApi $parliament, ONSApi $ons)
	{
		$this->parliament = $parliament;
		$this->ons = $ons;
	}

	public function updateGeojson()
	{
		$url = config('props.geoJsonUrl');
	}

	/**
	 * Fetches and update counties, and saves them to the DB
	 *
	 * @return Array
	 */
	public function updateCounties()
	{
		$results = $this->ons->getActiveCounties();

		$existing = County::all();

		$parsedResults = array_reduce($results, function($arr, $item) use ($existing) {
			if (!$this->_searchCollection($existing, 'cty18cd', $item->gsscode)) {
				$arr[] = [
					'name' => $item->name,
					'cty18cd' => $item->gsscode
				];
			}

			return $arr;
		}, []);

		$counties = County::insert($parsedResults);

		//@todo get counties to save in bulk, only name prop... same for constituents below

		return [
			'success' => !!$counties,
			'count' => count($parsedResults)
		];
	}

	/**
	 * Fetches and update constituents, and saves them to the DB
	 *
	 * @return Array
	 */
	public function updateConstituencies()
	{
		$results = $this->ons->getActiveConstituencies();

		$counties = County::all();
		$existing = Constituency::all();

		$parsedResults = [];


		foreach($results as $item) {
			if(!isset($item->within)) {
				$region = substr($item->gsscode, 0, 3);
				if(isset($this->regions[$region])) {
					$item->within = $this->regions[$region];
				}
			}

			if (!$this->_searchCollection($existing, 'cty16cd', $item->gsscode)) {
				!isset($parsedResults[$item->within]) ? $parsedResults[$item->within] = [] : null;

				$parsedResults[$item->within][] = [
					'name' => $item->name,
					'cty16cd' => $item->gsscode
				];
			}
		};

		$success = true;
		foreach($counties as $county) {
			if (isset($parsedResults[$county->cty18cd])) {
				$saved = $county->constituencies()->createMany($parsedResults[$county->cty18cd]);
				if (!$saved) {
					$success = false;
				}
			}
		}

		return [
			'success' => !!$success,
			'count' => count($parsedResults)
		];
	}

	/**
	 * Fetches and update members, and saves them to the DB
	 *
	 * @return Array
	 */
	public function updateMembers()
	{
		DB::table('members')->delete();

		$results = $this->parliament->getMembers();

		// Fetch All constituencies (used to save relationships later)
		$constituencies	= Constituency::all()->groupBy('cty16cd')->toArray();

		// Init empty arrays
		$parsedResults = [];

		// Fetch active constituencies (with resource ids)
		$resourceConstituencies = $this->parliament->getActiveConstituencies();

		// Get election results based on config
		$electionId = config('props.lastGeneralElectionId');
		$electionResults = $this->parliament->getElectionResults($electionId);

		$missing = [];
		// Empty out arrays
		foreach ($results as $key => $member) {

			if (!isset($parsedResults[$member['party']])) {
				$parsedResults[$member['party']] = [];
			}

			foreach ($resourceConstituencies as $c) {
				if ($member['constituencyResource'] === $c['resourceId']) {
					$constituency = isset($constituencies[$c['gsscode']][0]) ? $constituencies[$c['gsscode']][0] : null;

					if ($constituency) {
						$member['constituency_id'] = $constituency['id'];
						break;
					}

				}
			}

			if (!isset($member['constituency_id'])) {
				unset($results[$key]);
				continue;
			}


			foreach ($electionResults as $key => &$result) {
				// Check whether constituency matches current members constituency and winning party
				if (
					$result['constituency'] === $member['constituencyResource'] &&
					$member['party'] === $result['result']
				) {
					if ($this->_searchForMember($member['fullname'], $result['candidates'])) {
						$member['elected'] = true;
						unset($electionResults[$key]);
					}
					break;
				} elseif ($result['constituency'] === $member['constituencyResource']) {
					if ($this->_searchForMember($member['fullname'], $result['candidates'])) {
						$member['elected'] = true;
						unset($electionResults[$key]);
					}
				}
			}

			if (!isset($member['elected'])) {
				$member['elected'] = false;
			}

			$parsedResults[$member['party']][] = array_only($member, [ 'fullname', 'webpage', 'twitter', 'constituency_id', 'elected' ]);

		}

		$partyMembers = $this->updateParties($parsedResults);

		$newMembers = [];
		foreach($partyMembers as $partyId => $members) {
			foreach($members as $member) {
				$member['party_id'] = $partyId;
				$newMembers[] = $member;
			}
		}

		$success = Member::insert($newMembers);

		return [
			'success' => !!$success,
			'count' => count($newMembers)
		];
	}

	public function updateParties($parties)
	{
		$existing = Party::all();
		$membersResponse = [];

		foreach($existing as $existingParty) {
			if(!isset($parties[$existingParty->name]) || count($parties[$existingParty->name]) === 0) {
				$existingParty->delete();
				unset($parties[$existingParty->name]);
				continue;
			}

			if (array_search($existingParty->name, array_keys($parties)) !== false) {
				$membersResponse[$existingParty->id] = $parties[$existingParty->name];
				unset($parties[$existingParty->name]);
			}

			$existingParty->members = count($membersResponse[$existingParty->id]);
		}

		foreach($parties as $name => $members) {
			if(count($members) === 0) {
				continue;
			}

			$newParty = new Party([
				'name' => $name,
				'members' => count($members)
			]);

			$newParty->save();

			$membersResponse[$newParty->id] = $members;
		}

		return $membersResponse;
	}

	private function _searchCollection($collection, $property, $value)
	{
		foreach($collection as $item) {
			if(is_object($item)) {
				if (isset($item->$property) && $item->$property === $value) {
					return true;
				}
			} else {
				if (isset($item[$property]) && $item[$property] === $value) {
					return true;
				}
			}
		}
		return false;
	}

	private function _searchForMember($search, $list)
	{
		$results = [];
		foreach($list as $str) {
			preg_match_all("/\w*/", $search, $searchExplode);
			preg_match_all("/[A-z]*/", $str, $strExplode);


			$or = implode("|", array_filter($searchExplode[0]));

			preg_match_all("/${or}/", implode(" ", array_filter($strExplode[0])), $matches);

			$results[] = [
				'weight' => count($matches[0]),
				'value' => $str
			];
		}

		usort($results, function($a, $b) {
			return $a['weight'] < $b['weight'];
		});

		return $results[0]['weight'] > 0;
	}

}

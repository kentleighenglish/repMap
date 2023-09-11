<?php

namespace RepMap\Services;

use RepMap\Services\AbstractApi;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;

class ParliamentApi extends AbstractApi {

	public $oldHost = 'http://data.parliament.uk/';

	public function __construct()
	{
		$config = config('services.universe');

		$this->config['host'] = 'http://eldaddp.azurewebsites.net/';

		$this->config['prefix'] = '';

		$this->config['jsonEncode'] = true;

		$this->config['headers'] = [
			'Content-Type' => 'application/json'
		];
	}


	/**
	 * Fetches the active constituencies from the Parliamentary API
	 *
	 * @return Array
	 */
	public function getActiveConstituencies()
	{
		return $this->_handleResponse($this->get('constituencies.json?_pageSize=650&humanIndexable&exists-endedDate=false'), function($data) {
			$items = $data['result']->items;

			return array_reduce($items, function($arr, $item) {
				$arr[] = [
					'name' => $item->label->_value,
					'resourceId' => $this->_stripResourceUrl($item->_about),
					'gsscode' => $item->gssCode
				];

				return $arr;
			});
		});
	}


	/**
	 * Fetches members of parliament
	 *
	 * @return Array
	 */
	public function getMembers()
	{
		return $this->_handleResponse($this->get('members.json?exists-constituency=true&exists-deathDate=false&_pageSize=3000'), function($data) {
			$items = $data['result']->items;

			return array_reduce($items, function($arr, $item) {
				$arr[] = [
					'fullname' => $item->givenName->_value.' '.$item->familyName->_value,
					'constituency' => $item->constituency->label->_value,
					'constituencyResource' => $this->_stripResourceUrl($item->constituency->_about),
					'party' => $item->party->_value,
					'webpage' => isset($item->homePage) ? $item->homePage : null,
					'twitter' => isset($item->twitter->_value) ? $item->twitter->_value : null
				];

				return $arr;
			});
		});
	}

	public function getResource($id)
	{
		return $this->_handleResponse($this->get("resource/${id}.json"), function($data) {
			$result = $data['result'];

			return $result;
		});
	}

	public function getElections()
	{

	}

	public function getElectionResults($id)
	{
		return $this->_handleResponse($this->get("electionresults.json?electionId=${id}&_pageSize=650&_properties=candidate.fullName"), function($data) {
			$items = $data['result']->items;

			return array_reduce($items, function($arr, $item) {
				$arr[] = [
					'constituency' => $this->_stripResourceUrl($item->constituency->_about),
					'result' => $this->_parseElectionResult($item->resultOfElection),
					'turnout' => $item->turnout,
					'electorate' => $item->electorate,
					'candidates' => array_reduce($item->candidate, function($arr, $candidate) {
						$arr[] = $candidate->fullName->_value;
						return $arr;
					}, [])
				];

				return $arr;
			});
		});
	}


	/**
	 * Handles a typical response from Guzzle
	 *
	 * @param Array $response
	 * @param Function $callback
	 * @return Object|Array|Boolean
	 */
	private function _handleResponse($response, $callback)
	{
		// Log::debug('API Response: '.json_encode($response));
		if ($response['statusCode'] === 200) {
			return $callback($response['data']);
		} else {
			return false;
		}
	}

	private function _stripResourceUrl($url)
	{
		return str_replace($this->oldHost.'resources/', '', $url);
	}

	private function _parseElectionResult($resultStr)
	{
		$str = explode(" ", $resultStr)[0];
		$match = null;
		foreach (config('props.partyAliases') as $key => $aliases) {
			if (array_search($str, $aliases) !== false || $key === $str) {
				$match = $key;
			};
		}

		if(!$match) {
			Log::debug("Could not find party alias: ".$resultStr);
		}

		return $match;
	}

}

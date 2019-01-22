<?php

namespace RepMap\Services;

use RepMap\Services\ParliamentApi;
use RepMap\Services\ONSApi;

use RepMap\EloquentModels\County;
use RepMap\EloquentModels\Constituency;

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

		//@todo get counties to save in bulk, only name prop... same for constituents below

		return [
			'success' => !!$success,
			'count' => count($parsedResults)
		];
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

}

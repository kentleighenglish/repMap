<?php

namespace RepMap\Services;

use RepMap\Services\AbstractApi;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;

class ONSApi extends AbstractApi {

	public $propMap = [
		"constituencies" => [
			"http://publishmydata.com/def/ontology/spatial/within" => [
				"newKey" => "within",
				"propKey" => "@id",
				"resource" => true
			],
			"http://www.w3.org/2004/02/skos/core#notation" => [
				"newKey" => "cty16cd",
				"propKey" => "@value",
				"resource" => false
			],
			"http://statistics.data.gov.uk/def/statistical-geography#officialname" => [
				"newKey" => "name",
				"propKey" => "@value",
				"resource" => false
			]
		]
	];

	public function __construct()
	{
		$config = config('services.universe');

		$this->config['host'] = 'http://statistics.data.gov.uk/';

		$this->config['jsonEncode'] = true;

		$this->config['headers'] = [
			'Content-Type' => 'application/json'
		];
	}

	/**
	 * Fetches all active counties from England, Wales, Scotland, and North Ireland
	 *
	 * @return Array
	 */
	public function getActiveCounties()
	{
		$areas = [
			'E10',
			'N07',
			'S15',
			'W08'
		];

		$results = [];
		foreach ($areas as $a) {
			$url = "area_collection.json?per_page=50&in_collection=".$this->_genResourceUrl("def/geography/collection/${a}");

			$results = array_merge($results, $this->_handleResponse($this->get($url), function($data) {
				return array_map(function($a) { return $this->_mapKeys($a); }, $data);
			}));
		}

		return $results;
	}

	/**
	 * Fetches all active constituencies from England, Wales, Scotland, and North Ireland
	 *
	 * @return Array
	 */
	public function getActiveConstituencies()
	{
		$areas = [
			'E14',
			'N06',
			'S14',
			'W07'
		];

		$results = [];
		foreach ($areas as $a) {
			$url = "area_collection.json?per_page=650&in_collection=".$this->_genResourceUrl("def/geography/collection/${a}");

			$results = array_merge($results, $this->_handleResponse($this->get($url), function($data) {
				return array_map(function($a) { return $this->_mapKeys($a); }, $data);
			}));
		}

		return $results;
	}

	/**
	 * Fetches a candidate from Universe API
	 *
	 * @param String $code
	 * @return \StafflineCep\Http\Resources\Candidate
	 */
	public function getCounty($code)
	{
		return $this->_handleResponse($this->get($this->_genResourceUrl("statistical-geography/${code}")), function($data) {
			var_dump($data);
		});
	}

	// constituencies.json?_pageSize=650&humanIndexable&exists-endedDate=false

	// members.json?exists-constituency=true

	/**
	 * Handles a typical response from Guzzle
	 *
	 * @param Array $response
	 * @param Function $callback
	 * @return Object|Array|Boolean
	 */
	private function _handleResponse($response, $callback)
	{
		Log::debug('API Response: '.json_encode($response));
		if ($response['statusCode'] === 200) {
			return $callback($response['data']);
		} else {
			return false;
		}
	}

	/**
	 * Gets the ID out of a url property
	 *
	 * @param Array $response
	 * @param Function $callback
	 * @return Object|Array|Boolean
	 */
	private function _getIdFromUrl($url)
	{
		return preg_replace("/.*\/id\/[a-z\-]*\//", "", $url);
	}

	/**
	 * Gets the ID out of a url property
	 *
	 * @param Array $response
	 * @param Function $callback
	 * @return Object|Array|Boolean
	 */
	private function _genResourceUrl($path)
	{
		return urlencode($this->config['host'].$path);
	}

	private function _mapKeys($item)
	{
		$response = [];

		foreach($this->propMap as $key => $map) {
			if (isset($item->$key)) {
				$value = $item->$key;
				while (!is_string($value)) {
					if (is_array($value) && count($value)) {
						$value = $value[0];
					} elseif (is_object($value)) {
						$v = $map['propKey'];
						$value = isset($value->$v) ? $value->$v : false;
					} else {
						$value = false;
						break;
					}
				}

				if ($value) {
					$response[$map['newKey']] = $map['resource'] ? $this->_getIdFromUrl($value) : $value;
				}
			}
		}

		return (Object) $response;
	}

}

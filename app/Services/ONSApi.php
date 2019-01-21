<?php

namespace RepMap\Services;

use RepMap\Services\AbstractApi;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;

class ONSApi extends AbstractApi {

	public $props = [
		"within" => "http://publishmydata.com/def/ontology/spatial/within"
	];

	public function __construct()
	{
		$config = config('services.universe');

		$this->config['host'] = 'http://statistics.data.gov.uk/';

		$this->config['prefix'] = '';

		$this->config['jsonEncode'] = true;

		$this->config['headers'] = [
			'Content-Type' => 'application/json'
		];
	}


	/**
	 * Fetches a candidate from Universe API
	 *
	 * @param String $code
	 * @return \StafflineCep\Http\Resources\Candidate
	 */
	public function getCounty($code)
	{
		return $this->_handleResponse($this->get($this->genResourceUrl("statistical-geography/${code}")), function($data) {
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
	private function _getId($url)
	{
		return preg_replace(".*\/id\/[a-z\-]*\/", "", $url);
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

}

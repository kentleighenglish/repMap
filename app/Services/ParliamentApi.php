<?php

namespace RepMap\Services;

use RepMap\Services\AbstractApi;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;

class UniverseApi extends AbstractApi {

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
	 * Fetches a candidate from Universe API
	 *
	 * @param String $id
	 * @return \StafflineCep\Http\Resources\Candidate
	 */
	public function getActiveConstituencies()
	{
		return $this->_handleResponse($this->get('constituencies.json?_pageSize=650&humanIndexable&exists-endedDate=false'), function($data) {
			var_dump($data);
		});
	}

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

}

<?php

namespace RepMap\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class AbstractApi {

	protected $client;

	protected $config;

	public function __construct()
	{
		$this->config = [
			'host' => '',
			'prefix' => '',
			'jsonEncode' => false,
			'headers' => [
				'Content-Type' => 'application/json'
			]
		];
	}

	public function post($url, $data)
	{
		return $this->call($url, 'POST', $data);
	}

	public function multipartPost($url, $data)
	{
		return $this->call($url, 'POST', $data, true);
	}

	public function get($url)
	{
		return $this->call($url, 'GET');
	}

	protected function call($url, $method, $data = null, $multipart = false)
	{
		$host = isset($this->config['host']) ? $this->config['host'] : null;

		if (isset($this->config['prefix'])) {
			$url = $this->config['prefix'].$url;
		}

		if (Cache::has($host.$url)) {
			return [
				"data" => Cache::get($host.$url),
				"statusCode" => 200,
				"statusText" => "OK"
			];

		} else {
			$this->client = new Client([
				'base_uri' => $host,
				'http_errors' => false
			]);

			Log::debug("Calling ${host}${url}");
			Log::debug("Body: ".json_encode($data));
			$options = [
				'headers' => $this->config['headers']
			];

			if (isset($this->config['auth'])) {
				$options['auth'] = [$this->config['auth']['username'], $this->config['auth']['password']];
			}

			if ($data && !$multipart) {
				if ($method === 'POST') {
					$options['body'] = $data;
				}
			} elseif ($data && $multipart) {
				$options['multipart'] = $data;
				unset($options['headers']['Content-Type']);
			}

			if ($this->config['jsonEncode'] && isset($options['body']) && $method === 'POST') {
				$options['json'] = $options['body'];
				unset($options['body']);
				// $options['headers']['Content-Length'] = strlen(json_encode($options['body']));
			}

			$response = $this->client->request($method, $url, $options);

			$contentLength = $response->getHeaderLine('Content-Length');
			$contentType = $response->getHeaderLine('Content-Type');
			$contentDisposition = $response->getHeaderLine('Content-Disposition');

			$responseBody = (Array) json_decode($response->getBody()->getContents());
			$statusCode = $response->getStatusCode();
			$statusText = $response->getReasonPhrase();

			if ($statusCode === 200 && $responseBody) {
				Cache::put($host.$url, $responseBody, 120);
			}
		}

		return [
			'data' => $responseBody,
			'statusCode' => $statusCode,
			'statusText' => $statusText,
			'contentType' => $contentType,
			'contentDisposition' => $contentDisposition,
			'stream' => $response->getBody()
		];
	}

}

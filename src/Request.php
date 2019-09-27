<?php

namespace Humblebrag\Collector;

use GuzzleHttp\Client;

class Request
{
	protected $baseUrl;
	protected $sharedAccessKey;
	protected $username;

	protected function __construct($baseUrl = '', $sharedAccessKey = '', $username = '')
	{
		$this->baseUrl = $baseUrl;
		$this->sharedAccessKey = $sharedAccessKey;
		$this->username = $username;
	}

	public static function get($baseUrl = '', $sharedAccessKey = '', $username = '')
	{
		static $request;

		if(!$request) {
			$request = new self($baseUrl, $sharedAccessKey, $username);
		}

		return $request;
	}

	public function makeRequest($path, $method = 'POST', $body = [], $options = [])
	{
		$sharedKey = $this->getSharedKey($path, $body);

		$client = new Client(['base_uri' => $this->baseUrl, 'headers' => ['Authorization' => $sharedKey, 'Content-Type' => 'application/json']]);

		return $client->request($method, $this->baseUrl, $options + ['json' => $body])

		return $client->request($method, $this->baseUrl . $path, $options);
	}

	protected function getSharedKey($path, $requestBody)
	{
		$hash = $this->username . ':' . hash("sha256", json_encode($requestBody, JSON_UNESCAPED_SLASHES).$path.$this->sharedAccessKey);

		return 'SharedKey ' . base64_encode($hash);
	}
}
<?php

namespace Humblebrag\Collector;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;

class Request
{
	public $baseUrl;
	public $username;
	public $sharedAccessKey;
	public $client;

	protected function __construct($baseUrl = '', $sharedAccessKey = '', $username = '')
	{
		$this->baseUrl = $baseUrl;
		$this->sharedAccessKey = $sharedAccessKey;
		$this->username = $username;

		$mockHandler = new MockHandler();

		$mockHandler->append(new \GuzzleHttp\Psr7\Response(200, [], json_encode(
			[
			  "id" => "91714012-6ae9-4780-a927-fe459bc95bf6",
			  "data" => [
			    "privateId" => "1eec44b5-66d3-4058-a31f-3444229fb727",
			    "publicToken" => "public-SE-7f1b3d2a2a73d348dfbd17d3965ff1458c249f84c695eac1",
			    "expiresAt" => "2017-12-07T07:16:49.8098107+00:00"
			  ],
			  "error" => null
			]
		)));

		$this->client = new Client([
			'handler' => $mockHandler,
			'base_uri' => $this->baseUrl,
			'http_errors' => false,
			'headers' => ['Content-Type' => 'application/json']
		]);
	}

	public static function get()
	{
		return new static(Collector::$backendUrl, Collector::$sharedAccessKey, Collector::$username);
	}

	public function request($path, $method = 'POST', $body = [], $options = ['headers' => []])
	{
		$sharedKey = $this->getSharedKey($path, $body);

		$options['headers']['Authorization'] = $sharedKey;

		return $this->client->request($method, $path, $options + ['json' => $body]);
	}

	public function getSharedKey($path, $requestBody)
	{
		$requestBody = is_string($requestBody) ? $requestBody : json_encode($requestBody, JSON_UNESCAPED_SLASHES);

		$hash = $this->username . ':' . hash("sha256", $requestBody.$path.$this->sharedAccessKey);

		return 'SharedKey ' . base64_encode($hash);
	}
}
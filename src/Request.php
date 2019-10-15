<?php

namespace Humblebrag\Collector;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7;
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

		$this->client = new Client([
			'base_uri' => $this->baseUrl,
			'http_errors' => true,
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
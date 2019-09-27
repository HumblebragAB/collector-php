<?php

namespace Humblebrag\Collector;

class Collector
{
	const TESTING_FRONTEND = 'https://checkout-uat.collector.se';
	const TESTING_BACKEND = 'https://checkout-api-uat.collector.se';

	const LIVE_FRONTEND = 'https://checkout.collector.se';
	const LIVE_BACKEND = 'https://checkout-api.collector.se';

	const MODE_TEST = 'test';
	const MODE_LIVE = 'live';

	public static $sharedAccessKey;
	public static $username;
	public static $backendUrl = Collector::TESTING_BACKEND;
	public static $frontendUrl = Collector::TESTING_BACKEND;

	public static function setSharedAccessKey($key)
	{
		self::$sharedAccessKey = $key;
	}

	public static function setUsername($username)
	{
		self::$username = $username;
	}

	public static function setMode($mode = self::MODE_TEST)
	{
		if($mode === self::MODE_TEST) {
			self::$backendUrl = self::TESTING_BACKEND;
			self::$frontendUrl = self::TESTING_FRONTEND;
		} else {
			self::$backendUrl = self::LIVE_BACKEND;
			self::$frontendUrl = self::LIVE_FRONTEND;
		}
	}

	public static function getSettings()
	{
		return [
			'sharedAccessKey' => self::$sharedAccessKey,
			'username' => self::$username,
			'baseUrl' => self::$baseUrl
		];
	}

	public static function setSettings($settings = [])
	{
		foreach($settings as $key => $value) {
			self::$$key = $value;
		}
	}
}
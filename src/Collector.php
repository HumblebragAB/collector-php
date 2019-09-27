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
	public static $backendUrl = 'https://webhook.site/f4dbf4fe-5274-4b69-9952-0fe4ab39a317';
	public static $frontendUrl = Collector::TESTING_BACKEND;


	public static function branding()
	{
		return new class {
			function standard()
			{
				return 'https://checkout.collector.se/resources/images/sv-SE/collector-checkout-badge-color.svg';
			}

			function dark()
			{
				return 'https://checkout.collector.se/resources/images/sv-SE/collector-checkout-badge-white.svg';
			}
		};
	}

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
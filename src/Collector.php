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

	public static $backendUrl = Collector::TESTING_BACKEND;
	public static $frontendUrl = Collector::TESTING_FRONTEND;

	public static $sharedAccessKey;
	public static $username;
	public static $storeId;
	public static $countryCode;
	public static $merchantTermsUri;
	public static $notificationUri;
	public static $redirectPageUri;
	public static $validationUri;
	public static $profileName;

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
			'backendUrl' => self::$backendUrl,
			'frontendUrl' => self::$frontendUrl,
			'storeId' => self::$storeId,
			'countryCode' => self::$countryCode,
		];
	}

	public static function setSettings($settings = [])
	{
		foreach($settings as $key => $value) {
			if($key === 'mode') {
				self::setMode($value);
				continue;
			}
			
			self::$$key = $value;
		}
	}

	public static function init($settings = [])
	{
		self::setSettings($settings);
	}
}
<?php

namespace Humblebrag\Collector;

class Collector
{
	public static $sharedAccessKey;

	protected function __construct()
	{

	}

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

	public static function request()
	{
		return Request::get('', '', '');
	}

	public static function setSharedAccessKey($key)
	{
		self::$sharedAccessKey = $key;
	}

	public static function checkout()
	{
		
	}
}
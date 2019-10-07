<?php

use Humblebrag\Collector\Collector;
use PHPUnit\Framework\TestCase;

class CollectorTest extends TestCase
{
	public function test_initiating_with_settings_sets_them()
	{
		Collector::init([
			'sharedAccessKey' => 'shared_access_key',
			'username' => 'username',
			'backendUrl' => 'http://example.com/backend',
			'frontendUrl' => 'http://example.com/frontend',
			'storeId' => 123,
			'countryCode' => 'SE',
		]);

		$this->assertEquals([
			'sharedAccessKey' => 'shared_access_key',
			'username' => 'username',
			'backendUrl' => 'http://example.com/backend',
			'frontendUrl' => 'http://example.com/frontend',
			'storeId' => 123,
			'countryCode' => 'SE',
		], Collector::getSettings());
	}

	public function test_setting_mode_to_live_sets_urls_to_live_urls()
	{
		Collector::setMode(Collector::MODE_LIVE);

		$this->assertEquals(Collector::LIVE_BACKEND, Collector::$backendUrl);
		$this->assertEquals(Collector::LIVE_FRONTEND, Collector::$frontendUrl);
	}

	public function test_setting_mode_to_test_sets_urls_to_test_urls()
	{
		Collector::setMode(Collector::MODE_TEST);

		$this->assertEquals(Collector::TESTING_BACKEND, Collector::$backendUrl);
		$this->assertEquals(Collector::TESTING_FRONTEND, Collector::$frontendUrl);
	}
}
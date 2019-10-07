<?php

use Humblebrag\Collector\Collector;
use Humblebrag\Collector\Request;
use PHPUnit\Framework\TestCase;

class RequestTest extends TestCase
{
	public function test_shared_key_is_correctly_computed()
	{
		$path = '/checkout';
		$username = 'myUsername';
		$sharedAccessKey = 'mySharedKey';

		$requestBody = '{"storeId":123,"countryCode":"SE","reference":"123456789","notificationUri":"http://backend-api-notification-uri.com","redirectPageUri":"http://purchase-completed-confirmation-page.com","merchantTermsUri":"http://merchant-purchase-terms.com","cart":{"items":[{"id":1,"description":"Someproduct","unitPrice":200,"quantity":1,"vat":20}]}}';

		$shouldBe = 'SharedKey bXlVc2VybmFtZTpmNTJiYzE3YmIyNWFmOWYzMzVlY2M2MjhjOWY0N2RiNGMwNTdmY2ZhYmVlYzRjM2Y0ZDRiMjRiMTU2N2QwYWNk';

		Collector::init([
			'username' => $username,
			'sharedAccessKey' => $sharedAccessKey
		]);

		$actual = Request::get()->getSharedKey($path, $requestBody);

		$this->assertEquals($shouldBe, $actual);
	}

	public function test_request_base_url_is_collector_backend_url_by_default()
	{
		$request = Request::get();

		$this->assertEquals(Collector::$backendUrl, $request->baseUrl);
	}
}
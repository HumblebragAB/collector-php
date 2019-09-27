<?php

namespace Humblebrag\Collector;

use Humblebrag\Collector\Exceptions\PublicTokenMissing;
use Humblebrag\Collector\Fees;

/**
 *
 * Property			Required	Explanation
 * storeId			No			Received from Collector Merchant Services. The store ID is only required in the request when integrating
 *								multiple stores with Collector Checkout.
 * countryCode		Yes			The country code to use. SE, NO, FI, DK and DE is supported.
 * reference		No			A reference to the order, i.e. order ID or similar. Note that the reference provided here will be shown 
 *								to the customer on the invoice or receipt for the purchase. Max 50 chars.
 * redirectPageUri	No *		If set, the browser will redirect to this page once the purchase has been completed. 
 *								Used to display a thank-you-page for the end user. Hereafter referred to as the purchase confirmation page. 
 *								* Required if the Store is an InStore type.
 * merchantTermsUri	Yes			The page to which the Checkout will include a link for customers that wish to view the merchant terms for purchase.
 * notificationUri	Yes			The endpoint to be called whenever an event has occurred in the Collector Checkout that might be of interest. 
 *								For example, this callback is typically used to create an order in the merchant's system once 
 *								a purchase has been completed. Use HTTPS here for security reasons.
 * validationUri	No			Specify this uri when you want us to make an extra backend call to validate the articles during purchase. 
 *								Use HTTPS here for security reasons.
 * profileName		No			A name that referes to a specific settings profile. The profiles are setup by Merchant Services, 
 *								please contact them for more information merchant@collectorbank.se
 * cart				Yes			The initial Cart object with items to purchase (details below)
 * fees				No			Shipping fee and direct invoice notification fee (details below)
 * customer			No			Customer information for identification (details below)
 *
 */

class Checkout extends CollectorObject
{
	public $publicToken;
	public $privateId;

	public function send()
	{
		$response = Request::get()->request('/checkout', 'POST', $this);

		$data = json_decode($response->getBody()->getContents(), true);

		if($data['error'] !== null) {
			return [$response, $data['error'], true];
		}

		$this->publicToken = $data['data']['publicToken'];
		$this->privateId = $data['data']['privateId'];

		return [$response, $data['data'], false];
	}

	public function info($storeId, $privateId)
	{
		$response = Request::get()->request('/merchants/'.$storeId.'/checkouts/'.$privateId);

		return $response;
	}

	public function scriptTag($publicToken = null)
	{
		$publicToken = $publicToken ?? $this->publicToken;

		if($publicToken === null) {
			throw new PublicTokenMissing(
				'Public token is requried for the script tag. ' . 
				'It can either be added manually by calling ' . 
				'$this->publicToken($token) or by calling send() ' .
				'before calling this method.'
			);
		}

		$src = Collector::$frontendUrl . '/collector-checkout-loader.js';

		return '<script src="' . $src . '" data-token="' . $publicToken. '"></script>';
	}

	public function publicToken($publicToken)
	{
		$this->publicToken = $publicToken;

		return $this;
	}

	public function privateId($privateId)
	{
		$this->privateId = $privateId;

		return $this;
	}

	public function storeId($storeId)
	{
		$this->_values['storeId'] = $storeId;

		return $this;
	}

	public function countryCode($countryCode)
	{
		$this->_values['countryCode'] = $countryCode;

		return $this;
	}

	public function reference($reference)
	{
		$this->_values['reference'] = $reference;

		return $this;
	}

	public function redirectPageUri($redirectPageUri)
	{
		$this->_values['redirectPageUri'] = $redirectPageUri;

		return $this;
	}

	public function merchantTermsUri($merchantTermsUri)
	{
		$this->_values['merchantTermsUri'] = $merchantTermsUri;

		return $this;
	}

	public function notificationUri($notificationUri)
	{
		$this->_values['notificationUri'] = $notificationUri;

		return $this;
	}

	public function validationUri($validationUri)
	{
		$this->_values['validationUri'] = $validationUri;

		return $this;
	}

	public function profileName($profileName)
	{
		$this->_values['profileName'] = $profileName;

		return $this;
	}

	public function getCart()
	{
		$cart = $this->_values['cart'];
		return $cart instanceof Cart ? $cart : Cart::create($cart);
	}

	public function fees($fees)
	{
		$this->_values['fees'] = $fees;

		return $this;
	}

	public function customer($customer)
	{
		$this->_values['customer'] = $customer;

		return $this;
	}
}
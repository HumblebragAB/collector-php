<?php

namespace Humblebrag\Collector;

use Humblebrag\Collector\Exceptions\DuplicateItemException;
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

	protected $_requiredFields = ['countryCode', 'merchantTermsUri', 'notificationUri', 'cart'];
	protected $_castFields = ['cart' => Cart::class];

	public function fillSettingsFromBase()
	{
		$this->_values['countryCode'] = $this->_values['countryCode'] ?? Collector::$countryCode;
		$this->_values['merchantTermsUri'] = $this->_values['merchantTermsUri'] ?? Collector::$merchantTermsUri;
		$this->_values['notificationUri'] = $this->_values['notificationUri'] ?? Collector::$notificationUri;
		$this->_values['redirectPageUri'] = $this->_values['redirectPageUri'] ?? Collector::$redirectPageUri;
		$this->_values['validationUri'] = $this->_values['validationUri'] ?? Collector::$validationUri;
		$this->_values['profileName'] = $this->_values['profileName'] ?? Collector::$profileName;
		$this->_values['storeId'] = (int) ($this->_values['storeId'] ?? Collector::$storeId);

		foreach($this->_values as $key => $value) {
			if($value === null) {
				unset($this->_values[$key]);
			}
		}
	}

	public function send()
	{
		$this->fillSettingsFromBase();

		$this->castFields();

		$this->validate();

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

	public function scriptTag($publicToken = null, $settings = [])
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

		$settings['data-token'] = $publicToken;

		$settingsString = [];

		foreach($settings as $key => $value) {
			$settingsString[] = "$key='$value'";
		}

		$settings = implode(" ", $settingsString);

		$src = Collector::$frontendUrl . '/collector-checkout-loader.js';

		return "<script type='application/javascript' src='$src' $settings></script>";
	}

	public function getCart()
	{
		if(isset($this->_values['cart'])) {
			$cart = $this->_values['cart'];

			if(!($cart instanceof Cart)) {
				$cart = $this->_values['cart'] = Cart::create($cart);
			}
		} else {
			$cart = $this->_values['cart'] = Cart::create([]);
		}

		return $cart;
	}

	public function getFees()
	{
		if(isset($this->_values['fees'])) {
			$fees = $this->_values['fees'];

			if(!($fees instanceof Fees)) {
				$fees = $this->_values['fees'] = Fees::create($fees);
			}
		} else {
			$fees = $this->_values['fees'] = Fees::create([]);
		}

		return $fees;
	}

	public function addItem($item)
	{
		if($this->getFees()->hasItem($item)) {
			throw new DuplicateItemException(
				'Tried to add an item with id ' . $item->id .
				' and description ' . $item->description .
				', but a fee with the same id and description already exists'
			);
		}

		$this->getCart()->addItem($item);

		return $this;
	}

	public function addFee($fee, $type)
	{
		if($this->getCart()->hasItem($fee)) {
			throw new DuplicateItemException(
				'Tried to add a fee with id ' . $fee->id .
				' and description ' . $fee->description .
				', but a fee with the same id and description already exists'
			);
		}

		$this->getFees()->addFee($fee, $type);

		return $this;
	}
}
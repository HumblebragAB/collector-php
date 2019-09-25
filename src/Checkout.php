<?php

namespace Humblebrag\Collector;

use Humblebrag\Collector\Fees;

class Checkout extends CollectorObject
{
	protected function __construct($values)
	{
		parent::__construct($values);

		$this->_values['cart'] = Cart::create();
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
		return $this->_values['cart'];
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
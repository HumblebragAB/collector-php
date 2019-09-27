<?php

namespace Humblebrag\Collector;


/**
 *
 * Property						Required		Explanation
 * email						Yes				The customer's email address.
 * mobilePhoneNumber			Yes				The customer's mobile phone.
 * nationalIdentificationNumber	No				The customer's national identification number
 *
 */
class Customer extends CollectorObject
{
	public function email($email)
	{
		$this->_values['email'] = $email;

		return $this;
	}

	public function mobilePhoneNumber($mobilePhoneNumber)
	{
		$this->_values['mobilePhoneNumber'] = $mobilePhoneNumber;

		return $this;
	}
	
	public function nationalIdentificationNumber($nationalIdentificationNumber)
	{
		$this->_values['nationalIdentificationNumber'] = $nationalIdentificationNumber;

		return $this;
	}
}
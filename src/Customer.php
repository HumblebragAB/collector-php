<?php

namespace Humblebrag\Collector;

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
<?php

namespace Humblebrag\Collector;

use Humblebrag\Collector\Fees\Shipping;

class Fees extends CollectorObject
{
	protected function __construct()
	{
		$this->_values['shipping'] = null;

		$this->_values['directinvoicenotification'] = null;

		parent::__construct();
	}

	public function setShipping(Shipping $shipping)
	{
		$this->_values['shipping'] = $shipping;

		return $this;
	}

	public function setDirectInvoiceNotification(DirectInvoiceNotification $directInvoiceNotification)
	{
		$this->_values['directinvoicenotification'] = $directInvoiceNotification;

		return $this;
	}
}
<?php

namespace Humblebrag\Collector;

use Humblebrag\Collector\Fees\Shipping;

/**
 *
 * Property		Required	Explanation
 * id			Yes			An id of the fee item. Max 50 characters. Values are trimmed from leading and trailing white-spaces. 
 *							Shown on the invoice or receipt.
 * description	Yes			Descriptions longer than 50 characters will be truncated. Values are trimmed from leading and trailing white-spaces. 
 *							Shown on the invoice or receipt.
 * unitPrice	Yes			The unit price of the fee including VAT. Allowed values are 0 to 999999.99. Max 2 decimals, i.e. 25.00
 * vat			Yes			The VAT of the fee in percent. Allowed values are 0 to 100. Max 2 decimals, i.e. 25.00
 *
 * The combination of id and description of a fee must be unique within the cart and fees objects.		
 *
 */

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
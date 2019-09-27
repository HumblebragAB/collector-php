<?php

namespace Humblebrag\Collector;

/**
 *
 * Property				Required	Explanation
 * id					Yes			The article id or equivalent. Max 50 characters. Values are trimmed from leading and trailing white-spaces. 
 *									Shown on the invoice or receipt.
 * description			Yes			Descriptions longer than 50 characters will be truncated. 
 *									Values are trimmed from leading and trailing white-spaces. Shown on the invoice or receipt.
 * unitPrice			Yes			The unit price of the article including VAT. Positive and negative values allowed. Max 2 decimals, i.e. 100.00
 * quantity				Yes			Quantity of the article. Allowed values are 1 to 99999999.
 * vat					Yes			The VAT of the article in percent. Allowed values are 0 to 100. Max 2 decimals, i.e. 25.00
 * requiresElectronicId	No			When set to true it indicates that a product needs strong identification and the customer will need to 
 *									strongly identify themselves at the point of purchase using Mobilt BankID. 
 *									An example would be selling tickets that are delivered electronically. 
 *									At the moment it is only applicable to B2C on the Swedish market.
 * sku					No			A stock Keeping Unit is a unique alphanumeric code that is used to identify product types and variations. 
 * 									Maximum allowed characters are 1024.
 *
 */

class CartItem extends CollectorObject
{
	public function id($id)
	{
		$this->_values['id'] = $id;

		return $this;
	}

	public function description($description)
	{
		$this->_values['description'] = $description;

		return $this;
	}

	public function unitPrice($unitPrice)
	{
		$this->_values['unitPrice'] = $unitPrice;
		
		return $this;
	}

	public function quantity($quantity)
	{
		$this->_values['quantity'] = $quantity;
		
		return $this;
	}

	public function vat($vat)
	{
		$this->_values['vat'] = $vat;
		
		return $this;
	}

	public function requiresElectronicId($requiresElectronicId)
	{
		$this->_values['requiresElectronicId'] = $requiresElectronicId;
		
		return $this;
	}

	public function sku($sku)
	{
		$this->_values['sku'] = $sku;
		
		return $this;
	}
}
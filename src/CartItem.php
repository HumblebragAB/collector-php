<?php

namespace Humblebrag\Collector;

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
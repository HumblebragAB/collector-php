<?php

namespace Humblebrag\Collector;

class Cart extends CollectorObject
{
	protected function __construct($values)
	{
		$this->_values['items'] = [];

		parent::__construct($values);
	}

	public function addItem(CartItem $item)
	{
		$this->_values['items'][] = $item;

		return $this;
	}
}
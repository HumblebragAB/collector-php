<?php

namespace Humblebrag\Collector;

use Humblebrag\Collector\Exceptions\DuplicateItemException;

class Cart extends CollectorObject
{
	protected function __construct($values)
	{
		$this->_values['items'] = [];

		parent::__construct($values);
	}

	public function addItem(CartItem $item)
	{
		if($this->hasItem($item)) {
			throw new DuplicateItemException(
				'An item with id ' . $item->id .
				' and description ' . $item->description .
				' already exists'
			);
		}

		$this->_values['items'][] = $item;

		return $this;
	}

	public function getItems()
	{
		return $this->_values['items'];
	}

	public function hasItem($item)
	{
		$item = (object) $item;
		foreach($this->_values['items'] as $existingItem) {
			$existingItem = (object) $existingItem;
			if($existingItem->id . $existingItem->description === $item->id . $item->description) {
				return true;
			}
		}

		return false;
	}
}
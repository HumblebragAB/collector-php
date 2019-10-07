<?php

use Humblebrag\Collector\Cart;
use Humblebrag\Collector\CartItem;
use PHPUnit\Framework\TestCase;

class CartTest extends TestCase
{
	public function test_cart_has_get_items_function()
	{
		$cart = Cart::create();

		$this->assertEquals([], $cart->getItems());
	}

	public function test_adding_item_to_cart_adds_it()
	{
		$cart = Cart::create();

		$this->assertCount(0, $cart->getItems());

		$cart->addItem(CartItem::create());

		$this->assertCount(1, $cart->getItems());
	}
}
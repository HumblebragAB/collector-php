<?php

use Humblebrag\Collector\CartItem;
use Humblebrag\Collector\Checkout;
use Humblebrag\Collector\Collector;
use Humblebrag\Collector\Exceptions\DuplicateItemException;
use Humblebrag\Collector\Exceptions\PublicTokenMissing;
use Humblebrag\Collector\Exceptions\ValidationException;
use Humblebrag\Collector\Fees\Shipping;
use PHPUnit\Framework\TestCase;

class CheckoutTest extends TestCase
{
	public function test_throws_exception_on_missing_token_when_fetching_script_tag()
	{
		$this->expectException(PublicTokenMissing::class);

		$checkout = Checkout::create();

		$checkout->scriptTag();
	}

	public function test_script_contains_src_with_frontend_url()
	{
		$checkout = Checkout::create();

		$token = 'sometoken';

		$script = $checkout->scriptTag($token);

		$this->assertStringContainsString(Collector::$frontendUrl . '/collector-checkout-loader.js', $script);
	}

	public function test_script_contains_attribute_fields()
	{
		$checkout = Checkout::create();

		$token = 'sometoken';

		$settings = [
			'data-lang' => 'en-SE',
			'data-padding' => 'none',
			'data-container-id' => 'an-id-for-container',
			'data-action-color' => '#ff00ff',
			'data-action-text-color' => '#ffffff'
		];

		$script = $checkout->scriptTag($token, $settings);

		foreach($settings as $key => $value) {
			$this->assertStringContainsString($key, $script);
			$this->assertStringContainsString($value, $script);
		}
	}

	public function test_fills_settings_from_collector()
	{
		$checkout = Checkout::create();

		$this->assertFalse(isset($checkout->toArray()['countryCode']), 'Country code shouldn\'t be set on checkout');

		Collector::init(['countryCode' => 'SE']);

		$checkout->fillSettingsFromBase();
		
		$this->assertTrue(isset($checkout->toArray()['countryCode']), 'Country code should be set on checkout');
	}

	public function test_throws_validation_exception_on_missing_keys()
	{
		$this->expectException(ValidationException::class);

		$checkout = Checkout::create();

		$checkout->validate();
	}

	public function test_adding_item_with_same_id_and_description_twice_throws_exception()
	{
		$this->expectException(DuplicateItemException::class);

		$checkout = Checkout::create();

		$item = CartItem::create(['id' => 1, 'description' => 'Cart item description']);

		$checkout->addItem($item);
		$checkout->addItem($item);
	}

	public function test_adding_item_with_same_id_and_description_as_a_fee_throws_exception()
	{
		$this->expectException(DuplicateItemException::class);

		$checkout = Checkout::create();

		$item = CartItem::create(['id' => 1, 'description' => 'Cart item description']);
		$fee = Shipping::create(['id' => 1, 'description' => 'Cart item description']);

		$checkout->addItem($item);
		$checkout->addFee($fee, 'shipping');
	}
}
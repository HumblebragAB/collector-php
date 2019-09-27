<?php

namespace Humblebrag\Collector;

class Branding
{
	const LANG_SV_SE = 'sv-SE';
	const LANG_EN_SE = 'en-SE';
	const LANG_FI_FI = 'fi-FI';
	const LANG_SV_FI = 'sv-FI';
	const LANG_NB_NO = 'nb-NO';
	const LANG_DA_DK = 'da-DK';

	const BACKGROUND_STANDARD = 'color';
	const BACKGROUND_DARK = 'white';

	public static function get($lang = Branding::LANG_SV_SE, $background = Branding::BACKGROUND_STANDARD)
	{
		return "https://checkout.collector.se/resources/images/{$lang}/collector-checkout-badge-{$background}.svg";
	}
}
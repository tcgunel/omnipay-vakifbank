<?php

namespace Omnipay\Vakifbank\Constants;

class CurrencyCodes
{
	public const TRY = 949;
	public const USD = 840;
	public const EUR = 978;
	public const JPY = 392;
	public const GBP = 826;

    public static function asString(int $currency_code): string
    {
        $items = [
            self::TRY => 'TRY',
            self::USD => 'USD',
            self::EUR => 'EUR',
            self::JPY => 'JPY',
            self::GBP => 'GBP',
        ];

        return $items[$currency_code];
    }
}

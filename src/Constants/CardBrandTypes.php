<?php

namespace Omnipay\Vakifbank\Constants;

class CardBrandTypes
{
    public const VISA = 100;
    public const MASTERCARD = 200;
    public const TROY = 300;
    public const AMEX = 400;

    public static function get(string $name): int
    {
        $list = [
            'visa'       => self::VISA,
            'mastercard' => self::MASTERCARD,
            'troy'       => self::TROY,
            'amex'       => self::AMEX,
            'discover'   => self::TROY,
        ];

        return $list[$name];
    }
}

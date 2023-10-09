<?php

namespace Omnipay\Vakifbank\Helpers;

class Helper
{
    public static function flattenArray(array $array): array
    {
        $result = [];
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $result = array_merge($result, self::flattenArray($value, $key));
            } else {
                $result[$key] = $value;
            }
        }
        return $result;
    }
}

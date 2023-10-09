<?php

namespace Omnipay\Vakifbank\Models;

use ReflectionProperty;

class BaseModel
{
    public function __construct(?array $abstract)
    {
        foreach ($abstract as $key => $arg) {

            if (property_exists($this, $key)) {

                $property_type = (new ReflectionProperty($this, $key))->getType()->getName();

                if (class_exists($property_type)) {

                    $this->$key = new $property_type($arg);

                } else if (in_array($property_type, ['string', 'int', 'float', 'bool'], true)) {

                    $this->$key = $arg;

                }

            }

        }

        if (!empty($abstract))
            $this->original_response = json_encode($abstract);
    }

    public string $original_response;
}

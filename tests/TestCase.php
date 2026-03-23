<?php

namespace Omnipay\Vakifbank\Tests;

use Faker\Factory;
use Omnipay\Tests\GatewayTestCase;
use Omnipay\Vakifbank\Gateway;

class TestCase extends GatewayTestCase
{
    public $faker;

    public function setUp(): void
    {
        parent::setUp();

        $this->faker = Factory::create('tr_TR');

        $this->gateway = new Gateway($this->getHttpClient(), $this->getHttpRequest());
    }
}

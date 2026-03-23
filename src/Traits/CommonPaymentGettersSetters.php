<?php

namespace Omnipay\Vakifbank\Traits;

trait CommonPaymentGettersSetters
{
    public function getPaymentToken()
    {
        return $this->getParameter('payment_token');
    }

    public function setPaymentToken($value)
    {
        return $this->setParameter('payment_token', $value);
    }
}

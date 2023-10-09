<?php

namespace Omnipay\Vakifbank\Traits;

trait PurchaseGettersSetters
{
    public function getPassword()
    {
        return $this->getParameter('password');
    }

    public function setPassword($value)
    {
        return $this->setParameter('password', $value);
    }

    public function getInstallment()
    {
        return $this->getParameter('installment');
    }

    public function setInstallment($value)
    {
        return $this->setParameter('installment', $value);
    }

    public function getMerchantId()
    {
        return $this->getParameter('merchant_id');
    }

    public function setMerchantId($value)
    {
        return $this->setParameter('merchant_id', $value);
    }

    public function getSecure()
    {
        return $this->getParameter('secure');
    }

    public function setSecure($value)
    {
        return $this->setParameter('secure', $value);
    }

    public function getTerminalNo()
    {
        return $this->getParameter('terminal_no');
    }

    public function setTerminalNo($value)
    {
        return $this->setParameter('terminal_no', $value);
    }

    public function getTransactionType()
    {
        return $this->getParameter('transaction_type');
    }

    public function setTransactionType($value)
    {
        return $this->setParameter('transaction_type', $value);
    }

    public function getEci()
    {
        return $this->getParameter('eci');
    }

    public function setEci($value)
    {
        return $this->setParameter('eci', $value);
    }

    public function getCavv()
    {
        return $this->getParameter('cavv');
    }

    public function setCavv($value)
    {
        return $this->setParameter('cavv', $value);
    }

    public function getClientIp()
    {
        return $this->getParameter('client_ip');
    }

    public function setClientIp($value)
    {
        return $this->setParameter('client_ip', $value);
    }

    public function getTransactionDeviceSource()
    {
        return $this->getParameter('transaction_device_source');
    }

    public function setTransactionDeviceSource($value)
    {
        return $this->setParameter('transaction_device_source', $value);
    }

}

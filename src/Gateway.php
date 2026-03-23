<?php

namespace Omnipay\Vakifbank;

use Omnipay\Common\AbstractGateway;
use Omnipay\Vakifbank\Message\CommonPaymentQueryRequest;
use Omnipay\Vakifbank\Message\CommonPaymentRegisterRequest;
use Omnipay\Vakifbank\Message\EnrollmentRequest;
use Omnipay\Vakifbank\Message\FetchTransactionRequest;
use Omnipay\Vakifbank\Message\PurchaseRequest;
use Omnipay\Vakifbank\Message\RefundRequest;
use Omnipay\Vakifbank\Message\VoidRequest;
use Omnipay\Vakifbank\Traits\PurchaseGettersSetters;

/**
 * Vakifbank Gateway
 * (c) Tolga Can Günel
 * 2015, mobius.studio
 * http://www.github.com/tcgunel/omnipay-vakifbank
 * @method \Omnipay\Common\Message\NotificationInterface acceptNotification(array $options = [])
 * @method \Omnipay\Common\Message\RequestInterface completeAuthorize(array $options = [])
 */
class Gateway extends AbstractGateway
{
    use PurchaseGettersSetters;

    public function getName(): string
    {
        return 'Vakifbank';
    }

    public function getDefaultParameters()
    {
        return [
            'clientIp' => '127.0.0.1',
            'secure' => false,
        ];
    }

    public function enrollment(array $parameters = [])
    {
        return $this->createRequest(EnrollmentRequest::class, $parameters);
    }

    public function purchase(array $parameters = [])
    {
        return $this->createRequest(PurchaseRequest::class, $parameters);
    }

    public function fetchTransaction(array $parameters = [])
    {
        return $this->createRequest(FetchTransactionRequest::class, $parameters);
    }

    public function refund(array $parameters = [])
    {
        return $this->createRequest(RefundRequest::class, $parameters);
    }

    public function void(array $parameters = [])
    {
        return $this->createRequest(VoidRequest::class, $parameters);
    }

    public function commonPaymentRegister(array $parameters = [])
    {
        return $this->createRequest(CommonPaymentRegisterRequest::class, $parameters);
    }

    public function commonPaymentQuery(array $parameters = [])
    {
        return $this->createRequest(CommonPaymentQueryRequest::class, $parameters);
    }
}

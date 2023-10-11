<?php

namespace Omnipay\Vakifbank;

use Omnipay\Common\AbstractGateway;
use Omnipay\Vakifbank\Message\EnrollmentRequest;
use Omnipay\Vakifbank\Message\FetchTransactionRequest;
use Omnipay\Vakifbank\Traits\PurchaseGettersSetters;
use Omnipay\Vakifbank\Message\PurchaseRequest;

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
			"clientIp" => "127.0.0.1",
			"secure"        => false,
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
}

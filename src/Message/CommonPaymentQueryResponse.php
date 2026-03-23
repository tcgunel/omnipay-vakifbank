<?php

namespace Omnipay\Vakifbank\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RequestInterface;
use Omnipay\Vakifbank\Models\CommonPaymentQueryResponseModel;

class CommonPaymentQueryResponse extends AbstractResponse
{
    protected $response;

    protected $request;

    public function __construct(RequestInterface $request, $data)
    {
        parent::__construct($request, $data);

        $this->request = $request;

        $this->response = $data;
    }

    public function isSuccessful(): bool
    {
        return $this->response->RC === '0000';
    }

    public function getMessage(): string
    {
        return $this->response->ErrorMessage ?? '';
    }

    public function getData(): CommonPaymentQueryResponseModel
    {
        return $this->response;
    }

    public function getRedirectData()
    {
        return null;
    }

    public function getRedirectUrl()
    {
        return '';
    }
}

<?php

namespace Omnipay\Vakifbank\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RedirectResponseInterface;
use Omnipay\Common\Message\RequestInterface;

class CommonPaymentRegisterResponse extends AbstractResponse implements RedirectResponseInterface
{
    protected $test_redirect_base = 'https://onlineodemetest.vakifbank.com.tr:4443/UIService/CommonPayment.aspx';

    protected $prod_redirect_base = 'https://web.vakifbank.com.tr/ServiceHost/Vpos7/CommonPayment.aspx';

    protected $parsedData = [];

    protected $request;

    public function __construct(RequestInterface $request, $data)
    {
        parent::__construct($request, $data);

        $this->request = $request;

        // Parse key=value response body
        if (is_string($data)) {
            parse_str($data, $this->parsedData);
        }
    }

    public function isSuccessful(): bool
    {
        return !empty($this->getPaymentToken());
    }

    public function isRedirect(): bool
    {
        return $this->isSuccessful();
    }

    public function getPaymentToken(): ?string
    {
        $token = $this->parsedData['PaymentToken'] ?? null;

        return !empty($token) ? $token : null;
    }

    public function getRedirectUrl()
    {
        if (!$this->isSuccessful()) {
            return '';
        }

        $baseUrl = $this->request->getTestMode()
            ? $this->test_redirect_base
            : $this->prod_redirect_base;

        return $baseUrl . '?PaymentToken=' . urlencode($this->getPaymentToken());
    }

    public function getRedirectMethod(): string
    {
        return 'GET';
    }

    public function getRedirectData()
    {
        return null;
    }

    public function getMessage()
    {
        return $this->parsedData['ErrorMessage'] ?? '';
    }

    public function getData()
    {
        return $this->parsedData;
    }
}

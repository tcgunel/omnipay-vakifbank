<?php

namespace Omnipay\Vakifbank\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RedirectResponseInterface;
use Omnipay\Common\Message\RequestInterface;

/**
 * Vakifbank Common Payment Register Response (v2.1 API Gateway)
 *
 * Response is JSON:
 * {
 *   "CommonPaymentUrl": "https://guvenliodeme-test.vakifbank.com.tr/...",
 *   "PaymentToken": "uuid",
 *   "ShortLink": "https://...",
 *   "ResponseMessage": "ISLEM BASARILI",
 *   "ErrorCode": "0000"
 * }
 */
class CommonPaymentRegisterResponse extends AbstractResponse implements RedirectResponseInterface
{
    protected $parsedData = [];

    protected $request;

    public function __construct(RequestInterface $request, $data)
    {
        parent::__construct($request, $data);

        $this->request = $request;

        // v2.1 API Gateway returns JSON
        if (is_string($data)) {
            $decoded = json_decode($data, true);
            $this->parsedData = is_array($decoded) ? $decoded : [];
        }
    }

    public function isSuccessful(): bool
    {
        return ! empty($this->getPaymentToken())
            && ($this->parsedData['ErrorCode'] ?? '') === '0000';
    }

    public function isRedirect(): bool
    {
        return $this->isSuccessful();
    }

    public function getPaymentToken(): ?string
    {
        $token = $this->parsedData['PaymentToken'] ?? null;

        return ! empty($token) ? $token : null;
    }

    public function getRedirectUrl()
    {
        if (! $this->isSuccessful()) {
            return '';
        }

        // v2.1: redirect URL is returned dynamically in CommonPaymentUrl
        $baseUrl = $this->parsedData['CommonPaymentUrl'] ?? '';

        if (empty($baseUrl)) {
            return '';
        }

        return $baseUrl . '?Ptkn=' . urlencode($this->getPaymentToken());
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
        return $this->parsedData['ResponseMessage'] ?? $this->parsedData['ErrorMessage'] ?? '';
    }

    public function getErrorCode(): ?string
    {
        return $this->parsedData['ErrorCode'] ?? null;
    }

    public function getShortLink(): ?string
    {
        return $this->parsedData['ShortLink'] ?? null;
    }

    public function getData()
    {
        return $this->parsedData;
    }
}

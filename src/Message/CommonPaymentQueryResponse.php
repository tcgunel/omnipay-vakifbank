<?php

namespace Omnipay\Vakifbank\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RequestInterface;

/**
 * Vakifbank Common Payment Query Response (v2.1 API Gateway)
 *
 * Response is JSON. Success when ErrorCode === '0000'.
 * Error code '5003' means no completed payment found for this token/transaction.
 */
class CommonPaymentQueryResponse extends AbstractResponse
{
    protected $parsedData = [];

    protected $request;

    public function __construct(RequestInterface $request, $data)
    {
        parent::__construct($request, $data);

        $this->request = $request;

        $this->parsedData = is_array($data) ? $data : [];
    }

    public function isSuccessful(): bool
    {
        return ($this->parsedData['ErrorCode'] ?? '') === '0000';
    }

    public function getMessage(): string
    {
        return $this->parsedData['ResponseMessage'] ?? $this->parsedData['ErrorMessage'] ?? '';
    }

    public function getErrorCode(): ?string
    {
        return $this->parsedData['ErrorCode'] ?? null;
    }

    public function getTransactionId(): ?string
    {
        return $this->parsedData['TransactionId'] ?? null;
    }

    public function getAuthCode(): ?string
    {
        return $this->parsedData['AuthCode'] ?? null;
    }

    public function getAmount(): ?string
    {
        return $this->parsedData['Amount'] ?? null;
    }

    public function getData()
    {
        return $this->parsedData;
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

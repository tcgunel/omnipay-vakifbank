<?php

namespace Omnipay\Vakifbank\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RequestInterface;

/**
 * Vakifbank Common Payment Query Response (v2.1 API Gateway)
 *
 * Response format differs based on result:
 * - Error (no payment found): {"ErrorCode": "5003", "ResponseMessage": "Islem bulunamadi."}
 * - Success (payment found):  {"Rc": "0000", "AuthResultCode": "0000", "Amount": "...", ...}
 *
 * Note: successful payment responses do NOT have an ErrorCode field.
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
        // Error-only responses have ErrorCode (e.g., "5003" = not found)
        if (isset($this->parsedData['ErrorCode']) && $this->parsedData['ErrorCode'] !== '0000') {
            return false;
        }

        // Payment responses use Rc for the result code
        if (isset($this->parsedData['Rc'])) {
            return $this->parsedData['Rc'] === '0000';
        }

        return ($this->parsedData['ErrorCode'] ?? '') === '0000';
    }

    public function getMessage(): string
    {
        return $this->parsedData['ResponseMessage']
            ?? $this->parsedData['Message']
            ?? $this->parsedData['AuthResultDescription']
            ?? '';
    }

    public function getErrorCode(): ?string
    {
        return $this->parsedData['ErrorCode'] ?? $this->parsedData['Rc'] ?? null;
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

    public function getMaskedPan(): ?string
    {
        return $this->parsedData['MaskedPan'] ?? null;
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

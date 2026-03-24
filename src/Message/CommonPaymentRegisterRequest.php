<?php

namespace Omnipay\Vakifbank\Message;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Message\AbstractRequest;
use Omnipay\Vakifbank\Exceptions\OmnipayVakifbankCommonPaymentRequestException;
use Omnipay\Vakifbank\Traits\PurchaseGettersSetters;

/**
 * Vakifbank Common Payment (Ortak Odeme) - Register Transaction
 *
 * Uses the new API Gateway endpoints (v2.1, updated 27.02.2026).
 * The old .asmx endpoints have been decommissioned.
 */
class CommonPaymentRegisterRequest extends AbstractRequest
{
    use PurchaseGettersSetters;

    protected $test_endpoint = 'https://inbound.apigatewaytest.vakifbank.com.tr:8443/commonPayment/CreateToken';

    protected $prod_endpoint = 'https://inbound.apigateway.vakifbank.com.tr:8443/commonPayment/CreateToken';

    /**
     * @throws InvalidRequestException
     */
    public function getData()
    {
        $this->validate(
            'merchant_id',
            'password',
            'terminal_no',
            'transactionId',
            'amount',
            'currency',
            'returnUrl',
            'cancelUrl',
        );

        $amount = number_format((float) $this->getAmount(), 2, '.', '');
        $currencyCode = (string) $this->getCurrencyNumeric();

        $data = [
            'HostMerchantId' => $this->getMerchantId(),
            'MerchantId' => $this->getSubMerchantId() ?: '1',
            'MerchantPassword' => $this->getPassword(),
            'HostTerminalId' => $this->getTerminalNo(),
            'TransactionId' => $this->getTransactionId(),
            'Amount' => $amount,
            'AmountCode' => $currencyCode,
            'TransactionType' => 'Sale',
            'IsSecure' => 'true',
            'AllowNotEnrolledCard' => 'true',
            'SuccessUrl' => $this->getReturnUrl(),
            'FailUrl' => $this->getCancelUrl(),
        ];

        if ($this->getDescription()) {
            $data['OrderDescription'] = $this->getDescription();
        }

        if ($this->getInstallment() > 1) {
            $data['InstallmentCount'] = (string) $this->getInstallment();
        }

        return $data;
    }

    /**
     * @throws OmnipayVakifbankCommonPaymentRequestException
     */
    public function sendData($data)
    {
        $httpResponse = $this->httpClient->request(
            'POST',
            $this->getTestMode() ? $this->test_endpoint : $this->prod_endpoint,
            [
                'Content-Type' => 'application/x-www-form-urlencoded',
                'Accept' => 'application/json',
            ],
            http_build_query($data)
        );

        if ($httpResponse->getStatusCode() !== 200) {

            throw new OmnipayVakifbankCommonPaymentRequestException('Common Payment Register Request sirasinda bir hata olustu.', $httpResponse->getStatusCode());

        }

        $responseBody = (string) $httpResponse->getBody();

        return $this->response = new CommonPaymentRegisterResponse($this, $responseBody);
    }
}

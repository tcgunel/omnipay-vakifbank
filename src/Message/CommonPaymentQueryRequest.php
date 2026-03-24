<?php

namespace Omnipay\Vakifbank\Message;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Message\AbstractRequest;
use Omnipay\Vakifbank\Exceptions\OmnipayVakifbankCommonPaymentRequestException;
use Omnipay\Vakifbank\Traits\CommonPaymentGettersSetters;
use Omnipay\Vakifbank\Traits\PurchaseGettersSetters;

/**
 * Vakifbank Common Payment Query (Sorgulama) - Verify Transaction
 *
 * Uses the new API Gateway endpoints (v2.1, updated 27.02.2026).
 * CRITICAL: Must ALWAYS be called after customer returns from payment page.
 */
class CommonPaymentQueryRequest extends AbstractRequest
{
    use PurchaseGettersSetters;
    use CommonPaymentGettersSetters;

    protected $test_endpoint = 'https://inbound.apigatewaytest.vakifbank.com.tr:8443/commonPayment/GetVposTransaction';

    protected $prod_endpoint = 'https://inbound.apigateway.vakifbank.com.tr:8443/commonPayment/GetVposTransaction';

    /**
     * @throws InvalidRequestException
     */
    public function getData()
    {
        $this->validate(
            'merchant_id',
            'password',
            'terminal_no',
        );

        if (! $this->getPaymentToken() && ! $this->getTransactionId()) {

            throw new InvalidRequestException('PaymentToken yada TransactionId gerekli');

        }

        // v2.1 API uses different field names
        $data = [
            'MerchantNumber' => $this->getMerchantId(),
            'Password' => $this->getPassword(),
            'TerminalNumber' => $this->getTerminalNo(),
        ];

        if ($this->getPaymentToken()) {
            $data['PaymentToken'] = $this->getPaymentToken();
        }

        if ($this->getTransactionId()) {
            $data['TransactionId'] = $this->getTransactionId();
        }

        return $data;
    }

    /**
     * @throws OmnipayVakifbankCommonPaymentRequestException
     */
    public function sendData($data)
    {
        // v2.1 API uses JSON POST
        $httpResponse = $this->httpClient->request(
            'POST',
            $this->getTestMode() ? $this->test_endpoint : $this->prod_endpoint,
            [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ],
            json_encode($data)
        );

        if ($httpResponse->getStatusCode() !== 200) {

            throw new OmnipayVakifbankCommonPaymentRequestException('Common Payment Query Request sirasinda bir hata olustu.', $httpResponse->getStatusCode());

        }

        $responseBody = (string) $httpResponse->getBody();
        $responseData = json_decode($responseBody, true) ?? [];

        return $this->response = new CommonPaymentQueryResponse($this, $responseData);
    }
}

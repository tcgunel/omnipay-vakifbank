<?php

namespace Omnipay\Vakifbank\Message;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Message\AbstractRequest;
use Omnipay\Vakifbank\Exceptions\OmnipayVakifbankCommonPaymentRequestException;
use Omnipay\Vakifbank\Traits\CommonPaymentGettersSetters;
use Omnipay\Vakifbank\Traits\PurchaseGettersSetters;

class CommonPaymentQueryRequest extends AbstractRequest
{
    use PurchaseGettersSetters;
    use CommonPaymentGettersSetters;

    protected $test_endpoint = 'https://onlineodemetest.vakifbank.com.tr:4443/UIService/CommonPayment.asmx';

    protected $prod_endpoint = 'https://web.vakifbank.com.tr/ServiceHost/Vpos7/CommonPayment.asmx';

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

        if (!$this->getPaymentToken() && !$this->getTransactionId()) {

            throw new InvalidRequestException('PaymentToken yada TransactionId gerekli');

        }

        $data = [
            'HostMerchantId' => $this->getMerchantId(),
            'MerchantPassword' => $this->getPassword(),
            'HostTerminalId' => $this->getTerminalNo(),
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
        $httpResponse = $this->httpClient->request(
            'POST',
            $this->getTestMode() ? $this->test_endpoint : $this->prod_endpoint,
            [
                'Content-Type' => 'application/x-www-form-urlencoded',
                'Accept' => 'application/xml',
            ],
            http_build_query($data)
        );

        if ($httpResponse->getStatusCode() !== 200) {

            throw new OmnipayVakifbankCommonPaymentRequestException('Common Payment Query Request sırasında bir hata oluştu.', $httpResponse->getStatusCode());

        }

        return $this->response = new CommonPaymentQueryResponse($this, $httpResponse);
    }
}

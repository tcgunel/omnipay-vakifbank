<?php

namespace Omnipay\Vakifbank\Message;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Message\AbstractRequest;
use Omnipay\Vakifbank\Exceptions\OmnipayVakifbankFetchTransactionRequestException;
use Omnipay\Vakifbank\Exceptions\OmnipayVakifbankPurchaseResponseException;
use Omnipay\Vakifbank\Traits\PurchaseGettersSetters;

class FetchTransactionRequest extends AbstractRequest
{
    use PurchaseGettersSetters;

    protected $test_endpoint = "https://onlineodemetest.vakifbank.com.tr:4443/UIService/Search.aspx";

    protected $prod_endpoint = "https://onlineodeme.vakifbank.com.tr:4443/UIService/Search.aspx";

    /**
     * @throws InvalidRequestException
     */
    public function getData()
    {
        $this->validate(
            'merchant_id',
            'password',
        );

        if (!$this->getTransactionId() && !$this->getOrderId()) {

            throw new InvalidRequestException('TransactionId yada OrderId gerekli');

        }

        return [
            'HostMerchantId'   => $this->getMerchantId(),
            'MerchantPassword' => $this->getPassword(),
            'TransactionId'    => $this->getTransactionId(),
            'OrderId'          => $this->getOrderId(),
        ];
    }

    private function prepareXml(array $data): string
    {
        return "<SearchRequest>
                    <MerchantCriteria>
                        <HostMerchantId>$data[HostMerchantId]</HostMerchantId>
                        <MerchantPassword>$data[MerchantPassword]</MerchantPassword>
                    </MerchantCriteria>
                    <TransactionCriteria>
                        <TransactionId>$data[TransactionId]</TransactionId>
                        <OrderId>$data[OrderId]</OrderId>
                    </TransactionCriteria>
                </SearchRequest>";
    }

    /**
     * @throws OmnipayVakifbankFetchTransactionRequestException
     * @throws OmnipayVakifbankPurchaseResponseException
     */
    public function sendData($data)
    {
        $httpResponse = $this->httpClient->request(
            'POST',
            $this->getTestMode() ? $this->test_endpoint : $this->prod_endpoint,
            [
                'Content-Type' => 'application/x-www-form-urlencoded',
                'Accept'       => 'application/xml',
            ],
            http_build_query(['prmstr' => $this->prepareXml($data)])
        );

        if ($httpResponse->getStatusCode() !== 200) {

            throw new OmnipayVakifbankFetchTransactionRequestException('Fetch Transaction Request sırasında bir hata oluştu.', $httpResponse->getStatusCode());

        }

        return new FetchTransactionResponse($this, $httpResponse);
    }
}

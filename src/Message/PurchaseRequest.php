<?php

namespace Omnipay\Vakifbank\Message;

use Omnipay\Common\Exception\InvalidCreditCardException;
use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Message\AbstractRequest;
use Omnipay\Vakifbank\Exceptions\OmnipayVakifbankEnrollmentRequestException;
use Omnipay\Vakifbank\Exceptions\OmnipayVakifbankEnrollmentResponseException;
use Omnipay\Vakifbank\Exceptions\OmnipayVakifbankPurchaseRequestException;
use Omnipay\Vakifbank\Traits\PurchaseGettersSetters;

class PurchaseRequest extends AbstractRequest
{
    use PurchaseGettersSetters;

    protected $test_endpoint = "https://onlineodemetest.vakifbank.com.tr:4443/VposService/v3/Vposreq.aspx";

    protected $prod_endpoint = "https://onlineodeme.vakifbank.com.tr:4443/VposService/v3/Vposreq.aspx";

    /**
     * @throws InvalidRequestException
     * @throws InvalidCreditCardException
     */
    public function getData()
    {
        $this->validate(
            'merchant_id',
            'password',
            'terminal_no',
            'amount',
            'currency',
            'transactionId',
            'transaction_type',
            'eci',
            'cavv',
            'transactionReference',
            'client_ip',
            'transaction_device_source',
            'testMode',
            'order_id',
            'installment',
        );

        $this->getCard()->validate();

        return [
            'MerchantId'              => $this->getMerchantId(),
            'Password'                => $this->getPassword(),
            'TerminalNo'              => $this->getTerminalNo(),
            'Pan'                     => $this->getCard()->getNumber(),
            'Cvv'                     => $this->getCard()->getCvv(),
            'Expiry'                  => $this->getCard()->getExpiryDate('Ym'),
            'CardHoldersName'         => $this->getCard()->getName(),
            'CurrencyAmount'          => $this->getAmount(),
            'CurrencyCode'            => $this->getCurrencyNumeric(),
            'TransactionType'         => $this->getTransactionType(),
            'ECI'                     => $this->getEci(),
            'CAVV'                    => $this->getCavv(),
            'MpiTransactionId'        => $this->getTransactionReference(),
            'ClientIp'                => $this->getClientIp(),
            'TransactionDeviceSource' => $this->getTransactionDeviceSource(),
            'OrderId'                 => $this->getOrderId(),
            'OrderDescription'        => $this->getDescription(),
            'NumberOfInstallments'    => $this->getInstallment(),
        ];
    }

    private function prepareXml(array $data): string
    {
        $xml = '';
        foreach ($data as $k => $v) {
            $xml .= "<$k>$v</$k>";
        }

        return "<VposRequest>$xml</VposRequest>";
    }

    /**
     * @throws OmnipayVakifbankEnrollmentRequestException
     * @throws OmnipayVakifbankEnrollmentResponseException
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

            throw new OmnipayVakifbankPurchaseRequestException('Purchase Request sırasında bir hata oluştu.', $httpResponse->getStatusCode());

        }

        return new PurchaseResponse($this, $httpResponse);
    }

    protected function createResponse($data): EnrollmentResponse
    {

    }
}

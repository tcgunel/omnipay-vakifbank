<?php

namespace Omnipay\Vakifbank\Message;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Message\AbstractRequest;
use Omnipay\Vakifbank\Constants\TransactionTypes;
use Omnipay\Vakifbank\Exceptions\OmnipayVakifbankRefundRequestException;
use Omnipay\Vakifbank\Traits\PurchaseGettersSetters;

class RefundRequest extends AbstractRequest
{
    use PurchaseGettersSetters;

    protected $test_endpoint = 'https://onlineodemetest.vakifbank.com.tr:4443/VposService/v3/Vposreq.aspx';

    protected $prod_endpoint = 'https://onlineodeme.vakifbank.com.tr:4443/VposService/v3/Vposreq.aspx';

    /**
     * @throws InvalidRequestException
     */
    public function getData()
    {
        $this->validate(
            'merchant_id',
            'password',
            'terminal_no',
            'transactionReference',
            'amount',
            'currency',
        );

        return [
            'MerchantId' => $this->getMerchantId(),
            'Password' => $this->getPassword(),
            'TerminalNo' => $this->getTerminalNo(),
            'TransactionType' => TransactionTypes::REFUND,
            'ReferenceTransactionId' => $this->getTransactionReference(),
            'CurrencyAmount' => number_format((float) $this->getAmount(), 2, '.', ''),
            'CurrencyCode' => $this->getCurrencyNumeric(),
        ];
    }

    private function prepareXml(array $data): string
    {
        $xml = '';
        foreach ($data as $k => $v) {
            $xml .= "<$k>" . htmlspecialchars((string) $v, ENT_XML1, 'UTF-8') . "</$k>";
        }

        return "<VposRequest>$xml</VposRequest>";
    }

    /**
     * @throws OmnipayVakifbankRefundRequestException
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
            http_build_query(['prmstr' => $this->prepareXml($data)])
        );

        if ($httpResponse->getStatusCode() !== 200) {

            throw new OmnipayVakifbankRefundRequestException('Refund Request sirasinda bir hata olustu.', $httpResponse->getStatusCode());

        }

        return new RefundResponse($this, $httpResponse);
    }
}

<?php

namespace Omnipay\Vakifbank\Message;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Message\AbstractRequest;
use Omnipay\Vakifbank\Constants\TransactionTypes;
use Omnipay\Vakifbank\Exceptions\OmnipayVakifbankVoidRequestException;
use Omnipay\Vakifbank\Traits\PurchaseGettersSetters;

class VoidRequest extends AbstractRequest
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
        );

        return [
            'MerchantId' => $this->getMerchantId(),
            'Password' => $this->getPassword(),
            'TerminalNo' => $this->getTerminalNo(),
            'TransactionType' => TransactionTypes::CANCEL,
            'ReferenceTransactionId' => $this->getTransactionReference(),
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
     * @throws OmnipayVakifbankVoidRequestException
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

            throw new OmnipayVakifbankVoidRequestException('Void Request sirasinda bir hata olustu.', $httpResponse->getStatusCode());

        }

        return new VoidResponse($this, $httpResponse);
    }
}

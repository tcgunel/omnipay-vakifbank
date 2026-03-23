<?php

namespace Omnipay\Vakifbank\Message;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Message\AbstractRequest;
use Omnipay\Vakifbank\Exceptions\OmnipayVakifbankCommonPaymentRequestException;
use Omnipay\Vakifbank\Traits\PurchaseGettersSetters;

class CommonPaymentRegisterRequest extends AbstractRequest
{
    use PurchaseGettersSetters;

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
            'transactionId',
            'amount',
            'currency',
            'returnUrl',
            'cancelUrl',
            'installment',
        );

        $amount = number_format((float) $this->getAmount(), 2, '.', '');
        $currencyCode = (string) $this->getCurrencyNumeric();
        $transactionId = $this->getTransactionId();

        $data = [
            'HostMerchantId' => $this->getMerchantId(),
            'MerchantPassword' => $this->getPassword(),
            'HostTerminalId' => $this->getTerminalNo(),
            'TransactionId' => $transactionId,
            'Amount' => $amount,
            'CurrencyCode' => $currencyCode,
            'IsSecure' => 'true',
            'SuccessUrl' => $this->getReturnUrl(),
            'FailUrl' => $this->getCancelUrl(),
        ];

        if ($this->getDescription()) {
            $data['OrderDescription'] = $this->getDescription();
        }

        if ($this->getInstallment() > 1) {
            $data['InstallmentCount'] = (string) $this->getInstallment();
        }

        // Compute hash: SHA256(merchant_id + terminal_no + amount + currency_code + transaction_id + password)
        $hashInput = $this->getMerchantId() . $this->getTerminalNo() . $amount . $currencyCode . $transactionId . $this->getPassword();
        $hashInput = mb_convert_encoding($hashInput, 'ISO-8859-9', 'UTF-8');
        $data['HashData'] = base64_encode(hash('sha256', $hashInput, true));

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
                'Accept' => 'text/html',
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

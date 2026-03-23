<?php

namespace Omnipay\Vakifbank\Tests\Feature;

use Omnipay\Vakifbank\Constants\TransactionTypes;
use Omnipay\Vakifbank\Message\PurchaseRequest;
use Omnipay\Vakifbank\Tests\TestCase;

class PurchaseTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    public function test_purchase_request(): void
    {
        $params = [
            'merchant_id' => 'mid',
            'password' => 'P@ssw0rd',
            'terminal_no' => 'terminal_no',
            'testMode' => true,
            'amount' => '101.01',
            'currency' => 'TRY',
            'transactionId' => '77777777',
            'order_id' => '99999999',
            'transaction_type' => TransactionTypes::SALE,
            'eci' => 'eci',
            'cavv' => 'cavv',
            'transactionReference' => 'trans_reference',
            'client_ip' => '127.0.0.1',
            'transaction_device_source' => '0',
            'installment' => '1',
            'card' => [
                'number' => '6501700161161969',
                'expiryYear' => '2030',
                'expiryMonth' => '01',
                'cvv' => '555',
                'name' => 'card holder name',
            ],
        ];

        $params_to_be_expected_back = [
            'MerchantId' => 'mid',
            'Password' => 'P@ssw0rd',
            'TerminalNo' => 'terminal_no',
            'Pan' => '6501700161161969',
            'Cvv' => '555',
            'Expiry' => '203001',
            'CardHoldersName' => 'card holder name',
            'CurrencyAmount' => '101.01',
            'CurrencyCode' => '949',
            'TransactionType' => 'Sale',
            'ECI' => 'eci',
            'CAVV' => 'cavv',
            'MpiTransactionId' => 'trans_reference',
            'ClientIp' => '127.0.0.1',
            'TransactionDeviceSource' => '0',
            'OrderId' => '99999999',
            'OrderDescription' => null,
        ];

        $request = new PurchaseRequest($this->getHttpClient(), $this->getHttpRequest());

        $request->initialize($params);

        $data = $request->getData();

        self::assertSame($data, $params_to_be_expected_back);
    }
}

<?php

namespace Omnipay\Vakifbank\Tests\Feature;

use Omnipay\Vakifbank\Constants\TransactionTypes;
use Omnipay\Vakifbank\Message\VoidRequest;
use Omnipay\Vakifbank\Message\VoidResponse;
use Omnipay\Vakifbank\Models\VoidResponseModel;
use Omnipay\Vakifbank\Tests\TestCase;

class VoidTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    public function test_void_request(): void
    {
        $params = [
            'merchant_id' => 'mid',
            'password' => 'P@ssw0rd',
            'terminal_no' => 'terminal_no',
            'testMode' => true,
            'transactionReference' => 'original_trans_id',
        ];

        $params_to_be_expected_back = [
            'MerchantId' => 'mid',
            'Password' => 'P@ssw0rd',
            'TerminalNo' => 'terminal_no',
            'TransactionType' => TransactionTypes::CANCEL,
            'ReferenceTransactionId' => 'original_trans_id',
        ];

        $request = new VoidRequest($this->getHttpClient(), $this->getHttpRequest());

        $request->initialize($params);

        $data = $request->getData();

        self::assertSame($data, $params_to_be_expected_back);
    }

    public function test_void_response_successful(): void
    {
        $response_data = new VoidResponseModel([
            'MerchantId' => 'mid',
            'TransactionType' => 'Cancel',
            'TransactionId' => 'txn_456',
            'ResultCode' => '0000',
            'ResultDetail' => 'Basarili',
            'AuthCode' => 'auth_456',
            'HostDate' => '2026-03-23',
            'Rrn' => 'rrn_456',
            'TerminalNo' => 'terminal_no',
            'CurrencyAmount' => null,
            'CurrencyCode' => null,
            'BatchNo' => '1',
        ]);

        $response = new VoidResponse($this->getMockRequest(), $response_data);

        $this->assertTrue($response->isSuccessful());
        $this->assertEquals('Basarili', $response->getMessage());
        $this->assertEquals('auth_456', $response->getData()->AuthCode);
        $this->assertEquals('txn_456', $response->getData()->TransactionId);
    }

    public function test_void_response_failed(): void
    {
        $response_data = new VoidResponseModel([
            'MerchantId' => 'mid',
            'TransactionType' => 'Cancel',
            'TransactionId' => 'txn_456',
            'ResultCode' => '0001',
            'ResultDetail' => 'Islem basarisiz',
            'AuthCode' => null,
            'HostDate' => null,
            'Rrn' => null,
            'TerminalNo' => 'terminal_no',
            'CurrencyAmount' => null,
            'CurrencyCode' => null,
            'BatchNo' => null,
        ]);

        $response = new VoidResponse($this->getMockRequest(), $response_data);

        $this->assertFalse($response->isSuccessful());
        $this->assertEquals('Islem basarisiz', $response->getMessage());
    }

    public function test_void_gateway_method(): void
    {
        $request = $this->gateway->void([
            'merchant_id' => 'mid',
            'password' => 'P@ssw0rd',
            'terminal_no' => 'terminal_no',
            'testMode' => true,
            'transactionReference' => 'original_trans_id',
        ]);

        $this->assertInstanceOf(VoidRequest::class, $request);
    }
}

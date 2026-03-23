<?php

namespace Omnipay\Vakifbank\Tests\Feature;

use Omnipay\Vakifbank\Constants\TransactionTypes;
use Omnipay\Vakifbank\Message\RefundRequest;
use Omnipay\Vakifbank\Message\RefundResponse;
use Omnipay\Vakifbank\Models\RefundResponseModel;
use Omnipay\Vakifbank\Tests\TestCase;

class RefundTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    public function test_refund_request(): void
    {
        $params = [
            'merchant_id' => 'mid',
            'password' => 'P@ssw0rd',
            'terminal_no' => 'terminal_no',
            'testMode' => true,
            'amount' => '55.00',
            'currency' => 'TRY',
            'transactionReference' => 'original_trans_id',
        ];

        $params_to_be_expected_back = [
            'MerchantId' => 'mid',
            'Password' => 'P@ssw0rd',
            'TerminalNo' => 'terminal_no',
            'TransactionType' => TransactionTypes::REFUND,
            'ReferenceTransactionId' => 'original_trans_id',
            'CurrencyAmount' => '55.00',
            'CurrencyCode' => '949',
        ];

        $request = new RefundRequest($this->getHttpClient(), $this->getHttpRequest());

        $request->initialize($params);

        $data = $request->getData();

        self::assertSame($data, $params_to_be_expected_back);
    }

    public function test_refund_response_successful(): void
    {
        $response_data = new RefundResponseModel([
            'MerchantId' => 'mid',
            'TransactionType' => 'Refund',
            'TransactionId' => 'txn_123',
            'ResultCode' => '0000',
            'ResultDetail' => 'Basarili',
            'AuthCode' => 'auth_123',
            'HostDate' => '2026-03-23',
            'Rrn' => 'rrn_123',
            'TerminalNo' => 'terminal_no',
            'CurrencyAmount' => '55.00',
            'CurrencyCode' => '949',
            'BatchNo' => '1',
        ]);

        $response = new RefundResponse($this->getMockRequest(), $response_data);

        $this->assertTrue($response->isSuccessful());
        $this->assertEquals('Basarili', $response->getMessage());
        $this->assertEquals('auth_123', $response->getData()->AuthCode);
        $this->assertEquals('txn_123', $response->getData()->TransactionId);
    }

    public function test_refund_response_failed(): void
    {
        $response_data = new RefundResponseModel([
            'MerchantId' => 'mid',
            'TransactionType' => 'Refund',
            'TransactionId' => 'txn_123',
            'ResultCode' => '0001',
            'ResultDetail' => 'Islem basarisiz',
            'AuthCode' => null,
            'HostDate' => null,
            'Rrn' => null,
            'TerminalNo' => 'terminal_no',
            'CurrencyAmount' => '55.00',
            'CurrencyCode' => '949',
            'BatchNo' => null,
        ]);

        $response = new RefundResponse($this->getMockRequest(), $response_data);

        $this->assertFalse($response->isSuccessful());
        $this->assertEquals('Islem basarisiz', $response->getMessage());
    }

    public function test_refund_gateway_method(): void
    {
        $request = $this->gateway->refund([
            'merchant_id' => 'mid',
            'password' => 'P@ssw0rd',
            'terminal_no' => 'terminal_no',
            'testMode' => true,
            'amount' => '55.00',
            'currency' => 'TRY',
            'transactionReference' => 'original_trans_id',
        ]);

        $this->assertInstanceOf(RefundRequest::class, $request);
    }
}

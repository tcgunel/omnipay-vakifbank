<?php

namespace Omnipay\Vakifbank\Tests\Feature;

use Omnipay\Vakifbank\Message\CommonPaymentQueryRequest;
use Omnipay\Vakifbank\Message\CommonPaymentQueryResponse;
use Omnipay\Vakifbank\Models\CommonPaymentQueryResponseModel;
use Omnipay\Vakifbank\Tests\TestCase;

class CommonPaymentQueryTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    public function test_common_payment_query_request_with_payment_token(): void
    {
        $params = [
            'merchant_id' => 'mid',
            'password' => 'P@ssw0rd',
            'terminal_no' => 'terminal_no',
            'testMode' => true,
            'payment_token' => 'ABC123TOKEN',
        ];

        $request = new CommonPaymentQueryRequest($this->getHttpClient(), $this->getHttpRequest());

        $request->initialize($params);

        $data = $request->getData();

        self::assertEquals('mid', $data['HostMerchantId']);
        self::assertEquals('P@ssw0rd', $data['MerchantPassword']);
        self::assertEquals('terminal_no', $data['HostTerminalId']);
        self::assertEquals('ABC123TOKEN', $data['PaymentToken']);
    }

    public function test_common_payment_query_request_with_transaction_id(): void
    {
        $params = [
            'merchant_id' => 'mid',
            'password' => 'P@ssw0rd',
            'terminal_no' => 'terminal_no',
            'testMode' => true,
            'transactionId' => 'txn_unique_123',
        ];

        $request = new CommonPaymentQueryRequest($this->getHttpClient(), $this->getHttpRequest());

        $request->initialize($params);

        $data = $request->getData();

        self::assertEquals('mid', $data['HostMerchantId']);
        self::assertEquals('txn_unique_123', $data['TransactionId']);
    }

    public function test_common_payment_query_response_successful(): void
    {
        $response_data = new CommonPaymentQueryResponseModel([
            'RC' => '0000',
            'AuthCode' => 'auth_789',
            'TransactionId' => 'txn_unique_123',
            'Amount' => '55.00',
            'InstallmentCount' => '1',
            'ErrorMessage' => null,
            'Rrn' => 'rrn_789',
            'MerchantId' => 'mid',
            'TerminalNo' => 'terminal_no',
        ]);

        $response = new CommonPaymentQueryResponse($this->getMockRequest(), $response_data);

        $this->assertTrue($response->isSuccessful());
        $this->assertEquals('auth_789', $response->getData()->AuthCode);
        $this->assertEquals('txn_unique_123', $response->getData()->TransactionId);
        $this->assertEquals('55.00', $response->getData()->Amount);
    }

    public function test_common_payment_query_response_failed(): void
    {
        $response_data = new CommonPaymentQueryResponseModel([
            'RC' => '0001',
            'AuthCode' => null,
            'TransactionId' => 'txn_unique_123',
            'Amount' => '55.00',
            'InstallmentCount' => null,
            'ErrorMessage' => 'Islem basarisiz',
            'Rrn' => null,
            'MerchantId' => 'mid',
            'TerminalNo' => 'terminal_no',
        ]);

        $response = new CommonPaymentQueryResponse($this->getMockRequest(), $response_data);

        $this->assertFalse($response->isSuccessful());
        $this->assertEquals('Islem basarisiz', $response->getMessage());
    }

    public function test_common_payment_query_gateway_method(): void
    {
        $request = $this->gateway->commonPaymentQuery([
            'merchant_id' => 'mid',
            'password' => 'P@ssw0rd',
            'terminal_no' => 'terminal_no',
            'testMode' => true,
            'payment_token' => 'ABC123TOKEN',
        ]);

        $this->assertInstanceOf(CommonPaymentQueryRequest::class, $request);
    }
}

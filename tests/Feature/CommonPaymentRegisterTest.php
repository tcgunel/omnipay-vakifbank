<?php

namespace Omnipay\Vakifbank\Tests\Feature;

use Omnipay\Vakifbank\Message\CommonPaymentRegisterRequest;
use Omnipay\Vakifbank\Message\CommonPaymentRegisterResponse;
use Omnipay\Vakifbank\Tests\TestCase;

class CommonPaymentRegisterTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    public function test_common_payment_register_request(): void
    {
        $params = [
            'merchant_id' => 'mid',
            'password' => 'P@ssw0rd',
            'terminal_no' => 'terminal_no',
            'testMode' => true,
            'transactionId' => 'txn_unique_123',
            'amount' => '55.00',
            'currency' => 'TRY',
            'returnUrl' => 'https://example.com/success',
            'cancelUrl' => 'https://example.com/fail',
            'description' => 'Test order',
            'installment' => '1',
        ];

        $request = new CommonPaymentRegisterRequest($this->getHttpClient(), $this->getHttpRequest());

        $request->initialize($params);

        $data = $request->getData();

        self::assertEquals('mid', $data['HostMerchantId']);
        self::assertEquals('P@ssw0rd', $data['MerchantPassword']);
        self::assertEquals('terminal_no', $data['HostTerminalId']);
        self::assertEquals('txn_unique_123', $data['TransactionId']);
        self::assertEquals('55.00', $data['Amount']);
        self::assertEquals('949', $data['CurrencyCode']);
        self::assertEquals('true', $data['IsSecure']);
        self::assertEquals('https://example.com/success', $data['SuccessUrl']);
        self::assertEquals('https://example.com/fail', $data['FailUrl']);
        self::assertEquals('Test order', $data['OrderDescription']);
        self::assertArrayHasKey('HashData', $data);
        self::assertArrayNotHasKey('InstallmentCount', $data);
    }

    public function test_common_payment_register_request_with_installment(): void
    {
        $params = [
            'merchant_id' => 'mid',
            'password' => 'P@ssw0rd',
            'terminal_no' => 'terminal_no',
            'testMode' => true,
            'transactionId' => 'txn_unique_123',
            'amount' => '55.00',
            'currency' => 'TRY',
            'returnUrl' => 'https://example.com/success',
            'cancelUrl' => 'https://example.com/fail',
            'installment' => '3',
        ];

        $request = new CommonPaymentRegisterRequest($this->getHttpClient(), $this->getHttpRequest());

        $request->initialize($params);

        $data = $request->getData();

        self::assertEquals('3', $data['InstallmentCount']);
    }

    public function test_common_payment_register_hash_computation(): void
    {
        $params = [
            'merchant_id' => 'mid',
            'password' => 'P@ssw0rd',
            'terminal_no' => 'terminal_no',
            'testMode' => true,
            'transactionId' => 'txn_unique_123',
            'amount' => '55.00',
            'currency' => 'TRY',
            'returnUrl' => 'https://example.com/success',
            'cancelUrl' => 'https://example.com/fail',
            'installment' => '1',
        ];

        $request = new CommonPaymentRegisterRequest($this->getHttpClient(), $this->getHttpRequest());

        $request->initialize($params);

        $data = $request->getData();

        // Manually compute expected hash
        $hashInput = 'mid' . 'terminal_no' . '55.00' . '949' . 'txn_unique_123' . 'P@ssw0rd';
        $hashInput = mb_convert_encoding($hashInput, 'ISO-8859-9', 'UTF-8');
        $expectedHash = base64_encode(hash('sha256', $hashInput, true));

        self::assertEquals($expectedHash, $data['HashData']);
    }

    public function test_common_payment_register_response_successful(): void
    {
        $mockRequest = $this->getMockRequest();
        $mockRequest->shouldReceive('getTestMode')->andReturn(true);

        $response = new CommonPaymentRegisterResponse($mockRequest, 'PaymentToken=ABC123TOKEN');

        $this->assertTrue($response->isSuccessful());
        $this->assertTrue($response->isRedirect());
        $this->assertEquals('ABC123TOKEN', $response->getPaymentToken());
        $this->assertStringContainsString('ABC123TOKEN', $response->getRedirectUrl());
        $this->assertStringContainsString('onlineodemetest.vakifbank.com.tr', $response->getRedirectUrl());
    }

    public function test_common_payment_register_response_successful_prod(): void
    {
        $mockRequest = $this->getMockRequest();
        $mockRequest->shouldReceive('getTestMode')->andReturn(false);

        $response = new CommonPaymentRegisterResponse($mockRequest, 'PaymentToken=ABC123TOKEN');

        $this->assertTrue($response->isSuccessful());
        $this->assertStringContainsString('web.vakifbank.com.tr', $response->getRedirectUrl());
    }

    public function test_common_payment_register_response_failed(): void
    {
        $mockRequest = $this->getMockRequest();
        $mockRequest->shouldReceive('getTestMode')->andReturn(true);

        $response = new CommonPaymentRegisterResponse($mockRequest, 'ErrorMessage=Merchant not found');

        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertEquals('Merchant not found', $response->getMessage());
    }

    public function test_common_payment_register_response_empty_token(): void
    {
        $mockRequest = $this->getMockRequest();
        $mockRequest->shouldReceive('getTestMode')->andReturn(true);

        $response = new CommonPaymentRegisterResponse($mockRequest, 'PaymentToken=');

        $this->assertFalse($response->isSuccessful());
    }

    public function test_common_payment_register_gateway_method(): void
    {
        $request = $this->gateway->commonPaymentRegister([
            'merchant_id' => 'mid',
            'password' => 'P@ssw0rd',
            'terminal_no' => 'terminal_no',
            'testMode' => true,
            'transactionId' => 'txn_unique_123',
            'amount' => '55.00',
            'currency' => 'TRY',
            'returnUrl' => 'https://example.com/success',
            'cancelUrl' => 'https://example.com/fail',
            'installment' => '1',
        ]);

        $this->assertInstanceOf(CommonPaymentRegisterRequest::class, $request);
    }
}

<?php

namespace Omnipay\Vakifbank\Tests\Feature;

use Omnipay\Vakifbank\Constants\CardBrandTypes;
use Omnipay\Vakifbank\Message\EnrollmentRequest;
use Omnipay\Vakifbank\Message\EnrollmentResponse;
use Omnipay\Vakifbank\Models\EnrollmentResponseModel;
use Omnipay\Vakifbank\Tests\TestCase;

class EnrollmentTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    public function test_enrollment_request(): void
    {
        $params = [
            'merchant_id'   => 'mid',
            'password'      => 'P@ssw0rd',
            'secure'        => true,
            'test_mode'     => true,
            'currency'      => 'TRY',
            'amount'        => '101.01',
            'transactionId' => '77777777',
            'order_id'      => '88888888',
            'return_url'    => 'https://omnipay.dev/success_url',
            'cancel_url'    => 'https://omnipay.dev/cancel_url',
            'description'   => '',
            'card'          => [
                'number'      => '6501700161161969',
                'expiryYear'  => '2024',
                'expiryMonth' => '01',
            ],
        ];

        $params_to_be_expected_back = [
            'Pan'                       => '6501700161161969',
            'ExpiryDate'                => '2401',
            'PurchaseAmount'            => '101.01',
            'Currency'                  => '949',
            'BrandName'                 => CardBrandTypes::TROY,
            'VerifyEnrollmentRequestId' => '77777777',
            'SessionInfo'               => '',
            'MerchantId'                => 'mid',
            'MerchantPassword'          => 'P@ssw0rd',
            'SuccessUrl'                => 'https://omnipay.dev/success_url',
            'FailureUrl'                => 'https://omnipay.dev/cancel_url',
            'InstallmentCount'          => null,
        ];

        $request = new EnrollmentRequest($this->getHttpClient(), $this->getHttpRequest());

        $request->initialize($params);

        $data = $request->getData();

        self::assertEquals($data, $params_to_be_expected_back);
    }

    public function test_enrollment_response(): void
    {
        $response_data = new EnrollmentResponseModel([
            'MessageErrorCode' => 0,
            'ErrorMessage'     => '',
            'ID'               => '',
            'Version'          => '',
            'Status'           => 'Y',
            'PaReq'            => 'https://pareq.test',
            'ACSUrl'           => 'https://acsurl.test',
            'TermUrl'          => 'https://termurl.test',
            'MD'               => 'md',
            'ACTUALBRAND'      => 0,
        ]);

        $response = new EnrollmentResponse($this->getMockRequest(), $response_data);

        $data = $response->getData();

        $this->assertTrue($response->isSuccessful());

        $this->assertEquals($response->getRedirectUrl(), $data->ACSUrl);

        $this->assertEquals([
            'PaReq'   => $data->PaReq,
            'TermUrl' => $data->TermUrl,
            'MD'      => $data->MD,
        ], $response->getRedirectData());
    }
}

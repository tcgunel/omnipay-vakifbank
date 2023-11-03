<?php

namespace Omnipay\Vakifbank\Message;

use Omnipay\Common\Exception\InvalidCreditCardException;
use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Message\AbstractRequest;
use Omnipay\Vakifbank\Constants\CardBrandTypes;
use Omnipay\Vakifbank\Exceptions\OmnipayVakifbankEnrollmentRequestException;
use Omnipay\Vakifbank\Exceptions\OmnipayVakifbankEnrollmentResponseException;
use Omnipay\Vakifbank\Helpers\Helper;
use Omnipay\Vakifbank\Models\EnrollmentResponseModel;
use Omnipay\Vakifbank\Traits\PurchaseGettersSetters;

/**
 * Vakifbank 3D Secure enrolment request
 */
class EnrollmentRequest extends AbstractRequest
{
    use PurchaseGettersSetters;

    protected $test_endpoint = "https://3dsecuretest.vakifbank.com.tr:4443/MPIAPI/MPI_Enrollment.aspx";

    protected $prod_endpoint = "https://3dsecure.vakifbank.com.tr:4443/MPIAPI/MPI_Enrollment.aspx";

    /**
     * @throws InvalidRequestException
     * @throws InvalidCreditCardException
     */
    public function getData()
    {
        $this->validate(
            'amount',
            'currency',
            'transactionId',
            //'description',
            'merchant_id',
            'password',
            'returnUrl',
            'cancelUrl',
            'installment',
            'testMode',
        );

        $this->getCard()->validate();

        $this->getCard()->addSupportedBrand('troy', '/^(?:9792|65\d{2}|36|2205)\d{12}$/');

		$data = [
			'Pan'                       => $this->getCard()->getNumber(),
			'ExpiryDate'                => $this->getCard()->getExpiryDate('ym'),
			'PurchaseAmount'            => $this->getAmount(),
			'Currency'                  => $this->getCurrencyNumeric(),
			'BrandName'                 => CardBrandTypes::get($this->getCard()->getBrand()),
			'VerifyEnrollmentRequestId' => $this->getTransactionId(),
			'SessionInfo'               => $this->getDescription(),
			'MerchantId'                => $this->getMerchantId(),
			'MerchantPassword'          => $this->getPassword(),
			'SuccessUrl'                => $this->getReturnUrl(),
			'FailureUrl'                => $this->getCancelUrl(),
		];

		if ($this->getInstallment() > 1){

			$data['InstallmentCount'] = $this->getInstallment();

		}

        return $data;
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
            http_build_query($data)
        );

        if ($httpResponse->getStatusCode() !== 200) {

            throw new OmnipayVakifbankEnrollmentRequestException('Enrollment Request sırasında bir hata oluştu.', $httpResponse->getStatusCode());

        }

        try {

            $response = new EnrollmentResponseModel(
                Helper::flattenArray(
                    json_decode(
                        json_encode(
                            simplexml_load_string((string)$httpResponse->getBody()),
                            JSON_THROW_ON_ERROR
                        ),
                        true,
                        512,
                        JSON_THROW_ON_ERROR
                    )
                )
            );

        } catch (\JsonException $jsonException) {

            throw new OmnipayVakifbankEnrollmentResponseException('Enrollment Response sırasında bir hata oluştu. Hata mesajı: ' . $jsonException->getMessage());

        }

        return $this->response = new EnrollmentResponse($this, $response);
    }

    protected function createResponse($data): EnrollmentResponse
    {

    }
}

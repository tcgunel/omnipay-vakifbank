<?php

namespace Omnipay\Vakifbank\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RequestInterface;
use Omnipay\Vakifbank\Exceptions\OmnipayVakifbankPurchaseResponseException;
use Omnipay\Vakifbank\Models\PurchaseResponseModel;
use Psr\Http\Message\ResponseInterface;
use Omnipay\Vakifbank\Helpers\Helper;

class PurchaseResponse extends AbstractResponse
{
    protected $response;

    protected $request;

    public function __construct(RequestInterface $request, $data)
    {
        parent::__construct($request, $data);

        $this->request = $request;

        $this->response = $data;

        if ($this->response instanceof ResponseInterface) {

            try {

                var_dump((string)$this->response->getBody());

                $this->response = new PurchaseResponseModel(
                    Helper::flattenArray(
                        json_decode(
                            json_encode(
                                simplexml_load_string((string)$this->response->getBody()),
                                JSON_THROW_ON_ERROR
                            ),
                            true,
                            512,
                            JSON_THROW_ON_ERROR
                        )
                    )
                );

            } catch (\JsonException $jsonException) {

                throw new OmnipayVakifbankPurchaseResponseException('Purchase Response sırasında bir hata oluştu. Hata mesajı: ' . $jsonException->getMessage());

            }

        }
    }

    public function isSuccessful(): bool
    {
        return $this->response->ResultCode === '0000';
    }

    public function getMessage(): string
    {
        return $this->response->ResultDetail;
    }

    public function getData(): PurchaseResponseModel
    {
        return $this->response;
    }

    public function getRedirectData()
    {
        return null;
    }

    public function getRedirectUrl()
    {
        return '';
    }
}

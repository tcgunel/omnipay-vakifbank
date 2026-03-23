<?php

namespace Omnipay\Vakifbank\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RequestInterface;
use Omnipay\Vakifbank\Exceptions\OmnipayVakifbankRefundResponseException;
use Omnipay\Vakifbank\Helpers\Helper;
use Omnipay\Vakifbank\Models\RefundResponseModel;
use Psr\Http\Message\ResponseInterface;

class RefundResponse extends AbstractResponse
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

                $xmlResult = simplexml_load_string((string) $this->response->getBody());
                if ($xmlResult === false) {
                    throw new OmnipayVakifbankRefundResponseException('Refund Response geçersiz XML döndürdü.');
                }

                $this->response = new RefundResponseModel(
                    Helper::flattenArray(
                        json_decode(
                            json_encode(
                                $xmlResult,
                                JSON_THROW_ON_ERROR
                            ),
                            true,
                            512,
                            JSON_THROW_ON_ERROR
                        )
                    )
                );

            } catch (\JsonException $jsonException) {

                throw new OmnipayVakifbankRefundResponseException('Refund Response sirasinda bir hata olustu. Hata mesaji: ' . $jsonException->getMessage());

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

    public function getData(): RefundResponseModel
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

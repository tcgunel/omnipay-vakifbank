<?php

namespace Omnipay\Vakifbank\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RequestInterface;
use Omnipay\Vakifbank\Exceptions\OmnipayVakifbankVoidResponseException;
use Omnipay\Vakifbank\Helpers\Helper;
use Omnipay\Vakifbank\Models\VoidResponseModel;
use Psr\Http\Message\ResponseInterface;

class VoidResponse extends AbstractResponse
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
                    throw new OmnipayVakifbankVoidResponseException('Void Response geçersiz XML döndürdü.');
                }

                $this->response = new VoidResponseModel(
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

                throw new OmnipayVakifbankVoidResponseException('Void Response sirasinda bir hata olustu. Hata mesaji: ' . $jsonException->getMessage());

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

    public function getData(): VoidResponseModel
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

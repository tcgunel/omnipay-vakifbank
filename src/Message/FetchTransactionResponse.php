<?php

namespace Omnipay\Vakifbank\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RequestInterface;
use Omnipay\Vakifbank\Exceptions\OmnipayVakifbankFetchTransactionNotSuccessfulException;
use Omnipay\Vakifbank\Models\FetchTransactionResponseModel;
use Psr\Http\Message\ResponseInterface;

class FetchTransactionResponse extends AbstractResponse
{
    protected $response;

    protected $transaction_info;

    protected $request;

    public function __construct(RequestInterface $request, $data)
    {
        parent::__construct($request, $data);

        $this->request = $request;

        $this->response = $data;

        if ($this->response instanceof ResponseInterface) {

            $this->response = simplexml_load_string((string)$this->response->getBody());

            if ((string)$this->response->ResponseInfo->ResponseCode !== '0000'){

                throw new OmnipayVakifbankFetchTransactionNotSuccessfulException('Fetch transaction sırasında bir hata oluştu.' . $this->response->ResponseInfo->ResponseMessage);

            }

            $this->transaction_info = new FetchTransactionResponseModel(json_decode(json_encode($this->response->TransactionSearchResultInfo->TransactionSearchResultInfo[0], JSON_THROW_ON_ERROR), true, 512, JSON_THROW_ON_ERROR));
        }
    }

    public function isSuccessful(): bool
    {
        return (string)$this->response->ResponseInfo->ResponseCode === '0000' && $this->transaction_info->HostResultCode === '000' && $this->transaction_info->ResultCode === '0000';
    }

    public function getMessage(): string
    {
        return (string)$this->response->ResponseInfo->ResponseCode !== '0000' ? (string)$this->response->ResponseInfo->ResponseMessage : $this->transaction_info->ResponseMessage;
    }

    public function getData(): FetchTransactionResponseModel
    {
        return $this->transaction_info;
    }
}

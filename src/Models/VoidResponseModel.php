<?php

namespace Omnipay\Vakifbank\Models;

class VoidResponseModel extends BaseModel
{
    public ?string $MerchantId;
    public ?string $TransactionType;
    public ?string $TransactionId;
    public ?string $ResultCode;
    public ?string $ResultDetail;
    public ?string $AuthCode;
    public ?string $HostDate;
    public ?string $Rrn;
    public ?string $TerminalNo;
    public ?string $CurrencyAmount;
    public ?string $CurrencyCode;
    public ?string $BatchNo;
}

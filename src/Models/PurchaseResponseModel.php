<?php

namespace Omnipay\Vakifbank\Models;

class PurchaseResponseModel extends BaseModel
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
    public ?string $TotalPoint;
    public ?string $CurrencyAmount;
    public ?string $CurrencyCode;
    public ?string $ECI;
    public ?string $ThreeDSecureType;
    public ?string $TransactionDeviceSource;
    public ?string $BatchNo;
    public ?string $TLAmount;
}

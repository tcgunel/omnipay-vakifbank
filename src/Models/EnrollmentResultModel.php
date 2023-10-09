<?php

namespace Omnipay\Vakifbank\Models;

class EnrollmentResultModel extends BaseModel
{
    public ?string $MerchantId;
    public ?string $Pan;
    public ?string $Expiry;
    public ?string $PurchAmount;
    public ?string $PurchCurrency;
    public ?string $VerifyEnrollmentRequestId;
    public ?string $Xid;
    public ?string $SessionInfo;
    public ?string $Status;
    public ?string $Cavv;
    public ?string $Eci;
    public ?string $ExpSign;
    public ?string $InstallmentCount;
    public ?string $SubMerchantNo;
    public ?string $SubMerchantName;
    public ?string $SubMerchantNumber;
    public ?string $ErrorCode;
    public ?string $ErrorMessage;
}

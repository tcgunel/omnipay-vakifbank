<?php

namespace Omnipay\Vakifbank\Models;

class CommonPaymentQueryResponseModel extends BaseModel
{
    public ?string $RC;
    public ?string $AuthCode;
    public ?string $TransactionId;
    public ?string $Amount;
    public ?string $InstallmentCount;
    public ?string $ErrorMessage;
    public ?string $Rrn;
    public ?string $MerchantId;
    public ?string $TerminalNo;
}

<?php

namespace Omnipay\Vakifbank\Models;

class FetchTransactionResponseModel extends BaseModel
{
    public ?string $PaymentTransactionId;
    public ?string $TransactionType;
    public ?string $TransactionId;
    public ?string $OrderId;
    public ?string $Amount;
    public ?string $AmountCode;
    public ?string $AuthCode;
    public ?string $ReferenceTransactionId;
    public ?string $IsCanceled;
    public ?string $IsReversed;
    public ?string $IsRefunded;
    public ?string $ECI;
    public ?string $CAVV;
    public ?string $ResultCode;
    public ?string $Rrn;
    public ?string $HostDate;
    public ?string $ResponseMessage;
    public ?string $HostResultCode;
    public ?string $IsBatchClosed;
    public ?string $TransactionThreedSecureType;
    public ?string $PanMasked;
    public ?string $RequestInsertTime;
}

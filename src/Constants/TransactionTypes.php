<?php

namespace Omnipay\Vakifbank\Constants;

class TransactionTypes
{
    public const SALE = 'Sale'; // Satış/Taksitli Satış.
    public const CANCEL = 'Cancel'; // İptal.
    public const REFUND = 'Refund'; // İade.
    public const AUTH = 'Auth'; // Ön Prov.
    public const CAPTURE = 'Capture'; // Ön Prov. Kapama.
    public const REVERSAL = 'Reversal'; // Teknik İptal.
    public const CAMPAIGN_SEARCH = 'CampaignSearch';
    public const BATCH_CLOSED_SUCCESS_SEARCH = 'BatchClosedSuccessSearch';
    public const SURCHARGE_SEARCH = 'SurchargeSearch';
    public const VFT_SALE = 'VFTSale'; // Vade Farklı Satış.
    public const VFT_SEARCH = 'VFTSearch'; // Vade Farklı Sorgu.
    public const TK_SALE = 'TKSale'; // Tarım Kart Eşit Taksitli Satış.
    public const TK_FLEX_SALE = 'TKFlexSale'; // Tarım Kart Esnek Taksitli Satış.
    public const POINT_SALE = 'PointSale'; // Puan harcama.
    public const POINT_SEARCH = 'PointSearch'; // Puan Sorgu.
    public const CARD_TEST = 'CardTest'; // Kart Kontrol.
}

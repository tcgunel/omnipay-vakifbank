<?php

namespace Omnipay\Vakifbank\Models;

class EnrollmentResponseModel extends BaseModel
{
    /**
     * @var int 200 harici hata anlamına gelmekte.
     */
    public int $MessageErrorCode;

    public ?string $ErrorMessage;

    // GET 7/24 MPI tarafından üretilen işlem numarası
    public ?string $ID;

    // iPaySecure mesaj sürüm numarası
    public ?string $Version;

    // Kayıt kontrol sonucu
    public ?string $Status;

    // GET 7/24 MPI tarafından oluşturulup, ACS’e iletilmek üzere ÜİY Uygulamasına iletilen özel bilgi alanı
    public ?string $PaReq;

    // Ödemede kullanılan kredi kartını veren finansal kuruluşun ACS adresi
    public ?string $ACSUrl;

    // ACS’in, kart sahibi kimlik doğrulama sonucunu ileteceği MPI adresi
    public ?string $TermUrl;

    // Enrollment Kontrol İstek mesajından sonra GET 7/24 MPI’ın oluşturduğu alan
    public ?string $MD;

    // GET 7/24 MPI tarafından oluşturulup, 3-D Secure sorgulamasına gönderilen kart için kartın hangi kuruluşa ait olduğunu döner
    public ?int $ACTUALBRAND;
}

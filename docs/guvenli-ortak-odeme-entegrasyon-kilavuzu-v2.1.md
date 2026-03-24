# SANAL POS - GUVENLİ ORTAK ODEME (3DPAY) ENTEGRASYON DOKUMANI

*Bu doküman T.VAKIFLAR BANKASI T.A.O. tarafından hazırlanmış olup, bütün tasarım ve çoğaltma hakları T.VAKIFLAR BANKASI T.A.O.'ya aittir. Dokümanın bütünü ya da herhangi bir kısmı, T.VAKIFLAR BANKASI T.A.O.'nun yazılı izni olmaksızın, çoğaltılamaz ve kaynak gösterilemez.*

Kurum İçi Sınırsız Kullanım / Kişisel Veri

---

## DOKÜMAN TARİHÇESİ

| Versiyon | Açıklama | Tarih |
|----------|----------|-------|
| 1.0 | İlk sürüm | 02.01.2014 |
| 1.1 | 3DPay Özelliği Eklendi | 28.02.2014 |
| 1.2 | Erişim Adresleri Eklendi | 01.10.2014 |
| 1.3 | Erişim Adresleri Güncellendi | 24.10.2014 |
| 1.4 | İşlem Kayıt Eklemesi "CardHoldersName" "CustomItems" ve "Eğleri" parametreleri eklenmiştir. | 13.03.2015 |
| 1.5 | Ortak Ödeme Sayfasına yabancı dil desteği için "RequestLanguage" parametresi eklenmiştir. | 13.03.2015 |
| 1.6 | İşlem Sorgulama API ve URL bilgileri eklendi. Sorgulama API'si kullanıma alındı. SSS bölümüne 6.1.8 eklendi. | 06.06.2015 |
| 1.7 | CustomItems Kullanımı eklendi. SSS bölümüne 6.1.9 eklendi. | 12.06.2015 |
| 1.8 | İptal, İade ve mutabakat sorgulama işlemleri eklendi. Ortak Ödeme Sayfası port bilgisi eklendi. | 22.06.2016 |
| 1.9 | Dokümanların içeriği güncellendi. | 09.05.2022 |
| 2.0 | Dokümanların içeriği güncellendi. | 05.06.2023 |
| 2.1 | Erişim Adresleri güncellendi. | 27.02.2026 |

---

## İçindekiler

- [DOKÜMAN TARİHÇESİ](#doküman-tarihçesi)
- [1. GENEL AÇIKLAMA](#1-genel-açiklama)
- [2. TANIMLAR](#2-tanimlar)
- [3. ERİŞİM ADRESLERİ](#3-erişim-adresleri)
- [4. ORTAK ÖDEME SİSTEMİNE İŞLEM KAYDETME](#4-ortak-ödeme-sistemine-işlem-kaydetme)
- [5. İŞLEM KURALLARI VE ALAN AYRINTILARI](#5-işlem-kurallari-ve-alan-ayrintilari)
- [6. ORTAK ÖDEME EKRANININ AÇILMASI](#6-ortak-ödeme-ekraninin-açilmasi)
- [7. İŞLEM SONUÇ KODLARI](#7-işlem-sonuç-kodlari)
- [8. REFERANSLI İŞLEMLER](#8-referansli-işlemler)
- [9. MUTABAKAT SORGULARI](#9-mutabakat-sorgulari)

---

## 1. GENEL AÇIKLAMA

- Bankamız üye işyerlerine, müşterilerinin alışverişlerinde işlemlerin Bankamız Sanal POS'una iletilmesi için, kendi siteleri üzerinden çağırabilecekleri bir Ortak Ödeme sabitesi hazırlanmıştır.
- Üye işyerleri ödemelenliğin yapılacak alışverişlerde ilk adım olarak bilgilerini, Ortak Ödeme sistemine belleyip ve Ortak Ödeme sistemini müşteriler kredi kartı bilgilerini girerek satış işlemini başlatacaktır. İşlemin sonucu da üye işyerlerine bildirilecektir.
- Üye işyerlerinin Ortak ödeme sistemine entegrasyonu için özet olarak aşağıdaki adımların yapılması beklenilmektedir:

1) Web sitelerinden yapılan satış işlemine ait bilgiler, Ortak ödeme sistemine kaydedilmelidir.
2) Satış işlemini gerçekleştirmek için, kaydedilen işleme ait Ortak Ödeme sisteminden geri dönülen tekil işlem numarası ile Ortak Ödeme ekranlarına erişilmelidir.
3) Müşterinin Ortak Ödeme Ekranında yapacağı ve Bankamız Sanal POS'una iletilen işlemin bilgi bilgisi, üye işyerlerinin hazırladığı bir işlem dönüş sayfasına gönderilecektir.
4) Dönüş bilgisi alınan işlemin sonucu daha sonra tekil işlem numarasıyla sorgulama, başarılı ya da başarısız olmasına göre müşteriye yansıtılacaktır.

İşlem kaydının oluşumu **HTTP POST** metodu kullanılacaktır.

5) Üye işyerlerinin ortak ödeme sistemine kullanabilmeleri için bilmeleri gereken iki adres bulunmaktadır.
   - a. İşlem kayıt için API adresi: Bakınız: *API Adresi*
   - b. İşlem kaydından sonra Ortak Ödeme ekranın adresi: Bakınız: *UI Adresi*
   - c. İşlem Sonucu Sorgulama için, Sorgulama API adresi: Bakınız: *Sorgulama API Adresi*

---

## 2. TANIMLAR

| Tanım | Açıklama |
|-------|----------|
| **Üye İşyeri (ÜİY)** | Bankamızın güvenli **VPOS 724** altyapısını kullanarak kredi kartı ile tahsilat yapacak tüm kurum veya kuruluşlar. |
| **3DPay** | Kart bilgilerinin üye işyeri tarafından sisteme gönderildiği 3D Secure ve Provizyon işlemlerinin Banka tarafından gerçekleştirilerek işlem sonucunun üye işyerine dönüldüğü yapıdır. |
| **Güvenli Ortak Ödeme Sayfası** | Online satış işlemlerinin Bankamız güvencesiyle gerçekleştirilmesini sağlayan yapıdır. Bu yapı üye işyerlerine güvenilir bir ödeme aracı sunar. |

---

## 3. ERİŞİM ADRESLERİ

| | Test Ortamı Adresleri | Prod Ortam Adresleri |
|---|---|---|
| **Ortak Ödeme Json** | https://inbound.apigatewaytest.vakifbank.com.tr:8443/commonPayment/CreateTokenCPY | https://inbound.apigateway.vakifbank.com.tr:8443/commonPayment/CreateTokenCPY |
| **Ortak Ödeme HTTP** | https://inbound.apigatewaytest.vakifbank.com.tr:8443/commonPayment/CreateToken | https://inbound.apigateway.vakifbank.com.tr:8443/commonPayment/CreateToken |
| **İşlem Sorgulama** | https://inbound.apigatewaytest.vakifbank.com.tr:8443/commonPayment/GetVposTransaction | https://inbound.apigateway.vakifbank.com.tr:8443/commonPayment/GetVposTransaction |
| **Yönetim Paneli Adresi** | https://sanalpos.vakifbank.com.tr/ | https://sanalpos.vakifbank.com.tr/ |

---

## 4. ORTAK ÖDEME SİSTEMİNE İŞLEM KAYDETME

- Ortak Ödeme sayfalarında satış işlemleri başlamadan önce işlem kaydedilmelidir.
- İşlem kaydından oluşan Ortak Ödeme API'si Altışığı sayfası önceden belirtilen name-value çiftleriyle ve http post yöntemiyle çağrılmalıdır.
- API çağrısının sonucunda Ortak Ödeme sistemi bir GUID döndürecek. Bu GUID ile Güvenli Ortak Ödeme Ekranı açılacaktır.

| Alan Adı | Açıklama | Alan biçimi ve Uzunluğu | Alınacağı değer |
|----------|----------|------------------------|-----------------|
| **HostMerchantId** | Üye işyeri numarası | Sayısal - 5 | Ör: 00000000012456 |
| **HostTerminalId** | Üye işyeri terminal numarası | Alfa numerik - 8 | Ör: VP000125 |
| **MerchantId** | Üye sanal mağaza numarası | Sayısal - 15 | Ör: 1 |
| **Amount** | İşlem tutar bilgisi (en fazla 2 hane kuruşlu olarak) | Sayısal | Ör: 55.00, 145.50 |
| **MerchantPassword** | Üye şifre | Alfanumerik- 10 | |
| **TransactionId** | Üye işlem başına benzersiz olması gereken işlem numarası gönderilmelidir (durmaksızın sayı olması, değerlerin tekrarlanmaması) | Alfa numerik - 40 | |
| **OrderId** | Sipariş numarası, başarısıza olsan işlemin öncekini tekrar alınır gönderilebilir. OrderId ve sahip bir işlemin başarılı olduğuyla Aynı OrderId ile bir daha işlem yapılamaz. | Alfa numerik - 40 | |
| **OrderDescription** | Sipariş açıklaması | Alfa numerik - 2000 | |
| **InstallmentCount** / **TransactionType** | İşlem Tipi | Sabit | |
| **IsSecure** | İşlemin 3D yapılıp yapılmayacağına dair flag | boolean | Artık(zorunlu) değer: true |
| **AllowNotEnrolledCard** | Kartı sahihi 3D Secure programına dahil değil ancak kartı verene göre güvenli kabul edilmekte | boolean | Artık(zorunlu) değer: true |
| **SuccessUrl** | Başarılı işlem dönüş sayfası. Gönderilen değer bir url olmalıdır. | | Maksimum 255 karakter bir url Ör: https://www.example.com |
| **FailUrl** | Başarısız işlem dönüş sayfası. Gönderilen değer bir url olmalıdır. | | |
| **BrandNumber** | İşlem yapılacak kredi kartının marka bilgisini temel alır. | Sayısal - 3 | 100 = Visa, 200 = MasterCard, 300 = Amex, 400 = Orion Pay, 900 |
| **CVV** | İşlem yapılacak kredi kartı bilgilerin güvenlik kodu | Sayısal - 3 | |
| **PAN** | İşlem yapılacak kredi kartı numarası | Sayısal - 02 | |
| **ExpireMonth** | İşlem yapılacak olan kredi kartının son kullanma tarihinin ay bilgisini | Sayısal - 2 | |
| **ExpireYear** | İşlem yapılacak olan kredi kartının son kullanma tarihinin yıl bilgisini temel eder. | Sayısal - 4 | 2018 |
| **HashedData** | Güvenliğin bilgilerin güvenliğini sağlamada için gönderileni ek bilgi. HashedData gönderdikten sonra hash bilgisini doğrulamak için alınan bilgi. | | Ör: Mo3osFocd03/310bM 9 560/690aBt80770 |
| **RequestLanguage** | Ortak ödeme sayfasının açılacağı dil tercihini belirtir. | | Türkçe için tr-TR, İngilizce için en-US |
| **Extract** | Alt bayı ekranında gönderilen değer kart numarasını istemini tamamlama için çalışacak alan. Ortak ödeme ekranındaki alt bayı bilgileri gönderilebilir. | Alfa numerik - 40 | |
| **CardHoldersName** | Kart sahibinin adı bilgisi gönderilebilir | | |
| **CustomItems** | Üye işyeri tarafından işleme ait ek bilgiler veya CustomItems alanında görevinde "name" ve "value" attribute'ları altında bilgiler gönderilir. | Complex | Bu alanda herhangi bir bilgi gönderildiğinde Ortak Ödeme ekranına (sayısal) İlave bilgi alanı oluşur. 1= Değer1,İsim2:Değer2 |
| **TokenExpireTime** | Token'ın geçerlilik süresini belirtir | Sayısal (Saat) | 1, 2 |

*İle belirtilen alanların gönderilmesi zorunludur.*

**Örnek:** Ortak Ödeme API çağrısının doğru yapılması ve ürş çağırması başarılı olması durumunda ortak ödeme üye işyerine PaymentToken ve CommonPaymentUrl adresini dönecektir. Aksi takdirde hata ile ilgili ErrorCode bilgisi dönecektir.

Örnek: Başarılı bir çağrının cevabı aşağıdaki gibi olmalıdır:

```xml
<RegisterResponse xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
 xmlns:xsd="http://www.w3.org/2001/XMLSchema">
   <CommonPaymentUrl>...</CommonPaymentUrl>
   <PaymentToken>41...</PaymentToken>
</RegisterResponse>
```

Örnek: Başarısız cevabı aşağıdaki gibi olmalıdır.

```xml
<RegisterResponse xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
 xmlns:xsd="http://www.w3.org/2001/XMLSchema">
   <ErrorCode>5002</ErrorCode>
</RegisterResponse>
```

### Örnek token oluşturma:

```
curl --location 'https://inbound.apigatewaytest.vakifbank.com.tr:8443/commonPayment/CreateToken'
  --header 'Content-Type: application/x-www-form-urlencoded'
  --data-urlencode 'HostMerchantId=00000000007073'
  --data-urlencode 'Amount=1.00'
  --data-urlencode 'MerchantId=1'
  --data-urlencode 'HostTerminalId=VP000212'
  --data-urlencode 'MerchantPassword=123456Mv'
  --data-urlencode 'TransactionType=Sale'
  --data-urlencode 'TransactionId=http://www.vakifbank.com.tr'
  --data-urlencode 'FailUrl=http://www.google.com.tr'
  --data-urlencode 'CardHoldersName=AAA'
  --data-urlencode 'TokenExpireTime=2'
```

| Alan Adı | Açıklama | Alan biçimi ve Uzunluğu | Alınacağı değer |
|----------|----------|------------------------|-----------------|
| **PaymentToken** | Ortak ödemenin kayıt sonucunda oluşan işlem, yapılanları dönmeye yönelik olan bilgi tekil id. | Alfa numerik - 40 | Ör: e4efd1408204704beba4a2a700 4e30e1 |
| **CommonPaymentUrl** | Ortak Ödemenin host edildiği URI. | Alfa numerik - 2048 | UI Adresi |
| **ErrorCode** | Register çağrısında hata oluşursa ErrorCode dolu olacaktır. | Sayısal - 4 | Ör: 5002 |

**Dikkat:** CommonPaymentUrl de gelen alan ve PaymentToken birleştirilerek Ortak Ödeme sayfası açılabilir. PaymentToken'i ve CommonPaymentUrl adresi dönecektir. parametre adı "Ptkn" dir.

Ör: UI Adresi?Ptkn=e47dee4b748024704beba4a2a7004e30e1

---

## 5. İŞLEM KURALLARI VE ALAN AYRINTILARI

Ortak Ödeme sisteminde kullanılan alanların ayıntıları aşağıdaki şekilde gösterilmiştir:

- **İşlem Numarası (TransactionId):** Ortak Ödeme sisteminde gönderilen her işlem farklı bir işlem numarasına sahip olmalıdır ve her cevap alınan işlem sonucunun da farklı olması gereklidir. TransactionId bilgisi alfa numerik bir alıplar. TransactionId alan işletme karakter (alfanümerik) ve Türkçe karakterler içermemelidir. Üye işyerinin gönderdiği bu numaranı Sanal POS sistemine bir göndermektedir. İşyeri tarafından özelleştirme durumunda ortak ödeme sayfaları üstesinden olmak ortağından.

- **Üye İş Yeri Numarası (HostMerchantId):** Üye İşyerinin VakıfBank tarafından tanımlanmış işyeri numarasıdır.

- **Üye İş Yeri Terminal Numarası (HostTerminalId):** Üye İşyerinin VakıfBank tarafından tanımlanmış olan terminal numarasıdır.

- **Üye İş Yeri Apı Şifresi (MerchantPassword):** Sanal mağazanın tanımlanmış olan işyeri şifresi Password alanına gönderilmelidir.

- **Taksitli İşlemler (InstallmentCount):** Üye işyeri taksitli işlem yapmaya yetkili ise satış işlemlerini taksitli olarak gerçekleştirebilir. İşlem için taksit bilgisi **InstallmentCount** alanı olarak gönderilmelidir. Taksit sayısı poliçe tasnifçi olmayı ve üye işyeri çalışma panelinde belirtilen taksit aralıklarında yer almalıdır.

- **İşlem Kur Bilgisi (AmountCode):** Ortak Ödeme sisteminde yapılan işlemler Türk Lirası ile ve diğer yabancı para birimleri üzerinden yapılabilmektedir. **AmountCode** alanında **Türk Lirası** için **949** gönderilmelidir.

- **İşlem Tutar Bilgisi (Amount):** Ortak Ödeme sisteminde yapılan işlemlerin tutar bilgisi nokta kullanılarak gönderilmelidir. Gönderilen miktarın en bir basamağı sistem tarafında kuruş olarak algılanacaktır. Tutar bilgileri 2 hane kuruşen ve nokta kullanılması zorunludur.

- **Sipariş Numarası (OrderId):** Üye işyeri herhangi bir ürüne ilişleme dair bir sipariş numarası gönderebilir kullanıcılar ise bu bilgiyi "**OrderId**" altında gönderilmelidir. **OrderId** tekil değer başarılı işlem için arzu edilir. Başarısıza bir işlem için **OrderId** kullanılabilir tekrar gönderilecektir. **OrderId** ile yapılmış velinime yapılmış başarılı bir İşlem alt **OrderId** bileşen sistemine gönderilmelidir.

- **Sipariş Detayı (OrderDescription):** Üye işyeri OrderId gönderdiği herhangi bir işleme sipariş ile ilgili ek açıklama alanı ekleyebilir.

- **İşlem Tipi (TransactionType):** Üye işyeri Ortak Ödeme Üzerinden yapmak istediği işlem tipini bu alanla belirtir. Desteklenen işlem tipleri: **Sale** (Satış), **Auth** (OnProvizyon), **Vft** (Vadeli Farklı Taksitli Satış), **PointSale** (Puan Kullanılması).

- **3D İşlem Flagı (IsSecure):** Üye işyeri ortak ödeme üzerinden yapacağı işlemin 3D Secure programına dahil edilip edilmeyeceğini belirleyebilir. Bu alanın "true" gönderilmesi durumunda Ortak Ödeme işlemi Sanal Pos'a gönderilmeden önce 3D Secure doğrulaması yapılmaya çalışılacaktır. Bu alanın "false" gönderilmesi durumunda işlem doğrudan Sanal Pos'a gönderilecektir.

- **3D Programına Dahil Olmayan Kartlar İle İşlem Yapma Flağı (AllowNotEnrolledCard):** "3D İşlem Flagı" (IsSecure)="true" göndermiş işlemler için bir alt seçenektir. Kart sahibi "3D Secure" programına dahil değilse Ortak Ödemenin işlemi Sanal Pos'a güvenli gönderimyorumdan belirtir. "true" gönderilmesi durumunda kartı sahip 3D Secure programına dahil olsa bile işlemi Sanal Pos'a gönderecektir. Bu iki öğenin "Half Secure" olarak çalışma mekanizmaları belirtilir.

- **"Success Url (Başarılı İşlem Dönüş Sayfası):** Ortak Ödeme'nin başarılı işlem sonucunu dönüşü yapacağı sayfa adresini temel eder. Success Url ve FailUrl alanları aynı olabilir.

- **"FailUrl (Başarısız İşlem Dönüş Sayfası):** Ortak Ödeme'nin başarısız işlem sonucunu dönüş yapacağı sayfa adresini temel eder.

- **BrandNumber:** Kredi kartı marka bilgisini temel eder. Örnek olarak MasterCard için 200,Visa için 100, Troy için 300 değerleri olur.

- **CVV:** Kredi kartı güvenlik numarasını temel eder.

- **PAN:** Kredi kartı numarasını temel eder.

- **ExpireMonth:** İşlem yapılan kredi kartının son kullanma tarihinin ay bilgisini temal eder.

- **ExpireYear:** İşlem yapılan kredi kartının son kullanma tarihinin yıl bilgisini temal eder.

- **Payment Token (Ptkn):** Üye işyerinin API çağrısının başarılı olması durumunda ortak ödeme tarafından üye işyerine dönülen numaranın her başarılı çağrı için benzersiz bir numara üretilir. Üye işyeri bu numarayı Ortak Ödeme Ekranını açmak için kullanılır.

- **CommonPaymentUrl (Ortak Ödeme Adresi):** Ortak ödeme sayfasının adresini içerir.

- **ErrorCode:** İşlem kayıt çağrısında bir problem oluşma ve gerçenesi parametrelerin gönderilebilir bu alan dolu gönderilecektir.

- **Success** ve **Fail** url adreslerine dönülen bilgiler bilgi amaçlıdır.Bu adreslere **PaymentToken** ve **TransactionId** bilgileri dönülür bu bilgiler alındıktan sonra işlemin sonucunu öğrenmek için **Sorgulama API'sinin** çağrılması gerekecektir.

- **RequestLanguage:** Ortak Ödeme uygulaması İngilizce ve Türkçe dil desteğine sahiptir. Türkçe kullanım için, tr-TR; İngilizce kullanım için, en-US belirtilmelidir. Gönderilmeme durumunda, güvenlik varsayılanı: tr-TR ve en-US dışındaki dil gönderilmesini hepsinde ise, İngilizce gösterim yapacaktır.

- **CustomItems:** Üye işyeri, daha sonra sorgulama sonrasında kullanmak isteyeceği ekstra verilerleri(isim, Müşteri No, vb.) bu alanlarda gönderilebilir. Gönderim formatı: key1'value1,key2'value2,key3'value3... şeklinde olmalıdır. Bu alanda, kredi kartı numarası, cvv, expiredate vb. güvenlik bilgisi içeren kayıtlar hiçbir şekilde eklenmemelidir.

- **HashedData:** Gönderilen bilgilerin güvenliğinin sağlanması için gönderilen ek bilgi. Zorunlu bir alan olmamakla birlikte, aşağıdaki şekilde oluşturulmalıdır.

### HASH HESAPLAMA

Üye işyerleriniz tarafından daha güvenli bir işleme olarak sağlanması adına; doğrulama yapması bilmeden işlem kontrolü için aşağıdaki parametreleri krast olacak şekilde hash hesaplamaları(sha256) Bankamız sisteme tarafından doğrulama yapılacaktır. Bankamızın kendisi değiştirilip değiştirilmediğini olacaktır.

**Hash Hesaplama Parametreleri:**

```
VerifyEnrollmentRequestId
MerchantId
Amount (** Kullanılmadan küsarat dahil; örneğin 1.09 için 109 belirtilmeli)
ECI (0 dahil olacak şekilde 2 karakter olarak belirtilmeli)
CAVV
Pares Status
Merchant Password
```

Yukarıdaki parametrelerin birleştirilerek oluşturulması sonra ISO-8859-9 karakter setine göre byte dizisine çevrilmelidir. Ardından sha256 dizine çevrilmedir. Ardından bu sha256 dizisi de base64 ile stringe çevrilmelidir.

**DİKKAT: Merchant password değerinin saklanması ve korunması üye işyerinin sorumluluğundadır.**

```
VerifyEnrollmentRequestId + MerchantId+ CurrencyCode+ Amount+
Eci + Cavv + installsite + ParesStatus + merchant password
```

**Kod örnekleri:**

**C#:**

```csharp
private string Hash(string value)
{
    System.Security.Cryptography.SHA256 sha = new
    System.Security.Cryptography.SHA256CryptoServiceProvider();
    byte[] hashBytes = System.Text.Encoding.GetEncoding("ISO-8859-9").GetBytes(value);
    byte[] hashedBytes = sha.ComputeHash(hashBytes);
    string hashedString = Convert.ToBase64String(hashedBytes);
    return hashedString;
}
```

**Php:**

```php
function hash_value($value) {
    $hashBytes = mb_convert_encoding($value, "ISO-8859-9", "UTF-8");
    $hashed = hash("sha256", $hashBytes, true);
    $base64String = base64_encode($hashed);
    return $base64String;
}
```

**Python:**

```python
def hash_value(value):
    hash_bytes = value.encode('ISO-8859-9')  # Encode the string in ISO-8859-9
    sha = hashlib.sha256()
    sha.update(hash_bytes)
    hashed_bytes = sha.digest()
    base64_string = base64.b64encode(hashed_bytes).decode('utf-8')
    return base64_string
```

**Java:**

```java
import java.nio.charset.StandardCharsets;
import java.security.MessageDigest;
import java.util.Base64;

public class HashUtil {
    public static String hashValue(String value) throws Exception {
        byte[] hashBytes = value.getBytes("ISO-8859-9");
        MessageDigest sha = MessageDigest.getInstance("SHA-256");
        byte[] hashedBytes = sha.digest(hashBytes);
        String base64String = Base64.getEncoder().encodeToString(hashedBytes);
        return base64String;
    }
}
```

**Önemli:** Alt Bayı uygulaması ile çalışan üye işyerlerinin hash hesaplamasındaki MerchantId → ilgili alt bayı işyeri num

---

## 6. ORTAK ÖDEME EKRANININ AÇILMASI

### 6.1. Ödeme Bilgileri Giriş Ekranı

İşlem kaydedildikten sonra API tarafından döndürülen GUID numarasını URL'in sonuna şu elleme yapılarak çağrılmalıdır: `?Ptkn=` API'den dönen GUID numarası

Örneğin: UI Adresi?Ptkn=a302ae42e3e40388314a16f000a0b25

Gelen ekranda satış işlemini başlatan müşterine, kredi kartı bilgilerini girecek bir ekran ile karşılaşır gelecektir. Bu ekran üye iş yeri istek çerçe açılmasında, kullanıcı tarayıcısı üzerinde doğrudan erişilmelidir. Geçerli ve var olan bir işlem numarası sayfaya çağrıldığında, ödeme aracı,

*Not: Kart tipleri kredi kartı giriş yaptıktan sonra gelecektir.*

Bu ekrandan bilgilerin alışı, işlem sonucu üye işyerleri tarafından hazırlanacak olan dönüş sayfasına belirlenmiş ise, Ortak Ödeme ekranlarından dönüyoruz ve Ortak Ödeme Sayfaları oluşturulacaktır.

Ortak Ödeme sisteminin hazır olarak sunduğu bu ekranlardan detayları aşağıda anlatılmıştır.

Bu Ekranda, **Kart No**, **Son Kullanma Tarihi**, **Güvenlik Kodu** alanları mutlaka sıyılmelidir. Toplam Tutar bilgisi işlemi kaydetme sırasında gönderilmiş ve kaydedilmiş durumdadır.

### 6.2. İşlem Onay Ekranı

İşlem onaylandığında aşağıdaki gibi bir bilgi dönecektir.

Onayla ve Öde butonu ile kullanıcı kartını 3D programına dahil olup olmadığı VISA veya MASTERCARD sistemlerinden sorgulanır. İşlem 3Dsecure programına dahilse aşağıdaki adım gerçekleşir, programa dahil değilse doğrudan Sanal POS'a gönderilir.

### 6.3. 3DSecure Onay Ekranı (VISA Test Ortamı)

3D Secure için VISA Test sunucularında çalışan bu ekranda Submit butonuna basılarak onay verilirse, dönüş bilgileri değerlendirilip 3Dsecure onayı alınırsa işlem Sanal POS'a gönderilir. Yukarıdaki ekran gerçek ortamda, müşterinin sms 3DES göncelerim gerçekleştirilir bir giriş giriş ekranıdır.

### 6.4. Sanal POS İşlem Bilti Bilgisini Elde Etme

Ortak Ödeme, işlemin bir hataya ile de başarılıya neden birbirinin durumunda üye işyeri belirleyeceği hazırlılıp sonuç sayfasına dönüş yapacaktır. Bu dönüş sayfasına TransactionId bilgisi dönecektir. İşlem başarı sonuçlarının durumunda dönüş sayfasına biriliğin işlemin doğru RC ve MSG alanlara dip gönderilecek. İşlem başarıyla sonuçlanıp veya sanal POS'a gönderildiği üye iş yerlerin dönüş sayfasına TransactionId ve Pin belirtilir.

**Önemli Not:** Üye işyerinin dönüş sayfasında RC başarılı sonuçları bir bilgi olduğunu ve en hane kodusu geleceğini. Ortak Ödeme bilgisi üzerinden üye işyerinin dönüş sayfasına RC alanında '0000' gönderilirse. Dönüş sayfasında '0000' gelmesiyle modülsünde sorgulama API'sini kullanarak sonuç doğrulanmalıdır.

Üye işyeri, dönüş sayfasına gönderilen TransactionId ile mutlaka Ortak Ödeme işlem sorgulama API'sini çağırarak alınan şipariş kendi tutaçlarıymış gerçekten başarılı olduğu sonuç üretip üye yönetime doğrulamak yapılmalıdır.

**Örnek 1:** Aşağıdaki örnek query başarılı bir işlem çıkarılmaktan:

```
http://www.orderstatpage.com/result-order.aspx?MerchantId=1&HostMerchantId=00000000007073&
VerifyEnrollmentRequestId=78229a1ib01232a1783fde8de2d41emaddd35db469e2647144e2aade8637ba1d6c2ise8
```

**Örnek 2:** Aşağıdaki RC ve MSG alanları üye işyerinin dönüş sayfasına gönderilerek durumda üye işyeri işlemin sonucu ortak ödeme APIsini sorgulayabilir.

```
https://www.orderstatpage.com/result-order.aspx?TransactionId=370d56b1dc7d742a8a664570a1d6c2a86P0re>1aa629
7e564c7d83d24a17000e600c
```

### 6.5. Sorgulama API'sinin Kullanımı

İşlem Sorgulama (API'si) aşağıda belirtilen name value çiftleriyle ve http post yöntemiyle çağrılmalıdır:

| Alan Adı | Açıklama | Alan biçimi ve Uzunluğu | Alınacağı değer |
|----------|----------|------------------------|-----------------|
| **HostMerchantId** | Üye işyeri numarası | Sayısal - 15 | 00000000006606006 |
| **MerchantId** | Üye işyeri şifresi | Alfanumerik - 10 | |
| **Password** | Üye işyeri şifresi | Alfanumerik - 10 | |
| **TransactionId** | Her işlem başına benzersiz olması gerçek alan. Ortak Ödeme ekranının dönüş sayfasından alınabilir. | Alfa numerik - 40 | |
| **PaymentToken** | Ortak Ödeme yapılan kayıt sonuçlarında oluşan ve Ortak Ödeme ekranının açılan tür güvenli olan token. | Alfa numerik - 40 | |

*\* ile belirtilen alanların gönderilmesi zorunludur. **PaymentToken** veya **TransactionId** alanlarından birinin gönderilmesi yeterlidir. İşlemin sonuçlarını yönetir.*

*Üye işyeri dönüş sayfasına gönderilen. TransactionId'nin sonucunu doğrulamak için bu API'yi kullanırsınız. Bu API işlemin sanal pos gönderilmeyen TransactionId sorgulama durumu bu ErrorCode alanı bolu olsa ve ilgili hatayı anlatan bir cevap dönecek veya hatalı olma durumunda bir hata alağıtıyla gelir.*

*Ortak ölarak bi işlem sanal posa gönderilmemiş hatalar sonuçlarıyla ortak ödeme API'si aşağıdaki cevabı dönecektir.*

```xml
<RegisterResponse xmlns="http://www.w3.org/2001/XMLSchema-instance"
 xmlns:xsd="http://www.w3.org/2001/XMLSchema">
</RegisterResponse>
```

İşlem sanal posa gönderilememiş ve hata oluşmuş bir işlem için aşağıdaki gibi sonuç olacaktır:

```xml
<VposQueryResponse xmlns="http://www.w3.org/2001/XMLSchema-instance"
 xmlns:xsd="http://www.w3.org/2001/XMLSchema">
   <MerchantId>00000000007073</MerchantId>
   <SubMerchantId></SubMerchantId>
   <TransactionType></TransactionType>
   <TransactionId></TransactionId>
</VposQueryResponse>
```

Veya

```xml
<VposQueryResponse xmlns="http://www.w3.org/2001/XMLSchema-instance"
 xmlns:xsd="http://www.w3.org/2001/XMLSchema">
   <MerchantId> Yetkiniz Yoktur</MerchantId>
   <SubMerchantId> Yetkiniz Yoktur </SubMerchantId>
</VposQueryResponse>
```

Ortak Ödeme sorgulama API'sinin sonucunda RC alanının "0000" olmasığı bilgisi için **onaylanmış** denmektedir.

API cevabında dönen alanları açıklamaları aşağıdaki gibidir:

| Alan Adı | Açıklama |
|----------|----------|
| **ErrorCode** | Api çağrısı esnasında bir hata oluşursa ErrorCode dolu olacaktır. |
| **ResultCode** | Sanal POS'tan dönen sonuç kodu |
| **ResultDescription** | Sanal POS'tan dönen Onay Kodu |
| **AuthorizeId** | Provizyon numarası |
| **PaymentToken** | Ortak ödeme başarılıdan sonra elde ederse yük bakımdan üye gönderme gez dönüşüne ve ilse iç için ortak ödeme dönüş sayfalarında elde edebilirsiniz. |
| **TransactionId** | Üye işyerinin işlem. Ortak Ödeme'ye kayıt sıfıntısını kullanma gönderilmiş dij lam alman sanal pos sistemine gönderilir. El Öye can sayı tamtanbi cevabını nonfirdirmi Ortak Ödeme'de kaydı olunacaktır. |

### Örnek Curl Sorgulama:

```
curl --location
  'https://inbound.apigatewaytest.vakifbank.com.tr:8443/commonPayment/GetVposTransaction'
  --header 'Content-Type: application/json'
  --data '{
    "HostMerchantId":"00000000007073",
    "MerchantId":"1",
    "Password":"123456Mv",
    ...
  }'
```

Başarılı işlem cevabı **0000**'dır. Hatalı işlemler için Sanal POS'un ve Ortak Ödemenin döndürdüğü hata kodları aşağıdaki tabloda gösterilmektedir.

---

## 7. İŞLEM SONUÇ KODLARI

| Kod | Cevap Kodu Açıklaması | Onay |
|-----|----------------------|------|
| 0000 | Başarılı | |
| 0001 | BANKANIZI ARAYIN | |
| 0002 | BANKANIZI ARAYIN | |
| 0003 | ÜYE KODU HATALITANAMSIZ | |
| 0004 | KARTA EL KOYUNUZ | |
| 0005 | İŞLEM ONAYLANMADI | |
| 0006 | HATALI İŞLEM | |
| 0007 | KARTA EL KOYUNUZ | |
| 0009 | TEKRAR DENEYİNİZ | |
| 0010 | TEKRAR DENEYİNİZ | |
| 0011 | TEKRAR DENEYİNİZ | |
| 0012 | Geçersiz İşlem | |
| 0013 | Geçersiz İşlem Tutarı | |
| 0014 | Geçersiz Kart Numarası | |
| 0015 | MÜŞTERİ YOK/BIN HATALI | |
| 0021 | İŞLEM ONAYLANMADI | |
| 0030 | MESAJ FORMATI HATALI (ÜYE İŞYERİ) | |
| 0032 | DOSYASINA ULAŞILAMADI | |
| 0033 | SÜRESİ BİTMİŞ/İPTAL KART | |
| 0034 | SAHTE KART | |
| 0036 | İŞLEM ONAYLANMADI | |
| 0038 | ŞİFRE AŞILMIŞKARTA EL KOY | |
| 0041 | KAYIP KART- KARTA EL KOY | |
| 0043 | ÇALINTI KART-KARTA EL KOY | |
| 0051 | LİMİT YETERSİZ | |
| 0052 | HESAP NO'YU KONTROL EDİN | |
| 0053 | HESAP YOK | |
| 0054 | HARE SONU GEÇMİŞ KART | |
| 0055 | Hatalı Kart Şifresi | |
| 0056 | Kart / Kurum Değil | |
| 0057 | KARTILAN İŞLEMSİZ YOK | |
| 0058 | POS İŞLEM TİPİNE KAPALI | |
| 0059 | SAHTEKARLIK ŞÜPHESİ | |
| 0061 | PARA ÇEK TUTARI AŞILDI | |
| 0062 | YASAKLANMIŞ KART | |
| 0063 | GÜVENLİK İHLALİ | |
| 0065 | GÜN. İÇİ İŞLEM ADEDİ LİMİTİ AŞILDI | |
| 0066 | Şifre Deneme Sayısı Aşıldı | |
| 0067 | ŞİFRE SCRIPT TALEBİ REDDEDİLDİ | |
| 0068 | ŞİFRE GÜVENİLİR BULUNAMADI | |
| 0075 | ŞİFRE DAYANIŞMADI | |
| 0077 | KARTI VEREN BANKA HİZMET DIŞI | |
| 0080 | İŞAKASI BULANMIYOR | |
| 0082 | İŞLEM ONAYLANMADI | |
| 0083 | Bu TransactionId ile daha önce başarılı bir işlem gerçekleştirilmiş. | |
| 0084 | İşlem tutar hatası | |
| 0085 | İşlem yasaklandı veya izinsiz | |
| 0086 | İade işleminde tutar hatası. | |
| 0087 | İşlem tutar geçersiz. | |
| 0088 | Geçersiz tutar. | |
| 0089 | CVV Hatası | |
| 0090 | Kredi kartı numara hatası. | |
| 0091 | Kredi kartı son kullanma tarihi hatası. | |
| 0093 | Tansden sade denemes. | |
| 0096 | Hatalı işlem çekimi | |
| 0099 | İş yeninin işlemi için gerekli hakkı Kapa gelir. | |
| 1001 | İş yeninin işlemi için gerekli hakkı Kapa ( Batch Kapan.) | |
| 1006 | İş yeri aktif değil | |
| 1007 | İş yeri aktif değil | |
| 1046 | İşlem herşiğ sonrasında ya da referans işlem henüz | |
| 1047 | Hatanızdır | |
| 1049 | Sadakati puan kodu hatası | |
| 1050 | Para kodu hatası | |
| 1051 | Geçersiz sipariş numarası | |
| 1052 | Geçersiz sipariş açıklaması | |
| 1053 | Geçersiz tutar ya para tutar gönderilmediğim | |
| 1054 | Aynı sipariş numarasıyla daha önceden başarılı işlem | |
| 1057 | Ön provizyon daha önceden kapatılacak | |
| 1058 | Geçersiz sipariş tutar | |
| 1059 | Referans işlem daha önceden iptal edilmiş | |
| 1060 | Blanka başlıksız gün sonra olay yazıldığında sipariş ile | |
| 1061 | Kablanço para birlimiyle taksitlö provizyon kapama işlemi | |
| 1062 | Ön provizyon iptal edilmiş | |
| 1063 | Referans işlem yapılmak isleyen işlem için uygun değil | |
| 1064 | Bölüm numarası bulunamıyor | |
| 1067 | Recurring işlemin kapanan taksit sayısı hatası | |
| 1068 | Recurring işlem Testelarına göze hatası | |
| 1069 | Sadece Satış (Sale) işlemi Recurring olarak şartlandırılabilir | |
| 1070 | Bu TransactionId ile daha önce başarılı bir işlem | |
| 1080 | Lütfen geçerli bir Email adresi girişi | |
| 1081 | Lütfen geçerli bir IP adresi girişi | |
| 1087 | Lütfen geçerli bir CAVV değeri giriniz | |
| 1088 | Lütfen geçerli bir ECI değeri giriniz | |
| 1089 | Lütfen geçerli bir Kart Sahisi ismi giriniz. | |
| 1090 | Lütfen geçerli bir ID yazınız giriş alınm. | |
| 1092 | Recurring işlem analtik tipi hata bir dereceiyle sahip | |
| 1093 | İşlem bulunamadı | |
| 1094 | Kredi Kartı sağlayıcısı bulunamadı | |
| 1095 | Kart sahibinin alt il karakter geçmişiniz | |
| 1096 | Hatalı kart numarası | |
| 1097 | TransactionId daha önce kullanılmış | |
| 1098 | Bu işlem daha önce kullanılmış | |
| 1099 | İşlem daha önce kullanılmış | |
| 1012 | Herşiyi sayfanın takip ediltiği anlaşılmalıdır | |
| 1013 | Geçersiz Transactionld | |
| 1014 | Geçersiz işlem tutar | |
| 1015 | Geçersiz para birlik | |
| 1016 | İstale parametre | |
| 1017 | Geçersiz sipariş detay bilgisi | |
| 1018 | Geçersiz çıkış url | |
| 1019 | Odema blees esrak | |
| 1020 | Üye işyeri numarası eksek | |
| 1021 | Geçersiz üye iş yeri numarası | |
| 1022 | Geçersiz sipariş numarası uzunluğu | |
| 1023 | Geçersiz işlem tipi | |
| 1024 | Geçersiz TransactionId | |
| 1025 | Geçersiz HashedData | |
| 1026 | Geçersiz Transaction | |
| 1033 | Response değeri alınamadı | |
| 1034 | Geçersiz kayıt bulunması | |
| 1043 | Geçersiz PaymentToken | |
| 1044 | TransactionId değerlerinin geçersiz karakter | |
| 0097 | OrderId değerinde desteklenmeyen karakter var | OrderId değeri kart ve rakamlardan oluşabilir. Boşluk, tire, nokta, virgül vb. kullanım olmadan gelmelidir. Kontrol Regex'i aşağıdaki gibidir: @"^[0-9a-zA-Z-]+$" |
| 0098 | Telep mesajı olmuştur. (Mesaja'ya alan ait olan parametrelerin formatlarının kontrolü etdiniz) | İstam ALG yapılandırmalarının uygunluğu |

---

## 7.1. Sıkça Sorulan Sorular

### 7.1.1. Php ile İşlem Kayıt edilemiyor. RegisterTransaction isteğinden cevap alınamıyor.

Curl hatalarına bakışı olarak gerçekleştirmelidir; php curl_exec metodunu atılır şekilde aşağıdaki şekilde bir iletmeye yapabilirsiniz.
Php ile POST işlem yapması bir hata dönme durumunda bu alanda hata gösterilir: `$Serror = curl_error($Serno);echo "URL error (".$Serror_message.")". $Serror_message;`

### 7.1.2. Php ile İşlem kayıt edilemiyor: CURLE_SSL_CACERT (60) ... hatası alınıyor

Ortak Ödeme Sisteme İşlem Kayıt Etme işleminde cevap alınamıyor, php ile curl metodu kullanılarak HTTPS Secure soylasını bile çıkonlamaya bir problem vardır. php.ini dosyasında aşağıdaki elamanları yaparak kayıt edebil lır:

Https başarılıları curl alanığı aşağıdaki gibi,

- Php.ini dosyasında CURLOPT_CAINFO parametresinde certificate path i veritmelidir. (tam olarak sertifikanın bulunduğu, folderı düzelt verilmelidir.)

```ini
[curl]
curl.cainfo = c:\php\cacert.pem
```

- Alternatif olarak, her curl isteğinde ele de bu değer gönderilebilir.

```php
curl_setopt($ch, CURLOPT_CAINFO, "c:\php\cacert.pem");
```

Cacert.pem dosyasını bu bağlantıdan erişebilirsiniz:
http://curl.haxx.se/ca/cacert.pem

### 7.1.3. Php ile İşlem kayıt edilemiyor, curl_errno function not supported hatası alınıyor

Decay hatası CURLE_SSL_CACERT (60) PEER CERTIFICATE CANNOT BE AUTHENTICATED WITH KNOWN CA CERTIFICATES.

Curl fonksiyonlarının kullanılabilmesi için php_curl.dll php extension'ın müftiye vardır. bu dll php paketinde getmelerin ancak php ile doğrulalıya aktive edilmemiş olabilir.

Aktive etmek için, php.ini dosyasında aşağıdaki eklemenin yapılması gerekir. İşin php_curl.dll dosyasının bulunan yukaronunda dosyayı ve bulunduğuluk şekilde kullanılma ve referensını edilmesini:

```ini
extension=curl
```

### 7.1.4. 5037 hatası alınıyor.

Ortak Ödeme Sistemine İşlem Kayıt Etme işlemini sırasında beliren SuccessUrl değeri boş ya da hatalıdır.

Eğer başarılı işlem adresi: `http://localhost/xxxxx/xxxxx` olarak gönderiliyorsa, bunu `http://127.0.0.1/xxxxx/xxxxx` olarak değiştiriniz.

### 7.1.5. 5038 hatası alınıyor.

Ortak Ödeme Sistemine İşlem Kayıt Etme işlemi sırasında beliren FailUrl değeri boş ya da hatalıdır.

Eğer başarısız işlem adresi: `http://localhost/xxxxx/xxxxx` olarak gönderiliyorsa, bunu `http://127.0.0.1/xxxxx/xxxxx` olarak değiştiriniz.

### 7.1.6. Hatalı Tutarda işlem gerçekleşiyor.

Ortak Ödeme Sistemine İşlem Kayıt Etme işlemi sırasında beliren Amount değerinin xx.xx formatında biçimlenmesi gerekmektedir. Örneğin, 1.00 TL bir satış için, Amount="1.00" gönderilmelidir. Hatalı gönderimler yüze olabilecektir: 1.00

### 7.1.7. 5014 hatası alınıyor

Ortak Ödeme Sistemine İşlem Kayıt Etme işlemi sırasında beliren Amount değerinin xx.xx formatında biçimlenmesi gerekmektedir. Örneğin, 1.00 TL bir satış için, Amount="1.00" gönderilmelidir. Hatalı gönderimler yüze olabilecektir: 1.00

### 7.1.8. Sorgulama API Adresine istek gönderemiyorum.

Sorgulama API Adresine erişemin olup olmadığını kontrol edin.

- Aşağıdaki html kodu, html sayfası olarak kayıt edin ve herhangi bir browser yardımı ile açın.

```html
<html>
  <title>vp api vpos transaction</title>
  <body>
    <form
      action="https://apitest.vakifbank.com.tr:8443/CommonPaymentHttp/VposTransaction"
      method="post">
      <input type="text" size="102" name="HostMerchantId" value="Uyeisyeriniz">
      <br/>Password: <input type="text" size="102" value="Sifreniz" name="Password">
      <br/>Transactionld: <input type="text" value="1001" size="102" name="TransactionId">
      <br/><input type="Submit" value="SORGULA">
    </form>
  </body>
</html>
```

- Eğer sonuç olarak, bir dösya dönüyor ise, erişiminiz vardır ve istek gönderebilirsiniz.
- Eğer timeout vb. bir hata ile karşılaşıyor iseniz, erişiminiz mevcuttur. Erişim sıkıntısının iki aşağı vardır:
  - Kerel bilgayerınızdan https://apitest.vakifbank.com.tr:8443/CommonPaymentHttp/VposTransaction adresine çıkışınız kapaçtır.
  - a. Kontrol için; mobil internet vb. üzerinden bu işlemi yapmayı deneyin, o
  - b. Bankamız ilkin IP adreslerinden bu adrese erişimi kapatabilir.
    - Bankamızdan yetki talep edin.

---

## 8. REFERANSLI İŞLEMLER

Referanslı işlemler (İptal, İade ve Ön Provizyon Kapama) Sanal POS Yönetim Paneli üzerinden gerçekleştirebildiği gibi api üzerinden de gerçekleştirilebilir. Referanslı işlemler bulunabilecek ServiceUrl bilgisi:

| | Test Adresi | Production Adresi |
|---|---|---|
| **ServiceUrl** | https://apigateway.vakifbank.com.tr:8443/VirtualPos/referenceServiceUrl | https://apipos.vakifbank.com.tr:8443/VirtualPos/VfPOSResponse/1009998 |

### 8.1. İptal (Cancel)

İşlem iptalinin, başarılı gerçekleşmiş ve henüz gün sonunu almamış bir satış veya iade işlemini iptal etmek için kullanılır.

- İptal işlemi için **TransactionType** alanını "**Cancel**" olarak belirtilmesi gereklidir.
- Her başarılı İptal işleminde "**TransactionId**" değiştirilmelidir.
- İptal yapılacak işlemlerin orijinal kayıtların bulunulmasını için üye işyeri "**ReferenceTransactionId**" alanına iptal edilecek işlemin orijinal işlemin ait "**TransactionId**" numarası gönderilmelidir. Aksi takdirde işlem iptal edilemeyecektir, ilgili hata kodu dönülecektir.
- Taksitli işlemlerin tüm taksitleri herhangi bir taksitli yoktur.
- İptal işlemin iptal edilecek işlem ile eşit (nci) tutçu yapılabilir.
- İptal işlemin gerçekleştirmek için sadece ReferenceTransactionId nin dokunulması yeterlidir: Pan bilgileri veya CurrencyCode'nut bilgilerinin gönderilmesi Artık(yardım).
- Sadece Satış, Taksitli Satış, Puan Kullanım, Ön Provizyon, Provizyon Kapama, Puan Kullanım, VFT Satış işlemleri iptal edilebilir.

Örnek iptal mesajları ve VPOS 7.24'ün dönülen cevabı cevaplar aşağıda gösterilmektedir.

### 8.1.1. İptal isteği

```xml
<?xml version="1.0" encoding="utf-8"?>
<VposRequest>
    <MerchantId>...</MerchantId>
    <Password>...</Password>
    <TransactionType>Cancel</TransactionType>
    <ReferenceTransactionId>...</ReferenceTransactionId>
    <ClientIp>190.20.13.12</ClientIp>
</VposRequest>
```

### 8.1.2. İptal Cevabı

```xml
<?xml version="1.0" encoding="utf-8"?>
<VposResponse>
    <MerchantId>...</MerchantId>
    <TransactionId>70292ab0-3ad1-44fb-8866-31b9b786388b</TransactionId>
    <TransactionType>Cancel</TransactionType>
    <ResultCode>0000</ResultCode>
    <ResultDescription>İŞLEM BAŞARILI</ResultDescription>
</VposResponse>
```

### 8.2. İade (Refund)

İade işlemi, başarılı gerçekleşmiş ve gün sonunu almamış finansallaşmış bir işlem ilade etmek için kullanılır.

- Bu mesajı için TransactionType alanını "**Refund**" olarak gönderilmelidir.
- Her iade işleminde TransactionId değiştirilmelidir.
- İadesi yapılacak satış işleminin orijinal kayıtları bulunulmasını için üye işyeri "**ReferenceTransactionId**" alanında orijinal işleme ait "**TransactionId**" alanını gönderilmelidir. Aksi takdirde işlem iade edilemeyecektir ilgili hata kodu dönülecektir.
- VPOS 7.24 sisteminde 2 tür İade yapılabilir. Tamamın İade ya da Kısmi iade. İade işlem tutar satış işlem ücreti ile eşit ise tam iade, satış işlem tutarında küçük ise kısmi iade yapılır.
- Sadece Satış, Taksitli Satış, Puan Kullanım, Ön Provizyon Kapama ve VFT Satış işlemleri iade edilebilir.
- Kısmi iade işlemleri topladı tutar satış tutarını geçmeyen şeklide birden fazla iade işlemi yapılabilir.
- İade işlemi gün sonra almamış işlemler üzerinde geçerli olduğu gibi, aynı gün sonra alınmamış işlem için de geçerli olabilecektir. Gün sonu anlamında işlemin iade edilmesi için ayrı bir engel yoktur.

### 8.2.1. İade isteği

```xml
<?xml version="1.0" encoding="utf-8"?>
<VposRequest>
    <MerchantId>...</MerchantId>
    <Password>...</Password>
    <TransactionType>Refund</TransactionType>
    <ReferenceTransactionId>2847 0f95-1fe2-4dec-b4-0ddd0cf41d76</ReferenceTransactionId>
    <ClientIp>190.20.13.12</ClientIp>
</VposRequest>
```

### 8.2.2. İade Cevabı

```xml
<?xml version="1.0" encoding="utf-8"?>
<VposResponse>
    <MerchantId>...</MerchantId>
    <TransactionId>70292ab0-3ad1-44fb-8866-31b9b786388b</TransactionId>
    <TransactionType>Refund</TransactionType>
    <ResultCode>0000</ResultCode>
    <ResultDescription>İŞLEM BAŞARILI</ResultDescription>
    <AuthCode>19104</AuthCode>
    <HostDate>20191012030000</HostDate>
</VposResponse>
```

Ek parametreler (iade cevabında):

```xml
<GainedPoint>0</GainedPoint>
<TotalPoint>1.00</TotalPoint>
<CurrencyCode>949</CurrencyCode>
<ThreedsECICode></ThreedsECICode>
<TransactionDeviceSource>0</TransactionDeviceSource>
<TransactionId></TransactionId>
<SalesAmount></SalesAmount>
<CampaignId>0</CampaignId>
<BankBod></BankBod>
```

### 8.3. Teknik İptal (Reversal)

Öne uyarı VPOS 7.24'ten herhangi bir cevap alınamadığı durumunda gönderdiği işlemin geri alınması amacıyla teknik iptal mesajı gönderilir. Sadece finansal işlemlerde teknik iptal olabilir. VPOS 7.24 üye işyerinin gönderdiği Teknik İptal isteğini gün sonu geçişinden sonraki taksitler her zaman cevabı yanlı verecektir. Eğer reverse edilerek istenen işlem başka batch el al size 2202 hatası verecektir.

- Teknik iptal işlemi için TransactionType alanını "**Reversal**" olarak belirtilmesi gereklidir.
- Her teknik iptal işleminde teknik bir Transactionid kullanılmalıdır.
- Teknik iptal yapılacak işlemlerin orijinal kayıtları bulunulmasını için ÜİY, "ReferenceTransactionId" alanına teknik iptal gerçekleştirmek istediği işlemin ait TransactionId numarası gönderilmelidir.
- Teknik iptal işleminin gerçekleştirmek için sadece MerchantId, Password, ReferenceTransactionId, TransactionType alanlarını doğrulaması yeterlidir. Pan bilgilerin veya CurrencyAmount bilgilerini gönderilmesine bu fikre görecektir.
- Taksitli işlemlerin teknik iptalinde herhangi bir taksitlerde VPOS 7.24 üye işyerlerinin gönderilmesiyle çalışmalarn iptaline yapacak önceki iptal veya iade gibi işlemler yapılamaz.
- Referanslı bir iptal ile teknik iptal özelliğinin (yani, provizyon kapama gibi) orijinal işlem tekrar referanslı işlem yapılması uygun değer gelebilir. Ömeğin provizyon kapama işlemi reversi edildiğinde, reverse edilen provizyon kapama işlemin ait ön provizyon işlemi tekrar provizyon kapama uygulanabilir.
- Gün sonu geçişinden sonra önceki batch numarasıki ait işlemin reverse edilemez.

### 8.3.1. Teknik İptal İsteği

```xml
<?xml version="1.0" encoding="utf-8"?>
<VposRequest>
    <MerchantId>...</MerchantId>
    <Password>...</Password>
    <TransactionType>Reversal</TransactionType>
    <ReferenceTransactionId>a9aaae61-0b01-44fd-8 606-33 65 fa7a0aaa</ReferenceTransactionId>
    <ClientIp>190.20.13.12</ClientIp>
</VposRequest>
```

### 8.3.2. Teknik İptal Cevabı

```xml
<?xml version="1.0" encoding="utf-8"?>
<VposResponse>
    <MerchantId>...</MerchantId>
    <TransactionId>a-9 9aaae61-0b01-4-4fd-8-606-33 b06388b</TransactionId>
    <TransactionType>Reversal</TransactionType>
    <ResultCode>0000</ResultCode>
    <ResultDescription>İŞLEM BAŞARILI</ResultDescription>
    <HostDate> kro >201012060000</HostDate>
    <HostDate>1130143930</HostDate>
</VposResponse>
```

---

## 9. MUTABAKAT SORGULARI

Sanal POS İşlem Sorgulaması olarak 4 adet işlemden Sorgulaması için kodlularında servisler aşağıda belirtilmiştir. Referans servislerin, metedataService procedurelerinin sağlanılabilir.

Matabakat servis URL bilgisi aşağıdaki tabloda verilmiştir:

| | Test Adresi | Production Adresi |
|---|---|---|
| **Başarılı İşlem Sorgulama İsteği** | https://apigateway.vakifbank.com.tr:8443/VirtualPos/SettlementDetail (POST Request) | https://apipos.vakifbank.com.tr:8443/VirtualPos/SettlementDetail (POST Request) |
| **Başarılı İşlem Kırılım Sorgulama** | https://apigateway.vakifbank.com.tr:8443/VirtualPos/Settlement (POST Request) | https://apipos.vakifbank.com.tr:8443/VirtualPos/Settlement (POST Request) |

### 9.1. Başarılı İşlem Sorgulama (SettlementDetail)

Günlük, başarılı olarak gerçekleşen işlemlerin listesi, sayfa bazlı olarak alınabilmektedir. Bir sayfada maksimum 50 adet kayıt alınabilecek birilkte, sayfadaki maksimum kayıt sayısı, Üye İşyeri tarafından da belirlidir.

- İşlem Tarihi(**SettlementDate**).yyyy/mm/dd ve formatı gibi şekilde gönderilir: **yyyyMMdd**
- Sayfadaki Maksimum Kayıt Sayısı(**PageSize**), her sayfada dönülebek maksimum kayıt sayısını veritilmaktedir.
- Sayfa Numarası(**PageIndex**), sorgulanacak bir vüzlöni. Örneplığ, gün içinde toplam 502 işlem gerçektiğini düşünüldüğü,
10 kayıtlık bir sorgulamada ile görevlendirildi; PageSize=10, PageIndex=1 gönderilebilirdirt 50 (*10 * 1 + 2 = 502*) Toplam 51 ayrı çağrı erişmek için: PageIndex=21, PageSize=25 gönderilmelidir (25 * 20 + 2 = 502).
- Ortak Ödeme İşlem Sorgulama ile mesajlar ve VPOS 7.24'ün bu mesajlara döndüğü cevaplar aşağıda gösterilmektedir.

### 9.1.1. Başarılı İşlem Sorgulama İsteği

```xml
<SettlementDetailRequest>
    <MerchantCriteria>HostMerchantId=MerchantId</MerchantCriteria>
    <DateCriteria>SettlementDate=yyyyMMdd</DateCriteria>
    <RequestOrderCriteria>PageIndex=PageIndex1&PageSize=PageSize1</RequestOrderCriteria>
</SettlementDetailRequest>
```

### 9.1.2. Başarılı İşlem Sorgulama Cevabı

```xml
<?xml version="1.0" encoding="utf-8"?>
<SettlementDetailResponse>ResponseInfo>
    <ResponseCode>0000</ResponseCode>
    <ResponseMessage>...</ResponseMessage>
    <ResponseDateTime>2015-04-09T13:32:36.272289+02:00</ResponseDateTime>
    ...
</SettlementDetailResponse>
```

(Cevap içeriği TransactionInfo, Items, TotalItem öğelerini içerir. Her bir Item aşağıdaki alanları barındırır:)

```xml
<TransactionId>...</TransactionId>
<TransactionType>Sale</TransactionType>
<SurchargeAmount>0.00</SurchargeAmount>
<CurrencyCode>949</CurrencyCode>
<TotalAmount>...</TotalAmount>
<Items>
    <TotalItem>
        <TotalCount>...</TotalCount>
        <TotalAmount>...</TotalAmount>
        <CurrencyCode>949</CurrencyCode>
    </TotalItem>
</Items>
```

### 9.2. Başarılı İşlem Kırılımı Sorgulama (Settlement)

Günlük, başarılı olarak gerçekleşmiş işlemlerin işlem tipi bazında:

- Toplam işlem adedi sayısı
- Toplam para tutarı
- Toplam para birimi birimi alında alınabilmektedir.

Örnek Başarılı İşlem Kırılımı Sorgulama istek mesajları ve VPOS 7.24'ün bu mesajlara döndüğü cevaplar aşağıda gösterilmektedir.

### 9.2.1. Başarılı İşlem Kırılımı Sorgulama İsteği

```xml
<SettlementRequest>
    <MerchantCriteria>HostMerchantId=MerchantId</MerchantCriteria>
    <DateCriteria>MerchantSettlemnetCriteria>StartDate=2016-08-05</StartDate><EndDate>2016-08-05</EndDate></DateCriteria>
    <StartDate>StartDate=2014-01-05</StartDate>
    <AuthorizeTransactions>StartDate>startDate</AuthorizeTransactions>
</SettlementRequest>
```

### 9.2.2. Başarılı İşlem Kırılımı Sorgulama Cevabı

```xml
<?xml version="1.0" encoding="utf-8"?>
<SettlementResponse>ResponseInfo>
    <ResponseCode>0000</ResponseCode>
    <ResponseDateTime>2015-04-09T13:32:36.372289+02:00</ResponseDateTime>
    ...
    <HostMerchantId>00000000056</HostMerchantId>
    ...
    <TotalSaleCount>72</TotalSaleCount>
    <TotalAmount>7434.00</TotalAmount>
    <CurrencyCode>949</CurrencyCode>
    <TotalItems>
        <TotalItem>
            <TotalAmount>1200.00</TotalAmount>
            <CurrencyCode>949</CurrencyCode>
        </TotalItem>
        ...
    </TotalItems>
</SettlementResponse>
```

(İşlem tipi bazında kırılım öğeleri: Sale, Capture, Cancel, Refund, Auth, PointSale, VFTSale, Credit)

### 9.3. Kayıt Sorgulama (Search)

Kayıt Detay Sorgulama işlemi, ÜİY'ne ait belki OrderId ya da TransactionId'si ve sahip işlemlerini sorgulamak için kullanılmaktadır. İşlem aranacak yazılan yanlın birleşik aynıda dönülecektir.

- Her başarılı Kayıt Detay Sorgulama işleminin belirli bir **TransactionId** kullanılmalıdır.
- **TransactionId** alanına sorgulama istenen işlemin Transactionid belirli bilgisi yönetilmelidir.
- **OrderId** alanına sorgulanacak istenen işlemin **OrderId** bilgisi yazılmalıdır.
- **TransactionId** ya da **OrderId** alanından bir zor yazılmalıdır. Hem TransactionId hem de OrderId gönderilebilir yapılsa bin sorgulamada, TransactionId dikkate alınacaktır.
- **OrderId** ile sorgulama bu **OrderId** ile başarılı işlem varna başarılı işlem, yoksa son gönderilen işlem raporu döndürülecektir.
- Kayıt birden fazla olabilir. Ömeğin, aynı **TransactionId** ile birden farklı istek yapılmış ise her bir kayıt sorgu cevabında yer alır.
- Hız kayıt döneminin, kriterlere uygun kayıt bulunamadığı anlamıyla gelir ve cevaptaki **TotalItemCount** değeri 0 dönen ayrı zamanda da cevaptaki **TransactionSearchResultInfo** kanıncıyla hiçbir bişeyi işlem dönülmez.
- ResponseInfo alan, sorgulama işleminin başarılı olarak gerçekleştiğini gerçekleşmediğini döner.

Örnek Kayıt Sorgulama, istek mesajları ve VPOS 7.24'ün bu mesajlara döndüğü cevaplar aşağıda gösterilmektedir.

### 9.3.1. Kayıt Detay Sorgulama İsteği

```xml
<SearchRequest>
    <MerchantCriteria>HostMerchantId=MerchantId</MerchantCriteria>
    <TransactionSearchCriteria>TransactionId=b3d7f5c5-e3d2-4d01-0a76</TransactionSearchCriteria>
    <StartDate>2014-10-05</StartDate>
    <AuthorizeTransactions>startDate</AuthorizeTransactions>
</SearchRequest>
```

### 9.3.2. Kayıt Detay Sorgulama Cevabı

```xml
<?xml version="1.0" encoding="utf-8"?>
<SearchResponse>ResponseInfo>
    <ResponseCode>0000</ResponseCode>
    <ResponseDateTime>2015-04-09T13:32:36.372289+02:00</ResponseDateTime>
    ...
    <TransactionId>b3d7f5c5-e3d2-4d01-0a76 be6cba87cb5c</TransactionId>
    <TransactionType>Sale</TransactionType>
    ...
    <ResultCode>0000</ResultCode>
    <ResultDescription>İŞLEM BAŞARILI</ResultDescription>
    ...
    <HostDate>113014 5930</HostDate>
    ...
    <Amount>44000</Amount>
    <Name>...</Name>
    <Value>...</Value>
    ...
</SearchResponse>
```

### 9.4. Gün Sonu Alınacak Kayıtları Sorgulama (OpenBatchTransactions)

Belirti bir terminale ait henüz kapatılmamış olan Batch numarası üzerinden, başarılı olarak gerçekleşmiş işlemlerin gün kırılım bilgileri:

- Toplam işlem adedi sayısı
- Toplam para tutarı
- Toplam para birimi kırılımı alında alınabilmektedir.

Örnek Gün Sonu Alınacak Kayıtlar Sorgulama istek mesajları ve VPOS 7.24'ün bu mesajlara döndüğü cevaplar aşağıda gösterilmektedir.

### 9.4.1. Gün Sonu Alınacak Kayıtlar Sorgu İsteği

```xml
<SucceededOpenBatchTransactionRequest>
    <MerchantCriteria>HostMerchantId=...</MerchantCriteria>
    <MerchantPassword>MerchantPassword=...</MerchantPassword>
    <HostTerminalId>batch 34</HostTerminalId>
    <SucceededOpenBatchTransactionRequest>
```

### 9.4.2. Gün Sonu Alınacak Kayıtlar Sorgu Cevabı

```xml
<?xml version="1.0" encoding="utf-8"?>
<SucceededOpenBatchTransactionsResponse>
    <ResponseInfo>
        <Status>Success</Status>
        <ResponseCode>0000</ResponseCode>
        <ResponseDateTime>2015-04-09T13:32:36.372289+02:00</ResponseDateTime>
    </ResponseInfo>
    <HostMerchantId>00000000056</HostMerchantId>
    <HostTerminalId>000PP224</HostTerminalId>
    <BatchNumber>1100</BatchNumber>
    <TotalSaleCount>RPT</TotalSaleCount>
    <CurrencyCode>949</CurrencyCode>
    <TotalItems>
        <TotalItem>
            <TotalAmount>9830.00</TotalAmount>
            <CurrencyCode>949</CurrencyCode>
        </TotalItem>
    </TotalItems>
    <Sale>
        <Items>
            <TotalItem>
                <TotalCount>22</TotalCount>
                <TotalAmount>232.00</TotalAmount>
                <CurrencyCode>949</CurrencyCode>
            </TotalItem>
            <TotalItem>
                <TotalAmount>202.00</TotalAmount>
                <CurrencyCode>949</CurrencyCode>
            </TotalItem>
        </Items>
    </Sale>
    <Capture>
        <Items>
            <TotalItem>
                <TotalAmount>431.00</TotalAmount>
                <CurrencyCode>949</CurrencyCode>
            </TotalItem>
        </Items>
    </Capture>
    <Cancel>
        <Items>
            <TotalItem>
                <TotalCount>41</TotalCount>
                <TotalAmount>432.00</TotalAmount>
                <CurrencyCode>949</CurrencyCode>
            </TotalItem>
            <TotalItem>
                <TotalCount>54</TotalCount>
                <TotalAmount>532.00</TotalAmount>
                <CurrencyCode>84 0</CurrencyCode>
            </TotalItem>
        </Items>
    </Cancel>
    <Refund>
        <Items>
            <TotalItem>
                <TotalCount>41</TotalCount>
                <TotalAmount>432.00</TotalAmount>
                <CurrencyCode>949</CurrencyCode>
            </TotalItem>
        </Items>
    </Refund>
    <Auth>
        <Items>
            <TotalItem>
                <TotalCount>41</TotalCount>
                <TotalAmount>432.00</TotalAmount>
                <CurrencyCode>949</CurrencyCode>
            </TotalItem>
        </Items>
    </Auth>
    <PointSale>
        <Items>
            <TotalItem>
                <TotalCount>43</TotalCount>
                <TotalAmount>24.00</TotalAmount>
                <CurrencyCode>0</CurrencyCode>
            </TotalItem>
        </Items>
    </PointSale>
    <VFTSale>
        <Items>
            <TotalItem>
                <TotalCount>75</TotalCount>
                <TotalAmount>97.00</TotalAmount>
                <CurrencyCode>949</CurrencyCode>
            </TotalItem>
        </Items>
    </VFTSale>
    <Credit>
        <Items>
            <TotalItem>
                <TotalCount>85</TotalCount>
                <TotalAmount>48.00</TotalAmount>
                <CurrencyCode>949</CurrencyCode>
            </TotalItem>
        </Items>
    </Credit>
</SucceededOpenBatchTransactionsResponse>
```
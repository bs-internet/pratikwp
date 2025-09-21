# PratikWP - Elementor Başlangıç Teması

`PratikWP`, modern, hızlı ve SEO uyumlu WordPress siteleri geliştirmek için tasarlanmış, Elementor ile tam uyumlu bir başlangıç (starter) temasıdır. Bu tema, özellikle üzerine alt temalar (child themes) inşa edilerek projeler geliştirmeyi hedefleyen ajanslar ve geliştiriciler için sağlam bir temel sunar. Over-engineering'den kaçınılarak, temiz ve anlaşılır bir kod yapısıyla geliştirilmiştir.

## ✨ Temel Özellikler

-   **Elementor Odaklı:** Header, Footer, Single ve Archive gibi tüm temel alanlar Elementor Tema Oluşturucu ile yönetilebilir.
-   **Merkezi Yönetim Paneli:** Site genelindeki önemli bilgileri (Firma Bilgileri, Sosyal Medya, Slider, WhatsApp) tek bir yerden yönetmenizi sağlayan "PratikWp" yönetim paneli.
-   **Modüler ve Genişletilebilir:** `inc` klasörü altında organize edilmiş, sınıflara dayalı (object-oriented) yapısıyla yeni özellikler eklemek ve mevcutları yönetmek oldukça kolaydır.
-   **Özel Elementor Widget'ları:**
    -   Site Logosu
    -   Navigasyon Menüsü
    -   Breadcrumbs
    -   Slider
    -   Firma Bilgileri
    -   Sosyal Medya Linkleri
    -   Yazı Metası (Post Meta)
-   **Alt Tema (Child Theme) Mimarisi:** Tüm projelerin bu temaya bağlı alt temalar olarak geliştirilmesi için tasarlanmıştır. `template-functions.php` ve `template-hooks.php` dosyaları, alt temalardan kolayca özelleştirme yapılmasına olanak tanır.
-   **Modern Kod Standartları:** WordPress 6.0+ ve PHP 8.0+ standartlarına uygun olarak yazılmıştır.
-   **Hafif ve Hızlı:** Gereksiz kütüphanelerden arındırılmış, sadece ihtiyaç duyulan CSS ve JS dosyalarını yükleyerek performansı ön planda tutar.
-   **Kullanıcı Dostu:** Elementor eklentisi aktif olmadığında kullanıcıyı bilgilendiren uyarı sistemi gibi özelliklerle geliştirici ve son kullanıcı deneyimini iyileştirir.

## 📋 Gereksinimler

-   **WordPress Sürümü:** 6.0 veya üstü
-   **PHP Sürümü:** 8.0 veya üstü
-   **Gerekli Eklenti:** **Elementor** (Temanın tam potansiyelini kullanabilmek için zorunludur).

## 🚀 Kurulum

1.  En son `pratikwp.zip` dosyasını indirin.
2.  WordPress admin panelinizden **Görünüm > Temalar > Yeni Ekle** yolunu izleyin.
3.  **Tema Yükle** butonuna tıklayın ve indirdiğiniz `.zip` dosyasını seçerek yükleyin.
4.  Temayı etkinleştirin.
5.  Bu tema üzerine geliştireceğiniz projeler için bir **alt tema (child theme)** oluşturup onu etkinleştirmeniz önerilir.

## ⚙️ Tema Yönetimi ve Ayarlar

Temaya özel tüm ayarları WordPress admin panelindeki **PratikWp** menüsü altından yönetebilirsiniz.

-   **PratikWp > Kontrol Paneli:** Genel sistem durumu ve hızlı işlem linkleri.
-   **PratikWp > Firma Bilgileri:** Sitenin genelinde kullanılacak adres, telefon, e-posta gibi iletişim bilgileri. Bu bilgiler, ilgili Elementor widget'ı, standart WordPress widget'ı veya `[pratikwp_firma_bilgisi]` shortcode'u ile sitede gösterilebilir.
-   **PratikWp > Sosyal Medya:** Facebook, Instagram, Twitter gibi sosyal medya hesap linkleriniz. Bu linkler, ilgili Elementor widget'ı, standart WordPress widget'ı veya `[pratikwp_sosyal_medya]` shortcode'u ile gösterilebilir.
-   **PratikWp > WhatsApp:** Sitede görünecek olan sabit WhatsApp iletişim butonu ayarları.
-   **PratikWp > Slider Yönetimi:** Ana sayfada veya diğer sayfalarda kullanılabilecek slider'ların görsellerini, başlıklarını ve linklerini yönetin. `[pratikwp_slider]` shortcode'u veya Elementor Slider widget'ı ile kullanılır.

## 🎨 Elementor ile Kullanım

### Tema Oluşturucu (Theme Builder)

-   **Header & Footer:** Elementor Pro kullanarak sitenizin başlık ve altbilgi alanlarını görsel olarak tasarlayabilirsiniz. Tema, bu alanlar için gerekli lokasyonları tanır.
-   **Single & Archive:** Blog yazılarınızın tekil gösterimini ve kategori/arşiv sayfalarınızın tasarımını Elementor ile kolayca yapabilirsiniz.

### Özel Widget'lar

"PratikWp" kategorisi altında, sitenizin dinamik bileşenlerini yönetmek için geliştirilmiş özel widget'ları bulabilirsiniz:

-   **Site Logosu:** Sitenizin logosunu veya metin başlığını ekler.
-   **Navigasyon Menüsü:** "Görünüm > Menüler" altında oluşturduğunuz menüleri gösterir.
-   **Slider:** Yönetim panelinden eklediğiniz slaytları gösterir.
-   ... ve diğerleri.

## 📁 Dosya Yapısı

-   **/assets**: Temanın derlenmiş CSS ve JavaScript dosyaları ile diğer statik varlıkları içerir.
-   **/inc**: Temanın ana mantığını barındıran PHP dosyalarını içerir.
    -   **/admin**: Yönetim paneli meta kutuları.
    -   **/classes**: Temanın modüler yapısını oluşturan ana sınıflar (Admin, Elementor, Slider vb.).
    -   **/elementor**: Özel Elementor widget'ları.
    -   **/widgets**: Standart WordPress widget'ları.
-   **/languages**: Çeviri dosyaları (`.pot`, `.po`, `.mo`).
-   **/template-parts**: `header`, `footer`, `content` gibi yeniden kullanılabilir şablon parçalarını içerir.
-   **functions.php**: Temanın ana başlangıç dosyasıdır ve gerekli tüm bileşenleri yükler.
-   **style.css**: Tema bilgilerini ve temel stil kodlarını içerir.

## 📜 Lisans

PratikWP, GNU Genel Kamu Lisansı v2 veya üstü ile lisanslanmıştır.

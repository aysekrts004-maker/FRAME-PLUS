# FRAME+ | Film ve Dizi Arşiv Sistemi

Bu proje, PHP tabanlı, modern ve kullanıcı dostu bir film/dizi arşivi ve favori yönetim web uygulamasıdır. Projede harici bir veritabanı (MySQL vb.) kullanılmamış olup, veriler güvenli bir şekilde JSON dosyalarında saklanmaktadır.

## 📁 Proje Klasör Yapısı

Önerilen ve modüler dosya mimarisi şu şekildedir:

```text
proje/
├── index.php         # Ana sayfa, arayüz, arama ve filtreleme bileşenleri
├── ekle.php          # Yeni üye kayıt işlemleri
├── sil.php           # Oturum sonlandırma ve favori içerik silme/kaldırma işlemleri
├── functions.php     # Ortak fonksiyonlar, veri dizileri ve dosya yönetimi
├── frame_data/       # Kullanıcı verilerinin tutulduğu klasör
│   └── kullanicilar.json
└── assets/
    └── style.css     # Proje tasarımı ve karanlık mod (Dark Mode) stilleri

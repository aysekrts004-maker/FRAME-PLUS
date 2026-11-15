<?php
session_start();

// Veritabanı yerine JSON dosyası kullanılıyor (Hocanın "veri tabanı yok" şartına tam uygun)
$folder = __DIR__ . "/frame_data";
if (!is_dir($folder)) mkdir($folder, 0777, true);
$file = $folder . "/kullanicilar.json";
if (!file_exists($file)) file_put_contents($file, "[]");

$users = json_decode(file_get_contents($file), true) ?: [];

function saveUsers($users, $file) {
    file_put_contents($file, json_encode($users, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}

function e($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, "UTF-8");
}

$items = [
    ["Ayla","Film","Türk","Dram","2017","Ayla_(film)"],
    ["Aile Arasında","Film","Türk","Komedi","2017","Aile_Arasında"],
    ["Babam ve Oğlum","Film","Türk","Dram","2005","Babam_ve_Oğlum"],
    ["G.O.R.A.","Film","Türk","Komedi","2004","G.O.R.A."],
    ["Organize İşler","Film","Türk","Komedi","2005","Organize_İşler"],
    ["Düğün Dernek","Film","Türk","Komedi","2013","Düğün_Dernek"],
    ["Inception","Film","Yabancı","Bilim Kurgu","2010","Inception"],
    ["Interstellar","Film","Yabancı","Bilim Kurgu","2014","Interstellar"],
    ["Titanic","Film","Yabancı","Romantik","1997","Titanic_(1997_film)"],
    ["Avatar","Film","Yabancı","Bilim Kurgu","2009","Avatar_(2009_film)"],
    ["The Dark Knight","Film","Yabancı","Aksiyon","2008","The_Dark_Knight"],
    ["The Matrix","Film","Yabancı","Bilim Kurgu","1999","The_Matrix"],
    ["Barbie","Film","Yabancı","Komedi","2023","Barbie_(film)"],
    ["Penceremden","Film","Yabancı","Romantik","2022","Through_My_Window"],
    ["Gerçek Kötüler (Suicide Squad)","Film","Yabancı","Aksiyon","2016","Suicide_Squad_(2016_film)"],
    ["Ruhlar Bölgesi: Kırmızı Kapı","Film","Yabancı","Korku","2023","Insidious:_The_Red_Door"],
    ["Katil Kitap Kulübü","Film","Yabancı","Gerilim","2023","Killer_Book_Club"],
    ["Shrek","Film","Yabancı","Animasyon","2001","Shrek"],
    ["Shrek 2","Film","Yabancı","Animasyon","2004","Shrek_2"],
    ["Shrek the Third","Film","Yabancı","Animasyon","2007","Shrek_the_Third"],
    ["Shrek Forever After","Film","Yabancı","Animasyon","2010","Shrek_Forever_After"],
    ["Zootropolis (Zootopia)","Film","Yabancı","Animasyon","2016","Zootopia"],
    ["Ezel","Dizi","Türk","Dram","2009","Ezel_(TV_series)"],
    ["Aşk-ı Memnu","Dizi","Türk","Dram","2008","Aşk-ı_Memnu"],
    ["Kurtlar Vadisi","Dizi","Türk","Aksiyon","2003","Kurtlar_Vadisi"],
    ["Kızılcık Şerbeti","Dizi","Türk","Dram","2022","Kızılcık_Şerbeti"],
    ["Yargı","Dizi","Türk","Polisiye","2021","Yargı_(TV_series)"],
    ["Stranger Things","Dizi","Yabancı","Bilim Kurgu","2016","Stranger_Things"],
    ["Dark","Dizi","Yabancı","Bilim Kurgu","2017","Dark_(TV_series)"],
    ["La Casa de Papel","Dizi","Yabancı","Suç","2017","Money_Heist"],
    ["Lupin","Dizi","Yabancı","Gizem","2021","Lupin_(French_TV_series)"],
    ["The Vampire Diaries","Dizi","Yabancı","Fantastik","2009","The_Vampire_Diaries"],
    ["Wednesday","Dizi","Yabancı","Fantastik","2022","Wednesday_(TV_series)"],
    ["Breaking Bad","Dizi","Yabancı","Suç","2008","Breaking_Bad"],
    ["Sherlock","Dizi","Yabancı","Gizem","2010","Sherlock_(TV_series)"],
    ["The Witcher","Dizi","Yabancı","Fantastik","2019","The_Witcher_(TV_series)"]
];

$descriptions = [
    "Stranger Things"=>"Küçük bir kasabada yaşanan gizemli olayları ve arkadaş grubunun maceralarını anlatır.",
    "Dark"=>"Bir kasabada kaybolan çocukların ardından ortaya çıkan zaman ve aile sırlarını konu alır.",
    "La Casa de Papel"=>"Profesör tarafından hazırlanan büyük bir soygun planını anlatır.",
    "Lupin"=>"Arsen Lüpen hikâyelerinden ilham alan zekice planlar yapan bir karakterin maceraları.",
    "Penceremden"=>"İki genç arasında gelişen romantik bir hikâye.",
    "Gerçek Kötüler (Suicide Squad)"=>"Bir grup suçlunun gizli bir görev için bir araya getirilmesini anlatan DC filmi.",
    "Zootropolis (Zootopia)"=>"Tavşan polis Judy Hopps ve tilki Nick Wilde'ın maceralarını anlatan animasyon.",
    "Shrek"=>"Bir devin masalsı dünyadaki eğlenceli macerası.",
    "Ruhlar Bölgesi: Kırmızı Kapı"=>"Bir ailenin geçmişinden gelen doğaüstü olayları konu alan korku filmi.",
    "Katil Kitap Kulübü"=>"Bir grup gencin karanlık bir sır etrafında gelişen gerilimli hikâyesi.",
    "Barbie"=>"Barbie'nin kendi dünyasının dışına çıkarak kendini keşfetmesini anlatır.",
    "The Vampire Diaries"=>"Gizemli bir kasabada yaşayan gençlerin doğaüstü olaylarla kesişen hikâyesi."
];

$specialImages = [
    "Penceremden" => "https://www.impawards.com/intl/spain/2022/posters/traves_de_mi_ventana_xxlg.jpg",
    "Gerçek Kötüler (Suicide Squad)" => "https://image.tmdb.org/t/p/original/c4Hln1Jn4LnUr6DO50la4CqVpbt.jpg",
    "Zootropolis (Zootopia)" => "https://images.squarespace-cdn.com/content/v1/51b3dc8ee4b051b96ceb10de/1449808866717-WTIFQWAI9DC57UEMXFZE/disney-releases-humorous-parody-posters-for-zootopia8",
    "Interstellar" => "https://image.tmdb.org/t/p/w500/gEU2QniE6E77NI6lCU6MxlNBvIx.jpg"
];
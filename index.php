<?php
session_start();

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

$message = "";

if (isset($_POST["register"])) {
    $name = trim($_POST["name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $phone = trim($_POST["phone"] ?? "");
    $password = $_POST["password"] ?? "";

    if (!$name || !$email || !$phone || !$password) {
        $message = "Please fill in all fields.";
    } else {
        $exists = false;

        foreach ($users as $user) {
            if (($user["email"] ?? "") === $email || ($user["phone"] ?? "") === $phone) {
                $exists = true;
                break;
            }
        }

        if ($exists) {
            $message = "This email or phone number is already registered.";
        } else {
            $users[] = [
                "name" => $name,
                "email" => $email,
                "phone" => $phone,
                "password" => password_hash($password, PASSWORD_DEFAULT),
                "favorites" => []
            ];

            saveUsers($users, $file);
            $message = "Registration successful. You can log in now.";
        }
    }
}

if (isset($_POST["login"])) {
    $login = trim($_POST["login_info"] ?? "");
    $password = $_POST["password"] ?? "";

    foreach ($users as $i => $user) {
        if (($user["email"] ?? "") === $login || ($user["phone"] ?? "") === $login) {
            if (password_verify($password, $user["password"] ?? "")) {
                $_SESSION["user"] = $i;
                break;
            }
        }
    }

    if (!isset($_SESSION["user"])) {
        $message = "Incorrect email, phone or password.";
    }
}

if (isset($_GET["logout"])) {
    session_destroy();
    header("Location: index.php");
    exit;
}

if (isset($_POST["favorite"]) && isset($_SESSION["user"])) {
    $i = $_SESSION["user"];
    $id = $_POST["favorite"];
    $favorites = $users[$i]["favorites"] ?? [];

    if (in_array($id, $favorites)) {
        $favorites = array_values(array_diff($favorites, [$id]));
    } else {
        $favorites[] = $id;
    }

    $users[$i]["favorites"] = $favorites;
    saveUsers($users, $file);

    header("Location: index.php");
    exit;
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

$loggedIn = isset($_SESSION["user"]) && isset($users[$_SESSION["user"]]);
$favorites = $loggedIn ? ($users[$_SESSION["user"]]["favorites"] ?? []) : [];

$specialImages = [
    "Penceremden" => "https://www.impawards.com/intl/spain/2022/posters/traves_de_mi_ventana_xxlg.jpg",
    "Gerçek Kötüler (Suicide Squad)" => "https://image.tmdb.org/t/p/original/c4Hln1Jn4LnUr6DO50la4CqVpbt.jpg",
    "Zootropolis (Zootopia)" => "https://images.squarespace-cdn.com/content/v1/51b3dc8ee4b051b96ceb10de/1449808866717-WTIFQWAI9DC57UEMXFZE/disney-releases-humorous-parody-posters-for-zootopia8",
    "Interstellar" => "https://image.tmdb.org/t/p/w500/gEU2QniE6E77NI6lCU6MxlNBvIx.jpg"
];
?>

<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>FRAME+ | Movie & Series</title>

<style>
:root {
    --bg:#f3f4f6;
    --surface:#fff;
    --text:#20242c;
    --muted:#7a8290;
    --line:#e2e5e9;
    --accent:#657184;
    --shadow:0 10px 30px rgba(25,35,50,.07);
}

body.dark {
    --bg:#14171d;
    --surface:#20252e;
    --text:#f5f6f8;
    --muted:#aeb5c1;
    --line:#343b47;
    --accent:#aeb8c8;
    --shadow:0 10px 30px rgba(0,0,0,.2);
}

* {
    box-sizing:border-box;
}

body {
    margin:0;
    background:var(--bg);
    color:var(--text);
    font-family:Arial,sans-serif;
    transition:.25s;
}

button,input,select {
    font:inherit;
}

button {
    cursor:pointer;
}

header {
    position:sticky;
    top:0;
    z-index:5;
    background:var(--surface);
    border-bottom:1px solid var(--line);
    padding:17px 5%;
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap:15px;
}

.logo {
    font-size:27px;
    font-weight:800;
    letter-spacing:3px;
}

.logo span {
    color:#8794a7;
}

.header-right {
    display:flex;
    align-items:center;
    gap:10px;
    flex-wrap:wrap;
}

.header-right a {
    text-decoration:none;
}

.btn {
    border:1px solid var(--line);
    border-radius:9px;
    padding:10px 14px;
    background:var(--surface);
    color:var(--text);
}

.btn.primary {
    background:var(--accent);
    color:white;
    border:0;
}

.container {
    width:92%;
    max-width:1450px;
    margin:28px auto;
}

.hero {
    min-height:270px;
    border-radius:18px;
    padding:45px;
    display:flex;
    align-items:center;
    background:linear-gradient(110deg,#202630 0%,#394454 55%,#78869a 100%);
    color:white;
    position:relative;
    overflow:hidden;
}

.hero:after {
    content:"FRAME+";
    position:absolute;
    right:4%;
    bottom:-35px;
    font-size:110px;
    font-weight:900;
    color:#ffffff12;
    letter-spacing:8px;
}

.hero-content {
    max-width:570px;
    position:relative;
    z-index:1;
}

.hero small {
    letter-spacing:4px;
    color:#d3dae4;
}

.hero h1 {
    font-size:42px;
    margin:15px 0;
}

.hero p {
    line-height:1.7;
    color:#e0e5eb;
}

.section-title {
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin:32px 0 17px;
}

.section-title h2 {
    margin:0;
    font-size:23px;
}

.section-title span {
    font-size:13px;
    color:var(--muted);
}

.filters {
    display:flex;
    flex-wrap:wrap;
    gap:10px;
    margin:24px 0;
}

.filters input,.filters select {
    border:1px solid var(--line);
    border-radius:9px;
    padding:12px 14px;
    background:var(--surface);
    color:var(--text);
}

.filters input {
    flex:1;
    min-width:210px;
}

.catalog {
    display:grid;
    grid-template-columns:repeat(auto-fill,minmax(175px,1fr));
    gap:20px;
}

.card {
    background:var(--surface);
    border:1px solid var(--line);
    border-radius:13px;
    overflow:hidden;
    box-shadow:var(--shadow);
    transition:.2s;
}

.card:hover {
    transform:translateY(-6px);
}

.poster-wrap {
    height:265px;
    background:linear-gradient(145deg,#303b4b,#8995a5);
    overflow:hidden;
    position:relative;
}

.poster {
    width:100%;
    height:100%;
    object-fit:cover;
    display:block;
    transition:.3s;
}

.card:hover .poster {
    transform:scale(1.04);
}

.poster-fallback {
    display:none;
    position:absolute;
    inset:0;
    align-items:center;
    justify-content:center;
    text-align:center;
    padding:15px;
    color:white;
    font-size:19px;
    font-weight:bold;
}

.card-info {
    padding:13px;
}

.card-info h3 {
    font-size:15px;
    margin:0 0 8px;
    min-height:36px;
}

.meta {
    font-size:11px;
    color:var(--muted);
    min-height:27px;
    line-height:1.5;
}

.actions {
    display:flex;
    gap:7px;
    margin-top:11px;
}

.actions button {
    flex:1;
    border:0;
    border-radius:7px;
    padding:9px 5px;
    background:var(--accent);
    color:white;
    font-size:12px;
}

.actions .fav {
    flex:0 0 38px;
    background:#e9ebef;
    color:#323945;
    font-size:17px;
}

body.dark .actions .fav {
    background:#3c4553;
    color:white;
}

.empty {
    display:none;
    text-align:center;
    padding:30px;
    color:var(--muted);
}

.login-page {
    min-height:100vh;
    display:grid;
    place-items:center;
    padding:20px;
}

.login-box {
    width:100%;
    max-width:420px;
    background:var(--surface);
    padding:32px;
    border:1px solid var(--line);
    border-radius:17px;
    box-shadow:var(--shadow);
}

.login-box h1,.login-box p {
    text-align:center;
}

.login-box p {
    color:var(--muted);
}

.login-box input {
    width:100%;
    padding:13px;
    margin:9px 0;
    border:1px solid var(--line);
    border-radius:9px;
    background:var(--bg);
    color:var(--text);
}

.login-box button {
    width:100%;
    padding:13px;
    margin-top:10px;
}

.login-box a {
    display:block;
    text-align:center;
    color:var(--text);
    margin-top:17px;
}

.message {
    padding:11px;
    background:var(--bg);
    border-radius:8px;
    text-align:center;
}

.modal {
    display:none;
    position:fixed;
    inset:0;
    background:#080b10d9;
    z-index:20;
    align-items:center;
    justify-content:center;
    padding:20px;
}

.modal-content {
    background:var(--surface);
    color:var(--text);
    width:100%;
    max-width:720px;
    border-radius:17px;
    padding:25px;
    max-height:90vh;
    overflow:auto;
}

.modal-layout {
    display:grid;
    grid-template-columns:180px 1fr;
    gap:24px;
}

.modal-poster {
    width:100%;
    height:260px;
    object-fit:cover;
    border-radius:10px;
}

.modal-content p {
    line-height:1.7;
    color:var(--muted);
}

.close {
    float:right;
    border:0;
    background:none;
    color:var(--text);
    font-size:26px;
}

.footer {
    padding:35px;
    text-align:center;
    color:var(--muted);
    font-size:13px;
}

@media(max-width:650px) {
    .hero {
        padding:27px;
        min-height:220px;
    }

    .hero h1 {
        font-size:29px;
    }

    .hero:after {
        font-size:60px;
    }

    .catalog {
        grid-template-columns:repeat(2,minmax(0,1fr));
        gap:12px;
    }

    .poster-wrap {
        height:220px;
    }

    .modal-layout {
        grid-template-columns:1fr;
    }

    .modal-poster {
        width:130px;
        height:190px;
    }
}
</style>
</head>

<body>

<?php if (!$loggedIn): ?>

<div class="login-page">
    <div class="login-box">

        <h1 class="logo">FRAME<span>+</span></h1>
        <p>Your personal movie and series archive.</p>

        <?php if ($message): ?>
            <div class="message"><?=e($message)?></div>
        <?php endif; ?>

        <?php if (isset($_GET["register"])): ?>

            <h2>Üye Ol</h2>

            <form method="POST">
                <input name="name" placeholder="Ad Soyad" required>
                <input type="email" name="email" placeholder="E-posta" required>
                <input name="phone" placeholder="Telefon Numarası" required>
                <input type="password" name="password" placeholder="Şifre" required>
                <button class="btn primary" name="register">Kayıt Ol</button>
            </form>

            <a href="index.php">Giriş sayfasına dön</a>

        <?php else: ?>

            <h2>Giriş Yap</h2>

            <form method="POST">
                <input name="login_info" placeholder="E-posta veya Telefon" required>
                <input type="password" name="password" placeholder="Şifre" required>
                <button class="btn primary" name="login">Giriş Yap</button>
            </form>

            <a href="?register=1">Henüz üye değil misin? Üye Ol</a>

        <?php endif; ?>

    </div>
</div>

<?php else: ?>

<header>
    <div class="logo">FRAME<span>+</span></div>

    <div class="header-right">
        <span>Merhaba, <?=e($users[$_SESSION["user"]]["name"])?></span>
        <button class="btn" onclick="changeTheme()">☾ / ☀ Tema</button>
        <a class="btn" href="?logout=1">Çıkış Yap</a>
    </div>
</header>

<main class="container">

    <section class="hero">
        <div class="hero-content">
            <small>WELCOME TO FRAME+</small>
            <h1>Hikâyeni seç.</h1>
            <p>Sevdiğin filmleri ve dizileri keşfet. Arşivini oluştur, favorilerini kaydet ve yeni dünyalara adım at.</p>

            <button class="btn primary" onclick="document.getElementById('catalog').scrollIntoView({behavior:'smooth'})">
                Arşivi Keşfet
            </button>
        </div>
    </section>

    <div class="section-title">
        <h2>Keşfet</h2>
        <span><?=count($items)?> içerik</span>
    </div>

    <div class="filters">

        <input id="search" placeholder="Film veya dizi ara..." oninput="filterItems()">

        <select id="type" onchange="filterItems()">
            <option value="">Tüm içerikler</option>
            <option>Film</option>
            <option>Dizi</option>
        </select>

        <select id="country" onchange="filterItems()">
            <option value="">Tüm ülkeler</option>
            <option>Türk</option>
            <option>Yabancı</option>
        </select>

        <select id="genre" onchange="filterItems()">
            <option value="">Tüm kategoriler</option>
            <option>Komedi</option>
            <option>Dram</option>
            <option>Aksiyon</option>
            <option>Bilim Kurgu</option>
            <option>Romantik</option>
            <option>Fantastik</option>
            <option>Gizem</option>
            <option>Suç</option>
            <option>Animasyon</option>
            <option>Korku</option>
            <option>Gerilim</option>
            <option>Polisiye</option>
        </select>

        <select id="onlyFavorites" onchange="filterItems()">
            <option value="">Bütün liste</option>
            <option value="yes">Favorilerim</option>
        </select>

    </div>

    <div class="section-title">
        <h2>Tüm Filmler ve Diziler</h2>
        <span>FRAME+ Collection</span>
    </div>

    <div class="catalog" id="catalog">

        <?php foreach ($items as $n=>$item):

            [$name,$type,$country,$genre,$year,$wiki] = $item;

            $id = "content-" . $n;
            $isFav = in_array($id,$favorites);
            $desc = $descriptions[$name] ?? ($name . " yapımının detaylarını ve fragmanını keşfet.");
            $trailer = "https://www.youtube.com/results?search_query=" . rawurlencode($name . " resmi fragman");
            $direct = $specialImages[$name] ?? "";

        ?>

        <article class="card"
            data-name="<?=e(mb_strtolower($name,"UTF-8"))?>"
            data-type="<?=e($type)?>"
            data-country="<?=e($country)?>"
            data-genre="<?=e($genre)?>"
            data-favorite="<?=$isFav ? "1":"0"?>">

            <div class="poster-wrap">

                <img class="poster"
                    alt="<?=e($name)?> afişi"
                    data-name="<?=e($name)?>"
                    data-wiki="<?=e($wiki)?>"
                    data-direct="<?=e($direct)?>"
                    data-type="<?=e($type)?>"
                    loading="lazy"
                    onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">

                <div class="poster-fallback"><?=e($name)?></div>

            </div>

            <div class="card-info">

                <h3><?=e($name)?></h3>

                <div class="meta">
                    <?=e($type)?> · <?=e($country)?> · <?=e($genre)?> · <?=e($year)?>
                </div>

                <div class="actions">

                    <button onclick="showDetails(this)">Detayları Gör</button>

                    <form method="POST">
                        <button class="fav" name="favorite" value="<?=e($id)?>">
                            <?=$isFav ? "♥":"♡"?>
                        </button>
                    </form>

                </div>

                <span hidden class="description"><?=e($desc)?></span>
                <span hidden class="trailer"><?=e($trailer)?></span>

            </div>

        </article>

        <?php endforeach; ?>

    </div>

    <div id="noResults" class="empty">
        Aradığın içerik bulunamadı.
    </div>

</main>

<div class="modal" id="detailsModal" onclick="outsideClick(event)">

    <div class="modal-content">

        <button class="close" onclick="closeDetails()">×</button>

        <div class="modal-layout">

            <img id="detailPoster" class="modal-poster" alt="Film afişi">

            <div>
                <h2 id="detailName"></h2>
                <p id="detailMeta"></p>
                <p id="detailDescription"></p>

                <a id="trailerLink" class="btn primary" target="_blank" rel="noopener">
                    Fragmanı İzle
                </a>
            </div>

        </div>

    </div>

</div>

<footer class="footer">
    FRAME+ · Your Movie & Series Collection
</footer>

<?php endif; ?>

<script>

function changeTheme() {
    document.body.classList.toggle("dark");

    localStorage.setItem(
        "frame-theme",
        document.body.classList.contains("dark") ? "dark" : "light"
    );
}

if (localStorage.getItem("frame-theme") === "dark") {
    document.body.classList.add("dark");
}

function filterItems() {

    const q = document.getElementById("search").value.toLocaleLowerCase("tr");
    const type = document.getElementById("type").value;
    const country = document.getElementById("country").value;
    const genre = document.getElementById("genre").value;
    const favorites = document.getElementById("onlyFavorites").value;

    let count = 0;

    document.querySelectorAll(".card").forEach(card => {

        const ok =
            card.dataset.name.includes(q) &&
            (!type || card.dataset.type === type) &&
            (!country || card.dataset.country === country) &&
            (!genre || card.dataset.genre === genre) &&
            (favorites !== "yes" || card.dataset.favorite === "1");

        card.style.display = ok ? "" : "none";

        if (ok) count++;

    });

    document.getElementById("noResults").style.display = count ? "none" : "block";
}

function showDetails(button) {

    const card = button.closest(".card");

    document.getElementById("detailName").textContent =
        card.querySelector("h3").textContent;

    document.getElementById("detailMeta").textContent =
        card.querySelector(".meta").textContent;

    document.getElementById("detailDescription").textContent =
        card.querySelector(".description").textContent;

    document.getElementById("detailPoster").src =
        card.querySelector(".poster").src;

    document.getElementById("trailerLink").href =
        card.querySelector(".trailer").textContent;

    document.getElementById("detailsModal").style.display = "flex";
}

function closeDetails() {
    document.getElementById("detailsModal").style.display = "none";
}

function outsideClick(event) {
    if (event.target.id === "detailsModal") {
        closeDetails();
    }
}

/* Load real image files for the catalog. */

async function loadPosters() {

    const images = document.querySelectorAll(".poster");

    for (const img of images) {

        if (img.dataset.direct) {
            img.src = img.dataset.direct;
            continue;
        }

        try {

            let imageUrl = "";

            if (img.dataset.type === "Dizi") {

                const response = await fetch(
                    "https://api.tvmaze.com/search/shows?q=" +
                    encodeURIComponent(img.dataset.name)
                );

                const data = await response.json();

                if (data.length && data[0].show.image) {
                    imageUrl =
                        data[0].show.image.original ||
                        data[0].show.image.medium;
                }
            }

            if (!imageUrl) {

                const response = await fetch(
                    "https://en.wikipedia.org/api/rest_v1/page/summary/" +
                    encodeURIComponent(img.dataset.wiki)
                );

                if (response.ok) {

                    const data = await response.json();

                    if (data.thumbnail) {
                        imageUrl = data.thumbnail.source;
                    }

                    if (data.originalimage) {
                        imageUrl = data.originalimage.source;
                    }
                }
            }

            if (imageUrl) {
                img.src = imageUrl;
            }

        } catch (error) {
            console.log("Poster could not be loaded:", img.dataset.name);
        }
    }
}

if (document.querySelector(".poster")) {
    loadPosters();
}

</script>

</body>
</html>
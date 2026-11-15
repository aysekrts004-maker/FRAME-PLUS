<?php
require_once "functions.php";

// Oturumu kapatma (Çıkış)
if (isset($_GET["logout"])) {
    session_destroy();
    header("Location: index.php");
    exit;
}

// Favorilerden silme / çıkarma işlemi
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
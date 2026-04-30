<?php
session_start();

$id_produk = $_GET["id_produk"];
$jumlah    = $_GET["jumlah"];
// Simpan ke session
$_SESSION["beli_sekarang"] = [
    "id_produk" => $id_produk,
    "jumlah"    => $jumlah
];

header("Location: ../checkout.php");
exit;
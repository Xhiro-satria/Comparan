<?php
session_start();
require_once "../server.php";
require_once "../function/admin_function.php";

if (!isset($_SESSION["id_user"]) || $_SESSION["role"] !== "admin") {
    header("Location: ../home.php");
    exit;
}

$nama            = $_POST["nama"];
$nilai           = $_POST["nilai"];
$poin_diperlukan = $_POST["poin_diperlukan"];

tambahVoucher($connect, $nama, $nilai, $poin_diperlukan);

header("Location: ../admin/voucher.php?pesan=berhasil");
exit;
?>
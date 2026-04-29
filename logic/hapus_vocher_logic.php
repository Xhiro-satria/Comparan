<?php
    session_start();
    require_once "../server.php";
    require_once "../function/admin_function.php";

    if (!isset($_SESSION["id_user"]) || $_SESSION["role"] !== "admin") {
        header("Location: ../home.php");
        exit;
    }

    $id_voucher = $_GET["id_voucher"];

    hapusVoucher($connect, $id_voucher);

    header("Location: ../admin/voucher.php");
    exit;
?>
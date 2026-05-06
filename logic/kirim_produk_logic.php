<?php
    session_start();
    require_once "../server.php";
    require_once "../function/order_function.php";

    if (!isset($_SESSION["id_user"])) {
        header("Location: ../login.php");
        exit;
    }

    $id_item = $_GET["id_item"];

    kirimProduk($connect, $id_item);

    header("Location: ../pesanan_masuk.php?pesan=berhasil");
    exit;
?>
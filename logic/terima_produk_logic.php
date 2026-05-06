<?php
    session_start();
    require_once "../server.php";
    require_once "../function/order_function.php";

    if (!isset($_SESSION["id_user"])) {
        header("Location: ../login.php");
        exit;
    }

    $id_item  = $_GET["id_item"];
    $id_order = $_GET["id_order"];

    terimaProduk($connect, $id_item, $id_order);

    header("Location: ../riwayat_order.php?pesan=berhasil");
    exit;
?>
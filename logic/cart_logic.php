<?php 
    session_start();

    require_once '../server.php';
    require_once '../function/cart_function.php';

    $id_user = $_SESSION["id_user"];
    $id_produk = $_GET["id_produk"];
    $jumlah = $_GET["jumlah"];

    // Ambil id_cart berdasarkan id_user
    $sql = "SELECT id_cart FROM cart WHERE id_user = '$id_user'";
    $result = $connect->query($sql);
    $cart = $result->fetch_assoc();
    $id_cart = $cart["id_cart"];

    // Panggil fungsi tambahKeranjang
    tambahKeranjang($connect, $id_cart, $id_produk, $jumlah);

    header("Location: ../home.php#shop");
    exit;
?>
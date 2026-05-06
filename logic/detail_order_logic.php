<?php
session_start();
require_once "../server.php";
require_once "../function/order_function.php";

$id_user  = $_SESSION["id_user"];
$id_order = $_GET["id_order"];

$cek = $connect->query("SELECT * FROM orders WHERE id_order = '$id_order' AND id_user = '$id_user'");

if ($cek->num_rows === 0) {
    header("Content-Type: application/json");
    echo json_encode([]);
    exit;
}

$result = $connect->query("SELECT order_items.*, products.nama_produk, products.gambar,
                            orders.tanggal_order, orders.alamat_pengiriman
                            FROM order_items
                            JOIN products ON order_items.id_produk = products.id_produk
                            JOIN orders ON order_items.id_order = orders.id_order
                            WHERE order_items.id_order = '$id_order'");
$detail = $result->fetch_all(MYSQLI_ASSOC);

header("Content-Type: application/json");
echo json_encode($detail);
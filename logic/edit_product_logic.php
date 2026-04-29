<?php 
    session_start();
    require_once '../server.php';
    require_once '../function/product_function.php';

    $id_produk   = $_POST["id_produk"];
    $nama_produk = $_POST["nama_produk"];
    $harga       = $_POST["harga"];
    $stok        = $_POST["stok"];
    $deskripsi   = $_POST["deskripsi"];
    $kategori    = $_POST["kategori"];

    $gambar = null;
    if ($_FILES["gambar"]["name"] != "") {
        $gambar   = $_FILES["gambar"]["name"];
        $tempFile = $_FILES["gambar"]["tmp_name"];
        move_uploaded_file($tempFile, "../uploads/produk/" . $gambar);
    }
    editProduk($connect, $id_produk, $nama_produk, $harga, $stok, $deskripsi, $kategori, $gambar);

    header("Location: ../my_product.php");
    exit;
?>
<?php 
    session_start();

    require_once '../server.php';
    require_once '../function/cart_function.php';
    require_once '../function/order_function.php';
    require_once '../function/voucher_function.php';

    $id_user     = $_SESSION["id_user"];
    $alamat      = $_POST["alamat"];
    $id_vouchers = $_POST["id_vouchers"] ?? [];
    $dari_cart   = $_POST["dari_cart"];

    $sql = "SELECT id_cart FROM cart WHERE id_user = '$id_user'";
    $result  = $connect->query($sql);
    $cart    = $result->fetch_assoc();
    $id_cart = $cart["id_cart"];

    if ($dari_cart == 1) {
        $items          = tampilKeranjang($connect, $id_cart);
        $checkout_semua = 1;
    } else {
        $beli           = $_SESSION["beli_sekarang"];
        $id_produk      = $beli["id_produk"];
        $jumlah         = $beli["jumlah"];

        $result = $connect->query("SELECT * FROM products WHERE id_produk = '$id_produk'");
        $produk = $result->fetch_assoc();

        $items = [[
            "id_produk"   => $id_produk,
            "nama_produk" => $produk["nama_produk"],
            "harga"       => $produk["harga"],
            "jumlah"      => $jumlah,
            "gambar"      => $produk["gambar"],
        ]];

        $checkout_semua = 0;

        // Hapus session beli sekarang setelah dipakai
        unset($_SESSION["beli_sekarang"]);
    }

    $total = 0;
    foreach ($items as $item) {
        $total += $item["harga"] * $item["jumlah"];
    }

    $id_order = checkout($connect, $id_user, $id_cart, $alamat, $items, $total, $id_vouchers, $checkout_semua);

    header("Location: ../home.php?id_order=" . $id_order);
    exit;
?>
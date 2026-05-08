<?php

    function checkout($connect, $id_user, $id_cart, $alamat, $items, $total, $id_vouchers, $checkout_semua) {
        $diskon      = 0;
        $total_bayar = $total;

        foreach ($id_vouchers as $value) {
            $result  = $connect->query("SELECT vouchers.nilai 
                                    FROM user_vouchers 
                                    JOIN vouchers ON user_vouchers.id_voucher = vouchers.id_voucher 
                                    WHERE user_vouchers.id = '$value'");
            $voucher = $result->fetch_assoc();
            $diskon += $voucher["nilai"];
        }

        $total_bayar = $total - $diskon;
        if ($total_bayar < 0) $total_bayar = 0;

        // 1. Buat order
        $sql = "INSERT INTO orders (id_user, id_cart, alamat_pengiriman, total_harga, diskon, total_bayar, status) 
                    VALUES ('$id_user', '$id_cart', '$alamat', '$total', '$diskon', '$total_bayar', 'pending')";
        $connect->query($sql);

        $id_order = $connect->insert_id;

        // 2. Simpan voucher
        if (count($id_vouchers) > 0) {
            pakaiVoucher($connect, $id_order, $id_vouchers);
        }

        foreach ($items as $item) {
            $subtotal  = $item["harga"] * $item["jumlah"];
            $harga     = $item["harga"];
            $jumlah    = $item["jumlah"];
            $id_produk = $item["id_produk"];

            // 3. Simpan ke order_items
            $sql = "INSERT INTO order_items (id_order, id_produk, jumlah, harga_satuan, subtotal) 
                        VALUES ('$id_order', '$id_produk', '$jumlah', '$harga', '$subtotal')";
            $connect->query($sql);

            // 4. Kurangi stok
            $sql = "UPDATE products SET stok = stok - '$jumlah' WHERE id_produk = '$id_produk'";
            $connect->query($sql);

            // 5. Kalau stok habis ubah status jadi sold
            $sql = "UPDATE products SET status = 'sold' WHERE id_produk = '$id_produk' AND stok <= 0";
            $connect->query($sql);

            // 6. Hapus item dari keranjang
            if ($checkout_semua == 0) {
                $sql = "DELETE FROM cart_items WHERE id_produk = '$id_produk' AND id_cart = '$id_cart'";
                $connect->query($sql);
            }
        }

        // 7. Kalau checkout semua kosongkan kranjang sekaligus
        if ($checkout_semua == 1) {
            $sql = "DELETE FROM cart_items WHERE id_cart = '$id_cart'";
            $connect->query($sql);
        }

        // 8. Tambah poin
        $poin = intval($total_bayar / 1000);
        $sql = "UPDATE users SET poin = poin + '$poin' WHERE id_user = '$id_user'";
        $connect->query($sql);

        // 9. Catat poin
        $sql = "INSERT INTO point_logs (id_user, id_order, poin_didapat) 
                    VALUES ('$id_user', '$id_order', '$poin')";
        $connect->query($sql);

        return $id_order;
    }

    function riwayatOrder($connect, $id_user){
        $sql = "SELECT * FROM orders WHERE id_user = '$id_user' ORDER BY tanggal_order DESC";
        $result = $connect->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    function jumlah_riwayat_order($connect, $id_user){
        $sql = "SELECT COUNT(*) as jumlah_order FROM orders WHERE id_user = '$id_user'";
        $result = $connect->query($sql);
        return $result->fetch_assoc();
    }

    function detailOrder($connect, $id_order) {
    $result = $connect->query("SELECT order_items.*, products.nama_produk, products.gambar,
                                orders.tanggal_order, orders.alamat_pengiriman
                                FROM order_items
                                JOIN products ON order_items.id_produk = products.id_produk
                                JOIN orders ON order_items.id_order = orders.id_order
                                WHERE order_items.id_order = '$id_order'");
    return $result->fetch_all(MYSQLI_ASSOC);
}

    function pesananMasuk($connect, $id_user) {
        $result = $connect->query("SELECT order_items.*, products.nama_produk, products.gambar,
                                    orders.tanggal_order, orders.alamat_pengiriman, orders.status as status_order,
                                    users.nama as nama_pembeli
                                    FROM order_items
                                    JOIN products ON order_items.id_produk = products.id_produk
                                    JOIN orders ON order_items.id_order = orders.id_order
                                    JOIN users ON orders.id_user = users.id_user
                                    WHERE products.id_user = '$id_user'
                                    ORDER BY orders.tanggal_order DESC");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    function jumlahPesananMasuk($connect, $id_user) {
        $result = $connect->query("SELECT COUNT(*) as total FROM order_items
                                JOIN products ON order_items.id_produk = products.id_produk
                                JOIN orders ON order_items.id_order = orders.id_order
                                WHERE products.id_user = '$id_user' AND order_items.status = 'pending'");
        $data = $result->fetch_assoc();
        return $data["total"];
    }

    function kirimProduk($connect, $id_item, $id_order) {
        $connect->query("UPDATE orders SET status = 'dikirim' WHERE id_order = '$id_order'");
        $connect->query("UPDATE order_items SET status = 'dikirim' WHERE id_item = '$id_item'");
    }

    function terimaProduk($connect, $id_item, $id_order) {
    $connect->query("UPDATE order_items SET status = 'selesai' WHERE id_item = '$id_item'");

    // Cek apakah semua item di order ini sudah selesai
    $result = $connect->query("SELECT COUNT(*) as total FROM order_items 
                            WHERE id_order = '$id_order' AND status != 'selesai'");
    $data   = $result->fetch_assoc();

    // Kalau semua sudah selesai update status order jadi selesai
    if ($data["total"] == 0) {
        $connect->query("UPDATE orders SET status = 'selesai' WHERE id_order = '$id_order'");
    }
}
?>
<?php 
    function semuaUser($connect) {
        $sql = "SELECT * FROM users ORDER BY id_user DESC";
        $result = $connect->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    function semuaOrder($connect){
        $sql = "SELECT orders.*, users.nama 
                    FROM orders 
                    JOIN users ON orders.id_user = users.id_user 
                    ORDER BY tanggal_order DESC";        
                    $result = $connect->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    function semuaVoucher($connect) {
        $sql = "SELECT * FROM vouchers";
        $result = $connect->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    function tambahVoucher($connect, $nama, $nilai, $poin_diperlukan) {
        $sql = "INSERT INTO vouchers (nama, tipe, nilai, poin_diperlukan) 
                        VALUES ('$nama', 'potongan_harga', '$nilai', '$poin_diperlukan')";
        $connect->query($sql);
    }

    function hapusVoucher($connect, $id_voucher) {
        $sql = "DELETE FROM vouchers WHERE id_voucher = '$id_voucher'";
        $connect->query($sql);
    }

    function hapusUser($connect, $id_user) {
        //Hapus cart_items milik user
        $connect->query("DELETE cart_items FROM cart_items 
                        JOIN cart ON cart_items.id_cart = cart.id_cart 
                        WHERE cart.id_user = '$id_user'");

        //Hapus cart milik user
        $connect->query("DELETE FROM cart WHERE id_user = '$id_user'");

        //Hapus point_logs milik user
        $connect->query("DELETE FROM point_logs WHERE id_user = '$id_user'");

        //Hapus user_vouchers milik user
        $connect->query("DELETE FROM user_vouchers WHERE id_user = '$id_user'");

        //Hapus order_items milik user
        $connect->query("DELETE order_items FROM order_items 
                        JOIN orders ON order_items.id_order = orders.id_order 
                        WHERE orders.id_user = '$id_user'");

        //Hapus orders milik user
        $connect->query("DELETE FROM orders WHERE id_user = '$id_user'");

        //Hapus produk milik user
        $connect->query("DELETE FROM products WHERE id_user = '$id_user'");

        //Hapus user
        $connect->query("DELETE FROM users WHERE id_user = '$id_user'");
    }
?>
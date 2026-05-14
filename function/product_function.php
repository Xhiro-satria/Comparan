<?php
    // Fungsi buat tambah produk baru
    function tambahProduk($connect, $id_user, $nama_produk, $harga, $stok, $deskripsi, $kategori, $gambar) {
    $sql = "INSERT INTO products (id_user, nama_produk, harga, stok, deskripsi, kategori, gambar, status) 
            VALUES (?, ?, ?, ?, ?, ?, ?, 'open')";
    $stmt = $connect->prepare($sql);
    $stmt->bind_param("isiisss", 
        $id_user,      
        $nama_produk,  
        $harga,        
        $stok,         
        $deskripsi,    
        $kategori,     
        $gambar,       
    );
    $stmt->execute();
    return $stmt->insert_id;
}

    // Fungsi buat ambil produk berdasarkan id_user
    function produkSaya($connect, $id_user){
        $sql = "SELECT * FROM products WHERE id_user = ?";
        $stmt = $connect->prepare($sql);
        $stmt->bind_param("i", $id_user);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Fungsi buat ambil semua produk kecuali produk milik user
    function tampilSemuaProduk($connect, $id_user){
        $sql = "SELECT p.*, u.username as nama_pemilik FROM products as p INNER JOIN users as u ON p.id_user = u.id_user WHERE p.id_user != '$id_user' && p.stok > 0";
        $result = $connect->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
        //fetch_all() digunakan untuk mengambil semua baris hasil query. 
        // MYSQLI_ASSOC memastikan bahwa setiap baris dikembalikan sebagai array asosiatif, di mana nama kolom digunakan sebagai kunci.
    }

    // Fungsi ngedit data product
    function editProduk($connect, $id_produk, $nama_produk, $harga, $stok, $deskripsi, $kategori, $gambar) {

        if ($gambar) {
            $connect->query("UPDATE products SET 
                            nama_produk = '$nama_produk',
                            harga       = '$harga',
                            stok        = '$stok',
                            deskripsi   = '$deskripsi',
                            kategori    = '$kategori',
                            gambar      = '$gambar'
                            WHERE id_produk = '$id_produk'");
        } else {
            $connect->query("UPDATE products SET 
                            nama_produk = '$nama_produk',
                            harga       = '$harga',
                            stok        = '$stok',
                            deskripsi   = '$deskripsi',
                            kategori    = '$kategori'
                            WHERE id_produk = '$id_produk'");
        }

        // update status pas edit data product
        if ($stok > 0){
            $connect->query("UPDATE products SET 
                            status = 'open'
                            WHERE id_produk = '$id_produk'");
        } else {
            $connect->query("UPDATE products SET 
                            status = 'sold'
                            WHERE id_produk = '$id_produk'");
        }
    }

    function searchProduk($connect, $id_user, $keyword){
        $sql = "SELECT * FROM products 
                    WHERE id_user != '$id_user' 
                    AND status = 'open' 
                    AND nama_produk LIKE '%$keyword%'";
        $result = $connect->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Fungsi buat hapus produk
    function hapusProduk($connect, $id_produk) {
        $connect->query("DELETE FROM order_items WHERE id_produk = '$id_produk'");
        $connect->query("DELETE FROM products WHERE id_produk = '$id_produk'");

    }
?>
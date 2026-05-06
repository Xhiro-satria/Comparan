<?php 
    session_start();
    require_once 'server.php';
    require_once 'function/product_function.php';

    if(!isset($_SESSION["id_user"])){
        header("Location: login.php");
        exit();
    }

    $pesan = "";

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $id_user     = $_SESSION["id_user"];    // Mengambil ID user dari session dan data produk dari inputan form
        $nama_produk = $_POST["nama_produk"];
        $harga       = $_POST["harga"];
        $stok        = $_POST["stok"];
        $deskripsi   = $_POST["deskripsi"];
        $kategori    = $_POST["kategori"];

        //upload gambar                                                
        $gambar = $_FILES["gambar"]["name"];                            // Mengambil nama asli file yang diunggah
        $tempFile = $_FILES["gambar"]["tmp_name"];                      // Mengambil lokasi penyimpanan sementara file di server
        move_uploaded_file($tempFile, "uploads/produk/" . $gambar);     // Memindahkan file dari lokasi sementara ke folder tujuan (uploads/produk/)
        //paggil fungsi tambah produk
        $id_produk = tambahProduk($connect, $id_user, $nama_produk, $harga, $stok, $deskripsi, $kategori, $gambar);
        if ($id_produk) {
            $pesan = "Berhasil";
        } else {
            $pesan = "Gagal";
        }
    } 
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap 5 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">
    <!-- Bootstrap Icon -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <!-- css -->
    <link rel="stylesheet" href="css/style.css">
    <title>Document</title>
    <style>
        /* *{outline: solid red 2px;} */

        body{
            background-color: var(--hover-soft);
        }

        .form-control, label{
            font-family: 'Inter';
            font-size: 14px;
        }

        .containerProfil{ height: 100vh; width: 100%; }

        .card-body p, .card-body p span a{ color: var(--primary-main); }

        .buttonCheckout{ font-family: 'safira'; }
    </style>
</head>
<body>
    <div class="containerProfil d-flex justify-content-center align-items-center">
        <div class="card w-25 p-4 pt-1 rounded-5 shadow">
            <div class="card-body d-flex flex-column align-items-center">
                <a href="my_product.php" class=" w-100 text-end text-decoration-none text-black"><i class="bi bi-x-lg"></i></a>
                <h2 class="judul-form profile mb-3">Add Product</h2>
                <?php if ($pesan === "Berhasil"): ?>
                    <p class="mb-2">Add Product Success! <span><a href="my_product.php" class="fw-bold">See Your Product</a></span></p>
                <?php elseif ($pesan === "Gagal") : ?>
                        <p class="mb-2">Add Product Failed!</p>
                <?php endif; ?>
                
                <form action="" method="POST" enctype="multipart/form-data">
                    <input type="text" class="form-control mb-3" name="nama_produk" placeholder="Product Name" required>
                    <input type="number" class="form-control mb-3" name="harga" placeholder="Price" required>
                    <input type="number" class="form-control mb-3" name="stok" placeholder="Stock" required>
                    <input type="text" class="form-control mb-3" name="kategori" placeholder="Category" required>
                    <textarea type="text" class="form-control mb-3" name="deskripsi" placeholder="Description" required></textarea>
                    <label class="w-100 text-center">Add Product Image</label>    
                    <input type="file" class="form-control mb-3" name="gambar" required>
                    <button type="submit" class="buttonCheckout btn form-control ">Add Product</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
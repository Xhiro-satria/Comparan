<?php 
    session_start();
    require_once 'server.php';
    require_once 'function/product_function.php';

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
            $pesan = "Produk berhasil ditambahkan.";
        } else {
            $pesan = "Gagal menambahkan produk.";
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
        .form-control, label{
            font-family: 'Ethereal Medium';
            letter-spacing: 1px;
            font-size: 14px;
        }
    </style>
</head>
<body style="background-color: rgb(215, 231, 192);">
    <div class="containerProfil d-flex justify-content-center align-items-center" style="height: 100vh; width: 100%;">
        <div class="card w-25 p-4 pt-1 rounded-5 shadow">
            <div class="card-body d-flex flex-column align-items-center">
                <a href="my_product.php" class=" w-100 text-end text-decoration-none text-black"><i class="bi bi-x-lg"></i></a>
                <h2 class="profile mb-3" style="font-family: 'Aesthetic'; color: #5d9300;">Add Product</h2>
                <?php if ($pesan === "berhasil"): ?>
                    <p class="mb-2" style="color: #5d9300;">Add Product Success! <span><a href="home.php" class="fw-bold" style="color: #5d9300;">Back to home?</a></span></p>
                <?php endif; ?>
                <!-- Data Diri -->
            
                <!-- <h6 class="editProfile mb-5" style="font-family: 'Voguella', sans-serif;">Edit Profil</h6> -->
                <form action="" method="POST" enctype="multipart/form-data">
                    <!-- <label >Product Name</label>     -->
                    <input type="text" class="form-control mb-3" name="nama_produk" placeholder="Product Name">
                    <!-- <label >Price</label>     -->
                    <input type="number" class="form-control mb-3" name="harga" placeholder="Price">
                    <!-- <label >Stock</label>     -->
                    <input type="number" class="form-control mb-3" name="stok" placeholder="Stock">
                    <!-- <label >Category</label>     -->
                    <input type="text" class="form-control mb-3" name="kategori" placeholder="Category">
                    <!-- <label >Description</label>     -->
                    <textarea type="text" class="form-control mb-3" name="deskripsi" placeholder="Description"></textarea>
                    <label class="w-100 text-center">Add Product Image</label>    
                    <input type="file" class="form-control mb-3" name="gambar">
                    <button type="submit" class="buttonCheckout btn form-control ">Add Product</button>
                </form>
            </div>
        </div>
    </div>

</body>
</html>
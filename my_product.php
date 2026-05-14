<?php
    session_start();
    require_once "server.php";
    require_once "function/product_function.php";

    if(!isset($_SESSION["id_user"])){
        header("Location: login.php");
        exit;
    }

    $id_user = $_SESSION["id_user"];
    $produk  = produkSaya($connect, $id_user);
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
    <title>My Product</title>
    <style>
        body {
            font-family: sans-serif;
            padding: 20px;
            background: var(--hover-soft);
        }

        .grid { display: flex; flex-wrap: wrap; gap: 20px; justify-content: center;}

        .judul{
            font-family: 'Safira';
            font-weight: bold;
            color: var(--primary-main);
            margin: 0px 0px 20px;
        }
        .buttonGeser{
            text-decoration: none;
            padding: 10px 15px;
            border-radius: 50px;
        }
        
        .card { 
            background: var(--white); 
            border-radius: 10px; 
            overflow: hidden; 
            width: 200px; 
            box-shadow: 0 4px 6px var(--overlay-dark);
            transition: 0.3s;
        }
        .card:hover { transform: translateY(-5px); }

        .card img { width: 100%; object-fit: cover; aspect-ratio: 1/1;}

        .card-body { padding: 12px; }

        .card-title {
            font-family: 'Belgiano';
            font-weight: bold;
            font-size: 1.1em;
            display: block;
            margin-bottom: 5px;
            text-transform: capitalize;
        }
        
        .card-price { color: var(--primary-main); font-weight: bold; }
        
        .tombol-group { 
            display: flex; 
            border-top: 1px solid var(--gray); 
        }
        .tombol-group button { 
            flex: 1; 
            padding: 10px; 
            border: none; 
            background: var(--transparent); 
            cursor: pointer; 
            font-size: 12px;
            transition: 0.2s;
        }
        .btn-read { color: var(--primary-accent); font-weight: 600; border-end-start-radius: 20px;}

        .btn-edit {
            font-weight: 600;
            color: var(--text-voucher);
            border-left: 1px solid var(--gray) !important;
            border-right: 1px solid var(--gray) !important;
        }

        .btn-delete { color: var(--red); font-weight: 600; border-end-end-radius: 20px;}

        .tombol-group button:hover { background: var(--card-bg); font-weight: bold; }

        .overlay-bg { 
            display: none; 
            position: fixed; 
            top: 0; left: 0; 
            width: 100%; height: 100%; 
            background: var(--overlay-dark-lm); 
            z-index: 1000;
        }
        .modal-popUp { 
            background: var(--white); 
            width: 90%;
            max-width: 450px; 
            max-height: 560px;
            margin: 50px auto;
            padding: 25px; 
            border-radius: 12px; 
            position: relative;
            overflow-y: auto;
        }
        #m-gambar {
            width: 100%;
            border-radius: 8px;
            margin-bottom: 15px;
        }
        .tutup {
            position: absolute;
            top: 15px;
            right: 20px;
            cursor: pointer;
            font-size: 24px;
        
        }
        form input, form textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            box-sizing: border-box;
        }

        .produkKosong{
            font-family: 'Safira';
            font-size: 24px;
            color: red;
        }

        .form-control, label, p{
            font-family: 'Inter', sans-serif;
        }

        .bungkusJudul .judul-form{
            font-size: 28px;
        }

    </style>
</head>
<body class="my_produk">
    <div class="w-100 d-flex justify-content-start align-items-center">
        <a href="home.php" class="btn-kembali"><i class="bi bi-arrow-left"></i> Back to Home</a>
    </div>
    <div class="bungkusJudul w-100 d-flex justify-content-center align-content-center mt-5">
        <h2 class="judul-form">My Products <i class="bi bi-bag-heart-fill"></i></h2>
    </div>
    <hr>
    <div class="m-1">
        <?php if (count($produk) === 0): ?>
            <div class="w-100 d-flex flex-column justify-content-center align-items-center">
                <p class="produkKosong">You haven't uploaded any products yet</p>
                <div class="container-fluid w-100 text-center">
                    <a href="add_product.php" class="buttonGeser fw-semibold">+ Add product</a>
                </div>
            </div>
        <?php else: ?>
            <div class="container-fluid w-100 text-start px-0">
                <a href="add_product.php" class="buttonGeser fw-semibold">+ Add product</a>
            </div>
            <div class="grid mt-5 justify-content-center align-items-center">
                <?php foreach ($produk as $p): ?>
                <div class="card rounded-5">
                    <div class="card-body">
                        <img src="uploads/produk/<?= $p['gambar'] ?> " class="rounded-4">
                        <span class="card-title my-2"><?= htmlspecialchars($p['nama_produk']) ?></span>
                        <div class="card-price">Rp <?= number_format($p['harga'], 0, ',', '.') ?></div>
                        <small>Stock : <?= $p['stok'] ?></small>
                        <div class="tombol-group">
                            <button class="btn-read" onclick="bukaModal(
                                '<?= $p['gambar'] ?>', 
                                '<?= addslashes($p['nama_produk']) ?>', 
                                '<?= $p['harga'] ?>', 
                                '<?= $p['stok'] ?>', 
                                '<?= $p['kategori'] ?>', 
                                '<?= $p['status'] ?>', 
                                '<?= addslashes($p['deskripsi']) ?>'
                            )">Detail</button>
    
                            <button class="btn-edit" onclick="bukaEdit(
                                '<?= $p['id_produk'] ?>', 
                                '<?= addslashes($p['nama_produk']) ?>', 
                                '<?= $p['harga'] ?>', 
                                '<?= $p['stok'] ?>', 
                                '<?= $p['kategori'] ?>', 
                                '<?= addslashes($p['deskripsi']) ?>'
                            )">Edit</button>
    
                            <button class="btn-delete"
                                onclick="hapus('<?= $p['id_produk'] ?>')">
                                Delete
                            </button>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <div class="overlay-bg" id="modal-detail" onclick="tutupSemuaModal()">
        <div class="modal-popUp" onclick="event.stopPropagation()">
            <span class="tutup" onclick="tutupSemuaModal()">×</span>
            <img id="m-gambar" src="">
            <h3 id="m-nama"></h3>
            <hr>
            <p><strong>Price : </strong> Rp<span id="m-harga"></span></p>
            <p><strong>Stock : </strong> <span id="m-stok"></span></p>
            <p><strong>Category : </strong> <span id="m-kategori"></span></p>
            <p><strong>Status : </strong> <span id="m-status"></span></p>
            <p><strong>Description : </strong><br><span id="m-deskripsi"></span></p>
        </div>
    </div>

    <div class="overlay-bg" id="modal-edit" onclick="tutupSemuaModal()">
        <div class="modal-popUp" onclick="event.stopPropagation()">
            <span class="tutup" onclick="tutupSemuaModal()">×</span>
            <h3 class="titleEdit judul-form my-4 text-center">Edit Product Information</h3>
            <form method="POST" action="logic/edit_product_logic.php" enctype="multipart/form-data">
                <input type="hidden" name="id_produk" id="e-id">
                <label>Product Name</label>
                <input type="text" class="form-control" name="nama_produk" id="e-nama" required>
                <label>Price (Rp)</label>
                <input type="number" class="form-control" name="harga" id="e-harga" required>
                <label>Stock</label>
                <input type="number" class="form-control" name="stok" id="e-stok" required>
                <label>Category</label>
                <input type="text" class="form-control" name="kategori" id="e-kategori">
                <label>Description</label>
                <textarea name="deskripsi" class="form-control" id="e-deskripsi" rows="4"></textarea>
                <label>Change Image (Optional)</label>
                <input type="file" class="form-control" name="gambar">
                <button type="submit" class="buttonGeser mt-2 py-2">Save Change</button>
            </form>
        </div>
    </div>

    <script>
        function bukaModal(gambar, nama, harga, stok, kategori, status, deskripsi) {
            document.getElementById("m-gambar").src = "uploads/produk/" + gambar;
            document.getElementById("m-nama").innerText = nama;
            document.getElementById("m-harga").innerText = parseInt(harga).toLocaleString("id-ID");
            document.getElementById("m-stok").innerText = stok;
            document.getElementById("m-kategori").innerText = kategori;
            document.getElementById("m-status").innerText = status;
            document.getElementById("m-deskripsi").innerText = deskripsi;
            document.getElementById("modal-detail").style.display = "block";
        }

        function bukaEdit(id, nama, harga, stok, kategori, deskripsi) {
            document.getElementById("e-id").value = id;
            document.getElementById("e-nama").value = nama;
            document.getElementById("e-harga").value = harga;
            document.getElementById("e-stok").value = stok;
            document.getElementById("e-kategori").value = kategori;
            document.getElementById("e-deskripsi").value = deskripsi;
            document.getElementById("modal-edit").style.display = "block";
        }

        function tutupSemuaModal() {
            document.getElementById("modal-detail").style.display = "none";
            document.getElementById("modal-edit").style.display = "none";
        }

        function hapus(id_produk) {
            if (confirm("Apakah Anda yakin ingin menghapus produk ini secara permanen?")) {
                window.location.href = "logic/hapus_product_logic.php?id_produk=" + id_produk;
            }
        }
    </script>

</body>
</html>
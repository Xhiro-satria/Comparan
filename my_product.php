<?php
    session_start();
    require_once "server.php";
    require_once "function/product_function.php";

    $id_user = $_SESSION["id_user"];
    $produk  = produkSaya($connect, $id_user);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
        <!-- Bootstrap 5 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">
    <!-- Bootstrap Icon -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <!-- css -->
    <link rel="stylesheet" href="css/style.css">
    <title>Produk Saya</title>
    <style>
        body { font-family: sans-serif; padding: 20px; background: #f4f4f4; }
        .grid { display: flex; flex-wrap: wrap; gap: 20px; }
        
        /* Card Style */
        .card { 
            background: white; 
            border-radius: 10px; 
            overflow: hidden; 
            width: 200px; 
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: 0.3s;
        }
        .card:hover { transform: translateY(-5px); }
        .card img { width: 100%; height: 150px; object-fit: cover; }
        .card-body { padding: 12px; }
        .card-title { font-weight: bold; font-size: 1.1em; display: block; margin-bottom: 5px; }
        .card-price { color: #e67e22; font-weight: bold; }
        
        /* Button Group */
        .tombol-group { 
            display: flex; 
            border-top: 1px solid #eee; 
        }
        .tombol-group button { 
            flex: 1; 
            padding: 10px; 
            border: none; 
            background: white; 
            cursor: pointer; 
            font-size: 12px;
            transition: 0.2s;
        }
        .btn-read { color: #3498db; }
        .btn-edit { color: #f1c40f; border-left: 1px solid #eee !important; border-right: 1px solid #eee !important; }
        .btn-delete { color: #e74c3c; }
        .tombol-group button:hover { background: #f9f9f9; font-weight: bold; }

        /* Modal Style */
        .overlay { 
            display: none; 
            position: fixed; 
            top: 0; left: 0; 
            width: 100%; height: 100%; 
            background: rgba(0,0,0,0.6); 
            z-index: 1000;
        }
        .modal { 
            background: white; 
            width: 90%; max-width: 450px; 
            margin: 50px auto;
            height: 560px;
            padding: 25px; 
            border-radius: 12px; 
            position: relative;
            overflow-y: auto;
        }
        .modal img { width: 100%; border-radius: 8px; margin-bottom: 15px; }
        .tutup { position: absolute; top: 15px; right: 20px; cursor: pointer; font-size: 24px; }
        form input, form textarea { width: 100%; padding: 10px; margin-bottom: 10px; box-sizing: border-box; }
    </style>
</head>
<body style="background-color: rgb(215, 231, 192);">
    <div class="w-100 d-flex justify-content-center align-content-center">
        <h2 style="font-family: 'Voguella'; color: #6FB400; margin: 20px 0px 50px; font-weight: bold;">My Products <i class="bi bi-bag-heart-fill"></i></h2>
    </div>
    <hr>
    <div class="m-5">
        <?php if (count($produk) === 0): ?>
            <div class="w-100 d-flex flex-column justify-content-center align-items-center">
                <p style="font-family: 'Safira'; font-size: 24px; color: red;">You haven't uploaded any products yet</p>
                <div class="container-fluid w-100 text-center px-5">
                    <a href="add_product.php" class="buttonCheckout" style="text-decoration: none; padding: 10px 15px; border-radius: 50px;">+ Tambah Produk</a>
                </div>
            </div>
            <?php else: ?>
                <div class="container-fluid w-100 text-start">
                    <a href="add_product.php" class="buttonCheckout" style="text-decoration: none; padding: 10px 15px; border-radius: 50px;">+ Tambah Produk</a>
                </div>
                <div class="grid mt-5">
                    <?php foreach ($produk as $p): ?>
                    <div class="card rounded-5">
                        <div class="card-body">
                            <img src="uploads/produk/<?= $p['gambar'] ?> " class="rounded-4">
                            <span class="card-title my-2" style="text-transform: capitalize;"><?= htmlspecialchars($p['nama_produk']) ?></span>
                            <div class="card-price">Rp <?= number_format($p['harga'], 0, ',', '.') ?></div>
                            <small>Stok: <?= $p['stok'] ?></small>
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
                                    Hapus
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <div class="overlay" id="modal-detail" onclick="tutupSemuaModal()">
        <div class="modal" onclick="event.stopPropagation()">
            <span class="tutup" onclick="tutupSemuaModal()">×</span>
            <img id="m-gambar" src="">
            <h3 id="m-nama" style="margin:0"></h3>
            <hr>
            <p><strong>Harga:</strong> Rp <span id="m-harga"></span></p>
            <p><strong>Stok:</strong> <span id="m-stok"></span></p>
            <p><strong>Kategori:</strong> <span id="m-kategori"></span></p>
            <p><strong>Status:</strong> <span id="m-status"></span></p>
            <p><strong>Deskripsi:</strong><br><span id="m-deskripsi"></span></p>
        </div>
    </div>

    <div class="overlay" id="modal-edit" onclick="tutupSemuaModal()">
        <div class="modal" onclick="event.stopPropagation()">
            <span class="tutup" onclick="tutupSemuaModal()">×</span>
            <h3>Edit Informasi Produk</h3>
            <form method="POST" action="logic/edit_product_logic.php" enctype="multipart/form-data">
                <input type="hidden" name="id_produk" id="e-id">
                <label>Nama Produk</label>
                <input type="text" name="nama_produk" id="e-nama" required>
                <label>Harga (Rp)</label>
                <input type="number" name="harga" id="e-harga" required>
                <label>Jumlah Stok</label>
                <input type="number" name="stok" id="e-stok" required>
                <label>Kategori</label>
                <input type="text" name="kategori" id="e-kategori">
                <label>Deskripsi</label>
                <textarea name="deskripsi" id="e-deskripsi" rows="4"></textarea>
                <label>Ganti Gambar (Opsional)</label>
                <input type="file" name="gambar">
                <button type="submit" style="width:100%; background:#27ae60; color:white; border:none; padding:12px; border-radius:5px; cursor:pointer;">Simpan Perubahan</button>
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
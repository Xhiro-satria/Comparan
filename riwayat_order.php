<?php 
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    require_once 'server.php';
    require_once 'function/order_function.php';

    if (!isset($_SESSION["id_user"])) {
        header("Location: login.php");
        exit;
    }

    $id_user = $_SESSION["id_user"];
    $orders = riwayatOrder($connect, $id_user);  
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Order</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"/>
    
    <style>
        body { background-color: #f4f7f6; padding: 20px; }
        .container-order { max-width: 800px; margin: auto; }
        
        .card-order {
            background: white;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 15px;
            cursor: pointer;
            border-left: 5px solid #198754;
            transition: 0.2s;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .card-order:hover { transform: scale(1.01); }

        /* Overlay */
        .overlay {
            display: none;
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background: rgba(0,0,0,0.6);
            z-index: 9999;
        }

        /* Modal Custom (Ganti nama agar tidak bentrok dengan Bootstrap) */
        .modal-custom {
            background: white;
            width: 90%;
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
            border-radius: 12px;
            max-height: 80vh;
            overflow-y: auto;
            position: relative;
            z-index: 10000;
        }

        .tutup {
            float: right;
            cursor: pointer;
            font-size: 24px;
            font-weight: bold;
            color: #999;
        }
    </style>
</head>
<body>

<div class="container-order">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Riwayat Order</h2>
        <a href="home.php" class="btn btn-secondary btn-sm">Kembali</a>
    </div>

    <?php if (empty($orders)): ?>
        <div class="alert alert-info">Belum ada riwayat transaksi.</div>
    <?php else: ?>
        <?php foreach ($orders as $order): ?>
            <div class="card-order" onclick="bukaDetail('<?= $order['id_order'] ?>')">
                <div class="d-flex justify-content-between">
                    <b>Pesanan <?= $order["id_order"] ?></b>
                    <span class="badge bg-success"><?= $order["status"] ?></span>
                </div>
                <small class="text-muted"><?= date('d M Y, H:i', strtotime($order["tanggal_order"])) ?></small><br>
                <span class="d-block mt-2">Total Bayar: <b>Rp <?= number_format($order["total_bayar"], 0, ',', '.') ?></b></span>
                <!-- <small class="text-truncate d-block">Alamat: <?= $order["alamat_pengiriman"] ?></small> -->
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<div class="overlay" id="overlay" onclick="tutupModal()">
    <div class="modal-custom" onclick="event.stopPropagation()">
        <span class="tutup" onclick="tutupModal()">&times;</span>
        <h4 class="mb-4">Detail Item Order</h4>
        <div id="isi-detail text-center">
            <p id="loading-text" style="display:none;">Mengambil data...</p>
        </div>
        <div id="konten-item"></div>
    </div>
</div>

<script>
    function bukaDetail(id_order) {
        const konten = document.getElementById("konten-item");
        const overlay = document.getElementById("overlay");
        
        konten.innerHTML = "<p>Loading detail...</p>";
        overlay.style.display = "block";

        fetch("logic/detail_order_logic.php?id_order=" + id_order)
            .then(res => {
                if (!res.ok) throw new Error("Gagal mengambil data");
                return res.json();
            })
            .then(data => {
                let html = "";
                if (data.length === 0) {
                    html = "<p class='text-center'>Tidak ada item.</p>";
                } else {
                    data.forEach(item => {
                        html += `
                            <div style="display:flex; gap:15px; margin-bottom:15px; border-bottom:1px solid #eee; padding-bottom:15px;">
                                <img src="uploads/produk/${item.gambar}" width="80" height="80" style="object-fit:cover; border-radius:8px;">
                                <div>
                                    <b class="d-block">${item.nama_produk}</b>
                                    <small class="text-muted">Harga: Rp ${parseInt(item.harga_satuan).toLocaleString("id-ID")}</small><br>
                                    <small class="text-muted">Jumlah: ${item.jumlah}</small><br>
                                    <small class="text-muted">Tanggal: ${item.tanggal_order}</small><br>
                                    <small class="text-muted">Alamat: ${item.alamat_pengiriman}</small><br>
                                    <b class="text-success">Subtotal: Rp ${parseInt(item.subtotal).toLocaleString("id-ID")}</b>
                                </div>
                            </div>`;
                    });
                }
                konten.innerHTML = html;
            })
            .catch(err => {
                konten.innerHTML = "<p class='text-danger'>Terjadi kesalahan saat memuat data.</p>";
                console.error(err);
            });
    }

    function tutupModal() {
        document.getElementById("overlay").style.display = "none";
    }
</script>

</body>
</html>
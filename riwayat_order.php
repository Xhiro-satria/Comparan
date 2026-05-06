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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">
    <!-- Bootstrap Icon -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <!-- css -->
    <link rel="stylesheet" href="css/style.css">
    <title>Order History</title>
    <style>
        body { background-color: var(--hover-soft); padding: 20px; }

        .alert{
            background-color: var(--white);
            color: var(--red);
            font-size: 16px;
            font-family: 'Inter', sans-serif;
            font-weight: 600;
            letter-spacing: 1px;
        }

        .container-order { max-width: 800px; margin: auto; }
        .card-order {
            background: var(--white);
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 15px;
            cursor: pointer;
            border-left: 5px solid var(--primary-dark);
            transition: 0.2s;
            box-shadow: 0 2px 5px var(--overlay-dark);
        }
        .card-order:hover { transform: scale(1.01); }

        /* Overlay */
        .overlay {
            display: none;
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background: var(--overlay-dark);
            z-index: 9999;
        }

        /* Modal Custom (Ganti nama agar tidak bentrok dengan Bootstrap) */
        .modal-custom {
            background: var(--white);
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
            color: var(--gray);
        }

        #loading-text{ display:none; }

        .dataPesan{
            display:flex;
            gap:15px;
            margin-bottom:15px;
            border-bottom:1px solid var(--bg-soft);
            padding-bottom:15px;
        }

        .dataPesan img{ object-fit:cover; border-radius:8px;}

        .badge{ background-color: var(--primary-main); }

        .loading-text{ display:none; }

        .nama-produk{ text-transform: capitalize; font-family: 'Inter'; }

        .text-muted{ font-family: 'Inter'; }

        .sub-total{ color: var(--primary-main); font-family: 'Inter'; }
    </style>
</head>
<body>

<div class="container-order">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <a href="home.php" class="btn-kembali btn-sm position-fixed mt-2"><i class="bi bi-arrow-left"></i> Back to Home</a>
        <h2 class="judul-form mt-5 mb-1">Order History</h2>
    </div>

    <?php if (empty($orders)): ?>
        <div class="alert">No transaction history yet.</div>
    <?php else: ?>
        <?php foreach ($orders as $order): ?>
            <div class="card-order" onclick="bukaDetail('<?= $order['id_order'] ?>')">
                <div class="d-flex justify-content-between">
                    <b>Order <?= $order["id_order"] ?></b>
                    <span class="badge"><?= $order["status"] ?></span>
                </div>
                <small class="text-muted"><?= date('d M Y, H:i', strtotime($order["tanggal_order"])) ?></small><br>
                <span class="d-block mt-2">Total Payment : <b>Rp<?= number_format($order["total_bayar"], 0, ',', '.') ?></b></span>
                <small class="text-truncate d-block">Address : <?= $order["alamat_pengiriman"] ?></small>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<div class="overlay" id="overlay" onclick="tutupModal()">
    <div class="modal-custom" onclick="event.stopPropagation()">
        <span class="tutup" onclick="tutupModal()">&times;</span>
        <h4 class="mb-4">Detail Item Order</h4>
        <div id="isi-detail text-center">
            <p id="loading-text">Fetching data...</p>
        </div>
        <div id="konten-item"></div>
    </div>
</div>

<script>
    function bukaDetail(id_order) {
        const konten = document.getElementById("konten-item");
        const overlay = document.getElementById("overlay");
        
        konten.innerHTML = "<p class='text-center'>Loading detail...</p>";
        overlay.style.display = "block";

        fetch("logic/detail_order_logic.php?id_order=" + id_order)
            .then(res => {
                if (!res.ok) throw new Error("Failed to fetch data");
                return res.json();
            })
            .then(data => {
                let html = "";
                if (data.length === 0) {
                    html = "<p class='text-center'>Tidak ada item.</p>";
                } else {
                    data.forEach(item => {
                        // Logika Tombol Status
                        let tombol = "";
                        if (item.status === "dikirim") {
                            tombol = `<a href="logic/terima_produk_logic.php?id_item=${item.id_item}&id_order=${item.id_order}" 
                                         class="btn btn-success btn-sm mt-2"
                                         onclick="return confirm('Konfirmasi terima produk ini?')">
                                         Konfirmasi Terima
                                      </a>`;
                        } else if (item.status === "selesai") {
                            tombol = `<span class="badge bg-primary mt-2">Selesai</span>`;
                        } else {
                            tombol = `<span class="badge bg-secondary mt-2">Menunggu Kurir</span>`;
                        }

                        html += `

                            <div style="display:flex; gap:15px; margin-bottom:15px; border-bottom:1px solid #eee; padding-bottom:15px;">
                                <img src="uploads/produk/${item.gambar}" width="80" height="80" style="object-fit:cover; border-radius:8px;">
                                <div style="flex:1;">
                                    <b class="d-block">${item.nama_produk}</b>
                                    <small class="text-muted">Harga: Rp ${parseInt(item.harga_satuan).toLocaleString("id-ID")}</small><br>
                                    <small class="text-muted">Jumlah: ${item.jumlah}</small><br>
                                    <b class="text-success d-block">Subtotal: Rp ${parseInt(item.subtotal).toLocaleString("id-ID")}</b>
                                    ${tombol}

                                </div>
                            </div>`;
                    });
                }
                konten.innerHTML = html;
            })
            .catch(err => {
                konten.innerHTML = "<p class='text-danger'>An error occurred while loading data.</p>";
                console.error(err);
            });
    }

    function tutupModal() {
        document.getElementById("overlay").style.display = "none";
    }

    // Menutup modal jika klik di luar area modal (overlay)
    window.onclick = function(event) {
        const overlay = document.getElementById("overlay");
        if (event.target == overlay) {
            tutupModal();
        }
    }
</script>

</body>
</html>
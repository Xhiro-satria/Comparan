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
    $jumlah = jumlah_riwayat_order($connect, $id_user);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="css/style.css">
    <title>Order History</title>
    <style>
        body { background-color: var(--hover-soft); padding: 15px; }

        .alert{
            background-color: var(--white);
            color: var(--red);
            font-size: 14px;
            font-family: 'Inter', sans-serif;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        .container-order { max-width: 700px; margin: auto; }
        
        .judul-form { font-size: 1.5rem !important; margin-top: 4rem !important; }

        .card-order {
            background: var(--white);
            border-radius: 8px;
            padding: 12px;
            margin-bottom: 10px;
            cursor: pointer;
            border-left: 4px solid var(--primary-dark);
            transition: 0.2s;
            box-shadow: 0 2px 5px var(--overlay-dark);
        }
        .card-order:hover { transform: scale(1.01); }

        .overlay {
            display: none;
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background: var(--overlay-dark-lm);
            z-index: 9999;
        }

        .modal-custom {
            background: var(--white);
            width: 90%;
            max-width: 450px; 
            margin: 40px auto;
            padding: 15px;
            border-radius: 10px;
            max-height: 80vh;
            overflow-y: auto;
            position: relative;
            z-index: 10000;
        }

        .tutup {
            float: right;
            cursor: pointer;
            font-size: 20px; 
            font-weight: bold;
            color: var(--gray);
        }

        .badge{ background-color: var(--primary-main); text-transform: capitalize; font-size: 12px; padding: 4px 8px;}

        .nama-produk{ text-transform: capitalize; font-family: 'Inter'; font-size: 0.95rem; }
        .text-muted{ font-family: 'Inter'; font-size: 0.8rem; }
        .sub-total{ color: var(--primary-main); font-family: 'Inter'; font-size: 0.9rem; }
        
        .d-block{ text-transform: capitalize; font-size: 0.9rem; }
        b { font-size: 0.9rem; }
    </style>
</head>
<body>

<div class="container-order">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="home.php" class="btn-kembali btn-sm position-fixed mt-2"><i class="bi bi-arrow-left"></i> Back to Home</a>
        <h2 class="judul-form mb-1">Order History</h2>
    </div>

    <?php if (empty($orders)): ?>
        <div class="alert text-center p-3">No transaction history yet.</div>
    <?php else: ?>
        <?php $no = $jumlah['jumlah_order']; foreach ($orders as $order): ?>
            <div class="card-order" onclick="bukaDetail('<?= $order['id_order'] ?>')">
                <div class="d-flex justify-content-between align-items-center">
                    <b style="font-size: 0.85rem;">Order #<?= $no-- ?></b>
                    <span class="badge"><?= $order['status'] ?></span>
                </div>
                <small class="text-muted"><?= date('d M Y, H:i', strtotime($order["tanggal_order"])) ?></small><br>
                <span class="d-block mt-1">Total Payment : <b>Rp<?= number_format($order["total_bayar"], 0, ',', '.') ?></b></span>
                <small class="text-truncate d-block" style="max-width: 90%;">Address : <?= $order["alamat_pengiriman"] ?></small>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<div class="overlay" id="overlay" onclick="tutupModal()">
    <div class="modal-custom" onclick="event.stopPropagation()">
        <span class="tutup" onclick="tutupModal()">&times;</span>
        <h5 class="mb-3">Detail Item Order</h5> <!-- Diperkecil dari h4 -->
        <div id="isi-detail text-center">
            <p id="loading-text" class="small">Fetching data...</p>
        </div>
        <div id="konten-item"></div>
    </div>
</div>

<script>
    function bukaDetail(id_order) {
        const konten = document.getElementById("konten-item");
        const overlay = document.getElementById("overlay");
        
        konten.innerHTML = "<p class='text-center small'>Loading detail...</p>";
        overlay.style.display = "block";

        fetch("logic/detail_order_logic.php?id_order=" + id_order)
            .then(res => {
                if (!res.ok) throw new Error("Failed to fetch data");
                return res.json();
            })
            .then(data => {
                let html = "";
                if (data.length === 0) {
                    html = "<p class='text-center small'>No items yet.</p>";
                } else {
                    data.forEach(item => {
                        let tombol = "";
                        if (item.status === "dikirim") {
                            tombol = `<a href="logic/terima_produk_logic.php?id_item=${item.id_item}&id_order=${item.id_order}" 
                                        class="btn btn-success btn-sm mt-2" style="font-size:11px; padding:2px 8px;"
                                        onclick="return confirm('Confirm recieve this product?')">
                                        Confirm Receipt
                                    </a>`;
                        } else if (item.status === "selesai") {
                            tombol = `<span class="badge bg-primary mt-2" style="font-size:10px;">Completed</span>`;
                        } else {
                            tombol = `<span class="badge bg-secondary mt-2" style="font-size:10px;">Waiting for Courier</span>`;
                        }

                        html += `
                            <div style="display:flex; gap:10px; margin-bottom:12px; border-bottom:1px solid #eee; padding-bottom:12px;">
                                <img src="uploads/produk/${item.gambar}" width="65" height="65" style="object-fit:cover; border-radius:6px;">
                                <div style="flex:1;">
                                    <b class="d-block" style="font-size:13px; line-height:1.2;">${item.nama_produk}</b>
                                    <small class="text-muted" style="font-size:11px;">Harga: Rp ${parseInt(item.harga_satuan).toLocaleString("id-ID")}</small><br>
                                    <small class="text-muted" style="font-size:11px;">Jumlah: ${item.jumlah}</small><br>
                                    <b class="text-success d-block" style="font-size:12px;">Subtotal: Rp ${parseInt(item.subtotal).toLocaleString("id-ID")}</b>
                                    ${tombol}
                                </div>
                            </div>`;
                    });
                }
                konten.innerHTML = html;
            })
            .catch(err => {
                konten.innerHTML = "<p class='text-danger small'>An error occurred.</p>";
                console.error(err);
            });
    }

    function tutupModal() {
        document.getElementById("overlay").style.display = "none";
    }

    window.onclick = function(event) {
        const overlay = document.getElementById("overlay");
        if (event.target == overlay) {
            tutupModal();
        }
    }
</script>

</body>
</html>
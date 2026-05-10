<?php
session_start();
require_once "server.php";
require_once "function/order_function.php";

if (!isset($_SESSION["id_user"])) {
    header("Location: login.php");
    exit;
}

$id_user = $_SESSION["id_user"];
$pesanan = pesananMasuk($connect, $id_user);
$pesan   = $_GET["pesan"] ?? "";
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
    <title>Incoming Orders | Comparan</title>
    <style>
        @keyframes muter {
            from {
                transform: rotate(0deg);
            }
            to {
                transform: rotate(360deg);
            }
        }

        body { background-color: var(--hover-soft); padding: 20px; }

        .badge-pending  { background: #6c757d; }
        .badge-dikirim  { background: #ffc107; color: #000; }
        .badge-selesai  { background: #198754; }

        .judul-form{ margin-top: 5rem; }

        .alert{ background-color: var(--primary-light); border: 2px solid var(--primary-main); color: var(--primary-dark);}

        .card {
            background-color: var(--card-bg);
            border-radius: 16px;
            padding: 10px 20px;
            margin-bottom: 10px;
            /* display: flex; */
            /* gap: 10px; */
            /* align-items: start; */
            box-shadow: 0 0 10px var(--glow-green);
        }

        .bungkusGambar{
            aspect-ratio: 1 / 1;
            width: 200px;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .bungkusGambar img {
            object-fit: cover;
            width: 100%;
            height: 100%;
            border-radius: 18px;
            background-color: black;
        }

        .containerData{
            text-transform: capitalize;
            font-family: 'Inter', sans-serif;
            gap: 5px;
        }

        .namaProduk{
            text-transform: capitalize;
            font-family: 'Catchye';
            letter-spacing: 1px;
        }

        .badge1{
            background-color: var(--primary-accent);
            padding: 6px 10px;
            border-radius: 8px;
            color: var(--white);
            text-align: center;
        }

        .badge2{
            background-color: var(--yellow);
            padding: 6px 10px;
            border-radius: 8px;
            color: var(--white);
            text-align: center;
        }

        .bi-hourglass-split {
            display: inline-block;
            animation: muter 2s linear infinite;
        }
        

        .badge3{
            background-color: var(--text-secondary);
            padding: 6px 12px;
            border-radius: 8px;
            color: var(--white);
            text-align: center;
            text-decoration: none;
        }

        .bungkusBadge{
            display: flex;
            gap: 10px;
        }

        @media (max-width: 768px) {
            body {
                padding: 10px !important;
            }

            .judul-form {
                font-size: 1.4rem !important;
                margin-top: 3rem !important;
            }

            .card{ padding: 0 !important; }

            .card-body { padding: 12px !important; }

            .container-body { gap: 12px !important; align-items: flex-start !important; }

            .bungkusGambar {
                width: 85px !important; /* Diperkecil sedikit lagi agar teks luas */
                height: 85px !important;
                min-width: 85px !important;
                border-radius: 12px !important;
                overflow: hidden;
            }

            .bungkusGambar img { border-radius: 12px !important; }

            .containerData { gap: 0px !important; }

            .namaProduk {
                font-size: 0.8rem !important;
                line-height: 1.2;
                margin-bottom: 4px !important;
            }

            .containerData small { font-size: 0.6rem !important; color: #6c757d !important;}

            small b { font-size: 0.65rem !important; margin: 6px 0 !important; color: var(--primary-dark) !important; }

            .bungkusBadge {
                margin-top: 5px !important;
                flex-direction: row !important;
                flex-wrap: wrap;
                gap: 4px !important;
            }

            .badge1, .badge2, .badge3 {
                font-size: 0.6rem !important;
                padding: 4px 8px !important;
                white-space: nowrap;
            }
        }
    </style>
</head>
<body>
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <a href="home.php" class="btn-kembali btn-sm position-fixed mt-2"><i class="bi bi-arrow-left"></i> Back to Home</a>
        <h1 class="judul-form mb-1"> Incoming Orders</h1>
    </div>

    <?php if ($pesan === "berhasil"): ?>
        <div class="alert fw-semibold">Status Update Successfully!</div>
    <?php endif; ?>

    <?php if (count($pesanan) === 0): ?>
        <div class="alert alert-info">No orders have been received yet.</div>
    <?php else: ?>
        <?php foreach ($pesanan as $p): ?>
            <div class="card mb-3">
                <div class="card-body">
                    <div class="container-body d-flex gap-3">
                        <div class="bungkusGambar">
                            <img src="uploads/produk/<?= $p["gambar"] ?>">
                        </div>
                        <div class="containerData d-flex flex-column">
                            <b class="namaProduk"><?= $p["nama_produk"] ?></b>
                            <small class="text-muted">Buyer : <?= $p["nama_pembeli"] ?></small>
                            <small class="text-muted">Quantity : <?= $p["jumlah"] ?></small>
                            <small class="text-muted">Address : <?= $p["alamat_pengiriman"] ?></small>
                            <small class="text-muted">Date : <?= date('d M Y, H:i', strtotime($p["tanggal_order"])) ?></small>
                            <small class="text-muted"><b>Subtotal : Rp<?= number_format($p["subtotal"], 0, ',', '.') ?></b></small>

                            <?php if ($p["status_order"] === "pending"): ?>
                                <div class="bungkusBadge">
                                    <span class="badge1 badge-<?= $p["status_order"] ?>">
                                        <?= $p["status_order"] ?>
                                    </span>
                                    <div class="d-flex">
                                        <a href="logic/kirim_produk_logic.php?id_item=<?= $p["id_item"] ?>&id_order=<?= $p['id_order'] ?>"
                                            class="badge3"
                                            onclick="return confirm('Konfirmasi kirim produk ini?')">
                                            Send <i class="bi bi-send-fill"></i>
                                        </a>
                                    </div>
                                </div>
                            <?php elseif ($p["status_order"] === "dikirim"): ?>
                                <div class="bungkusBadge">
                                    <span class="badge1 badge-<?= $p["status_order"] ?>">
                                        <?= $p["status_order"] ?>
                                    </span>
                                    <div class="d-flex">
                                        <span class="badge2">Waiting for confirmation...<i class="bi bi-hourglass-split"></i></span>
                                    </div>
                                </div>
                            <?php elseif ($p["status_order"] === "selesai"): ?>
                                <div class="d-flex">
                                    <span class="badge1"><i class="bi bi-check-circle-fill"></i> Completed</span>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
</body>
</html>
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
    <title>Pesanan Masuk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <style>
        .badge-pending  { background: #6c757d; }
        .badge-dikirim  { background: #ffc107; color: #000; }
        .badge-selesai  { background: #198754; }
    </style>
</head>
<body>
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Pesanan Masuk</h2>
        <a href="home.php" class="btn btn-secondary btn-sm">← Kembali</a>
    </div>

    <?php if ($pesan === "berhasil"): ?>
        <div class="alert alert-success">Status berhasil diupdate!</div>
    <?php endif; ?>

    <?php if (count($pesanan) === 0): ?>
        <div class="alert alert-info">Belum ada pesanan masuk.</div>
    <?php else: ?>
        <?php foreach ($pesanan as $p): ?>
            <div class="card mb-3">
                <div class="card-body">
                    <div class="d-flex gap-3">
                        <img src="uploads/produk/<?= $p["gambar"] ?>" 
                             width="80" height="80" 
                             style="object-fit:cover; border-radius:8px;">
                        <div class="flex-grow-1">
                            <b><?= $p["nama_produk"] ?></b><br>
                            <small class="text-muted">Pembeli: <?= $p["nama_pembeli"] ?></small><br>
                            <small class="text-muted">Jumlah: <?= $p["jumlah"] ?></small><br>
                            <small class="text-muted">Alamat: <?= $p["alamat_pengiriman"] ?></small><br>
                            <small class="text-muted">Tanggal: <?= date('d M Y, H:i', strtotime($p["tanggal_order"])) ?></small><br>
                            <small class="text-muted">Subtotal: Rp <?= number_format($p["subtotal"], 0, ',', '.') ?></small><br><br>

                            <span class="badge badge-<?= $p["status"] ?>">
                                <?= $p["status"] ?>
                            </span>

                            <?php if ($p["status"] === "pending"): ?>
                                <a href="logic/kirim_produk_logic.php?id_item=<?= $p["id_item"] ?>"
                                   class="btn btn-primary btn-sm ms-2"
                                   onclick="return confirm('Konfirmasi kirim produk ini?')">
                                    Kirim
                                </a>
                            <?php elseif ($p["status"] === "dikirim"): ?>
                                <span class="text-warning ms-2">Menunggu konfirmasi pembeli</span>
                            <?php elseif ($p["status"] === "selesai"): ?>
                                <span class="text-success ms-2">Selesai</span>
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
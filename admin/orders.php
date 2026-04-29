<?php 
    session_start();
    require_once "../server.php";
    require_once "../function/admin_function.php";

    if (!isset($_SESSION["id_user"]) || $_SESSION["role"] !== "admin") {
        header("Location: ../home.php");
        exit;
    }

    $orders = semuaOrder($connect);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Semua Order</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"/>
</head>
<body>
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Semua Order</h2>
        <a href="index.php" class="btn btn-secondary btn-sm">← Dashboard</a>
    </div>

    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>No</th>
                <th>User</th>
                <th>Tanggal</th>
                <th>Alamat</th>
                <th>Total Bayar</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($orders) === 0): ?>
                <tr><td colspan="6" class="text-center">Belum ada order.</td></tr>
            <?php else: ?>
                <?php $i = 1 ?>
                <?php foreach ($orders as $order): ?>
                    <tr>
                        <td><?= $i ?></td>
                        <td><?= $order["nama"] ?></td>
                        <td><?= date('d M Y, H:i', strtotime($order["tanggal_order"])) ?></td>
                        <td><?= $order["alamat_pengiriman"] ?></td>
                        <td>Rp <?= number_format($order["total_bayar"], 0, ',', '.') ?></td>
                        <td><span class="badge bg-success"><?= $order["status"] ?></span></td>
                        <?php $i++ ?>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>
</body>
</html>
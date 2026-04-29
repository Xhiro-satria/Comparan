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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Order | Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        body {
            background-color: rgb(215, 231, 192);
            font-family: 'Inter', sans-serif;
            color: #2f3e46;
            padding: 40px 0;
        }

        .order-container {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(15px);
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.08);
        }

        h2 {
            font-weight: 700;
            color: #1b4332;
            letter-spacing: -0.5px;
        }

        .btn-back {
            background-color: white;
            color: #2d6a4f;
            border: 2px solid #2d6a4f;
            border-radius: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-back:hover {
            background-color: #2d6a4f;
            color: white;
            transform: translateX(-3px);
        }

        /* Table Styling */
        .table-responsive-custom {
            border-radius: 15px;
            overflow: hidden;
            background: white;
            border: 1px solid rgba(0,0,0,0.05);
        }

        .table {
            margin-bottom: 0;
        }

        .table thead {
            background-color: #2d6a4f;
            color: white;
        }

        .table thead th {
            padding: 18px 15px;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            border: none;
        }

        .table tbody tr {
            transition: background-color 0.2s;
        }

        .table tbody tr:hover {
            background-color: #f8fdf9;
        }

        .table tbody td {
            padding: 16px 15px;
            vertical-align: middle;
            border-bottom: 1px solid #edf2f4;
            font-size: 0.9rem;
        }

        /* Status Badge Custom */
        .badge-order {
            padding: 6px 14px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.75rem;
            background-color: #d8f3dc;
            color: #1b4332;
            display: inline-flex;
            align-items: center;
        }

        .badge-order::before {
            content: '';
            width: 8px;
            height: 8px;
            background-color: #2d6a4f;
            border-radius: 50%;
            margin-right: 8px;
        }

        .text-price {
            font-family: 'Courier New', Courier, monospace; /* Memberikan kesan struk belanja */
            font-weight: 700;
            color: #1b4332;
        }

        .text-date {
            font-size: 0.8rem;
            color: #6c757d;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="order-container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2>Semua Order</h2>
                <p class="text-muted mb-0"><i class="bi bi-info-circle me-1"></i> Memantau semua transaksi yang masuk.</p>
            </div>
            <a href="index.php" class="btn btn-back btn-sm d-flex align-items-center">
                <i class="bi bi-arrow-left-short fs-5"></i> Dashboard
            </a>
        </div>

        <div class="table-responsive table-responsive-custom">
            <table class="table">
                <thead>
                    <tr>
                        <th class="text-center">No</th>
                        <th>Pelanggan</th>
                        <th>Waktu Transaksi</th>
                        <th>Alamat Pengiriman</th>
                        <th>Total Bayar</th>
                        <th class="text-center">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($orders) === 0): ?>
                        <tr><td colspan="6" class="text-center py-5 text-muted">Belum ada pesanan yang tercatat.</td></tr>
                    <?php else: ?>
                        <?php $i = 1 ?>
                        <?php foreach ($orders as $order): ?>
                            <tr>
                                <td class="text-center text-muted fw-bold"><?= $i ?></td>
                                <td>
                                    <div class="fw-bold"><?= htmlspecialchars($order["nama"]) ?></div>
                                    <small class="text-muted small">User ID: <?= $order["id_user"] ?? 'N/A' ?></small>
                                </td>
                                <td>
                                    <div class="text-date">
                                        <i class="bi bi-calendar3 me-1"></i> <?= date('d M Y', strtotime($order["tanggal_order"])) ?><br>
                                        <i class="bi bi-clock me-1"></i> <?= date('H:i', strtotime($order["tanggal_order"])) ?> WIB
                                    </div>
                                </td>
                                <td>
                                    <small class="text-wrap d-block" style="max-width: 250px;">
                                        <?= htmlspecialchars($order["alamat_pengiriman"]) ?>
                                    </small>
                                </td>
                                <td>
                                    <span class="text-price">Rp <?= number_format($order["total_bayar"], 0, ',', '.') ?></span>
                                </td>
                                <td class="text-center">
                                    <span class="badge-order">
                                        <?= strtoupper($order["status"]) ?>
                                    </span>
                                </td>
                                <?php $i++ ?>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
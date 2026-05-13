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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">
    <title>Orders history - Admin | Comparan</title>
    <style>
        body {
            background-color: var(--hover-soft);
            font-family: 'Inter', sans-serif;
            padding: 40px 0;
        }

        .order-container {
            background: var(--bg-soft);
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 12px 40px var(--overlay-dark);
        }

        h2 {
            font-weight: 700;
            color: var(--text-main);
            letter-spacing: -0.5px;
        }

        .btn-outline-success {
            background-color: var(--transparent);
        }

        /* Table Styling */
        .table-responsive-custom {
            border-radius: 15px;
            overflow: hidden;
            background: white;
            border: 2px solid var(--gray);
        }

        .table {
            margin-bottom: 0;
        }

        .table thead {
            color: var(--white);
        }

        .table thead th {
            padding: 18px 15px;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            border: none;
            border-bottom: 1px solid var(--gray);
        }

        .table tbody td {
            padding: 16px 15px;
            vertical-align: middle;
            border-bottom: 1px solid var(--gray);
            font-size: 0.9rem;
        }

        .badge-order {
            padding: 6px 14px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.75rem;
            background-color: var(--glow-green);
            color: var(--primary-dark);
            display: inline-flex;
            align-items: center;
        }

        .badge-order::before {
            content: '';
            width: 8px;
            height: 8px;
            background-color: var(--primary-dark);
            border-radius: 50%;
            margin-right: 8px;
        }

        .text-price {
            font-family: 'Courier New', Courier, monospace;
            font-weight: 700;
            color: var(--primary-dark2);
        }

        .text-date {
            font-size: 0.8rem;
            color: var(--overlay-dark);
        }

        @media (max-width: 768px) {
            body{ padding: 20px 10px; }

            .order-container{ padding: 20px 15px; border-radius: 16px; }

            .order-container .d-flex{ flex-direction: column; align-items: flex-start !important; gap: 15px; }

            h2{ font-size: 1.5rem; }

            .order-container p{ font-size: 0.9rem; }

            .btn-outline-success{ font-size: 0.85rem; padding: 7px 16px !important; }

            .table-responsive-custom{ overflow-x: auto; -webkit-overflow-scrolling: touch; }

            .table{ min-width: 850px; }

            .table thead th{ padding: 12px 10px; font-size: 0.72rem; white-space: nowrap; }

            .table tbody td{ padding: 12px 10px; font-size: 0.8rem; white-space: nowrap; }

            .text-date{ font-size: 0.72rem; }

            .text-price{ font-size: 0.85rem; } 

            .badge-order{ font-size: 0.68rem; padding: 5px 12px; }

            .badge-order::before{ width: 6px; height: 6px; margin-right: 6px; }

            td small{ font-size: 0.72rem; }

            .btn-outline-success{ font-size: 0.8rem; padding: 6px 14px !important; }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="order-container">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2>All Orders</h2>
                    <p class="text-muted mb-0"><i class="bi bi-info-circle me-1"></i> Monitoring all incoming transactions.</p>
                </div>
                <a href="index.php" class="btn-outline-success btn-sm rounded-pill px-3">
                    <i class="bi bi-arrow-left me-1"></i> Dashboard
                </a>
            </div>

            <div class="table-responsive table-responsive-custom">
                <table class="table">
                    <thead>
                        <tr>
                            <th class="text-center">No</th>
                            <th>Customer</th>
                            <th>Date</th>
                            <th>Shipping Address</th>
                            <th>Total Payment</th>
                            <th class="text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($orders) === 0): ?>
                            <tr><td colspan="6" class="text-center py-5 text-muted">No orders have been recorded yet.</td></tr>
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
                                        <small class="text-wrap d-block">
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
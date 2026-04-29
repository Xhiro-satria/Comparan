<?php
    session_start();
    require_once "../server.php";
    require_once "../function/admin_function.php";

    if(!isset($_SESSION["id_user"]) || $_SESSION["role"] !== "admin"){
        header("Location: ../login.php");
        exit();
    }
    $total_user = count(semuaUser($connect));
    $total_order  = count(semuaOrder($connect));
    $total_voucher = count(semuaVoucher($connect));
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin | Modern UI</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        body {
            background-color: rgb(215, 231, 192);;
            font-family: 'Inter', sans-serif;
            color: #334155;
        }

        .dashboard-container {
            padding-top: 50px;
            padding-bottom: 50px;
        }

        .header-section {
            margin-bottom: 40px;
        }

        .header-section h2 {
            font-weight: 700;
            color: #1e293b;
            letter-spacing: -0.5px;
        }

        /* Styling Kartu Custom */
        .card-custom {
            border: none;
            border-radius: 16px;
            transition: all 0.3s ease;
            overflow: hidden;
            position: relative;
        }

        .card-custom:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
        }

        .card-body {
            padding: 1.5rem;
            z-index: 1;
        }

        .card-icon {
            position: absolute;
            right: -10px;
            bottom: -10px;
            font-size: 5rem;
            opacity: 0.15;
            transform: rotate(-15deg);
        }

        .stat-label {
            font-size: 0.9rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            opacity: 0.9;
        }

        .stat-value {
            font-size: 2.5rem;
            font-weight: 700;
            margin: 10px 0;
        }

        .card-link {
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            transition: gap 0.3s;
        }

        .card-link:hover {
            gap: 8px;
            color: white;
            text-decoration: underline;
        }

        /* Warna Gradasi Spesifik */
        .bg-gradient-primary { background: linear-gradient(135deg, #4e73df 0%, #224abe 100%); }
        .bg-gradient-success { background: linear-gradient(135deg, #1cc88a 0%, #13855c 100%); }
        .bg-gradient-warning { background: linear-gradient(135deg, #f6c23e 0%, #dda20a 100%); }

        .btn-logout {
            border-radius: 10px;
            padding: 8px 20px;
            font-weight: 600;
            box-shadow: 0 4px 6px rgba(220, 53, 69, 0.2);
        }
    </style>
</head>
<body>

<div class="container dashboard-container">
    <div class="header-section d-flex justify-content-between align-items-center">
        <div>
            <h2>Dashboard Admin</h2>
            <p class="text-muted">Selamat datang kembali, admin! Berikut ringkasan hari ini.</p>
        </div>
        <a href="../logout.php" class="btn btn-danger btn-logout">
            <i class="bi bi-box-arrow-right me-2"></i>Logout
        </a>
    </div>

    <div class="row g-4">
        <div class="col-md-4">
            <div class="card card-custom text-white bg-gradient-primary shadow-sm">
                <div class="card-body">
                    <i class="bi bi-people card-icon"></i>
                    <div class="stat-label">Total User</div>
                    <div class="stat-value"><?= number_format($total_user ?? 0) ?></div>
                    <a href="users.php" class="text-white card-link">
                        Lihat User <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card card-custom text-white bg-gradient-success shadow-sm">
                <div class="card-body">
                    <i class="bi bi-cart-check card-icon"></i>
                    <div class="stat-label">Total Order</div>
                    <div class="stat-value"><?= number_format($total_order ?? 0) ?></div>
                    <a href="orders.php" class="text-white card-link">
                        Lihat Order <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card card-custom text-white bg-gradient-warning shadow-sm">
                <div class="card-body">
                    <i class="bi bi-ticket-perforated card-icon"></i>
                    <div class="stat-label">Total Voucher</div>
                    <div class="stat-value"><?= number_format($total_voucher ?? 0) ?></div>
                    <a href="voucher.php" class="text-white card-link">
                        Lihat Voucher <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
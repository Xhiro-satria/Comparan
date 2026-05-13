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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">
    <title>Dashboard Admin | Comparan</title>
    <style>
        body {
            background-color: var(--black);
            background-image: url(../assets/backgroundd.jpeg);
            background-repeat: none;
            margin: 0;
            height: 100vh;
        }

        .container-fluid{
            background-color: var(--overlay-dark-lm);
            height: 100%;
        }

        .dashboard-container {
            padding-top: 50px;
            padding-bottom: 50px;
        }

        .logoNav{ width: 120px; animation: float 3s ease-in-out infinite; transition: all 1s ease-in-out;}

        .logoNav:hover{ animation: float 1s ease-in-out infinite;}

        .header-section {
            margin-bottom: 40px;
        }

        .header-section h2, .header-section p {
            font-weight: 700;
            color: var(--hover-soft);
            letter-spacing: -0.5px;
            font-family: 'Inter', sans-serif;
        }

        .card-custom {
            border: none;
            border-radius: 16px;
            transition: all 0.3s ease;
            overflow: hidden;
            position: relative;
            background-color: var(--shadow-white);
            backdrop-filter: blur(10px);
            box-shadow: 0 0 12px var(--overlay-dark-lm);
        }

        .card-custom:hover{
            transform: translateY(-5px);
            background-color: var(--glow-green);
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
            font-size: 1rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            opacity: 0.9;
            font-family: 'Chocolate';
            letter-spacing: 1.5px;
        }

        .stat-value {
            font-size: 2.5rem;
            font-weight: 700;
            margin: 10px 0;
            font-family: 'Chocolate';
            letter-spacing: 1px;
        }

        .card-link {
            text-decoration: none;
            font-size: 0.85rem;
            display: inline-flex;
            align-items: center;
            transition: gap 0.3s;
            background-color: var(--text-main);
            padding: 6px 10px;
            border-radius: 50px;
            font-family: 'Chocolate';
            letter-spacing: 1px;
        }
        
        .card-link:hover {
            gap: 8px;
            color: white;
            font-style: italic;
        }

        #overlay-logout{
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: var(--overlay-dark-more);
            z-index: 9999;
        }

        .containerConfirm{
            background: var(--white);
            width: 300px;
            margin: 200px auto;
            padding: 20px;  
            border-radius: 10px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container-fluid m-0 px-5 p-0">
        <div class="w-100 d-flex justify-content-between align-items-center pt-4 mb-3">
            <a class="bungkusLogo d-flex justify-content-lg-start align-items-center w-100" href="#">
                <img src="../assets/logo-fix.png" alt="Logo Comparan" class="logoNav">
            </a>
            <a href="#" class="btn-kembali text-end position-static" onclick="konfirmasiSignOut(); return false;">
                <span class="d-flex"><i class="bi bi-box-arrow-right me-2"></i> Logout</span>
            </a>
        </div>
        <div class="dashboard-container m-0 p-0">
            <div class="header-section d-flex justify-content-between align-items-center">
                <div>
                    <h2>Dashboard Admin</h2>
                    <p class="opacity-50 fw-semibold">Welcome back, Admin! Here is today's summary.</p>
                </div>
            </div>
        
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card card-custom text-white">
                        <div class="card-body">
                            <i class="bi bi-people card-icon"></i>
                            <div class="stat-label">Total Users</div>
                            <div class="stat-value"><?= number_format($total_user ?? 0) ?></div>
                            <a href="users.php" class="text-white card-link">
                                See Users <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
        
                <div class="col-md-4">
                    <div class="card card-custom text-white">
                        <div class="card-body">
                            <i class="bi bi-cart-check card-icon"></i>
                            <div class="stat-label">Total Orders</div>
                            <div class="stat-value"><?= number_format($total_order ?? 0) ?></div>
                            <a href="orders.php" class="text-white card-link">
                                See Orders <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
        
                <div class="col-md-4">
                    <div class="card card-custom text-white">
                        <div class="card-body">
                            <i class="bi bi-ticket-perforated card-icon"></i>
                            <div class="stat-label">Total Vouchers</div>
                            <div class="stat-value"><?= number_format($total_voucher ?? 0) ?></div>
                            <a href="voucher.php" class="text-white card-link">
                                See Vouchers <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
            <div id="overlay-logout">
                <div class="containerConfirm">
                    <h5>Logout Confirmation</h5>
                    <p>Do you want to logout?</p>
                    <div class="d-flex justify-content-center">
                        <a href="../logout.php" class="btn btn-outline-danger mx-1">Yes</a>
                        <button onclick="tutupLogout();" class="btn-outline-success">Cancel</button>
                    </div>
                </div>
            </div>
    </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function tutupLogout() {
        document.getElementById("overlay-logout").style.display = "none";
    }
    function konfirmasiSignOut() {
        document.getElementById("overlay-logout").style.display = "block";
    }
</script>
</body>
</html>
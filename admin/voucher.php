<?php 
    session_start();
    require_once "../server.php";
    require_once "../function/admin_function.php";

    if (!isset($_SESSION["id_user"]) || $_SESSION["role"] !== "admin") {
        header("Location: ../home.php");
        exit;
    }
    $vouchers = semuaVoucher($connect);
    $pesan = $_GET["pesan"] ?? "";
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
    <title>Manage Vouchers - Admin | Comparan</title>
    <style>
        body {
            background-color: var(--hover-soft);
            font-family: 'Inter', sans-serif;
            padding: 40px 0;
        }

        .main-card {
            background: var(--bg-soft);
            border-radius: 20px;
            border: none;
            box-shadow: 0 10px 30px var(--overlay-dark);
            padding: 30px;
        }

        h2, h5 {
            font-weight: 700;
            color: var(--text-main);
        }

        .btn-outline-success{
            background-color: var(--transparent);
        }

        .form-section {
            background: var(--white);
            border-radius: 15px;
            padding: 20px;
            border: 1px solid var(--gray);
            margin-bottom: 30px;
        }

        .form-control {
            border-radius: 10px;
            border: 1px solid var(--gray);
            padding: 10px 15px;
            transition: all 0.3s;
        }

        .form-control:focus {
            border-color: var(--gray);
            box-shadow: 0 0 0 0.25rem var(--title-glow);
        }
        
        .btn-success-custom {
            background-color: var(--primary-main);
            border: none;
            border-radius: 10px;
            padding: 10px 25px;
            font-weight: 600;
            transition: 0.3s;
        }

        .btn-success-custom:hover {
            background-color: var(--primary-dark);
            transform: translateY(-2px);
        }

        .table-container {
            border-radius: 15px;
            overflow: hidden;
            background: white;
        }

        .table th {
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border: none;
            padding: 15px;
            border-bottom: 1px solid var(--gray);
        }

        .table td {
            vertical-align: middle;
            padding: 15px;
            border-bottom: 1px solid var(--gray);
        }

        .badge-poin {
            background-color: var(--glow-green);
            color: var(--primary-main);
            font-weight: 700;
            padding: 5px 12px;
            border-radius: 6px;
        }

        .alert-berhasil {
            border-radius: 12px;
            border: none;
            background-color: var(--primary-accent);
            color: white;
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

        @media (max-width: 768px) {

            .main-card {
                padding: 18px;
                border-radius: 15px;
            }

            h2 { font-size: 1.4rem; }

            h5 { font-size: 1rem; }

            .d-flex.justify-content-between {
                flex-direction: column;
                align-items: flex-start !important;
                gap: 15px;
            }

            .btn-outline-success {
                width: 100%;
                text-align: center;
                padding: 8px 12px;
            }

            .form-section { padding: 15px; }

            .form-label { font-size: 0.85rem; }

            .form-control { font-size: 0.9rem; padding: 10px; }

            .btn-success-custom { padding: 10px; font-size: 0.9rem; }

            .table-container { overflow-x: auto; }

            .table { min-width: 650px; }

            .table th, .table td {
                padding: 10px;
                font-size: 0.85rem;
                white-space: nowrap;
            }

            .badge-poin { font-size: 0.75rem; padding: 4px 8px; }

            .btn-outline-success{ font-size: 0.8rem; padding: 6px 14px !important; }

            .containerConfirm{
                width: 90%;
                margin: 150px auto;
                padding: 20px 15px;
            }

            .containerConfirm h5{ font-size: 1rem; }

            .containerConfirm p{ font-size: 0.85rem; }

            .containerConfirm .d-flex{ flex-direction: column; gap: 10px; }

            .containerConfirm a, .containerConfirm button{ width: 100%; }
        }
    </style>
</head>
<body>

<div class="container">
    <div class="main-card">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2>Manage Vouchers</h2>
                <p class="mb-0">Create and manage rewards for loyal users</p>
            </div>
            <a href="index.php" class="btn-outline-success rounded-pill px-2 btn-sm">
                <i class="bi bi-arrow-left me-1"></i> Dashboard
            </a>
        </div>

        <?php if ($pesan === "berhasil"): ?>
            <div class="alert alert-berhasil alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> New voucher has been added successfully!
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="form-section">
            <h5 class="mb-3"><i class="bi bi-plus-circle me-2"></i>Add Voucher</h5>
            <form method="POST" action="../logic/tambah_voucher_logic.php">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label small fw-bold">Voucher Name</label>
                        <input type="text" name="nama" class="form-control" placeholder="Contoh: Diskon Kopi" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-bold">Discount Value (Rp)</label>
                        <input type="number" name="nilai" class="form-control" placeholder="10000" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-bold">Required Points</label>
                        <input type="number" name="poin_diperlukan" class="form-control" placeholder="50" required>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-success-custom text-light w-100">Save</button>
                    </div>
                </div>
            </form>
        </div>

        <div class="table-container shadow-sm">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th class="text-center">ID</th>
                        <th>Voucher Name</th>
                        <th>Discount Value</th>
                        <th>Required Points</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($vouchers) === 0): ?>
                        <tr><td colspan="5" class="text-center py-5 text-muted">No active vouchers available.</td></tr>
                    <?php else: ?>
                        <?php $i = 1 ?>
                        <?php foreach ($vouchers as $v): ?>
                            <tr>
                                <td class="text-center text-muted fw-bold"><?= $i ?></td>
                                <td class="fw-semibold"><?= htmlspecialchars($v["nama"]) ?></td>
                                <td class="text-success fw-bold">Rp <?= number_format($v["nilai"], 0, ',', '.') ?></td>
                                <td><span class="badge-poin"><?= $v["poin_diperlukan"] ?> Pts</span></td>
                                <td class="text-center">
                                    <a href="#"
                                        onclick="konfirmasiHapus(); return false;"
                                        class="btn btn-outline-danger btn-sm border-0">
                                        <i class="bi bi-trash3"></i> Delete
                                    </a>
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

    <div id="overlay-logout">
        <div class="containerConfirm d-flex flex-column align-middle">
            <h5>Delete Confirmation</h5>
            <p>Do you want to delete this voucher?</p>
            <div class="d-flex justify-content-center  align-items-center w-100">
                <a href="../logic/hapus_vocher_logic.php?id_voucher=<?= $v["id_voucher"] ?>" class="btn btn-outline-danger mx-1">Delete</a>
                <button onclick="tutupHapus();" class="btn-outline-success">Cancel</button>
            </div>
        </div>
    </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function tutupHapus() {
        document.getElementById("overlay-logout").style.display = "none";
    }
    function konfirmasiHapus() {
        document.getElementById("overlay-logout").style.display = "block";
    }
</script>
</body>
</html>

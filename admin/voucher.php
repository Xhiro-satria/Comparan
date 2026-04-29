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
    <title>Kelola Voucher | Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        body {
            background-color: rgb(215, 231, 192); /* Sage Green Pilihan Anda */
            font-family: 'Inter', sans-serif;
            color: #2f3e46;
            padding: 40px 0;
        }

        .main-card {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(12px);
            border-radius: 20px;
            border: none;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            padding: 30px;
        }

        h2, h5 {
            font-weight: 700;
            color: #1b4332;
        }

        /* Form Styling */
        .form-section {
            background: #ffffff;
            border-radius: 15px;
            padding: 20px;
            border: 1px solid rgba(0,0,0,0.05);
            margin-bottom: 30px;
        }

        .form-control {
            border-radius: 10px;
            border: 1px solid #ced4da;
            padding: 10px 15px;
            transition: all 0.3s;
        }

        .form-control:focus {
            border-color: #52796f;
            box-shadow: 0 0 0 0.25rem rgba(82, 121, 111, 0.25);
        }

        /* Button Custom */
        .btn-success-custom {
            background-color: #2d6a4f;
            border: none;
            border-radius: 10px;
            padding: 10px 25px;
            font-weight: 600;
            transition: 0.3s;
        }

        .btn-success-custom:hover {
            background-color: #1b4332;
            transform: translateY(-2px);
        }

        .btn-back {
            background-color: transparent;
            color: #2d6a4f;
            border: 2px solid #2d6a4f;
            border-radius: 10px;
            font-weight: 600;
        }

        /* Table Styling */
        .table-container {
            border-radius: 15px;
            overflow: hidden;
            background: white;
        }

        .table thead {
            background-color: #52796f;
            color: white;
        }

        .table th {
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border: none;
            padding: 15px;
        }

        .table td {
            vertical-align: middle;
            padding: 15px;
            border-bottom: 1px solid #f1f3f5;
        }

        .badge-poin {
            background-color: #d8f3dc;
            color: #1b4332;
            font-weight: 700;
            padding: 5px 12px;
            border-radius: 6px;
        }

        .alert-berhasil {
            border-radius: 12px;
            border: none;
            background-color: #40916c;
            color: white;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="main-card">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2>Kelola Voucher</h2>
                <p class="text-muted mb-0">Buat dan atur reward untuk user setia</p>
            </div>
            <a href="index.php" class="btn btn-back btn-sm">
                <i class="bi bi-arrow-left me-1"></i> Dashboard
            </a>
        </div>

        <?php if ($pesan === "berhasil"): ?>
            <div class="alert alert-berhasil alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> Voucher baru telah ditambahkan!
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="form-section">
            <h5 class="mb-3"><i class="bi bi-plus-circle me-2"></i>Tambah Voucher</h5>
            <form method="POST" action="../logic/tambah_voucher_logic.php">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label small fw-bold">Nama Voucher</label>
                        <input type="text" name="nama" class="form-control" placeholder="Contoh: Diskon Kopi" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-bold">Nilai Potongan (Rp)</label>
                        <input type="number" name="nilai" class="form-control" placeholder="10000" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-bold">Poin Diperlukan</label>
                        <input type="number" name="poin_diperlukan" class="form-control" placeholder="50" required>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-success-custom text-light w-100">Simpan</button>
                    </div>
                </div>
            </form>
        </div>

        <div class="table-container shadow-sm">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th class="text-center">ID</th>
                        <th>Nama Voucher</th>
                        <th>Nilai Potongan</th>
                        <th>Kebutuhan Poin</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($vouchers) === 0): ?>
                        <tr><td colspan="5" class="text-center py-5 text-muted">Belum ada voucher aktif.</td></tr>
                    <?php else: ?>
                        <?php foreach ($vouchers as $v): ?>
                            <tr>
                                <td class="text-center text-muted fw-bold">#<?= $v["id_voucher"] ?></td>
                                <td class="fw-semibold"><?= htmlspecialchars($v["nama"]) ?></td>
                                <td class="text-success fw-bold">Rp <?= number_format($v["nilai"], 0, ',', '.') ?></td>
                                <td><span class="badge-poin"><?= $v["poin_diperlukan"] ?> Pts</span></td>
                                <td class="text-center">
                                    <a href="../logic/hapus_voucher_logic.php?id_voucher=<?= $v["id_voucher"] ?>"
                                       onclick="return confirm('Hapus voucher ini?')"
                                       class="btn btn-outline-danger btn-sm border-0">
                                        <i class="bi bi-trash3"></i> Hapus
                                    </a>
                                </td>
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

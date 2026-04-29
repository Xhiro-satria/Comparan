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
    <title>Kelola Voucher</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"/>
</head>
<body>
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Kelola Voucher</h2>
        <a href="index.php" class="btn btn-secondary btn-sm">← Dashboard</a>
    </div>

    <?php if ($pesan === "berhasil"): ?>
        <div class="alert alert-success">Voucher berhasil ditambahkan!</div>
    <?php endif; ?>

    <!-- Form Tambah Voucher -->
    <div class="card mb-4">
        <div class="card-body">
            <h5>Tambah Voucher</h5>
            <form method="POST" action="../logic/tambah_voucher_logic.php">
                <div class="mb-2">
                    <input type="text" name="nama" class="form-control" placeholder="Nama Voucher" required>
                </div>
                <div class="mb-2">
                    <input type="number" name="nilai" class="form-control" placeholder="Nilai Potongan (Rp)" required>
                </div>
                <div class="mb-2">
                    <input type="number" name="poin_diperlukan" class="form-control" placeholder="Poin Diperlukan" required>
                </div>
                <button type="submit" class="btn btn-success">Tambah</button>
            </form>
        </div>
    </div>

    <!-- Daftar Voucher -->
    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Nama</th>
                <th>Nilai</th>
                <th>Poin</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($vouchers) === 0): ?>
                <tr><td colspan="5" class="text-center">Belum ada voucher.</td></tr>
            <?php else: ?>
                <?php foreach ($vouchers as $v): ?>
                    <tr>
                        <td><?= $v["id_voucher"] ?></td>
                        <td><?= $v["nama"] ?></td>
                        <td>Rp <?= number_format($v["nilai"], 0, ',', '.') ?></td>
                        <td><?= $v["poin_diperlukan"] ?> poin</td>
                        <td>
                            <a href="../logic/hapus_voucher_logic.php?id_voucher=<?= $v["id_voucher"] ?>"
                               onclick="return confirm('Yakin hapus voucher ini?')"
                               class="btn btn-danger btn-sm">Hapus</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>
</body>
</html>

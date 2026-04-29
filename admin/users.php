<?php
    session_start();
    require_once "../server.php";
    require_once "../function/admin_function.php";

    if (!isset($_SESSION["id_user"]) || $_SESSION["role"] !== "admin") {
        header("Location: ../home.php");
        exit;
    }

    $users = semuaUser($connect);
    $pesan = $_GET["pesan"] ?? "";
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"/>
</head>
<body>
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Kelola User</h2>
        <a href="index.php" class="btn btn-secondary btn-sm">← Dashboard</a>
    </div>

    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Username</th>
                <th>Role</th>
                <th>Poin</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($users) === 0): ?>
                <tr><td colspan="6" class="text-center">Belum ada user.</td></tr>
            <?php else: ?>
                <?php $i = 1; ?>
                <?php foreach ($users as $u): ?>
                    <tr>
                        <td><?= $i ?></td>
                        <td><?= $u["nama"] ?></td>
                        <td><?= $u["username"] ?></td>
                        <td><?= $u["role"] ?></td>
                        <td><?= $u["poin"] ?></td>
                        <?php $i++; ?>
                        <td>
                            <?php if ($u["role"] !== "admin"): ?>
                                <a href="../logic/hapus_user_logic.php?id_user=<?= $u["id_user"] ?>"
                                   onclick="return confirm('Yakin hapus user ini?')"
                                   class="btn btn-danger btn-sm">Hapus</a>
                            <?php else: ?>
                                <span class="text-muted">-</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>
</body>
</html>
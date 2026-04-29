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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola User | Admin</title>
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

        .manage-container {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(15px);
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        }

        h2 {
            font-weight: 700;
            color: #1b4332;
            margin: 0;
        }

        /* Styling Tabel */
        .table-responsive-custom {
            border-radius: 15px;
            overflow: hidden;
            background: white;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
        }

        .table {
            margin-bottom: 0;
        }

        .table thead {
            background-color: #2d6a4f;
            color: white;
            border: none;
        }

        .table thead th {
            font-weight: 600;
            padding: 15px;
            border: none;
            text-transform: uppercase;
            font-size: 0.8rem;
            letter-spacing: 0.5px;
        }

        .table tbody td {
            padding: 15px;
            vertical-align: middle;
            color: #495057;
            border-bottom: 1px solid #f1f3f5;
        }

        /* Badge untuk Role */
        .badge-role {
            padding: 6px 12px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.75rem;
        }
        .bg-admin { background-color: #d8f3dc; color: #1b4332; }
        .bg-user { background-color: #e9ecef; color: #495057; }

        /* Button Custom */
        .btn-back {
            background-color: white;
            color: #2d6a4f;
            border: 2px solid #2d6a4f;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s;
        }

        .btn-back:hover {
            background-color: #2d6a4f;
            color: white;
        }

        .btn-delete {
            border-radius: 8px;
            padding: 5px 12px;
            font-weight: 600;
            transition: transform 0.2s;
        }

        .btn-delete:hover {
            transform: scale(1.05);
        }
    </style>
</head>
<body>

<div class="container">
    <div class="manage-container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2>Kelola User</h2>
                <p class="text-muted mb-0">Manajemen data akun dan hak akses</p>
            </div>
            <a href="index.php" class="btn btn-back btn-sm">
                <i class="bi bi-arrow-left me-1"></i> Dashboard
            </a>
        </div>

        <div class="table-responsive table-responsive-custom">
            <table class="table">
                <thead>
                    <tr>
                        <th class="text-center">No</th>
                        <th>Nama Lengkap</th>
                        <th>Username</th>
                        <th>Role</th>
                        <th class="text-center">Poin</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($users) === 0): ?>
                        <tr><td colspan="6" class="text-center py-5 text-muted">Belum ada user yang terdaftar.</td></tr>
                    <?php else: ?>
                        <?php $i = 1; ?>
                        <?php foreach ($users as $u): ?>
                            <tr>
                                <td class="text-center text-muted fw-bold"><?= $i ?></td>
                                <td class="fw-semibold"><?= htmlspecialchars($u["nama"]) ?></td>
                                <td><span class="text-muted">@</span><?= htmlspecialchars($u["username"]) ?></td>
                                <td>
                                    <span class="badge-role <?= $u["role"] === 'admin' ? 'bg-admin' : 'bg-user' ?>">
                                        <?= strtoupper($u["role"]) ?>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-light text-dark border"><?= number_format($u["poin"]) ?></span>
                                </td>
                                <?php $i++; ?>
                                <td class="text-center">
                                    <?php if ($u["role"] !== "admin"): ?>
                                        <a href="#"
                                            onclick="konfirmasiHapus(); return false;"
                                            class="btn btn-danger btn-sm btn-delete">
                                            <i class="bi bi-trash3 me-1"></i> Hapus
                                        </a>
                                    <?php else: ?>
                                        <span class="text-muted small italic">Sistem Terkunci</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

    <div id="overlay-logout" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.6); z-index:9999;">
        <div style="background:white; width:300px; margin:200px auto; padding:20px; border-radius:10px; text-align:center;">
            <h5>Konfirmasi Hapus</h5>
            <p>Apakah kamu yakin ingin Hapus user ini ?</p>
            <div style="display:flex; gap:10px; justify-content:center;">
                <a href="../logic/hapus_user_logic.php?id_user=<?= $u["id_user"] ?>" style="background:red; color:white; padding:8px 16px; border-radius:5px; text-decoration:none;">Hapus</a>
                <button onclick="tutupHapus();" class="btn btn-primary">Batal</button>
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
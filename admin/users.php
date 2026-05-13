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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">
    <title>Manage User - Admin | Comparan</title>
    <style>
        body {
            background-color: var(--hover-soft);
            font-family: 'Inter', sans-serif;
            padding: 40px 0;
        }

        .manage-container {
            background: var(--bg-soft);
            backdrop-filter: blur(15px);
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 10px 30px var(--overlay-dark);
        }

        h2 {
            font-weight: 700;
            color: var(--text-main);
            margin: 0;
        }

        .table-responsive-custom {
            border-radius: 15px;
            overflow: hidden;
            background: white;
            box-shadow: 0 4px 12px var(--overlay-dark);
        }

        .table {
            margin-bottom: 0;
        }

        .table thead {
            color: var(--white);
            border: none;
        }

        .table thead th {
            font-weight: 600;
            padding: 15px;
            border: none;
            text-transform: uppercase;
            font-size: 0.9rem;
            letter-spacing: 0.5px;
            border-bottom: 1px solid var(--gray);
        }

        .table tbody td {
            padding: 16px;
            vertical-align: middle;
            color: var(--soft-black);
            border-bottom: 1px solid var(--gray);
        }

        /* Badge untuk Role */
        .badge-role {
            padding: 6px 12px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.75rem;
        }
        .bg-admin { background-color: var(--glow-green); color: var(--primary-dark); }
        .bg-user { background-color: var(--gray); color: var(--soft-black); }

        .btn-outline-success{
            background-color: var(--transparent);
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

            body{
                padding: 20px 10px;
            }

            .manage-container{
                padding: 20px 15px;
                border-radius: 16px;
            }

            .manage-container .d-flex{
                flex-direction: column;
                align-items: flex-start !important;
                gap: 15px;
            }

            h2{
                font-size: 1.5rem;
            }

            .manage-container p{
                font-size: 0.9rem;
            }

            .table-responsive-custom{
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }

            .table{
                min-width: 700px;
            }

            .table thead th{
                font-size: 0.75rem;
                padding: 12px 10px;
                white-space: nowrap;
            }

            .table tbody td{
                font-size: 0.8rem;
                padding: 12px 10px;
                white-space: nowrap;
            }

            .badge-role{
                font-size: 0.7rem;
                padding: 5px 10px;
            }

            .btn-delete{
                padding: 4px 10px;
                font-size: 0.75rem;
            }

            .btn-outline-success{
                font-size: 0.8rem;
                padding: 6px 14px !important;
            }

            .containerConfirm{
                width: 90%;
                margin: 150px auto;
                padding: 20px 15px;
            }

            .containerConfirm h5{
                font-size: 1rem;
            }

            .containerConfirm p{
                font-size: 0.85rem;
            }

            .containerConfirm .d-flex{
                flex-direction: column;
                gap: 10px;
            }

            .containerConfirm a,
            .containerConfirm button{
                width: 100%;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <div class="manage-container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2>Manage User</h2>
                <p class="text-muted mb-0">User account and access control management</p>
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
                        <th>Name</th>
                        <th>Username</th>
                        <th>Role</th>
                        <th class="text-center">Point</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($users) === 0): ?>
                        <tr><td colspan="6" class="text-center py-5 text-muted">There are no registered users.</td></tr>
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
                                            <i class="bi bi-trash3 me-1"></i> Delete
                                        </a>
                                    <?php else: ?>
                                        <span class="text-muted small italic">System Locked</span>
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

    <div id="overlay-logout">
        <div class="containerConfirm d-flex flex-column align-middle">
            <h5>Delete Confirmation</h5>
            <p>Do you want to delete this user?</p>
            <div class="d-flex flex-row justify-content-center align-items-center w-100">
                <a href="../logic/hapus_user_logic.php?id_user=<?= $u["id_user"] ?>" class="btn btn-outline-danger mx-1">Delete</a>
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
<?php
session_start();
require "server.php";

if (!isset($_SESSION["id_user"])) {
    header("Location: login.php");
    exit;
}

$id_user = $_SESSION['id_user'] ?? 0;

// 🔹 Ambil Top 10
$sql = "SELECT u.id_user, u.username as nama, u.foto_profile, SUM(o.total_bayar) as total 
        FROM orders o 
        JOIN users u ON o.id_user = u.id_user 
        GROUP BY o.id_user 
        ORDER BY total DESC 
        LIMIT 10";

$result = $connect->query($sql);
$leaders = $result->fetch_all(MYSQLI_ASSOC);

// 🔹 Cari ranking user login
$sql_rank = "SELECT id_user, SUM(total_bayar) as total 
            FROM orders 
            GROUP BY id_user 
            ORDER BY total DESC";

$result_rank = $connect->query($sql_rank);

$rank_counter = 1;
$my_rank = "-";

while ($row = $result_rank->fetch_assoc()) {
    if ($row['id_user'] == $id_user) {
        $my_rank = $rank_counter;
        break;
    }
    $rank_counter++;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icon -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

    <!-- Main CSS -->
    <link rel="stylesheet" href="css/style.css">

    <title>Leaderboard | Comparan</title>

    <style>

        :root {
            --gold: linear-gradient(45deg, #ff9d00, #ffeb3b);
            --silver: linear-gradient(45deg, #757f9a, #d7dde8);
            --bronze: linear-gradient(45deg, #804a00, #ff9d42);
        }

        body {
            background: var(--hover-soft);
            font-family: 'Inter', sans-serif;
            padding: 20px;
        }

        .container-leaderboard {
            max-width: 480px;
            margin: auto;
        }

        .judul-form {
            font-family: 'Safira', sans-serif;
            letter-spacing: 2px;

            text-align: center;

            margin-top: 5rem !important;
            margin-bottom: 2rem;

            background: var(--primary-main);

            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .btn-home{
            position: fixed;
            margin-top: 8px;
        }

        .my-rank-card {
            background: var(--primary-main);
            color: var(--white);
            border-radius: 20px;
            padding: 15px;
            display: flex;
            justify-content: space-around;
            align-items: center;
            margin-bottom: 25px;
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }

        .rank-item{ text-align: center; }

        .rank-item small{ display: block; opacity: 0.75; }

        .divider-rank{ width: 1px; height: 30px; background: rgba(255,255,255,0.3); }

        .card-rank {
            background: var(--white);
            border-radius: 18px;
            padding: 12px 18px;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            transition: all 0.3s ease;
            border: 1px solid rgba(0,0,0,0.03);
        }

        .card-rank:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 15px rgba(0,0,0,0.05);
        }

        .rank-number {
            font-weight: 800;
            font-size: 1.1rem;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            margin-right: 15px;
        }

        .top-1 {
            background: var(--gold);
            color: var(--white);
            box-shadow: 0 5px 15px rgba(255, 157, 0, 0.3);
        }

        .top-2 { background: var(--silver); color: var(--white); }

        .top-3 { background: var(--bronze); color: var(--white); }

        .userFotoProfil {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 15px;
            border: 2px solid white;
        }

        .user-info { flex-grow: 1; }

        .user-info b {
            font-size: 0.95rem;
            display: block;
            text-transform: capitalize;
        }

        .user-info span { font-size: 0.75rem; opacity: 0.8; }

        .points {
            text-align: right;
            font-weight: 700;
            color: var(--primary-dark);
        }

        .me {
            border: 2px solid var(--primary-main) !important;
            position: relative;
        }

        .me::after {
            content: "YOU";
            position: absolute;
            top: -10px;
            right: 20px;
            background: var(--primary-main);
            color: var(--white);
            font-size: 0.6rem;
            font-weight: bold;
            padding: 2px 8px;
            border-radius: 10px;
        }

        @media (max-width: 576px){

            body{ padding: 14px; }

            .container-leaderboard{ max-width: 100%; }

            .judul-form{ font-size: 2rem; }

            .my-rank-card{ padding: 14px 10px; }

            .rank-number{
                width: 35px;
                height: 35px;
                font-size: 0.95rem;
                margin-right: 10px;
            }

            .userFotoProfil{
                width: 40px;
                height: 40px;
                margin-right: 10px;
            }

            .user-info b{ font-size: 0.85rem; }

            .points{ font-size: 0.9rem; }
        }

    </style>
</head>
<body>

    <div class="container-leaderboard">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <a href="home.php" class="btn-kembali btn-sm position-fixed mt-2"><i class="bi bi-arrow-left"></i> Back to Home</a>
        </div>

        <h2 class="judul-form">Leaderboard</h2>

        <div class="my-rank-card">
            <div class="rank-item">
                <small>Your Rank</small>
                <h4 class="m-0 fw-bold">#<?= $my_rank ?></h4>
            </div>
            <div class="divider-rank"></div>
            <div class="rank-item">
                <small>Status</small>
                <h6 class="m-0 fw-bold">
                    <?= $my_rank <= 10 ? 'Pro Buyer' : 'Elite' ?>
                </h6>
            </div>
        </div>

        <?php 
        $current_rank = 1;

        foreach($leaders as $l): 
            $total_poin = $l['total'] / 1000;
            $top_class = '';

            if ($current_rank == 1){
                $top_class = 'top-1';
            } elseif ($current_rank == 2){
                $top_class = 'top-2';
            } elseif ($current_rank == 3){
                $top_class = 'top-3';
            }

            $me_class = ($l['id_user'] == $id_user) ? 'me' : '';

            $foto = !empty($l['foto_profile']) 
                ? 'uploads/profil/'.$l['foto_profile'] 
                : 'uploads/profil/default.png';
        ?>
            <div class="card-rank <?= $me_class ?>">
                <div class="rank-number <?= $top_class ?>">
                    <?= $current_rank ?>
                </div>

                <img src="<?= $foto ?>" class="userFotoProfil shadow-sm">

                <div class="user-info">
                    <b class="<?= ($current_rank <= 3) ? 'fw-bold' : '' ?>">
                        <?= htmlspecialchars($l['nama']) ?>
                    </b>
                    <span class="text-muted">
                        Collector Status
                    </span>
                </div>
                <div class="points">
                    <?= number_format($total_poin, 1) ?>
                    <small class="fw-light">pts</small>
                </div>
            </div>
            <?php $current_rank++; ?>
        <?php endforeach; ?>
    </div>
</body>
</html>
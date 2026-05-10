<?php 
    session_start();
    require_once "server.php";
    require_once "function/voucher_function.php";

    if(!isset($_SESSION["id_user"])){
        header("Location: login.php");
        exit;
    }

    $id_user = $_SESSION["id_user"];
    $pesan = $_GET["pesan"] ?? "";
    $vouchers  = daftarVoucher($connect);
    $milik = voucherSaya($connect, $id_user);

    $sql = "SELECT poin FROM users WHERE id_user = '$id_user'";
    $result = $connect->query($sql);
    $user = $result->fetch_assoc();
    $poin = $user["poin"];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap 5 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">
    <!-- Bootstrap Icon -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <!-- css -->
    <link rel="stylesheet" href="css/style.css">
    <title>Voucher Saya</title>
    <style>

        body {
            font-family: 'Alphazet', sans-serif;
            background-color: var(--hover-soft);
            color: var(--text);
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 1100px;
            margin: 60px auto 0 auto;
        }

        .poin-card {
            background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary-main) 100%);
            color: var(--white);
            padding: 20px;
            border-radius: 20px;
            box-shadow: 0 10px 20px -5px var(--low-green);
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
        }

        .poin-card span{ font-weight: bold; font-size: 20px; }

        .poin-card b { font-size: 30px; }

        .main-layout {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            align-items: start;
        }

        .ticket {
            display: flex;
            background: var(--bg-soft);
            margin-bottom: 15px;
            border-radius: 12px;
            position: relative;
            overflow: hidden;
            opacity: 0.95;  
        }
        
        .ticket::before, .ticket::after {
            content: '';
            position: absolute;
            left: 75%;
            width: 20px;
            height: 20px;
            background: var(--hover-soft);
            border-radius: 50%;
            z-index: 2;
        }
        
        .ticket::before { top: -10px; box-shadow: 0 0px 10px rgba(0,0,0,0.05);}

        .ticket::after { bottom: -10px; box-shadow: 0 0px 10px rgba(0,0,0,0.05);}
    
        .ticket-left {
            padding: 15px;
            flex: 1;
            border-right: 2px dashed var(--bg);
            box-shadow: 0 0px 10px rgba(0,0,0,0.05);
        }

        .ticket-right {
            width: 25%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--bg-soft);
            box-shadow: 0 0px 10px rgba(0,0,0,0.05);
        }

        .ticket-name {
            font-family: 'Belgiano';
            font-weight: bold;
            font-size: 16px;
            letter-spacing: 1px;
            display: block; 
        }

        .ticket-val {
            color: var(--primary);
            font-weight: bold;
            font-size: 14px;
        }

        .ticket-cost {
            font-size: 11px;
            color: var(--soft-black);
            margin-top: 5px;
            display: block;
        }

        .btn-tukar {
            background-color: var(--primary-main);
            color: white;
            text-decoration: none;
            padding: 6px 10px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: bold;
        }

        .status-badge {
            text-transform: uppercase;
            font-size: 12px;
            font-weight: bold;
            color: var(--primary-main);
            border: 2px solid var(--primary-main);
            padding: 2px 8px;
            border-radius: 6px;
        }

        .alert{
            background-color: var(--primary-accent);
            grid-column: span 2;
            padding: 12px;
            border-radius: 10px;
            margin-bottom: 10px;
            text-align: center;
        }

        .alert-success { background: var(--hover-success); color: var(--primary-accent); }

        .alert-danger { background: var(--white); color: var(--red); }

        h3 { 
            font-family: 'Belgiano';
            font-size: 18px; 
            margin-top: 0; 
            margin-bottom: 20px; 
            padding-bottom: 10px;
            border-bottom: 2px solid rgba(0,0,0,0.05);
        }
        
        .kosong{
            text-align: start;
            font-family: 'Belgiano';
            font-size: 18px;
            color: var(--red);
        }

        #overlay-logout{
            display:none;
            position:fixed;
            top:0;
            left:0;
            width:100%;
            height:100%;
            background: var(--overlay-dark);
            z-index:9999;
        }

        .bg-redeem{
            background:white;
            width:300px;
            margin:200px auto;
            padding:20px;
            border-radius:10px;
            text-align:center;
        }

        .bg-redeem div{
            display:flex;
            gap:10px;
            justify-content:center;
        }

        @media (max-width: 768px){
            .main-layout { grid-template-columns: 1fr; }
            
            .container { margin-top: 80px; }

            .alert { grid-column: span 1; }
            .poin-card{
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
                padding: 18px;
                border-radius: 16px;
            }

            .poin-card span{ font-size: 16px; }

            .poin-card b{ font-size: 24px; }

            .main-layout{ grid-template-columns: 1fr; gap: 20px; }

            .ticket{ flex-direction: row; align-items: stretch; }

            .ticket-left{ padding: 14px; }

            .ticket-right{ width: 90px; padding: 10px; }

            .ticket::before, .ticket::after{ left: calc(100% - 45px); }

            .ticket-name{ font-size: 14px; }

            .ticket-val{ font-size: 13px; }

            .ticket-cost{ font-size: 10px; }

            .btn-tukar{ font-size: 10px; padding: 6px 8px; text-align: center; }

            .status-badge{ font-size: 10px; padding: 4px 6px; text-align: center; }

            h3{ font-size: 16px; margin-bottom: 15px; }

            .kosong{ font-size: 15px; }

            .bg-redeem{ width: 90%; margin: 180px auto; padding: 18px; }

            .bg-redeem div{ flex-direction: column; }

            .bg-redeem a, .bg-redeem button{ width: 100%; }

            /* tombol back */
            .btn-kembali{
                top: 15px;
                left: 15px;
                font-size: 12px;
                padding: 8px 12px;
            }
        }
    </style>
</head>
<body>

<a href="home.php" class="btn-kembali btn-danger"><i class="bi bi-arrow-left"></i> Back to Home</a>

<div class="container">
    <div class="poin-card">
        <span>Your Points : </span>
        <b><i class="bi bi-c-circle text-warning"></i> <?= number_format($poin, 0, ',', '.') ?></b>
    </div>

    <div class="main-layout">
        <?php if ($pesan === "berhasil"): ?>
            <div class="alert alert-success">✨ Redeem Success!</div>
        <?php elseif ($pesan === "poin_kurang"): ?>
            <div class="alert alert-danger">❌ Not enough points.</div>
        <?php endif; ?>

        <div class="section">
            <h3><i class="bi bi-tags"></i> Redeem Voucher</h3>
            <?php if (count($vouchers) === 0): ?>
                <p class="kosong">Nothing available vouchers yet.</p>
            <?php else: ?>
                <?php foreach ($vouchers as $v): ?>
                    <div class="ticket">
                        <div class="ticket-left">
                            <span class="ticket-name"><?= $v["nama"] ?></span>
                            <span class="ticket-val">Rp <?= number_format($v["nilai"], 0, ',', '.') ?> Discount</span>
                            <span class="ticket-cost">Cost: <?= $v["poin_diperlukan"] ?> Points</span>
                        </div>
                        <div class="ticket-right">
                            <a href="#" 
                                class="btn-tukar" onclick="konfirmasiTukar(<?= $v['id_voucher'] ?>); return false;">REDEEM</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- Modal Konfirmasi -->
    <div id="overlay-logout">
        <div class="bg-redeem">
            <h5>Confirm Redeem</h5>
            <p class="text-center">Are you sure you want to redeem this voucher?</p>
            <div>
                <a href="logic/tukar_voucher_logic.php?id_voucher=<?= $v["id_voucher"] ?>" class="btn-outline-success" >Yes</a>
                <button onclick="tutupLogout()" class="btn btn-outline-danger mx-1">Cancel</button>
            </div>
        </div>
    </div>

        <div class="section">
            <h3><i class="bi bi-tags-fill"></i> My Vouchers</h3>
            <?php if (count($milik) === 0): ?>
                <p class="kosong">You don't have any vouchers yet.</p>
            <?php else: ?>
                <?php foreach ($milik as $v): ?>
                    <div class="ticket">
                        <div class="ticket-left">
                            <span class="ticket-name"><?= $v["nama"] ?></span>
                            <span class="ticket-val">Rp<?= number_format($v["nilai"], 0, ',', '.') ?> Discount</span>
                        </div>
                        <div class="ticket-right">
                            <span class="status-badge"><?= $v["status"] ?></span>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
    function konfirmasiTukar(id_voucher) {
        // Set the voucher ID in the modal
        document.getElementById("overlay-logout").setAttribute("data-voucher-id", id_voucher);
        document.getElementById("overlay-logout").style.display = "block";
    }

    function tutupLogout() {
        document.getElementById("overlay-logout").style.display = "none";
    }
</script>

</body>
</html>
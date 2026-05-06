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

        /* Header Poin Full Width */
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

        /* Grid Layout Utama */
        .main-layout {
            display: grid;
            grid-template-columns: 1fr 1fr; /* Membagi 2 kolom sama besar */
            gap: 30px;
            align-items: start;
        }

        /* Responsive: Jadi 1 kolom kalau di HP */
        @media (max-width: 850px) {
            .main-layout { grid-template-columns: 1fr; }
            .container { margin-top: 80px; }
        }

        /* Gaya Tiket */
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
            font-size: 10px;
            font-weight: bold;
            color: var(--success);
            border: 1px solid var(--success);
            padding: 2px 6px;
            border-radius: 4px;
        }

        .alert {
            grid-column: span 2;
            padding: 12px;
            border-radius: 10px;
            margin-bottom: 10px;
            text-align: center;
        }
        @media (max-width: 850px) { .alert { grid-column: span 1; } }

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
        
        p{
            text-align: start;
            font-family: 'Belgiano';
            font-size: 18px;
            color: var(--red);
        }
    </style>
</head>
<body>

<a href="home.php" class="btn-kembali btn-danger"><i class="bi bi-arrow-left"></i> Back to Home</a>

<div class="container">
    <div class="poin-card">
        <span style="font-weight: bold; font-size: 20px;">Saldo Poin Anda:</span>
        <b>🪙 <?= number_format($poin, 0, ',', '.') ?></b>
    </div>

    <div class="main-layout">
        <?php if ($pesan === "berhasil"): ?>
            <div class="alert alert-success">✨ Berhasil menukar voucher!</div>
        <?php elseif ($pesan === "poin_kurang"): ?>
            <div class="alert alert-danger">❌ Poin tidak cukup.</div>
        <?php endif; ?>

        <div class="section">
            <h3>🎟️ Tukar Voucher</h3>
            <?php if (count($vouchers) === 0): ?>
                <p style="color: #64748b;">Belum ada voucher tersedia.</p>
            <?php else: ?>
                <?php foreach ($vouchers as $v): ?>
                    <div class="ticket">
                        <div class="ticket-left">
                            <span class="ticket-name"><?= $v["nama"] ?></span>
                            <span class="ticket-val">Potongan Rp <?= number_format($v["nilai"], 0, ',', '.') ?></span>
                            <span class="ticket-cost">Biaya: <?= $v["poin_diperlukan"] ?> Poin</span>
                        </div>
                        <div class="ticket-right">
                            <a href="#" 
                                class="btn-tukar" onclick="konfirmasiTukar(<?= $v['id_voucher'] ?>); return false;">TUKAR</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- Modal Konfirmasi -->
    <div id="overlay-logout" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.6); z-index:9999;">
        <div style="background:white; width:300px; margin:200px auto; padding:20px; border-radius:10px; text-align:center;">
            <h5>Konfirmasi Tukar Voucher</h5>
            <p>Apakah kamu yakin ingin menukar Voucher ini?</p>
            <div style="display:flex; gap:10px; justify-content:center;">
                <a href="logic/tukar_voucher_logic.php?id_voucher=<?= $v["id_voucher"] ?>" class="btn btn-primary" >Ya, Tukar</a>
                <button onclick="tutupLogout()" class="btn btn-danger">Batal</button>
            </div>
        </div>
    </div>

        <div class="section">
            <h3>🎁 Voucher Saya</h3>
            <?php if (count($milik) === 0): ?>
                <p style="color: #64748b;">Kamu belum memiliki voucher.</p>
            <?php else: ?>
                <?php foreach ($milik as $v): ?>
                    <div class="ticket" style="opacity: 0.95;">
                        <div class="ticket-left">
                            <span class="ticket-name"><?= $v["nama"] ?></span>
                            <span class="ticket-val">Potongan Rp <?= number_format($v["nilai"], 0, ',', '.') ?></span>
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
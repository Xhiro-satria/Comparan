<?php 
    session_start();
    require_once 'server.php';
    require_once 'function/cart_function.php';
    require_once 'function/voucher_function.php';
    require_once 'function/product_function.php';

    if(!isset($_SESSION["id_user"])){
        header("Location: login.php");
        exit();
    }

    $id_user  = $_SESSION["id_user"];
    $vouchers = voucherSaya($connect, $id_user);

    // Cek apakah dari beli sekarang atau dari cart
    if (isset($_SESSION["beli_sekarang"])) {
        $id_produk = $_SESSION["beli_sekarang"]["id_produk"];
        $jumlah    = $_SESSION["beli_sekarang"]["jumlah"];

        $result  = $connect->query("SELECT * FROM products WHERE id_produk = '$id_produk'");
        $produk  = $result->fetch_assoc();

        $items = [[
            "id_produk"    => $id_produk,
            "nama_produk"  => $produk["nama_produk"],
            "harga"        => $produk["harga"],
            "jumlah"       => $jumlah,
            "gambar"       => $produk["gambar"],
        ]];

        $dari_cart = false;
    } else {
        $result  = $connect->query("SELECT id_cart FROM cart WHERE id_user = '$id_user'");
        $cart    = $result->fetch_assoc();
        $id_cart = $cart["id_cart"];
        $items   = tampilKeranjang($connect, $id_cart);
        $dari_cart = true;
    }

    $total = 0;
    foreach ($items as $item) {
        $total += $item["harga"] * $item["jumlah"];
    }
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="css/style.css">
    <title>Checkout | Comparan</title>
    <style>
        body{ background-color: var(--hover-soft); font-family: 'Inter';}

        .judul-form{ font-size: 36px;}

        .container-content {
            background: white;
            width: 480px;
            max-height: 540px;
            margin: 50px auto;
            padding: 24px;
            border-radius: 24px;
            overflow-y: auto;
        }
        
        .tutup { float: right; cursor: pointer; font-size: 20px; }
        
        button{
            background-color: var(--primary-main);
            padding: 6px 12px;
            border: none;
            border-radius: 8px;
            color: var(--text-light);
        }

        .button2{
            background-color: var(--red);
            padding: 6px 12px;
            border: none;
            border-radius: 8px;
            color: var(--text-light);
            text-decoration: none;
        }

        .containerData{ padding: 12px; margin-bottom: 10px; background-color: var(--bg-soft); border: 2px solid var(--primary-main); border-radius: 8px;}

        .containerData b{ text-transform: capitalize; }

        label{ color: var(--primary-dark); font-weight: 600;}
    </style>
</head>
<body>
    <a href="cart.php" class="btn-kembali">← Back to Cart</a>
    <h2 class="judul-form text-center mt-5">Checkout</h2>

    <div class="container-content">
        <?php foreach ($items as $item): ?>
            <div class="containerData">
                <b><?= $item["nama_produk"] ?></b><br>
                Price     : Rp<?= number_format($item["harga"], 0, ',', '.') ?><br>
                Quantity  : <?= $item["jumlah"] ?><br>
                Subtotal  : Rp<?= number_format($item["harga"] * $item["jumlah"], 0, ',', '.') ?>
            </div>
        <?php endforeach; ?>
    
        <form method="POST" action="logic/checkout_logic.php">
            <input type="text" name="alamat" class="form-control mb-3" placeholder="Shipping address" required>
    
            <?php if (count($vouchers) > 0): ?>
                <label>Select Voucher (can choose more than one) : </label><br>
                <?php foreach ($vouchers as $v): ?>
                    <input type="checkbox"
                        name="id_vouchers[]"
                        value="<?= $v["id"] ?>"
                        data-nilai="<?= $v["nilai"] ?>"
                        onchange="hitungDiskon()">
                    <?= $v["nama"] ?> — Rp<?= number_format($v["nilai"], 0, ',', '.') ?> discount voucher<br>
                <?php endforeach; ?>
                <br>
            <?php else: ?>
                <p class="alert alert-danger py-2">Nothing available vouchers yet.</p>
            <?php endif; ?>
    
            <p class="mb-1">Total         : Rp<?= number_format($total, 0, ',', '.') ?></p>
            <p class="mb-1">Discount      : Rp <span id="diskon">0</span></p>
            <p class="mb-3">Total Payment : Rp <span id="total-bayar"><?= number_format($total, 0, ',', '.') ?></span></p>
    
            <input type="hidden" name="total" value="<?= $total ?>">
            <button type="submit">Buy</button>
        </form>
    </div>

    <script>
        let totalAsli = <?= $total ?>;

        function hitungDiskon() {
            let checkboxes  = document.querySelectorAll("input[name='id_vouchers[]']:checked");
            let totalDiskon = 0;

            checkboxes.forEach(cb => {
                totalDiskon += parseInt(cb.dataset.nilai);
            });

            let totalBayar = totalAsli - totalDiskon;
            if (totalBayar < 0) totalBayar = 0;

            document.getElementById("diskon").innerText      = totalDiskon.toLocaleString("id-ID");
            document.getElementById("total-bayar").innerText = totalBayar.toLocaleString("id-ID");
        }
    </script>

</body>
</html>
<?php 
    session_start();
    require_once 'server.php';
    require_once 'function/cart_function.php';
    require_once 'function/voucher_function.php';

    if(!isset($_SESSION["id_user"])){
        header("Location: login.php");
        exit();
    }

    $id_user = $_SESSION["id_user"];
    $sql = "SELECT id_cart FROM cart WHERE id_user = '$id_user'";
    $result = $connect->query($sql);
    // Ambil id_cart berdasarkan id_user
    $cart = $result->fetch_assoc();
    $id_cart = $cart["id_cart"];

    $items = tampilKeranjang($connect, $id_cart);
    $vouchers = voucherSaya($connect, $id_user);

    $total = 0;
    foreach($items as $item){
        $total += $item["harga"] * $item["jumlah"];
    }
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Checkout</title>
</head>
<body>
    <h2>Checkout</h2>
    <a href="cart.php">← Kembali ke Keranjang</a><br><br>

    <?php foreach ($items as $item): ?>
        <div style="border: 1px solid #ccc; padding: 10px; margin-bottom: 10px;">
            <b><?= $item["nama_produk"] ?></b><br>
            Harga   : Rp <?= number_format($item["harga"], 0, ',', '.') ?><br>
            Jumlah  : <?= $item["jumlah"] ?><br>
            Subtotal: Rp <?= number_format($item["harga"] * $item["jumlah"], 0, ',', '.') ?>
        </div>
    <?php endforeach; ?>

    <form method="POST" action="logic/checkout_logic.php">
        <input type="text" name="alamat" placeholder="Alamat Pengiriman" required><br><br>

        <?php if (count($vouchers) > 0): ?>
            <label>Pilih Voucher (bisa lebih dari satu):</label><br>
            <?php foreach ($vouchers as $v): ?>
                <input type="checkbox"
                       name="id_vouchers[]"
                       value="<?= $v["id"] ?>"
                       data-nilai="<?= $v["nilai"] ?>"
                       onchange="hitungDiskon()">
                <?= $v["nama"] ?> — Potongan Rp <?= number_format($v["nilai"], 0, ',', '.') ?><br>
            <?php endforeach; ?>
            <br>
        <?php else: ?>
            <p>Tidak ada voucher tersedia.</p>
        <?php endif; ?>

        <p>Total Harga : Rp <?= number_format($total, 0, ',', '.') ?></p>
        <p>Diskon      : Rp <span id="diskon">0</span></p>
        <p>Total Bayar : Rp <span id="total-bayar"><?= number_format($total, 0, ',', '.') ?></span></p>

        <input type="hidden" name="total" value="<?= $total ?>">
        <button type="submit">Bayar</button>
    </form>

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
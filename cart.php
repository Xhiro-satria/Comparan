<?php 
    session_start();
    require_once 'server.php';
    require_once 'function/cart_function.php';
    require_once 'function/voucher_function.php';

    $id_user = $_SESSION["id_user"];        // Mengambil ID user dari session yang sedang aktif
    $sql = "SELECT id_cart FROM cart WHERE id_user = '$id_user'";
    $result = $connect->query($sql);

    $cart = $result->fetch_assoc();         // Mengambil hasil query dalam bentuk array asosiatif
    $id_cart = $cart["id_cart"];            // Menyimpan ID keranjang ke dalam variabel

    $items    = tampilKeranjang($connect, $id_cart);    // Mengambil daftar barang yang ada di dalam keranjang
    $vouchers = voucherSaya($connect, $id_user);        // Mengambil daftar voucher yang dimiliki oleh user tersebut


    $total = 0;
    foreach ($items as $item) {
        $total += $item["harga"] * $item["jumlah"];
    }
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Keranjang</title>
    <style>
        .item {
            border: 1px solid #ccc;
            padding: 10px;
            margin-bottom: 10px;
            display: flex;
            gap: 10px;
            align-items: center;
        }
        .item img {
            width: 80px;
            height: 80px;
            object-fit: cover;
        }
        .overlay {
            display: none;
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background: rgba(0,0,0,0.5);
        }
        .modal {
            background: white;
            width: 400px;
            margin: 80px auto;
            padding: 20px;
            border-radius: 8px;
        }
        .tutup {
            float: right;
            cursor: pointer;
            font-size: 20px;
        }
    </style>
</head>
<body>
    <h2>Keranjang Belanja</h2>
    <a href="home.php">← Kembali</a><br><br>

    <?php if (count($items) === 0): ?>
        <p>Keranjang kosong.</p>
    <?php else: ?>

        <?php foreach ($items as $item): ?>
            <div class="item">
                <img src="uploads/produk/<?= $item["gambar"] ?>">
                <div style="flex:1;">
                    <b><?= $item["nama_produk"] ?></b><br>
                    Harga   : Rp <?= number_format($item["harga"], 0, ',', '.') ?><br>
                    Jumlah  : <?= $item["jumlah"] ?><br>
                    Subtotal: Rp <?= number_format($item["harga"] * $item["jumlah"], 0, ',', '.') ?>
                </div>
                <div>
                    <button onclick="bukaModal(
                        '<?= $item['id_item'] ?>',
                        '<?= $item['id_produk'] ?>',
                        '<?= $item['jumlah'] ?>',
                        '<?= $item['harga'] * $item['jumlah'] ?>'
                    )">Beli</button>
                    <a href="logic/hapus_item.php?id_item=<?= $item["id_item"] ?>">Hapus</a>
                </div>
            </div>
        <?php endforeach; ?>

        <b>Total Semua: Rp <?= number_format($total, 0, ',', '.') ?></b><br><br>
        <button onclick="bukaModalSemua()">Checkout Semua</button>

    <?php endif; ?>

    <!-- Modal Checkout -->
    <div class="overlay" id="overlay" onclick="tutupModal()">
        <div class="modal" onclick="event.stopPropagation()">
            <span class="tutup" onclick="tutupModal()">✕</span>
            <h3>Checkout</h3>

            <form method="POST" action="logic/checkout_logic.php">
                <input type="hidden" name="id_item" id="m-id-item">
                <input type="hidden" name="id_produk" id="m-id-produk">
                <input type="hidden" name="jumlah" id="m-jumlah">
                <input type="hidden" name="total" id="m-total">
                <input type="hidden" name="checkout_semua" id="m-semua" value="0">

                <p>Total: Rp <span id="m-tampil-total"></span></p>

                <input type="text" name="alamat" placeholder="Alamat Pengiriman" required><br><br>

                <?php if (count($vouchers) > 0): ?>
                    <label>Pilih Voucher (opsional):</label><br>
                    <?php foreach ($vouchers as $v): ?>
                        <input type="checkbox"
                            name="id_vouchers[]"
                            value="<?= $v["id"] ?>"
                            data-nilai="<?= $v["nilai"] ?>"
                            onchange="hitungDiskon()">
                        <?= $v["nama"] ?> — Potongan Rp <?= number_format($v["nilai"], 0, ',', '.') ?><br>
                    <?php endforeach; ?>
                    <br>
                <?php endif; ?>

                <p>Diskon      : Rp <span id="m-diskon">0</span></p>
                <p>Total Bayar : Rp <span id="m-total-bayar"></span></p>

                <button type="submit">Bayar</button>
            </form>
        </div>
    </div>

    <script>
    var totalModal = 0;
    var totalSemua = <?php echo $total; ?>;

    function bukaModal(id_item, id_produk, jumlah, total) {
        totalModal = parseInt(total);
        document.getElementById("m-id-item").value         = id_item;
        document.getElementById("m-id-produk").value       = id_produk;
        document.getElementById("m-jumlah").value          = jumlah;
        document.getElementById("m-total").value           = total;
        document.getElementById("m-semua").value           = 0;
        document.getElementById("m-tampil-total").innerText = totalModal.toLocaleString("id-ID");
        document.getElementById("m-total-bayar").innerText  = totalModal.toLocaleString("id-ID");
        document.getElementById("m-diskon").innerText       = "0";

        document.querySelectorAll("input[name='id_vouchers[]']").forEach(function(cb) {
            cb.checked = false;
        });

        document.getElementById("overlay").style.display = "block";
    }

    function bukaModalSemua() {
        totalModal = totalSemua;
        document.getElementById("m-id-item").value         = "";
        document.getElementById("m-id-produk").value       = "";
        document.getElementById("m-jumlah").value          = "";
        document.getElementById("m-total").value           = totalSemua;
        document.getElementById("m-semua").value           = 1;
        document.getElementById("m-tampil-total").innerText = totalSemua.toLocaleString("id-ID");
        document.getElementById("m-total-bayar").innerText  = totalSemua.toLocaleString("id-ID");
        document.getElementById("m-diskon").innerText       = "0";

        document.querySelectorAll("input[name='id_vouchers[]']").forEach(function(cb) {
            cb.checked = false;
        });

        document.getElementById("overlay").style.display = "block";
    }

    function tutupModal() {
        document.getElementById("overlay").style.display = "none";
    }

    function hitungDiskon() {
        var checkboxes  = document.querySelectorAll("input[name='id_vouchers[]']:checked");
        var totalDiskon = 0;

        checkboxes.forEach(function(cb) {
            totalDiskon += parseInt(cb.dataset.nilai);
        });

        var totalBayar = totalModal - totalDiskon;
        if (totalBayar < 0) totalBayar = 0;

        document.getElementById("m-diskon").innerText      = totalDiskon.toLocaleString("id-ID");
        document.getElementById("m-total-bayar").innerText = totalBayar.toLocaleString("id-ID");
    }
</script>

</body>
</html>
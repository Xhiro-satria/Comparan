<?php 
    session_start();
    require_once 'server.php';
    require_once 'function/cart_function.php';
    require_once 'function/voucher_function.php';

    if(!isset($_SESSION["id_user"])){
        header("Location: login.php");
        exit;
    }

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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- Bootstrap 5 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">
    <!-- Bootstrap Icon -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <!-- css -->
    <link rel="stylesheet" href="css/style.css">
    <title>Cart | Comparan</title>
    <style>
        body{ background-color: var(--hover-soft); margin: 20px; }
        .item {
            background-color: var(--card-bg);
            border-radius: 16px;
            padding: 10px 20px;
            margin-bottom: 10px;
            display: flex;
            gap: 10px;
            align-items: center;
            box-shadow: 0 0 10px var(--glow-green);
        }
        .item img {
            width: 80px;
            aspect-ratio: 1/1;
            object-fit: cover;
            border-radius: 8px;
            box-shadow: 0 0 8px var(--overlay-dark);
        }
        .overlayy {
            display: none;
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background: var(--overlay-dark);
        }
        .modal-content {
            background: white;
            width: 400px;
            max-height: 540px;
            margin: 80px auto;
            padding: 20px;
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

        .emptyCart{
            font-family: 'Belgiano';
            font-size: 18px;
            color: var(--red);
        }

        .bungkusData{ flex:1; }

        .bungkusData b{ text-transform: capitalize; }

        form{ font-family: 'Inter', sans-serif; }

        @media (max-width: 768px){
            .container-button-data{
                display: flex;
                flex-direction: column;
                justify-content: start;
            }

            .bungkus-img{ height: 100% !important; }

            .bungkus-img img{ width: 100px; border-radius: 8px; }

            b{ font-size: 13px; }

            td{ font-size: 12px; }

            button, .button2 {
                margin-top: 4px;
                font-size: 12px;
                padding: 4px 8px;
                border-radius: 5px;
            }

            .overlayy{ padding: 20px; }

            .modal-content{ width: 360px; }
            
            .judul-form{ font-size: 20px !important; }

            p, label{ font-size: 14px !important; margin-bottom: 4px; }

            .text-voucher, input{ font-size: 12px !important; }
        }
    </style>
</head>
<body>
    <a href="home.php" class="btn-kembali"><i class="bi bi-arrow-left"></i> Back to Home</a>
    <div class="w-100 d-flex justify-content-center align-items-center">
        <h2 class="judul-form mt-5 mb-3">Your Seedlings <i class="bi bi-cart"></i></h2>
    </div>

    <?php if (count($items) === 0): ?>
        <hr>
        <p class="emptyCart my-4 text-center">Your cart is empty <i class="bi bi-emoji-frown"></i></p>
    <?php else: ?>

        <?php foreach ($items as $item): ?>
            <div class="item">
                <div class="bungkus-img">
                    <img src="uploads/produk/<?= $item["gambar"] ?>">
                </div>
                <div class="container-button-data d-flex justify-content-between w-100">
                    <div class="bungkusData">
                        <b><?= $item["nama_produk"] ?></b><br>
                        <table>
                            <tr>
                                <td>Price per item</td>
                                <td>:</td>
                                <td>Rp<?= number_format($item["harga"], 0, ',', '.') ?></td>
                            </tr>
                            <tr>
                                <td>Quantity</td>
                                <td>:</td>
                                <td><?= $item["jumlah"] ?></td>
                            </tr>
                            <tr>
                                <td>Subtotal</td>
                                <td>:</td>
                                <td>Rp<?= number_format($item["harga"] * $item["jumlah"], 0, ',', '.') ?></td>
                            </tr>
                        </table>
                    </div>
                    <div class="text-start d-flex align-items-center">
                        <button onclick="bukaModal(
                            '<?= $item['id_item'] ?>',
                            '<?= $item['id_produk'] ?>',
                            '<?= $item['jumlah'] ?>',
                            '<?= $item['harga'] * $item['jumlah'] ?>'
                        )">Checkout</button>
                        <a href="logic/hapus_item.php?id_item=<?= $item["id_item"] ?>" class="button2 mx-2">Delete</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>

        <b>Total All Items : Rp<?= number_format($total, 0, ',', '.') ?></b><br>
        <button class="mt-3" onclick="bukaModalSemua()">Checkout All Items</button>

    <?php endif; ?>

    <!-- Modal Checkout -->
    <div class="overlayy" id="overlayy" onclick="tutupModal()">
        <div class="modal-content" onclick="event.stopPropagation()">
            <span class="tutup text-end" onclick="tutupModal()">✕</span>
            <h3 class="judul-form my-3 text-center">Checkout</h3>

            <form method="POST" action="logic/checkout_logic.php">
                <input type="hidden" name="id_item" id="m-id-item">
                <input type="hidden" name="id_produk" id="m-id-produk">
                <input type="hidden" name="jumlah" id="m-jumlah">
                <input type="hidden" name="total" id="m-total">
                <input type="hidden" name="checkout_semua" id="m-semua" value="0">
                <input type="hidden" name="dari_cart" id="m-cart" value="0">

                <p>Total : Rp<span id="m-tampil-total"></span></p>

                <?php if (count($vouchers) > 0): ?>
                    <label>Choose Vouchers (optional) : </label><br>
                    <?php foreach ($vouchers as $v): ?>
                        <input type="checkbox"
                            name="id_vouchers[]"
                            value="<?= $v["id"] ?>"
                            data-nilai="<?= $v["nilai"] ?>"
                            onchange="hitungDiskon()">
                        <span class="text-voucher"><?= $v["nama"] ?> — Rp<?= number_format($v["nilai"], 0, ',', '.') ?> Discount Voucher</span><br>
                    <?php endforeach; ?>
                <?php endif; ?>

                <p class="mt-3 mb-0">Discount      : Rp <span id="m-diskon">0</span></p>
                <p>Total Payment : Rp <span id="m-total-bayar"></span></p>

                <input type="text" class="form-control mb-3" name="alamat" placeholder="Shipping Address" required>
                
                <button type="submit" class="text-end">Buy</button>
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
        document.getElementById("m-cart").value           = 1;
        document.getElementById("m-semua").value           = 0;
        document.getElementById("m-tampil-total").innerText = totalModal.toLocaleString("id-ID");
        document.getElementById("m-total-bayar").innerText  = totalModal.toLocaleString("id-ID");
        document.getElementById("m-diskon").innerText       = "0";

        document.querySelectorAll("input[name='id_vouchers[]']").forEach(function(cb) {
            cb.checked = false;
        });

        document.getElementById("overlayy").style.display = "block";
    }

    function bukaModalSemua() {
        totalModal = totalSemua;
        document.getElementById("m-id-item").value         = "";
        document.getElementById("m-id-produk").value       = "";
        document.getElementById("m-jumlah").value          = "";
        document.getElementById("m-total").value           = totalSemua;
        document.getElementById("m-cart").value           = 0;
        document.getElementById("m-semua").value           = 1;
        document.getElementById("m-tampil-total").innerText = totalSemua.toLocaleString("id-ID");
        document.getElementById("m-total-bayar").innerText  = totalSemua.toLocaleString("id-ID");
        document.getElementById("m-diskon").innerText       = "0";

        document.querySelectorAll("input[name='id_vouchers[]']").forEach(function(cb) {
            cb.checked = false;
        });

        document.getElementById("overlayy").style.display = "block";
    }

    function tutupModal() {
        document.getElementById("overlayy").style.display = "none";
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
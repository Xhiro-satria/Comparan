<?php 
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    require_once 'server.php';
    require_once 'function/product_function.php';
    require_once 'function/user_function.php';

    // Proteksi Login
    if (!isset($_SESSION["id_user"])) {
        
        header("Location: login.php");
        exit();
    }
    // Mengambil ID user yang sedang login dari data session
    $id_user = $_SESSION["id_user"];        //Agar fungsi tahu data milik siapa yang harus diambil.
    $dataUser = tampilDataUser($connect, $id_user);
    $produk = tampilSemuaProduk($connect, $id_user);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Comparan</title>

    <!-- Bootstrap 5 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">
    <!-- Bootstrap Icon -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <!-- css -->
    <link rel="stylesheet" href="css/style.css">
    <style>
        /* .gambuar{
            
        } */
    </style>
</head>
<body class="bodyHome mx-4 m-0 p-0" style="background-color: rgb(215, 231, 192);">
    <nav class="navbar navbar-expand-lg sticky-top" style="background-color: rgb(215, 231, 192);">
        <div class="container-fluid">
            <a class="navbar-brand d-flex align-items-center mx-3" href="#">
                <img src="assets/logo-fix.png" alt="logo" class="logoNav" style="width: 120px;">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                <div class="row w-100 align-items-center">
                    <div class="col-11 d-flex justify-content-center">
                        <div class="containerItemNav navbar-nav d-flex justify-content-center">
                            <a class="nav-link rounded-start-pill px-3" aria-current="page" href="#">Dashboard</a>
                            <a class="nav-link px-3" href="#about">About</a>
                            <a class="nav-link rounded-end-pill px-3" href="#shop">Shop</a>
                        </div>
                    </div>
                    <div class="col-1 text-lg-end p-0">
                        <div class="d-flex justify-content-between">
                            <a class="nav-link d-flex align-items-center" aria-disabled="true" href="cart.php" id="navbarNavAltMarkup">
                                <i class="cartIcon bi bi-cart d-flex align-items-center fs-2"></i>
                            </a>
                            <span class="d-flex align-items-center">|</span>
                            <div id="buka_profil" onclick="bukaProfil(
                                '<?= $dataUser['nama'] ?>',
                                '<?= $dataUser['username'] ?>',
                                '<?= $dataUser['poin'] ?>',
                                '<?= $dataUser['email'] ?>'
                            )">
                                <div class="fotoProfileNav w-100 d-flex justify-content-center align-items-center">
                                    <?php if ($dataUser["foto_profile"]): ?>
                                        <div style="width: 40px; height: 40px; border-radius:100%; overflow: hidden;">
                                            <img src="uploads/profil/<?= $dataUser["foto_profile"]?>" style="width: 100%; height: 100%; object-fit: cover;"
                                            class="gambuar d-flex align-items-center fs-2 justify-content-center">
                                        </div>
                                    <?php else: ?>
                                        <i class="bi bi-person-circle d-flex align-items-center fs-2" style="cursor: pointer;"></i>
                                    <?php endif; ?>    
                                </div>
                            </div>
                            
                            <!-- Profile Open -->
                            <div class="overlay1 position-fixed" id="overlay1" onclick="tutupModal()">
                                <div class="containerShowProfile">
                                    <div class="showProfile row m-0 mb-auto">
                                        <div class="fotoProfile w-100 d-flex justify-content-center align-items-center">
                                            <?php if ($dataUser["foto_profile"]): ?>
                                                <div style="width: 100px; height: 100px; border-radius:100%; overflow: hidden; box-shadow: 0 0 20px #00000050;">
                                                    <img src="uploads/profil/<?= $dataUser["foto_profile"]?>" 
                                                    style="width:100%; height: 100%; object-fit: cover;" class="d-flex align-items-center fs-2 justify-content-center mb-3">
                                                </div>
                                                <?php else: ?>
                                                <div style="width: 100px; height: 100px; border-radius:100%; overflow: hidden; box-shadow: 0 0 20px #00000050;">
                                                    <img src="uploads/profil/default.png" 
                                                    style="width:100%; height: 100%; object-fit: cover;" class="d-flex align-items-center fs-2 justify-content-center mb-3">
                                                </div>
                                            <?php endif; ?>    
                                        </div>
                                        <div class="editProfile w-100 d-flex justify-content-center align-items-center">
                                            <a href="profil.php" class="p-1 px-2 rounded-3" style="border: solid 1.5px black; font-size: 12px;">Edit Profil</a>
                                        </div>
                                                <!-- <i class="bi bi-person-circle d-flex align-items-center fs-2 justify-content-center mb-3"></i> -->
                                        <p><b><span id="p-nama" style="text-transform: capitalize;"></span></b><br><i>@<span id="p-username"></span></i></p>
                                        <p class="m-0"><i class="bi bi-c-circle"></i> <span id="p-poin"></span><br><span class="m-0"><i class="bi bi-envelope-at"> <span id="p-email" style="font-style: none;"></span></i></span></p>
                                        <hr>
                                        <a href="my_product.php"><i class="bi bi-bag"></i> My Product</a>
                                        <a href="riwayat_order.php"><i class="bi bi-clock-history"></i> Order History</a>
                                        <a href="add_product.php"><i class="bi bi-upload"></i> Upload Product</a>
                                        <a href="voucher.php"><i class="bi bi-tag"></i> My Voucher</a>
                                        <hr>
                                        <a href="logout.php"><i class="bi bi-box-arrow-right"></i> Sign Out</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>
    <section id="dashboard" class="bg-black rounded-4 m-1 mx-4 d-flex justify-content-center align-items-center">
        <div class="col position-relative">
            <video autoplay muted loop class="w-100 rounded-4 z-2" style="height: 640px; object-fit: cover; display: block;">
                <source src="assets/Animasi_Foto_Menjadi_Video_Tangan copy.mp4" type="video/mp4">
            </video>
            <div class="dashText position-absolute top-50 start-50 translate-middle z-1">
                <h1 class="dashTitle m-0 p-0">Nuture, Nature, Restore The Earth</h1>
                <h1 class="dashTagline m-0 p-0">choose and buy your sustainable products</h1>
            </div>
        </div>
    </section>
    <br><hr><br>
    <section id="about" class="bg- text-center d-flex justify-content-center align-items-center">
        <div class="row px-0 w-100 justify-content-center">
            <div class="row-100 px-0">
                <a href="#about" class="aboutTitle btn text-light fw-semibold">About Comparan</a>
                <h2 class="taglineAboutContent  text-start">Solution for your <span  style="font-family: Belgiano; font-style: italic; letter-spacing: -1px; color: black;">sustainable</span> living</h2>
                <div class="row w-100 m-0 my-4">
                    <div class="kolomKiri col-12 col-md-6 m-0">
                        <video autoplay muted loop class="vidKemasBibit opacity-100 rounded-4 p-0">
                            <source src="assets/kemasBibit.mp4" type="video/mp4">
                        </video>
                    </div>
                    <div class="kolomKanan col-12 col-md-6 m-0">
                        <h3 class="titleKemas text-start m-0">Help you live sustainably</h3>
                        <h4 class="subTitleKemas text-start">"E-<span class="comparanMeans">Co</span>mmerce <span class="comparanMeans">P</span>encint<span class="comparanMeans">a</span> Tandu<span class="comparanMeans">ran</span>"<hr></h4>
                        <p class="explainComparan text-start">We call it "<span style="font-family: 'Alphazet', sans-serif; color: #5d9300; font-weight: 600;">COMPARAN</span>", an online platform for plant lovers who care about nature and sustainability. It provides a variety of plant seedlings and eco-friendly products to support a greener lifestyle. Through this platform, users can easily purchase plants while also contributing to environmental conservation and supporting local farmers.  We provide eco-friendly products and solutions to help you live a more sustainable lifestyle.</p>
                    </div>
                </div>
            </div>
            <!-- this is card -->
            <h3 class="text-center mt-5 mb-3 fw-semibold" style="font-family: 'Alphazet', sans-serif; color:#5d9300;">Why Choose Comparan?</h3>
            <div class="row px-0 w-100 justify-content-center">

                <div class="iconCard col-12 col-md-4 mb-3 border-0" style="padding-left: 0;">

                    <div class="row g-0">
                        <div class="iconBody card-body text-start">
                            <h5 class="iconTitle card-title"><i class="bi bi-leaf-fill"></i> Sustainable Products</h5>
                            <p class="explainIcon card-text">Eco-friendly products for a greener lifestyle.</p>
                        </div>
                    </div>
                </div>

                <div class="iconCard col-12 col-md-4 mb-3 border-0">

                    <div class="row g-0">
                        <div class="iconBody card-body text-start">
                            <h5 class="iconTitle card-title"><i class="bi bi-truck"></i> Safe and Fast Delivery</h5>
                            <p class="explainIcon card-text">Safe and fast delivery for your plants.</p>
                        </div>
                    </div>
                </div>

                <div class="iconCard col-12 col-md-4 mb-3 border-0" style=" padding-right: 0;">

                    <div class="row g-0">
                        <div class="iconBody card-body text-start">
                            <h5 class="iconTitle card-title"><i class="bi bi-person-heart"></i> Support Local Farmers</h5>
                            <p class="explainIcon card-text">Supporting local farmers and communities.</p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end of card -->
            
            <!-- this is vision -->
            <div class="w-100 mt-5 mt-md-5">
                <h2 class="vision text-center">We have vision to <span style="color: #b5bc00;">Save The Earth</span></h2>
            </div>
            <div class="containerCard row px-0 w-100 m-0 my-4 justify-content-between">
                <div class="col-6 col-md-3 visionCard bg-transparent border-0 rounded- m-0 px-5" >
                    <div class="visionBody position-relative">
                        <img src="assets/image-1.png" class="imageVisionCard card-img-top rounded-5" alt="Image 1">
                        <p class="visionText card-text">Sustainability</p>
                    </div>
                </div>
                <div class="col-6 col-md-3 visionCard bg-transparent border-0 rounded-5 m-0 px-5" >
                    <div class="visionBody position-relative">
                        <img src="assets/image-3.png" class="imageVisionCard card-img-top rounded-5" alt="Image 3">
                        <p class="visionText card-text">Conservation</p>
                    </div>
                </div>
                <div class="col-6 col-md-3 visionCard bg-transparent border-0 rounded- m-0 px-5" >
                    <div class="visionBody position-relative">
                        <img src="assets/image-2.png" class="imageVisionCard card-img-top rounded-5" alt="Image 2">
                        <p class="visionText card-text">Greening The Earth</p>
                    </div>
                </div>
                <div class="col-6 col-md-3 visionCard bg-transparent border-0 rounded-5 m-0 px-5" >
                    <div class="visionBody position-relative">
                        <img src="assets/image-4.png" class="imageVisionCard card-img-top rounded-5" alt="Image 4">
                        <p class="visionText card-text">Reforestation</p>
                    </div>
                </div>
                <!-- card end -->
            </div>
            <!-- <h3>Let's see, choose, buy our plant, and get <span style="color: ;">an exclusive voucher</span>!</h3> -->
        </div>
    </section class="bg-black">
    

    <section id="shop" class="mx-4">
        <!-- <div class="w-100 text-center">
            <span class="heroBadge text-center my-2 fw-bold">✦ WELCOME TO OUR SHOP ✦</span>
        </div> -->
        <div class="heroShop text-center d-flex align-items-center justify-content-center mt-4 rounded-4">
            <div class="heroContent">
                <h1 class="heroTitle display-4 fw-bold">Everything starts with <span style="color: #cbf485;">Small steps</span></h1>
                <p class="heroSubtitle mb-4">Choose the best seeds for a greener future. Every seed you plant brings new life to the world.</p>
            </div>
        </div>
        <h1 class="ourProduct text-start mx-3 mt-5 mb-5" style="font-family: 'Aesthetic'; color: #75b800; font-style:italic;"><u>Our products✦</u></h1>
        <?php if (count($produk) === 0): ?>
            <p>Belum ada produk tersedia.</p>
            <?php else: ?>
                <?php if (count($produk) <= 18) {?>
                <div class="row m-0 w-100 justify-content-start">
                    <?php foreach ($produk as $p): ?>
                        <div class="productCard col-6 col-md-2">
                            <div class="productBody card-body">
                                <img src="uploads/produk/<?= $p["gambar"] ?>">
                                <b class="mt-2" style="text-transform: capitalize;"><?= $p["nama_produk"] ?></b>
                                <div class="productHarga mb-3">
                                    Rp<?= number_format($p["harga"], 0, ',', '.') ?>
                                </div>
                                <button class="showDetails w-100 rounded-pill" onclick="bukaModal(
                                '<?= $p['gambar'] ?>',
                                '<?= $p['nama_pemilik'] ?>',
                                '<?= htmlspecialchars(addslashes($p['nama_produk'])) ?>',
                                '<?= $p['harga'] ?>',
                                '<?= $p['stok'] ?>',
                                '<?= $p['kategori'] ?>',
                                '<?= htmlspecialchars(addslashes(preg_replace('/\s+/', ' ', $p['deskripsi']))) ?>',
                                '<?= $p['status'] ?>',
                                '<?= $p['id_produk'] ?>'
                                )">
                                show details
                                </button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php } else { ?>
                    XXXXXXXX
                <?php } ?>
                </div>
            <?php endif; ?>
    
        <!-- Modal -->
        <div class="overlay2" id="overlay2" onclick="tutupModal()">
            <div class="modal-konten row w-75" onclick="event.stopPropagation()">
                <span class="tutup p-0 text-end" onclick="tutupModal()"><i class="bi bi-x"></i></span><br>
                <div class="modalCol col-5 p-3"> 
                    <img id="m-gambar" src="" class="img-fluid"><br><br>
                </div>
                <div class="modalCol col-7 p-3 d-flex justify-content-between">
                    <div class="productInfo">
                        <b id="m-nama";"></b> 
                        <h5 class="pemilik"><span id="m-namaPemilik" style="text-transform: capitalize;"></span>'s product</h5>
                        <div class="hargaProduct">
                            Rp<span id="m-harga"></span>
                        </div>
                        <hr>
                        <i class="fw-semibold fs-4" style="font-family: 
                        'Belgiano', sans-serif; color: #456f00;">Spesifications</i>
                        <h5 class="productData"><i class="bi bi-tree"></i> Category  : <span id="m-kategori"></span></h5>
                        <h5 class="productData"><i class="bi bi-check-circle"></i> Status : <span id="m-status"></span></h5>
                        <h5 class="productData"><i class="bi bi-bookmark"></i> Description : <br><span id="m-deskripsi"></span></h5>
                    </div>
                    <div class="buyOption mx-2">
                        <div class="buyOptionItems col">
                            <label class="fw-semibold mb-2 fs-5" style="color: #456f00; font-family: 'Belgiano', sans-serif;">Set quantity</label>
                            <div class="buttonQuantity">
                                <button type="button" class="btn-qty" onclick="hitungJumlah(-1)">-</button>
                                <input type="number" id="m-jumlah" class="text-center bg-transparent rounded-2" value="1" min="1" style="color:#406700; border: 2px solid #406700;">
                                <button type="button" class="btn-qty " onclick="hitungJumlah(1)">+</button>
                            </div>
                            <h5 class="productStock mb-3">Stock : <span id="m-stok"></span></h5>
                            <div class="row m-0">
                                <button class="buttonCart btn" onclick="tambahKeranjang()">+ Add to cart <i class="bi bi-cart-fill"></i></button>
                                <button class="buttonCheckout btn" onclick="beliSekarang()">Checkout now</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>




        
    </div>

</div>
<script>
    let idProdukDipilih = null;
    let stokTersedia    = 0;

    function bukaProfil(nama, username, poin, email){
        // document.getElementById("p-gambar").src = 
        document.getElementById("p-nama").innerText = nama;
        document.getElementById("p-username").innerText = username;
        document.getElementById("p-poin").innerText = poin;
        document.getElementById("p-email").innerText = email;
        document.getElementById("overlay1").classList.add("show");
    }

    function bukaModal(gambar, nama_pemilik, nama, harga, stok, kategori, deskripsi, status, id_produk) {
        idProdukDipilih = id_produk;
        stokTersedia    = parseInt(stok);

        document.getElementById("m-gambar").src          = "uploads/produk/" + gambar;
        document.getElementById("m-namaPemilik").innerText       = nama_pemilik;
        document.getElementById("m-nama").innerText       = nama;
        document.getElementById("m-harga").innerText      = parseInt(harga).toLocaleString("id-ID");
        document.getElementById("m-stok").innerText       = stok;
        document.getElementById("m-kategori").innerText   = kategori;
        document.getElementById("m-deskripsi").innerText  = deskripsi;
        document.getElementById("m-status").innerText     = status;
        document.getElementById("m-jumlah").max           = stok;
        document.getElementById("overlay2").classList.add("show");
    }

    function tutupModal() {
        const resetJumlah = document.getElementById("m-jumlah");
        resetJumlah.value = 1;
        document.getElementById("overlay1").classList.remove("show");
        document.getElementById("overlay2").classList.remove("show");
    }

    function hitungJumlah(jml){
        const inputJumlah = document.getElementById("m-jumlah");

        let ubahInput = parseInt(inputJumlah.value);
        let hitung = ubahInput + jml;

        if (hitung >= 1 && hitung <= stokTersedia){
            inputJumlah.value = hitung;
        } else if (hitung > stokTersedia) {
            alert("You hit the stock limit! (" + stokTersedia + ")")
        }
    }

    function tambahKeranjang() {
        let jumlah = document.getElementById("m-jumlah").value;

        if(parseInt(jumlah) > stokTersedia) {
            alert("Maaf, stok tidak mencukupi!");
            return;
        }
        window.location.href = "logic/cart_logic.php?id_produk=" + idProdukDipilih + "&jumlah=" + jumlah;
    }

    function beliSekarang() {
        let jumlah = document.getElementById("m-jumlah").value;
        if(parseInt(jumlah) > stokTersedia) {
            alert("Maaf, stok tidak mencukupi!");
            return;
        }
        window.location.href = "checkout.php?id_produk=" + idProdukDipilih + "&jumlah=" + jumlah;
    }
</script>
</body>
</html>
<?php 
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    require_once 'server.php';
    require_once 'function/product_function.php';
    require_once 'function/user_function.php';

    if (!isset($_SESSION["id_user"])) {
        
        header("Location: login.php");
        exit();
    }

    $id_user = $_SESSION["id_user"];
    $dataUser = tampilDataUser($connect, $id_user);

    $keyword = $_GET["keyword"] ?? "";

    if($keyword){
        $produk = searchProduk($connect, $id_user, $keyword);
    }else {
        $produk = tampilSemuaProduk($connect, $id_user);
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="css/style.css">
    <title>Dashobard | Comparan</title>
    <style>
        .bodyHome, nav{ background-color: var(--hover-soft); }

        .logoNav{ width: 120px; animation: float 3s ease-in-out infinite; transition: all 1s ease-in-out;}

        .logoNav:hover{ animation: float 1s ease-in-out infinite;}

        .containerFotoProfilNav{
            width: 40px;
            height: 40px;
            border-radius:100%;
            overflow: hidden;
        }

        .containerFotoProfilNav img{
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .bi-person-circle{ cursor: pointer; }

        .fotoProfilContent{
            width: 100px;
            height: 100px;
            border-radius:100%;
            overflow: hidden;
            box-shadow: 0 0 20px var(--overlay-dark);
        }

        .fotoProfilContent img{
            width:100%;
            height: 100%;
            object-fit: cover;
        }

        .showProfile a:hover {
            color: var(--primary-accent);
        }

        .editProfile a{
            background-color: var(--hover-soft);
            color: var(--primary-dark2);
            font-size: 14px;
            font-weight: 600;
        }

        .editProfile a:hover {
            color: var(--primary-main);
        }

        #p-nama{ text-transform: capitalize; }

        #p-email{ font-style: none; }

        .videoDash{
            height: 640px;
            object-fit: cover;
            display: block;
        }

        .reasonChoose{ font-family: 'Alphazet', sans-serif; color: var(--primary-main); }
        
        .vision span{ color: var(--yellow); }

        .ourProduct{
            margin: 16px !important;
            font-family: 'Voguella';
            color: var(--primary-main);
            font-style: italic;
        }

        .carousel-right{
            display: grid;
            grid-auto-flow: column;
            grid-template-rows: repeat(2, auto);

            gap: 16px;

            overflow-x: auto;
            overflow-y: hidden;

            padding-bottom: 10px;

            scrollbar-width: none;
            -ms-overflow-style: none;
        }

        .carousel-right::-webkit-scrollbar{
            display: none;
        }

        .carousel-right .productCard{
            width: 210px;
        }


        .productBody b, .productHarga{
            font-size: 14px;
            text-transform: capitalize;
        }

        #m-pemilik{ text-transform: capitalize; }

        .spesifikasi, .buyOptionItems label{ font-family: 'Belgiano', sans-serif; color: var(--primary-dark); }

        .buttonQantity{ color: var(--primary-dark); border: 2px solid var(--primary-dark); }

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

        .footer-comparan {
            background-color: var(--soft-black);
            color: var(--card-bg);
            padding: 60px 0 20px 0;
            margin-top: 80px;
            border-top: 8px solid var(--primary-accent);
            border-top-right-radius: 36px;
            border-top-left-radius: 36px;
            animation: flicker-bg 5s infinite;
        }

        .footer-brand .logoNav {
            width: 150px;
            margin-bottom: 15px;
        }

        .footer-text {
            font-size: 14px;
            color: var(--card-bg);
            line-height: 1.6;
        }

        .footer-title {
            font-family: 'Alphazet', sans-serif;
            color: var(--primary-main);
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 20px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .footer-links {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .footer-links li { margin-bottom: 10px; }

        .footer-links a {
            text-decoration: none;
            color: var(--card-bg);
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .footer-links a:hover {
            color: var(--yellow);;    
            padding-left: 5px;
        }

        .social-icons a {
            display: inline-block;
            width: 35px;
            height: 35px;
            background-color: rgba(255, 255, 255, 0.1);
            color: var(--white);
            text-align: center;
            line-height: 35px;
            border-radius: 50%;
            margin-right: 10px;
            transition: 0.3s;
        }

        .social-icons a:hover {
            background-color: var(--primary-main);
            color: var(--white);
        }

        .newsletter-form .form-control {
            background-color: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: var(--white);
        }

        .newsletter-form .form-control:focus {
            box-shadow: none;
            border-color: var(--primary-main);
        }

        .newsletter-form .btn-subscribe {
            background-color: var(--primary-main);
            color: var(--white);
            border: none;
        }

        .newsletter-form .btn-subscribe:hover { background-color: var(--primary-dark); }

        .footer-bottom {
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            margin-top: 40px;
            padding-top: 20px;
            font-size: 13px;
            color: var(--gray);
        }

        @media (max-width: 768px){
            .overlay2.show{
                display: flex;
                justify-content: center;
                align-items: start;
                padding-top: 100px;
            }

            .modal-konten{
                display: flex !important;
                flex-direction: row !important;
                border-radius: 16px;
                padding: 8px 16px !important;
                overflow-y: auto;
                max-height: 640px;
            }

            .col-5{
                width: 100% !important;
                height: 270px;
                padding: 8px 12px !important;
            }

            .col-5 img{
                width: 100%;
                object-fit: cover !important;
                border-radius: 16px;
            }

            #m-nama{ font-size: 16px; }

            .pemilik{ font-size: 12px; margin: 0; }

            .hargaProduct{ font-size: 16px !important; margin: 0; }

            .col-7{ width: 100%; display: flex; justify-content: space-between !important; }

            .productInfo hr{ margin: 2px; }

            .spesifikasi{ font-size: 14px !important; }

            .productData{ font-size: 12px !important; margin: 0 !important; }

            .buyOption{ width: 50%; margin-right: 0 !important; }

            .buyOptionItems{ padding: 4px 8px; border-radius: 8px; }

            .buyOptionItems label{ font-size: 16px !important; }

            .buttonQuantity{ width: 100% !important; height: 30px !important; }

            .btn-qty{ font-size: 16px; }

            #m-jumlah{ font-size: 12px; }

            .productStock{ margin-top: 4px; font-size: 16px; }

            .buttonGeser{ font-size: 11px !important; padding: 4px 4px;}
        }
    </style>
</head>
<body class="bodyHome mx-4 m-0 p-0">
    <nav class="navbar navbar-expand-lg sticky-top">
        <div class="container-fluid">
            <a class="navbar-brand d-flex align-items-center mx-3" href="#">
                <img src="assets/logo-fix.png" alt="Logo Comparan" class="logoNav">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                <div class="bungkusKananKiri row w-100 align-items-between">
                    <div class="navKiri col-11 d-flex justify-content-center">
                        <div class="containerItemNav navbar-nav d-flex justify-content-center">
                            <a class="nav-link pt-2" aria-current="page" href="#">Dashboard</a>
                            <a class="nav-link pt-2" href="#about">About</a>
                            <a class="nav-link pt-2" href="#shop">Shop</a>
                            <a class="nav-link pt-2" href="leaderboard.php">Leaderboard</a>
                        </div>
                    </div>
                    <div class="navKanan col-1 text-lg-end p-0">
                        <div class="isiKanan d-flex justify-content-between align-content-center mt-1">
                            <a class="nav-link d-flex align-items-center" aria-disabled="true" href="cart.php" id="navbarNavAltMarkup">
                                <i class="cartIcon bi bi-cart d-flex align-items-center fs-2"></i>
                            </a>
                            <span class="divider d-flex align-items-center">|</span>
                            <div id="buka_profil" onclick="bukaProfil(
                                '<?= $dataUser['nama'] ?>',
                                '<?= $dataUser['username'] ?>',
                                '<?= $dataUser['poin'] ?>',
                                '<?= $dataUser['email'] ?>'
                            )">
                                <div class="fotoProfileNav w-100 d-flex justify-content-center align-items-center">
                                    <?php if ($dataUser["foto_profile"]): ?>
                                        <div class="containerFotoProfilNav">
                                            <img src="uploads/profil/<?= $dataUser["foto_profile"]?>" class="d-flex align-items-center fs-2 justify-content-center">
                                        </div>
                                    <?php else: ?>
                                        <i class="bi bi-person-circle d-flex align-items-center fs-2"></i>
                                    <?php endif; ?>    
                                </div>
                            </div>
                            
                            <!-- Profile Open -->
                            <div class="overlay1 position-fixed" id="overlay1" onclick="tutupModal()">
                                <div class="containerShowProfile">
                                    <div class="showProfile row m-0 mb-auto">
                                        <div class="fotoProfile w-100 d-flex justify-content-center align-items-center">
                                            <?php if ($dataUser["foto_profile"]): ?>
                                                <div class="fotoProfilContent">
                                                    <img src="uploads/profil/<?= $dataUser["foto_profile"]?>" class="d-flex align-items-center fs-2 justify-content-center mb-3">
                                                </div>
                                                <?php else: ?>
                                                <div class="fotoProfilContent">
                                                    <img src="uploads/profil/default.png" class="d-flex align-items-center fs-2 justify-content-center mb-3">
                                                </div>
                                            <?php endif; ?>    
                                        </div>
                                        <div class="editProfile w-100 d-flex justify-content-center align-items-center">
                                            <a href="profil.php" class="p-1 px-2 rounded-3">Edit Profil</a>
                                        </div>
                                        <p><b><span id="p-nama"></span></b><br><i>@<span id="p-username"></span></i></p>
                                        <p class="m-0"><i class="bi bi-c-circle"></i> <span id="p-poin"></span><br><span class="m-0"><i class="bi bi-envelope-at"> <span id="p-email"></span></i></span></p>
                                        <hr>
                                        <a href="my_product.php"><i class="bi bi-bag"></i> My Product</a>
                                        <a href="pesanan_masuk.php"><i class="bi bi-send"></i> Incoming Orders</a>
                                        <a href="riwayat_order.php"><i class="bi bi-clock-history"></i> Order History</a>
                                        <a href="add_product.php"><i class="bi bi-upload"></i> Upload New Product</a>
                                        <a href="voucher.php"><i class="bi bi-tag"></i> My Voucher</a>
                                        <hr>
                                        <a href="#" onclick="konfirmasiSignOut(); return false;"><i class="bi bi-box-arrow-right"></i> Sign Out</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <div id="overlay-logout">
        <div class="containerConfirm">
            <h5>Logout Confirmation</h5>
            <p>Do you want to logout?</p>
            <div class="d-flex justify-content-center">
                <a href="logout.php" class="btn btn-outline-danger mx-1">Yes</a>
                <button onclick="tutupLogout()" class="btn-outline-success mx-1">Cancel</button>
            </div>
        </div>
    </div>

    <section id="dashboard" class="bg-black rounded-4 m-1 mx-4 d-flex justify-content-center align-items-center">
        <div class="col position-relative">
            <video autoplay muted loop class="videoDash w-100 rounded-4 z-2">
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
                <h2 class="taglineAboutContent  text-start">Solution for your <span>sustainable</span> living</h2>
                <div class="row w-100 m-0 my-4">
                    <div class="kolomKiri col-12 col-md-6 m-0">
                        <video autoplay muted loop class="vidKemasBibit opacity-100 rounded-4 p-0">
                            <source src="assets/kemasBibit.mp4" type="video/mp4">
                        </video>
                    </div>
                    <div class="kolomKanan col-12 col-md-6 m-0">
                        <h3 class="titleKemas text-start m-0">Help you live sustainably</h3>
                        <h4 class="subTitleKemas text-start">"E-<span class="comparanMeans">Co</span>mmerce <span class="comparanMeans">P</span>encint<span class="comparanMeans">a</span> Tandu<span class="comparanMeans">ran</span>"<hr></h4>
                        <p class="explainComparan text-start">We call it "<span>COMPARAN</span>", an online platform for plant lovers who care about nature and sustainability. It provides a variety of plant seedlings and eco-friendly products to support a greener lifestyle. Through this platform, users can easily purchase plants while also contributing to environmental conservation and supporting local farmers.  We provide eco-friendly products and solutions to help you live a more sustainable lifestyle.</p>
                    </div>
                </div>
            </div>
            <!-- this is card -->
            <h3 class="reasonChoose text-center mt-5 mb-3 fw-semibold">Why Choose Comparan?</h3>
            <div class="row px-0 w-100 justify-content-center">

                <div class="iconCard col-12 col-md-4 mb-3 border-0">

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

                <div class="iconCard col-12 col-md-4 mb-3 border-0">

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
                <h2 class="vision text-center">We have vision to <span>Save The Earth</span></h2>
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
        </div>
    </section class="bg-black">
    


    <?php if ($keyword): ?>
        <p>Hasil pencarian untuk: <b><?= $keyword ?></b></p>
    <?php endif; ?>

    <section id="shop" class="mx-4">
        <!-- <div class="w-100 text-center">
            <span class="heroBadge text-center my-2 fw-bold">✦ WELCOME TO OUR SHOP ✦</span>
        </div> -->
        <div class="heroShop text-center d-flex align-items-center justify-content-center mt-4 rounded-4">
            <div class="heroContent">
                <h1 class="heroTitle display-4 fw-bold">Everything starts with <span>Small steps</span></h1>
                <!-- <p class="heroSubtitle mb-4">Choose the best seeds for a greener future. Every seed you plant brings new life to the world.</p> -->
            </div>
        </div>
        <div class="d-flex ">
            <h1 class="ourProduct text-start mx-3 mt-5 mb-5"><u>Our products</u></h1>
            <!-- <form method="GET">
                <input type="text" 
                    name="keyword" 
                    value="<?= $keyword ?>" 
                    placeholder="Cari produk...">
                <button type="submit">Cari</button>
                <?php if ($keyword): ?>
                    <a href="home.php">Reset</a>
                <?php endif; ?>
            </form><br>

            <?php if ($keyword): ?>
                <p>Hasil pencarian untuk: <b><?= $keyword ?></b></p>
            <?php endif; ?> -->
        </div>
        <?php if (count($produk) === 0): ?>
            <p>Belum ada produk tersedia.</p>
            <?php else: ?>
                <?php 
                $isScroll = count($produk) > 19;
                ?>

                <div class="<?= $isScroll ? 'carousel-right' : 'row row-cols-2 row-cols-md-4 row-cols-lg-6 g-3' ?>">
                    <?php foreach ($produk as $p): ?>
                        <div class="productCard">
                            <div class="productBody card-body">
                                <img src="uploads/produk/<?= $p["gambar"] ?>">
                                <b class="mt-2"><?= $p["nama_produk"] ?></b>
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
                </div>
            <?php endif; ?>
    
        <!-- Modal -->
        <div class="overlay2" id="overlay2" onclick="tutupModal()">
            <div class="modal-konten row w-75" onclick="event.stopPropagation()">
                <div class="w-100 text-end p-0  ">
                    <span class="tutup p-0 text-end" onclick="tutupModal()"><i class="bi bi-x"></i></span><br>
                </div>
                <div class="modalCol col-5 p-3"> 
                    <img id="m-gambar" src="" class="img-fluid"><br><br>
                </div>
                <div class="modalCol col-7 p-3 d-flex justify-content-between">
                    <div class="productInfo col-6">
                        <b id="m-nama";"></b> 
                        <h5 class="pemilik"><span id="m-namaPemilik"></span>'s product</h5>
                        <div class="hargaProduct">
                            Rp<span id="m-harga"></span>
                        </div>
                        <hr>
                        <i class="spesifikasi fw-semibold fs-4">Spesifications</i>
                        <h5 class="productData"><i class="bi bi-tree"></i> Category  : <span id="m-kategori"></span></h5>
                        <h5 class="productData"><i class="bi bi-check-circle"></i> Status : <span id="m-status"></span></h5>
                        <h5 class="productData"><i class="bi bi-bookmark"></i> Description : <br><span id="m-deskripsi"></span></h5>
                    </div>
                    <div class="buyOption mx-2 col-6">
                        <div class="buyOptionItems col">
                            <label class="fw-semibold mb-2 fs-5">Set quantity</label>
                            <div class="buttonQuantity">
                                <button type="button" class="btn-qty" onclick="hitungJumlah(-1)">-</button>
                                <input type="number" id="m-jumlah" class="text-center bg-transparent rounded-2" value="1" min="1">
                                <button type="button" class="btn-qty " onclick="hitungJumlah(1)">+</button>
                            </div>
                            <h5 class="productStock mb-3">Stock : <span id="m-stok"></span></h5>
                            <div class="row m-0">
                                <button class="buttonGeser mb-2" onclick="tambahKeranjang()">+ Add to cart <i class="bi bi-cart-fill"></i></button>
                                <button class="buttonGeser mb-2" onclick="beliSekarang()">Checkout now</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer id="footer" class="footer-comparan mx-4">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-6 mb-4 mb-lg-0">
                    <div class="footer-brand">
                        <img src="assets/logo-fix.png" alt="Logo Comparan" class="logoNav">
                        <p class="footer-text mt-3">
                            "E-Commerce Pencinta Tanduran"<br>
                            Penyedia bibit tanaman dan produk ramah lingkungan untuk mendukung gaya hidup yang lebih hijau dan berkelanjutan.
                        </p>
                        <div class="social-icons mt-4">
                            <a href="on_progress_footer.php"><i class="bi bi-instagram"></i></a>
                            <a href="on_progress_footer.php"><i class="bi bi-tiktok"></i></a>
                            <a href="on_progress_footer.php"><i class="bi bi-twitter-x"></i></a>
                            <a href="on_progress_footer.php"><i class="bi bi-youtube"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6 mb-4 mb-lg-0">
                    <h4 class="footer-title">Explore</h4>
                    <ul class="footer-links">
                        <li><a href="#"><i class="bi bi-chevron-right fs-6"></i> Dashboard</a></li>
                        <li><a href="#shop"><i class="bi bi-chevron-right fs-6"></i> Shop Products</a></li>
                        <li><a href="#about"><i class="bi bi-chevron-right fs-6"></i> About Comparan</a></li>
                        <li><a href="profil.php"><i class="bi bi-chevron-right fs-6"></i> My Account</a></li>
                        <li><a href="voucher.php"><i class="bi bi-chevron-right fs-6"></i> My Vouchers</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6 mb-4 mb-md-0">
                    <h4 class="footer-title">Customer Care</h4>
                    <ul class="footer-links">
                        <li><a href="faq.php"><i class="bi bi-question-circle"></i> FAQ & Help Center</a></li>
                        <li><a href="shipping_delivery.php"><i class="bi bi-box-seam"></i> Shipping & Delivery</a></li>
                        <li><a href="return_policy.php"><i class="bi bi-arrow-counterclockwise"></i> Return Policy</a></li>
                        <li><a href="terms_privacy.php"><i class="bi bi-shield-check"></i> Terms & Privacy</a></li>
                        <li><a href="https://mail.google.com/mail/?view=cm&fs=1&to=cndrmhrdka@gmail.com&su=Customer Support Comparan&body=Hello Comparan Team," target="_blank"><i class="bi bi-headset"></i> Contact Us</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h4 class="footer-title">Join The Movement</h4>
                    <p class="footer-text">Subscribe for gardening tips, eco-friendly news, and special offers!</p>
                    <form class="newsletter-form d-flex mt-3">
                        <input type="email" class="form-control rounded-start-2 rounded-end-0" placeholder="Email address" aria-label="Email address" required>
                        <button class="btn btn-subscribe rounded-end-2 rounded-start-0" type="submit"><i class="bi bi-send-fill"></i></button>
                    </form>
                </div>
            </div>
            <div class="row footer-bottom">
                <div class="col-md-6 text-center text-md-start mb-2 mb-md-0">
                    &copy; 2026 Comparan. All Rights Reserved.
                </div>
                <div class="col-md-6 text-center text-md-end">
                    Nurture, Nature, Restore The Earth <i class="bi bi-globe-americas"></i>
                </div>
            </div>
        </div>
    </footer>

<script>
    let idProdukDipilih = null;
    let stokTersedia    = 0;

    function bukaProfil(nama, username, poin, email){
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
        let jumlah     = document.getElementById("m-jumlah").value;
        let id_produk  = idProdukDipilih;
        window.location.href = "logic/beli_sekarang_logic.php?id_produk=" + id_produk + "&jumlah=" + jumlah;
    }

    function konfirmasiSignOut() {
        document.getElementById("overlay-logout").style.display = "block";
    }

    function tutupLogout() {
        document.getElementById("overlay-logout").style.display = "none";
    }
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
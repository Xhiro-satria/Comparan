<?php 
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    // Opsional: Panggil koneksi dan fungsi jika di halaman FAQ butuh data user (misal untuk foto profil di navbar)
    require_once 'server.php';
    require_once 'function/user_function.php';

    $id_user = $_SESSION["id_user"] ?? null; 
    $dataUser = $id_user ? tampilDataUser($connect, $id_user) : null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <!-- Bootstrap 5 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">
    <!-- Bootstrap Icon -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <!-- css -->
    <link rel="stylesheet" href="css/style.css">
    <title>FAQ & Help Center - Comparan</title>
    <style>
        body, nav{ background-color: var(--hover-soft); }

        .logoNav{ width: 120px; }

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
        .editProfile a{
            background-color: var(--soft-black);
            color: var(--white);
            font-size: 14px;
            font-weight: 600;
        }

        #p-nama{ text-transform: capitalize; }

        #p-email{ font-style: none; }

        .containerAll{ max-width: 800px; min-height: 50vh; }

        .faq-header {
            color: var(--primary-main);
            font-family: 'Safira', sans-serif;
            text-align: center;
            margin-top: 50px;
            margin-bottom: 30px;
        }
        .accordion-button:not(.collapsed) {
            background-color: var(--primary-main);
            color: white;
        }
        .accordion-button:focus {
            box-shadow: none;
            border-color: rgba(0,0,0,.125);
        }

        .footer-comparan {
            background-color: var(--soft-black);
            color: var(--card-bg);
            padding: 60px 0 20px 0;
            margin-top: 80px;
            border-top: 5px solid var(--primary-accent);
            border-top-right-radius: 20px;
            border-top-left-radius: 20px;
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
    </style>
</head>
<body class="mx-4 m-0 p-0">
    <nav class="navbar navbar-expand-lg sticky-top">
        <div class="container-fluid">
            <a class="navbar-brand d-flex align-items-center mx-3" href="home.php">
                <img src="assets/logo-fix.png" alt="Logo Comparan" class="logoNav">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                <div class="row w-100 align-items-center">
                    <div class="col-11 d-flex justify-content-center">
                        <div class="containerItemNav navbar-nav d-flex justify-content-center">
                            <a class="nav-link rounded-start-pill px-3" aria-current="page" href="home.php#dashboard">Dashboard</a>
                            <a class="nav-link px-3" href="home.php#about">About</a>
                            <a class="nav-link rounded-end-pill px-3" href="home.php#shop">Shop</a>
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
                                        <a href="riwayat_order.php"><i class="bi bi-clock-history"></i> Order History</a>
                                        <a href="add_product.php"><i class="bi bi-upload"></i> Upload Product</a>
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
    
    <div class="containerAll container mt-5">
        <h2 class="faq-header">Frequently Asked Questions</h2>
        <p class="text-center mb-5 text-muted">Find answers to the most commonly asked questions about Comparan.</p>

        <div class="accordion" id="accordionFAQ">
            <div class="accordion-item mb-3 border-0 rounded-3 shadow-sm">
                <h2 class="accordion-header" id="headingOne">
                    <button class="accordion-button rounded-3" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                        <i class="bi bi-box-seam me-2"></i> How does Comparan ensure plant seedlings are safe during transit?
                    </button>
                </h2>
                <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionFAQ">
                    <div class="accordion-body text-secondary">
                        We work with local farmers using strict <i>eco-packaging</i> standards. Plant roots are wrapped in moist <i>cocopeat</i> or <i>sphagnum moss</i> to keep them hydrated. We use thick cardboard boxes with good air circulation and strictly minimize the use of single-use plastics.
                    </div>
                </div>
            </div>
            <div class="accordion-item mb-3 border-0 rounded-3 shadow-sm">
                <h2 class="accordion-header" id="headingTwo">
                    <button class="accordion-button collapsed rounded-3" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                        <i class="bi bi-shield-check me-2"></i> What if my plant arrives wilted or dead?
                    </button>
                </h2>
                <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionFAQ">
                    <div class="accordion-body text-secondary">
                        Mild wilting upon arrival is perfectly normal due to "transit stress". Simply place the plant in a shaded area and water it. However, if the plant arrives <b>completely dead, broken, or rotten</b>, we offer a <b>100% Guarantee</b>. Please submit a complaint along with an uncut unboxing video within 24 hours after the package status is marked as "Delivered".
                    </div>
                </div>
            </div>
            <div class="accordion-item mb-3 border-0 rounded-3 shadow-sm">
                <h2 class="accordion-header" id="headingThree">
                    <button class="accordion-button collapsed rounded-3" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                        <i class="bi bi-c-circle me-2"></i> I see a 'Points' section in my profile. How do I earn them?
                    </button>
                </h2>
                <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#accordionFAQ">
                    <div class="accordion-body text-secondary">
                        You can earn Comparan Points (C-Points) by making purchases, leaving product reviews, or participating in our green campaigns. These points can later be exchanged for discounts in the <b>My Voucher</b> menu.
                    </div>
                </div>
            </div>
            <div class="accordion-item mb-3 border-0 rounded-3 shadow-sm">
                <h2 class="accordion-header" id="headingFour">
                    <button class="accordion-button collapsed rounded-3" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                        <i class="bi bi-shop me-2"></i> Can I sell my own homegrown plants on Comparan?
                    </button>
                </h2>
                <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour" data-bs-parent="#accordionFAQ">
                    <div class="accordion-body text-secondary">
                        Absolutely! Comparan is a platform for the community. You can start selling seedlings from your garden or homemade compost by going to your profile menu and selecting <b>Upload Product</b>.
                    </div>
                </div>
            </div>

        </div>
    </div>

    <footer class="footer-comparan">
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
                            <a href="#"><i class="bi bi-instagram"></i></a>
                            <a href="#"><i class="bi bi-tiktok"></i></a>
                            <a href="#"><i class="bi bi-twitter-x"></i></a>
                            <a href="#"><i class="bi bi-youtube"></i></a>
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
                        <li><a href="#"><i class="bi bi-box-seam"></i> Shipping & Delivery</a></li>
                        <li><a href="#"><i class="bi bi-arrow-counterclockwise"></i> Return Policy</a></li>
                        <li><a href="#"><i class="bi bi-shield-check"></i> Terms & Privacy</a></li>
                        <li><a href="#"><i class="bi bi-headset"></i> Contact Us</a></li>
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function bukaProfil(nama, username, poin, email){
        document.getElementById("p-nama").innerText = nama;
        document.getElementById("p-username").innerText = username;
        document.getElementById("p-poin").innerText = poin;
        document.getElementById("p-email").innerText = email;
        document.getElementById("overlay1").classList.add("show");
    }

    function tutupModal() {
        document.getElementById("overlay1").classList.remove("show");

    }

    function konfirmasiSignOut() {
        document.getElementById("overlay-logout").style.display = "block";
    }

    function tutupLogout() {
        document.getElementById("overlay-logout").style.display = "none";
    }
</script>
</script>

</body>
</html>
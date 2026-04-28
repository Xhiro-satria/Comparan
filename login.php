<?php 
    require_once 'server.php';
    require_once 'function/user_function.php';
    
    $pesan = "";
    $check = false;

    // Proses login
    if($_SERVER["REQUEST_METHOD"] === "POST"){
        $username = $_POST["username"];
        $password = $_POST["password"];
        $ingatSaya = $_POST["ingatSaya"] ?? 0 ;
        
        //panggil fungsi loginUser untuk memeriksa kredensial
        $user = loginUser($connect, $username, $password);

        if($user){
            session_start();
            $_SESSION["id_user"] = $user["id_user"];
            $_SESSION["nama"] = $user["nama"];
            $_SESSION["username"] = $user["username"];
            $_SESSION["role"] = $user["role"];
            $_SESSION["poin"] = $user["poin"];
            $_SESSION["email"] = $user["email"];

            if($ingatSaya){
                setcookie("username", $username, time() + (30 * 24 * 60 * 60), "/");
                setcookie("password", $_POST["password"], time() + (30 * 24 * 60 * 60), "/");
            }else {
                setcookie("username", "", time() - 3600, "/");
                setcookie("password", "", time() - 3600, "/");
            }
            header("Location: home.php");
            exit();
        } else {
            $check = true;
            // $pesan = "Login gagal. Periksa username dan password.";
            $pesan = "Akun tidak ditemukan.";
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <title>Sign In</title>
    <style>
        body{
            background-color: black;
            background-image: url(assets/backgroundd.jpeg);
            background-repeat: none;
            margin: 0;
            height: 100vh;
        }
    </style>
</head>
<body>
    <div class="container-fluid m-0 p-0" style="background-color: #00000054;">
        <?php if($check): ?>
            <h5 class="fixed-top bg-danger text-light fw-lighter text-center fs-6 p-1"><?= $pesan ?></h5>
        <?php endif; ?>     
        <div class="row align-items-center text-center p-0 m-0 vh-100">
            <div class="kolom-kiri col-12 col-md-7 h-100 px-5 pt-3 d-flex flex-column justify-content-between">
                <div class="kiri">
                    <div class="d-flex justify-content-start mb-4 mt-3 align-items-center">
                        <img src="assets/logo-fix.png" class="logo" alt="" style="width: 200px;">
                        <!-- <h5 class="mx-2 m-0" style="font-size: 32px; color: #5d9601;">COMPARAN</h5> -->
                    </div>
                    <h1 class="tagline text-start mt-3 fw-medium">Welcome back!<br><span class="taglineSpan fw-medium" style="font-family: 'Voguella'; font-style: italic; font-size: 50px; color: #84d31e;">Ready to grow?</span><br>Let's get started with us!</h1>
                </div>
                <div>
                    <h4 class="taglineBottom text-center fw-medium">-make the world a better place. Small actions grow into meaningful change-</h4>
                </div>
            </div>
            <div class="kolom-kanan col-12 col-md-5 bg-white rounded-0 rounded-start-4 m-0 p-0 order-1 order-md-2">
                <div class="kanan">
                    <h2 class="titleFormLogin text-start fw-semibold text-black mt-5 mx-5 mb-0 p-0">Hello user!</h2>
                    <p class=" text-start text-secondary mx-5 p-0">Please Sign-In to continue to our shop</p>
                    <div class="">
                        <div class="container text-center">
                            <div class="label-login row align-items-center mx-5 p-2 rounded-pill">
                                <a href="" class="signInButton col p-2 rounded-pill">Sign In</a>
                                <a href="add_user.php" class="signUpButton col p-2 rounded-pill">Sign Up</a>
                            </div>
                            <form action="" method="POST">
                                <div class="mt-4 text-start w-100 px-5">
                                    <label for="exampleFormControlInput1" class="form-label fw-semibold">Username</label>
                                    <input type="text" class="form-control rounded-4 px-4 p-2" id="username" name="username" placeholder="Input your username" required>
                                </div>
                                <div class="px-5 mt-4 text-start w-100">
                                    <label for="exampleFormControlInput1" class="form-label fw-semibold">Password</label>
                                    <input type="password" class="form-control rounded-4 px-4 p-2" id="password" name="password" placeholder="Input your password" required>
                                </div>
                                <div class="form-check text-start mx-5 mt-3">
                                    <input class="form-check-input" type="checkbox" name="ingatSaya" id="checkDefault1" value="1">
                                    <label class="form-check-label" for="checkDefault1">Remember me</label>
                                </div>
                                <div class="px-5 mt-3">
                                    <button type="submit" value="Login" class="loginButton w-100">Login</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
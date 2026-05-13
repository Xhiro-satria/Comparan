<?php
require_once "server.php";
require_once "function/user_function.php";

$pesan = "";
$check = false;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nama     = $_POST["name"];
    $username = $_POST["username"];
    $password = $_POST["password"];
    $email = $_POST["email"];

    $id = tambahUser($connect, $nama, $username, $password, $email);

    if ($id === "email_sudah_ada" || $id === "username_sudah_ada") {
        $check = true;
        $_SESSION['cek_email'] = $check;

        if ($id === "email_sudah_ada") {
            $pesan = "Email sudah digunakan!";
        } elseif ($id === "username_sudah_ada") {
            $pesan = "Username sudah digunakan!";
        }
    } elseif ($id) {
        $pesan = "Registrasi berhasil!";
        header("Location: home.php");
        exit();
    } else {
        $pesan = "Registrasi gagal.";
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
    <title>Sign Up | Comparan</title>
    <style>
        body {
            background-color: var(--black);
            background-image: url(assets/backgroundd.jpeg);
            background-repeat: none;
            margin: 0;
            height: 100vh;
        }

        .container-fluid {
            background-color: var(--overlay-dark);
        }

        .signUpButton {
            background-color: var(--primary-main);
            color: white;
            transition: .3s all ease-in-out;
            text-decoration: none;
            font-weight: 500;
        }

        .signUpButton:hover {
            background-color: var(--primary-dark);
            color: var(--white);
        }

        .signInButton {
            background-color: var(--white);
            transition: .3s all ease-in-out;
            color: var(--primary-main);
            text-decoration: none;
            font-weight: 500;
        }

        .signInButton:hover {
            background-color: var(--primary-main);
            color: white;
        }
    </style>
</head>

<body>
    <div class="container-fluid m-0 p-0">
        <div class="row align-items-center text-center p-0 m-0 vh-100">
            <div class="kolom-kiri col-12 col-md-7 h-100 px-5 pt-3 d-flex flex-column justify-content-between">
                <div class="kiri">
                    <div class="d-flex justify-content-start mb-4 mt-3 align-items-center">
                        <img src="assets/logo-fix.png" class="logo" alt="Logo Comparan">
                    </div>
                    <h1 class="tagline text-start mt-3 fw-medium">Hello Newbie!<br><span class="taglineSpan fw-medium">Ready to grow?</span><br>Let's join with us!</h1>
                </div>
                <div>
                    <h4 class="taglineBottom text-center fw-medium">-make the world a better place. Small actions grow into meaningful change-</h4>
                </div>
            </div>
            <div class="kolom-kanan-up col-12 col-md-5 bg-white rounded-start-4 h-100 m-0 p-0">
                <?php if ($check): ?>
                    <h5 class="fixed-top bg-danger text-light fw-lighter text-center fs-6 p-1"><?= $pesan ?></h5>
                <?php endif; ?>
                <div>
                    <h2 class="text-start fw-semibold text-black mt-5 mx-5 mb-0 p-0">Hello user!</h2>
                    <p class=" text-start text-secondary mx-5 p-0">Please Sign-Up to continue to our shop</p>
                    <div class="">
                        <div class="container text-center">
                            <div class="label-login row align-items-center mx-5 p-2 rounded-pill">
                                <a href="login.php" class="signInButton col p-2 rounded-pill">Sign In</a>
                                <a href="#" class="signUpButton col p-2 rounded-pill">Sign Up</a>
                            </div>
                            <form action="add_user.php" method="POST">
                                <div class="mt-4 text-start w-100 px-5">
                                    <label for="exampleFormControlInput1" class="form-label fw-semibold">Username</label>
                                    <input type="text" class="form-control rounded-4 px-4 p-2" id="username" name="username" placeholder="Input your username" required>
                                </div>
                                <div class="mt-4 text-start w-100 px-5">
                                    <label for="exampleFormControlInput1" class="form-label fw-semibold">Name</label>
                                    <input type="text" class="form-control rounded-4 px-4 p-2" id="name" name="name" placeholder="Input your name" required>
                                </div>
                                <div class="mt-4 text-start w-100 px-5">
                                    <label for="exampleFormControlInput1" class="form-label fw-semibold">Email</label>
                                    <input type="email" class="form-control rounded-4 px-4 p-2" id="email" name="email" placeholder="Input your email" required>
                                </div>
                                <div class="px-5 mt-4 text-start w-100">
                                    <label for="exampleFormControlInput1" class="form-label fw-semibold">Password</label>
                                    <input type="password" class="form-control rounded-4 px-4 p-2" id="password" name="password" placeholder="Input your password" required>
                                </div>
                                <div class="px-5 mt-4">
                                    <button type="submit" value="Add User" class="loginButton w-100">Create Account</button>
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
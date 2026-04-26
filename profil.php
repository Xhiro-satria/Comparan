<?php 
    session_start();
    require_once "server.php";
    require_once "function/user_function.php";

    $id_user = $_SESSION["id_user"];
    $user = tampilDataUser($connect, $id_user);
    $pesan   = $_GET["pesan"] ?? "";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <title>Profil</title>
    <style>
        .foto {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
        }

        .form-control, label{
            font-family: 'Belgiano', sans-serif;
            /* letter-spacing: 3px; */
        }

        /* .profile, .editProfile{
            font-family: 'Aesthetic';
        } */
    </style>
</head>
<body style="background-color: rgb(215, 231, 192);">
    <div class="containerProfil d-flex justify-content-center align-items-center" style="height: 100vh; width: 100vw;">
        <div class="card w-25 p-4 pt-1 rounded-5 shadow">
            <div class="card-body d-flex flex-column align-items-center">
                <a href="home.php" class=" w-100 text-end text-decoration-none text-black"><i class="bi bi-x-lg"></i></a>
                <h2 class="profile mb-5" style="font-family: 'Aesthetic'; color: #5d9300;">Edit Profile</h2>
                <?php if ($pesan === "berhasil"): ?>
                    <p class="mb-2" style="color: #5d9300;">Profil berhasil diupdate! <span><a href="home.php" class="fw-bold" style="color: #5d9300;">Back to home?</a></span></p>
                <?php endif; ?>
                <!-- Data Diri -->
            
                <!-- <h6 class="editProfile mb-5" style="font-family: 'Voguella', sans-serif;">Edit Profil</h6> -->
                <form method="POST" action="logic/edit_profil_logic.php" enctype="multipart/form-data">
                    <label >Change Name</label>    
                    <input type="text" class="form-control mb-4" name="nama" value="<?= $user["nama"] ?>" placeholder="Name">
                    <label >Change Username</label>    
                    <input type="text" class="form-control mb-4" name="username" value="<?= $user["username"] ?>" placeholder="Username">
                    <label >Change Password</label>    
                    <input type="password" class="form-control mb-4" name="password" placeholder="(let it blank if not change)">
                    <label >Change Profile Photo</label>    
                    <input type="file" class="form-control mb-4" name="foto">
                    <button type="submit" class="buttonCheckout btn form-control">Save Change</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
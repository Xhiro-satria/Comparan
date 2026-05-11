<?php
    // Fungsi buat tambah user baru
    function tambahUser($connect, $nama, $username, $password, $email) {

        // cek email
        $cek = "SELECT * FROM users WHERE email = ?";
        $stmt = $connect->prepare($cek);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            return "email_sudah_ada";
        }
    
        $passwordHash = password_hash($password, PASSWORD_BCRYPT);          //hashing (BCRYPT) agar tidak tersimpan dalam teks biasa

        $sql = "INSERT INTO users (nama, username, password, role, poin, email) VALUES (?, ?, ?, 'user', 0, ?)";
        $stmt = $connect->prepare($sql);
        $stmt->bind_param("ssss", $nama, $username, $passwordHash, $email);
        $stmt->execute();
        // Mengambil ID terakhir yang baru saja dibuat secara otomatis (auto-increment)
        $id_user = $connect->insert_id;
        
        //stlah buat user, langsung buat cart kosong untuk user tsb
        $sql = "INSERT INTO cart (id_user) VALUES (?)";
        $stmt = $connect->prepare($sql);
        $stmt->bind_param("i", $id_user);
        $stmt->execute();
        return $id_user;
    }

    // Fungsi buat login user
    function loginUser($connect, $username, $password) {
        $sql = "SELECT * FROM users WHERE username= ? ";
        $stmt = $connect->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        // Verifikasi password pakek password_verify
        if ($row && password_verify($password, $row['password'])) {
            return $row;
        }
        return false;
    }

    // Fungsi buat ambil data user untuk profil
    function tampilDataUser($connect, $id_user){
        $sql = "SELECT * FROM users WHERE id_user = '$id_user'";
        $result = $connect->query($sql);
        return $result->fetch_assoc();
        //fetch_all() digunakan untuk mengambil semua baris hasil query. 
        // MYSQLI_ASSOC memastikan bahwa setiap baris dikembalikan sebagai array asosiatif, di mana nama kolom digunakan sebagai kunci.
    }

    // Fungsi edit profil
    function editProfil($connect, $id_user, $nama, $username, $password, $foto_profile){
        if ($password) {
        $passwordHash = password_hash($password, PASSWORD_BCRYPT);
            if ($foto_profile) {
                $connect->query("UPDATE users SET nama = '$nama', username = '$username', password = '$passwordHash', foto_profile = '$foto_profile' WHERE id_user = '$id_user'");
            } else {
                $connect->query("UPDATE users SET nama = '$nama', username = '$username', password = '$passwordHash' WHERE id_user = '$id_user'");
            }
            } else {
                if ($foto_profile) {
                    $connect->query("UPDATE users SET nama = '$nama', username = '$username', foto_profile = '$foto_profile' WHERE id_user = '$id_user'");
                } else {
                    $connect->query("UPDATE users SET nama = '$nama', username = '$username' WHERE id_user = '$id_user'");
            }
        }
    }
?>

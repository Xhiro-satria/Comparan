<?php
    session_start();
    require_once "../server.php";
    require_once "../function/admin_function.php";

    if (!isset($_SESSION["id_user"]) || $_SESSION["role"] !== "admin") {
        header("Location: ../home.php");
        exit;
    }

    $id_user = $_GET["id_user"];

    hapusUser($connect, $id_user);

    header("Location: ../admin/users.php");
    exit;
?>
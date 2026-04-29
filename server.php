<?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "comparan";

    $connect = new mysqli($servername, $username, $password, $database);
    if (!$connect) {
        die("Connection failed: " . mysqli_connect_error());
    }
?>
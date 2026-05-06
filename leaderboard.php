<?php

require "server.php";

// $_SESSION['id_user'];

$sql = "SELECT u.username as nama, SUM(o.total_bayar) as total FROM orders o JOIN users u ON o.id_user = u.id_user GROUP BY o.id_user ORDER BY total desc";
$result = $connect->query($sql);
$leader = $result->fetch_all(MYSQLI_ASSOC);


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <center>
        <?php foreach($leader as $l) : ?>
            <p><?= $l['nama'] ?></p><br>
            <p><?= $l['total'] ?></p>
        <?php endforeach; ?>
    </center>
</body>
</html>
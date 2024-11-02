<?php
session_start();
if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="kk">
<head>
    <meta charset="UTF-8">
    <title>Басты бет</title>
</head>
<body>
    <h1>Қош келдіңіз, <?php echo $_SESSION["username"]; ?>!</h1>
    <p>Бұл - негізгі бет.</p>
    <a href="logout.php">Шығу</a>
</body>
</html>
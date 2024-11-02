<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "register1";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Қосылу қатесі: " . $conn->connect_error);
}

// Логин формасы жіберілгенін тексеру
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Пайдаланушыны табу
    $sql = "SELECT * FROM registration WHERE username='$username'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        
        // Парольді тексеру
        if (password_verify($password, $row["password"])) {
            $_SESSION["username"] = $username;
            header("Location: index.php"); // Сәтті логин болса, басты бетке бағыттау
            exit();
        } else {
            echo "Пароль дұрыс емес.";
        }
    } else {
        echo "Пайдаланушы табылмады.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="kk">
<head>
    <meta charset="UTF-8">
    <title>Кіру</title>
</head>
<body>
    <h2>Кіру формасы</h2>
    <form action="login.php" method="post">
        <label for="username">Пайдаланушы аты:</label>
        <input type="text" id="username" name="username" required><br><br>

        <label for="password">Пароль:</label>
        <input type="password" id="password" name="password" required><br><br>

        <button type="submit">Кіру</button>
    </form>
</body>
</html>
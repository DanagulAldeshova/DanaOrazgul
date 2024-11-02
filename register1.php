<?php
// Дерекқорға қосылу
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "register1";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Қосылу қатесі: " . $conn->connect_error);
}

// Тіркеу формасы жіберілгенін тексеру
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);

    // Деректерді дерекқорға енгізу
    $sql = "INSERT INTO registration (username, email, password) VALUES ('$username', '$email', '$password')";

    if ($conn->query($sql) === TRUE) {
        header("Location: login.php"); // Тіркеу сәтті болса, логин бетіне бағыттау
        exit();
    } else {
        echo "Қате: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="kk">
<head>
    <meta charset="UTF-8">
    <title>Тіркеу</title>
</head>
<body>
    <h2>Тіркеу формасы</h2>
    <form action="register1.php" method="post">
        <label for="username">Пайдаланушы аты:</label>
        <input type="text" id="username" name="username" required><br><br>

        <label for="email">Электрондық пошта:</label>
        <input type="email" id="email" name="email" required><br><br>

        <label for="password">Пароль:</label>
        <input type="password" id="password" name="password" required><br><br>

        <button type="submit">Тіркелу</button>
    </form>
</body>
</html>
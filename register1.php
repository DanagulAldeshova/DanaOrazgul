<?php
// Дерекқорға қосылу
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "register1";

$conn = new mysqli($servername, $username, $password, $dbname);

// Қосылым қатесін тексеру
if ($conn->connect_error) {
    die("Қосылу қатесі: " . $conn->connect_error);
}

// Тіркеу формасы жіберілгенін тексеру
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Пайдаланушы енгізген деректерді алу
    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $password = password_hash(trim($_POST["password"]), PASSWORD_DEFAULT);

    // Деректерді валидациялау
    if (empty($username) || empty($email) || empty($password)) {
        echo "Барлық өрістерді толтыру қажет!";
        exit();
    }

    // Электрондық пошта адресінің уникалдығын тексеру
    $stmt = $conn->prepare("SELECT COUNT(*) FROM registration WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    if ($count > 0) {
        echo "Бұл электрондық пошта адресі бұрыннан тіркелген.";
        exit();
    }

    // Дайындалған мәлімдеме жасау
    $stmt = $conn->prepare("INSERT INTO registration (username, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $email, $password);

    // Деректерді дерекқорға енгізу
    if ($stmt->execute()) {
        header("Location: login.php"); // Тіркеу сәтті болса, логин бетіне бағыттау
        exit();
    } else {
        echo "Қате: " . $stmt->error;
    }

    // Дайындалған мәлімдемені жабу
    $stmt->close();
}

$conn->close();
?>
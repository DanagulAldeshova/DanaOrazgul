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
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 350px; /* Контейнердің ені */
            margin: 50px auto;
            background-color: white;
            padding: 30px; /* Контейнердің ішкі жиегі */
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            border-radius: 8px; /* Контейнердің бұрыштарының радиусы */
        }
        h2 {
            text-align: center;
            color: #333;
            font-size: 24px; /* Заголовоктың өлшемі */
        }
        .logo {
            display: block;
            margin: 0 auto 20px;
            width: 80%;
        }
        label {
            font-weight: bold;
            margin-bottom: 8px; /* Жоғарғы жиегі */
            display: block;
            font-size: 16px; /* Мәтіннің өлшемі */
        }
        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: calc(100% - 20px);
            padding: 12px; /* Ішкі жиегі */
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px; /* Шрифт өлшемі */
        }
        button {
            width: 100%;
            padding: 12px; /* Батырманың ішкі жиегі */
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 18px; /* Батырма мәтінінің өлшемі */
            cursor: pointer;
            font-family: 'Times New Roman', Times, serif;
        }
        button:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
    <div class="container">
        <img src="https://avatars.mds.yandex.net/get-ydo/1449941/2a0000016e2dd99c2b32388338cfaf353be1/diploma" alt="Логотип" class="logo"> <!-- Логотип -->
        <h2>Біздің дүкенге қош келдіңіз!</h2>
        <h2>Тіркелу</h2>
        <form action="register1.php" method="post">
            <label for="username">Пайдаланушы аты:</label>
            <input type="text" id="username" name="username" required>

            <label for="email">Электрондық пошта:</label>
            <input type="email" id="email" name="email" required>

            <label for="password">Пароль:</label>
            <input type="password" id="password" name="password" required>

            <button type="submit">Дайын</button>
        </form>
    </div>
</body>
</html>
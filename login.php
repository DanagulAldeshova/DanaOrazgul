<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "интернет_магазин";

$conn = new mysqli($servername, $username, $password, $dbname);

// Дерекқорға қосылу қатесі
if ($conn->connect_error) {
    die("Қосылу қатесі: " . $conn->connect_error);
}

// Логин формасы жіберілгенін тексеру
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["username"]) && isset($_POST["password"])) {
        $username = $_POST["username"];
        $password = $_POST["password"];

        // Пайдаланушыны табу
        $stmt = $conn->prepare("SELECT * FROM пользователи WHERE username=?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            
            // Парольді тексеру
            if (password_verify($password, $row["password"])) {
                $_SESSION["username"] = $username;
                header("Location: index.php"); // Сәтті логин болса, басты бетке бағыттау
                exit();
            } else {
                echo "<div class='error'>Пароль дұрыс емес.</div>";
            }
        } else {
            echo "<div class='error'>Пайдаланушы табылмады.</div>";
        }
    } else {
        echo "<div class='error'>Пайдаланушы аты немесе пароль анықталмады.</div>";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="kk">
<head>
    <meta charset="UTF-8">
    <title>Кіру</title>
    <style>
    body {
        font-family: 'Times New Roman', Times, serif;
        color: black; /* Қараңғы көк мәтін */
        margin: 0;
        padding: 20px;
        text-align: center; /* Мәтінді орталау */
        }


        .logo {
            width: 250px; /* Логотиптің ені */
            height: 250px; /* Логотиптің биіктігі */
            border-radius: 50%; /* Дөңгелек форма */
            background-color: #27ae60; /* Логотиптің фоны */
            margin: 30px auto; /* Орталау үшін автоматты маржа */
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 24px;
            font-weight: bold;
        }

        form {
            background-color: #ffffff; /* Ақ фон */
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            margin: auto;
        }
        label {
            display: block;
            margin: 10px 0 5px;
        }
        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 5px 0 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            font-family: 'Times New Roman', Times, serif;
            background-color: #00008B; /* Жасыл түсті батырма */
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
        }
        button:hover {
            background-color: #00008B; /* Батырманы басқанда сәл қоюлау жасыл */
        }
        .error {
            color: red;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="logo">
    <img src="img/логотип.jpg" class="logo">
    </div> <!-- Логотип блокы -->
    
    <?php if (isset($_SESSION["username"])): ?>
        <h3>Қош келдіңіз, <?php echo htmlspecialchars($_SESSION["username"]); ?>!</h3> <!-- Пайдаланушы аты -->
    <?php endif; ?>
    <form action="login.php" method="post">
        <label for="username">Пайдаланушы аты:</label>
        <input type="text" id="username" name="username" required>

        <label for="password">Пароль:</label>
        <input type="password" id="password" name="password" required>

        <button type="submit">Кіру</button>
    </form>
</body>
</html>

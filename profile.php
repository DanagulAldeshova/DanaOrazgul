<?php
session_start();

// Сессия бойынша пайдаланушының логині бар-жоғын тексеру
if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}

// MySQL қосылымын орнату
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "интернет_магазин";

// Қосылымды тексеру
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Қосылымды орнату мүмкін болмады: " . $conn->connect_error);
}

// Сессиядан пайдаланушының логинін алу
$current_username = $_SESSION["username"];

// Пайдаланушының мәліметтерін алу
$sql = "SELECT * FROM пользователи WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $current_username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    echo "Пайдаланушы табылмады.";
    exit();
}

// Ақпаратты жаңарту
$message = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_phone = $_POST['phone'];
    $new_address = $_POST['address'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Құпиясөзді жаңарту
    if (!empty($new_password) && $new_password === $confirm_password) {
        $update_sql = "UPDATE пользователи SET password = ? WHERE username = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("ss", $new_password, $current_username);
        $update_stmt->execute();
    }

    // Телефон нөмірі мен мекенжайды жаңарту
    $update_sql = "UPDATE пользователи SET phone = ?, address = ? WHERE username = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("sss", $new_phone, $new_address, $current_username);
    $update_stmt->execute();

    // Суретті жаңарту
    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === 0) {
        $image = $_FILES['profile_image'];
        $image_path = 'img/' . basename($image['name']);

        // Суретті серверге көшіру
        if (move_uploaded_file($image['tmp_name'], $image_path)) {
            // Суретті мәліметтер базасына сақтау
            $update_sql = "UPDATE пользователи SET profile_image = ? WHERE username = ?";
            $update_stmt = $conn->prepare($update_sql);
            $update_stmt->bind_param("ss", $image_path, $current_username);
            $update_stmt->execute();
        }
    }
}

// Қосылымды жабу
$conn->close();
?>

<!DOCTYPE html>
<html lang="kk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Пайдаланушы профилі</title>
    <style>
        body {
            font-family: "Times New Roman", serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
            color: #333;
        }

        header {
            background-color: #003366;
            color: white;
            text-align: center;
            padding: 20px 0;
        }

        main {
            max-width: 600px;
            margin: 30px auto;
            background: #fff;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            padding: 20px;
        }

        .profile {
            text-align: center;
            margin-bottom: 30px;
        }

        .profile img {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            margin-bottom: 20px;
        }

        .profile p {
            font-size: 16px;
            color: #003366;
        }

        .form {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
        }

        .profile label {
            display: inline-block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #003366;
            text-align: left;
            width: 100%;
        }

        .profile input[type="text"], .profile textarea, .profile input[type="password"], .profile input[type="file"] {
            font-family: "Times New Roman", serif;
            width: 100%;
            padding: 8px;
            margin: 8px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
        }

        .profile button {
            font-family: "Times New Roman", serif;
            background-color: #003366;
            color: white;
            padding: 8px 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
        }

        .profile button:hover {
            background-color: #002244;
        }

        .message {
            font-family: "Times New Roman", serif;
            color: green;
            font-weight: bold;
            margin-top: 20px;
        }

        .error {
            color: red;
            font-weight: bold;
            margin-top: 20px;
        }

        footer {
            text-align: center;
            padding: 10px 0;
            margin-top: 20px;
            font-size: 14px;
            background-color: #f1f1f1;
            color: #555;
        }

        .button-container {
            display: flex;
            justify-content: space-between;
            width: 100%;
        }

        .button-container a, .button-container button {
            width: 48%;
            padding: 8px 16px;
            text-align: center;
            font-size: 14px;
            border-radius: 5px;
            cursor: pointer;
            color: white;
            text-decoration: none;
        }

        .button-container button {
            background-color: #003366;
            border: none;
        }

        .button-container button:hover {
            background-color: #002244;
        }

        .button-container a {
            background-color: #FF6347;
        }

        .button-container a:hover {
            background-color: #E5533D;
        }
        .btn { 
        font-family: "Times New Roman", serif;
        display: block;
    margin-top: 20px;
    font-size: 18px;
    font-weight: bold;
    color: #003366;
    text-decoration: none;
    position: fixed;
    left: 20px;
    top: 20px;
    padding: 10px 20px;
    background-color: #f1f1f1;
    border: 1px solid #003366;
    border-radius: 5px;
}
   </style>
    <form action="index.php" method="get">
    <button type="submit" class="btn">Басты бетке өту</button>
</form>
    
</head>
<body>
    <header>
        <h1>Пайдаланушы профилі</h1>
    </header>

    <main>
        <section class="profile">
            <!-- Пайдаланушының суретін көрсету -->
            <?php if ($user['profile_image']): ?>
                <img src="<?php echo $user['profile_image']; ?>" alt="Пайдаланушы суреті">
            <?php else: ?>
                <img src="default_image.jpg" alt="Пайдаланушы суреті"> <!-- Әдепкі сурет -->
            <?php endif; ?>

            <p><strong>Аты:</strong> <?php echo htmlspecialchars($user['username']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>

            <!-- Жаңарту формасы -->
            <div class="form">
                <?php if ($message): ?>
                    <p class="message"><?php echo $message; ?></p>
                <?php endif; ?>
                <form method="POST" enctype="multipart/form-data">
                    <!-- Телефон мен мекенжай -->
                    <label for="phone">Телефон нөмірі</label>
                    <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>" required>

                    <label for="address">Мекенжай</label>
                    <textarea id="address" name="address" required><?php echo htmlspecialchars($user['address']); ?></textarea>

                    <!-- Құпиясөз жаңарту -->
                    <label for="new_password">Жаңа құпиясөз</label>
                    <input type="password" id="new_password" name="new_password">

                    <label for="confirm_password">Құпиясөзді растаңыз</label>
                    <input type="password" id="confirm_password" name="confirm_password">

                    <!-- Сурет жаңарту -->
                    <label for="profile_image">Профиль суреті</label>
                    <input type="file" id="profile_image" name="profile_image" accept="image/*">

                    <div class="button-container">
                        <button type="submit">Жаңарту</button>
                        <a href="logout.php" class="logout-btn">Шығу</a>
                    </div>
                </form>
            </div>
        </section>
    </main>

    <footer>
        <p>© 2024 Онлайн Дүкен. Барлық құқықтар қорғалған.</p>
    </footer>
</body>
</html>

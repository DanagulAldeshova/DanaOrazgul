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

// Ең көп сатылатын тауарларды алу
$sql = "
    SELECT товары.товар_id, товары.название, товары.цена, товары.ссылка_на_изображение, 
           COUNT(заказы_товары.товар_id) AS total_sales
    FROM товары
    LEFT JOIN заказы_товары ON товары.товар_id = заказы_товары.товар_id
    GROUP BY товары.товар_id
    ORDER BY total_sales DESC
    LIMIT 3";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="kk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Онлайн Дүкен</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <!-- Жоғарыдағы логотип пен меню -->
    <header>
        <div class="logo">
         <img src="логотип.jpg">
        </div>
        <nav>
            <ul>
                <li><a href="index.php">Басты бет</a></li>
                <li><a href="category.php">Категориялар</a></li>
                <li><a href="cart.php">Корзина</a></li>
                <li><a href="contact.php">Байланыс</a></li>
                <li><a href="users.php">Профиль</a></li>
            </ul>
        </nav>
        <div class="user-info">
            <p>Қош келдіңіз, <?php echo $_SESSION["username"]; ?>!</p>
            <a href="logout.php">Шығу</a>
        </div>
    </header>

    <!-- Негізгі бет мазмұны -->
    <main>
        <!-- Жарнамалық сурет -->
        <section class="banner">
            <img src="афиш.jpg">
        </section>

        <!-- Көп сатылатын тауарлар -->
        <section class="top-products">
            <h2>Көп сатылатын тауарлар</h2>
            <div class="product-list">
                <?php
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<div class='product-item'>
                                <img src='" . htmlspecialchars($row["ссылка_на_изображение"]) . "' alt='" . htmlspecialchars($row["название"]) . "'>
                                <p>" . htmlspecialchars($row["название"]) . "</p>
                                <span>Бағасы: " . htmlspecialchars($row["цена"]) . " KZT</span>
                                <span>Сатылымдар саны: " . htmlspecialchars($row["total_sales"]) . "</span>
                              </div>";
                    }
                } else {
                    echo "Тауарлар табылмады.";
                }
                $conn->close();
                ?>
            </div>
        </section>
    </main>
</body>
</html>
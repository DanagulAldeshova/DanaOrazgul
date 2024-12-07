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
           COUNT(заказы_товары.товар_id) AS total_sales, товары.категория
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
    <title>Басты бет</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        /* Жалпы стильдер */
        body {
            margin: 0;
            font-family: 'Times New Roman', Times, serif;
        }
        header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background-color: #003366;
            padding: 10px 20px;
            color: white;
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1000;
        }

        .logo-container {
            display: flex;
            align-items: center;
        }

        .logo img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            margin-right: 10px;
        }

        .shop-name {
            font-size: 20px;
            font-weight: bold;
        }

        nav {
            flex-grow: 1;
        }

        nav ul {
            list-style: none;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: space-around; /* Мәзір элементтерін тең тарату */
        }

        nav ul li {
            margin: 0;
        }

        nav ul li a {
            color: white;
            text-decoration: none;
            font-weight: bold;
            font-size: 18px; /* Меню элементтерінің шрифті үлкейтілді */
            padding: 8px 15px;
            background-color: #004080; /* Сәл қоюлау көк түс */
            border-radius: 5px; /* Жұмсақ жиектер */
            transition: background-color 0.3s, transform 0.2s;
            display: flex;
            align-items: center;
        }

        nav ul li a i {
            margin-right: 8px; /* Иконка мен мәтін арасы */
        }

        nav ul li a:hover {
            background-color: #0059b3; /* Түсі өзгеру */
            transform: scale(1.05); /* Ауқымды аздап үлкейту */
        }

        main {
            margin-top: 80px;
        }

        .banner img {
            width: 100%;
            height: auto;
        }

        .top-products {
            margin-top: 40px;
            padding: 20px;
        }

        .top-products h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .product-list {
            display: flex;
            justify-content: space-between;
            gap: 20px;
        }

        .product-item {
            text-align: center;
            width: 30%;
            padding: 10px;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .product-item:hover {
            transform: translateY(-10px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
        }

        .product-item img {
            width: 100%;
            height: 200px;
            object-fit: contain;
            margin-bottom: 10px;
        }

        .product-item p {
            font-size: 1em;
            margin: 10px 0;
            line-height: 1.5;
            color: #333;
        }

        .product-item span {
            display: block;
            font-size: 0.9em;
            margin: 5px 0;
            color: #666;
        }

        .category-link {
            display: block;
            font-size: 0.9em;
            color: #003366;
            margin-top: 10px;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <header>
        <div class="logo">
            <img src="img/лого.png" alt="Логотип">
        </div>
        
        <nav>
            <ul>
                <li><a href="index.php"><i class="fas fa-home"></i> Басты бет</a></li>
                <li><a href="category.php"><i class="fas fa-th-list"></i> Категориялар</a></li>
                <li><a href="cart.php"><i class="fas fa-shopping-cart"></i> Себет</a></li>
                <li><a href="contact.php"><i class="fas fa-phone"></i> Байланыс</a></li>
                <li><a href="profile.php"><i class="fas fa-user"></i> Профиль</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <section class="banner">
            <img src="img/афишаа.jpg" alt="Жарнама">
        </section>

        <!-- Көп сатылатын тауарлар -->
        <section class="top-products">
            <h2>Көп сатылатын тауарлар</h2>
            <div class="product-list">
                <?php
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        // Категория атауын алу және оны URL үшін форматтау
                        $category_link = strtolower(str_replace(' ', '-', $row["категория"])); 

                        echo "<div class='product-item'>
                                <a href='category.php?category=" . $category_link . "'>
                                    <img src='" . htmlspecialchars($row["ссылка_на_изображение"]) . "' alt='" . htmlspecialchars($row["название"]) . "'>
                                    <p>" . htmlspecialchars($row["название"]) . "</p>
                                    <span>Бағасы: " . htmlspecialchars($row["цена"]) . " KZT</span>
                                  
                                </a>
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

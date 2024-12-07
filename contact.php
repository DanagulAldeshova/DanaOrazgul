<?php
// Сессияны бастау
session_start();

// Сессия бойынша пайдаланушының логині бар-жоғын тексеру
if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="kk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Байланыс бөлімі</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">


    <style>
    body {
        font-family: "Times New Roman", serif;
        margin: 0;
        padding: 0;
        background-color: #f7f7f7;
        color: #333;
    }

    header {
        background-color: #003366;
        color: white;
        text-align: center;
        padding: 20px 0;
    }

    main {
        max-width: 800px;
        margin: 30px auto;
        background: #ffffff;
        box-shadow: 0 4px 10px rgba(0, 40, 85, 0.3);
        border-radius: 8px;
        padding: 20px;
    }

    .contact-info h2 {
        color: #003366;
    }

    .contact-info p {
        font-size: 18px;
        margin: 10px 0;
    }

    footer {
        text-align: center;
        padding: 10px 0;
        margin-top: 20px;
        font-size: 14px;
        background-color: #f1f1f1;
        color: #555;
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
a {
        text-decoration: none;
        color: #003366;
        font-size: 18px;
        margin: 0 10px;
    }

    a i {
        margin-right: 5px;
        font-size: 24px;
    }

    a:hover {
        color: #0077b5; /* Facebook түсі */
    }

    a:active {
        color: #e1306c; /* Instagram түсі */
    }
   </style>
    <form action="index.php" method="get">
    <button type="submit" class="btn">Басты бетке өту</button>
</form>


</head>
<body>
    <header>
        <h1>Байланыс Бөлімі</h1>
    </header>

    <main>
    <section class="contact-info">
        <h2>Бізбен байланысыңыз</h2>
        <p><strong>Телефон:</strong></p>
        <p>
            <a href="https://wa.me/77084142029" target="_blank">+7 708 414 2029</a>
        </p>
        <p>
            <a href="https://wa.me/77474206804" target="_blank">+7 747 420 6804</a>
        </p>

        <p><strong>Email:</strong></p>
        <p>
            <a href="mailto:orazgul_nurmankyzy05@mail.ru">orazgul_nurmankyzy05@mail.ru</a>
        </p>
        <p>
            <a href="mailto:danaldesh@mail.ru">danagul@mail.ru</a>
        </p>

        <p><strong>Мекен-жайы:</strong></p>
        <p>Алматы қ., Абылай хан даңғылы, 45</p>
        <p><strong>Жұмыс уақыты:</strong></p>
<p>Дүйсенбі – Жұма: 09:00 – 18:00</p>
<p>Сенбі: 10:00 – 14:00</p>
<p>Жексенбі: Демалыс күні</p>

        <p><strong>Әлеуметтік желілер:</strong></p>
       
<p>
    <a href="https://www.instagram.com/0razgu1_" target="_blank">
        <i class="fab fa-instagram"></i> Instagram
    </a> |
    <a href="https://www.facebook.com/your_account" target="_blank">
        <i class="fab fa-facebook"></i> Facebook
    </a>
</p>


    </section>
</main>


    <footer>
        <p>© 2024 Онлайн Дүкен. Барлық құқықтар қорғалған.</p>
    </footer>
</body>
</html>

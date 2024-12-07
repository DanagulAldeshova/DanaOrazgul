<?php
session_start();

// Деректер базасына қосылу
$mysqli = new mysqli("localhost", "root", "", "интернет_магазин");

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Егер корзина бос болса
if (empty($_SESSION['cart'])) {
    $cart_message = "Дәл қазір себетте тауар жоқ!";
    $show_add_product_button = true;
} else {
    // Корзинадағы тауарларды шығару
    $cart_ids = implode(",", $_SESSION['cart']);
    $sql = "SELECT * FROM products WHERE id IN ($cart_ids)";
    $result = $mysqli->query($sql);
}

// Сатып алу процесі
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['checkout'])) {
    $payment_method = $_POST['payment_method'];
    if ($payment_method == 'qr') {
        $cart_message = "<div class='qr-container'>
                            <img src='https://f.nodacdn.net/408120' alt='QR код' class='qr-code'>
                            Сканерлеңіз және төлем жасаңыз.
                            <button id='qr-payment-btn' onclick='processPayment()'>OK</button>
                          </div>";
    } elseif ($payment_method == 'card') {
        $card_number = $_POST['card_number'];
        $expiry_date = $_POST['expiry_date'];
        $cvv = $_POST['cvv'];
        if (preg_match('/^\d{16}$/', $card_number) && preg_match('/^\d{2}\/\d{2}$/', $expiry_date) && preg_match('/^\d{3}$/', $cvv)) {
            $cart_message = "Төлем сәтті өтті! Рақмет!";
            $_SESSION['cart'] = []; // Корзинаны тазалау
        } else {
            $cart_message = "Қате: Карта деректері дұрыс емес.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="kk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Себет</title>
    <style>
        body {
            font-family: "Times New Roman", serif;
            background-color: #f7f7f7;
        }
        header {
            background-color: #003366;
            padding: 20px;
            text-align: center;
            color: white;
        }

        .cart-item {
            border: 1px solid #ddd;
            padding: 10px;
            margin: 10px 0;
        }

        button {
            font-family: "Times New Roman", serif;
            background-color: #28a745;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
        }

        .message {
            color: green;
            font-weight: bold;
        }

        .error {
            color: red;
            font-weight: bold;
        }

        /* QR кодты орталау үшін стильдер */
        .qr-container {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            margin-top: 20px;
        }

        .qr-code {
            width: 200px;
            height: 200px;
            margin-bottom: 10px;
        }

        /* "OK" батырмасын стилдеу */
        #qr-payment-btn {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 10px;
        }

        #qr-payment-btn:hover {
            background-color: #0056b3;
        }

        /* Төлем сәтті өткен хабарламасын көрсету үшін */
        .payment-success {
            color: green;
            font-weight: bold;
            margin-top: 20px;
        }
        
        .btn { 
            display: block;
            font-family: "Times New Roman", serif;
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
    <script>
        function updateTotal() {
            var total = 0;
            var items = document.querySelectorAll('.cart-item');
            items.forEach(function(item) {
                var price = parseFloat(item.querySelector('.price').textContent.replace('теңге', '').trim());
                var quantity = parseInt(item.querySelector('.quantity').value);
                total += price * quantity;
            });
            document.getElementById('total').textContent = total.toFixed(2) + ' теңге';
        }

        document.addEventListener('DOMContentLoaded', function() {
            var quantityInputs = document.querySelectorAll('.quantity');
            quantityInputs.forEach(function(input) {
                input.addEventListener('input', updateTotal);
            });
            updateTotal();
        });

        function processPayment() {
            const successMessage = document.createElement('p');
            successMessage.classList.add('payment-success');
            successMessage.textContent = "Төлем сәтті өтті! Рақмет!";
            const qrContainer = document.querySelector('.qr-container');
            qrContainer.appendChild(successMessage);
            document.getElementById('qr-payment-btn').style.display = 'none';
        }
    </script>
</head>
<body>
    <header>
        <h1>Себет бөлімі</h1>
    </header>

    <h1>Себеттегі тауарларыңыз</h1>

    <?php if (isset($cart_message)) echo "<p class='" . (strpos($cart_message, 'Қате') === false ? 'message' : 'error') . "'>$cart_message</p>"; ?>

    <?php
    if (isset($show_add_product_button) && $show_add_product_button) {
        echo "<a href='category.php' class='add-product-btn'>Тауар қосу</a>";
    }

    if (isset($result) && $result->num_rows > 0) {
        echo "<form method='POST' action=''>";

        $total = 0;
        while ($row = $result->fetch_assoc()) {
            $quantity = isset($_POST['quantity'][$row['id']]) ? (int)$_POST['quantity'][$row['id']] : 1;
            $total += $row['price'] * $quantity;

            echo "<div class='cart-item'>
                    <h2>" . htmlspecialchars($row['name']) . "</h2>
                    <p>Бағасы: <span class='price'>" . htmlspecialchars($row['price']) . "</span> теңге </p>
                    <p>Сипаттамасы: " . htmlspecialchars($row['description']) . "</p>
                    <label>Саны: <input type='number' name='quantity[" . $row['id'] . "]' value='$quantity' min='1' style='width: 50px;' class='quantity'></label>
                  </div>";
        }

        echo "<p>Жалпы сома: <span id='total'>" . number_format($total, 2) . "</span> теңге</p>";
        echo "<h3>Төлем әдісін таңдаңыз:</h3>
              <div>
                  <label>
                      <input type='radio' name='payment_method' value='qr' required /> QR код арқылы төлеу
                  </label><br><br>
                  <label>
                      <input type='radio' name='payment_method' value='card' required /> Карта арқылы төлеу
                  </label>
              </div>
              <div id='card-info' style='display:none;'>
                  <label>Карта нөмірі: <input type='text' name='card_number' maxlength='16'></label><br>
                  <label>Жарамдылық мерзімі (MM/YY): <input type='text' name='expiry_date' maxlength='5'></label><br>
                  <label>CVV: <input type='text' name='cvv' maxlength='3'></label>
              </div>
              <button type='submit' name='checkout'>Сатып алу</button>
              </form>";

        echo "<script>
                document.querySelectorAll('input[name=\"payment_method\"]').forEach(el => {
                    el.addEventListener('change', function() {
                        document.getElementById('card-info').style.display = this.value === 'card' ? 'block' : 'none';
                    });
                });
              </script>";
    }
    ?>

</body>
</html>

<?php
$mysqli->close();
?>

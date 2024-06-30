<?php
// Start a session
session_start();

// Retrieve cart details from the session
$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : array();
$total = 0.00;
foreach ($cart as $item) {
    $total += $item['subtotal'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout page</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
        * {
            font-family: 'Poppins', 'Roboto';
            background-color: #f9f9f9;
        }
        body {
            margin: 50px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        .payment-methods {
            margin-bottom: 20px;
        }
        .payment-methods input[type="radio"] {
            margin-right: 10px;
        }
        .payment-methods label {
            font-weight: normal;
        }
        button {
            background-color: #23255D;
            color: #fff;
            padding: 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background-color: #23255D;
        }
        .required {
            color: red;
        }
        .popup {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.4);
        }
        .popup-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #23255D;
            width: 80%;
            max-width: 300px;
            text-align: center;
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }
        .close:hover, .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
        @media (max-width: 1023px) {
            .billing-details, .order-details {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="order-details">
        <h2>Your order</h2>
        <table>
            <tr>
                <th>Product</th>
                <th>Subtotal</th>
            </tr>
            <?php
            foreach ($cart as $item) {
                echo "<tr>
                        <td>{$item['name']} × {$item['quantity']}</td>
                        <td>RM{$item['subtotal']}</td>
                      </tr>";
            }
            ?>
            <tr>
                <td>Subtotal</td>
                <td>RM<?php echo number_format($total, 2); ?></td>
            </tr>
            <tr>
                <td>Total</td>
                <td>RM<?php echo number_format($total, 2); ?></td>
            </tr>
        </table>
        <p>First renewal: <?php echo date('F d, Y', strtotime('+1 month')); ?></p>

        <div class="payment-methods">
            <input type="radio" id="cdm-transfer" name="payment-method" value="cdm-transfer" checked>
            <label for="cdm-transfer">CDM Transfer</label>
            <p>Bayaran melalui mesin CDM dan hantarkan bukti bayaran beserta no order ke whatsapp kami untuk tujuan verifikasi.<br>Maybank – 562674209696 (IS Education Network SDN BHD)</p>

            <input type="radio" id="bank-transfer" name="payment-method" value="bank-transfer">
            <label for="bank-transfer">Online Bank Transfer</label>
        </div>

        <input type="checkbox" id="terms" name="terms" required>
        <label for="terms">I have read and agree to the website terms and conditions <span class="required">*</span></label>

        <form action="process-payment.php" method="post">
            <input type="hidden" name="cart" value='<?php echo json_encode($cart); ?>'>
            <input type="hidden" name="total" value='<?php echo $total; ?>'>
            <button type="submit" id="paymentButton">BAYAR SEKARANG</button>
        </form>
    </div>

    <div id="popup" class="popup">
        <div class="popup-content">
            <span class="close">&times;</span>
            <p>Your payment is successful!</p>
        </div>
    </div>

    <script>
        document.getElementById('paymentButton').onclick = function() {
            if (!document.getElementById('terms').checked) {
                alert("Please agree to the terms and conditions before proceeding.");
                return false;
            }
        };

        document.querySelector('.close').onclick = function() {
            document.getElementById('popup').style.display = 'none';
        };

        window.onclick = function(event) {
            if (event.target == document.getElementById('popup')) {
                document.getElementById('popup').style.display = 'none';
            }
        };
    </script>
</body>
</html>

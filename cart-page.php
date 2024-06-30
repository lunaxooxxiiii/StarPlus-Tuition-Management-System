<?php
// Start a session
session_start();

// Initialize total variables
$total = 0;

// Get form type
$form = isset($_GET['form']) ? $_GET['form'] : '';

// Check if subjectCode is passed for adding
if (isset($_GET['subjectCode'])) {
    $subjectCode = $_GET['subjectCode'];

    // Database connection
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "starplus";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare SQL query to fetch subject details
    $sql = "SELECT * FROM starplus.subject WHERE SubjectCode = ?";

    // Prepare and bind parameter
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $subjectCode);

    // Execute query
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if subject found
    if ($result->num_rows > 0) {
        // Fetch subject details
        $row = $result->fetch_assoc();
        $productName = $row['SubjectName'];
        $productImage = $row['subjectImage'];
        $productPrice = $row['SubjectPrice'];

        // Store in session
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = array();
        }

        if (!isset($_SESSION['cart'][$subjectCode])) {
            $_SESSION['cart'][$subjectCode] = array(
                'name' => $productName,
                'image' => $productImage,
                'price' => $productPrice,
                'quantity' => 1,
                'subtotal' => $productPrice
            );
        } else {
            $_SESSION['cart'][$subjectCode]['quantity'] += 1;
            $_SESSION['cart'][$subjectCode]['subtotal'] += $productPrice;
        }
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
}

// Check if action is passed for updating quantity
if (isset($_GET['action']) && isset($_GET['subjectCode'])) {
    $action = $_GET['action'];
    $subjectCode = $_GET['subjectCode'];

    if ($action == 'increase') {
        $_SESSION['cart'][$subjectCode]['quantity'] += 1;
        $_SESSION['cart'][$subjectCode]['subtotal'] += $_SESSION['cart'][$subjectCode]['price'];
    } elseif ($action == 'decrease') {
        if ($_SESSION['cart'][$subjectCode]['quantity'] > 1) {
            $_SESSION['cart'][$subjectCode]['quantity'] -= 1;
            $_SESSION['cart'][$subjectCode]['subtotal'] -= $_SESSION['cart'][$subjectCode]['price'];
        }
    } elseif ($action == 'remove') {
        unset($_SESSION['cart'][$subjectCode]);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart Page</title>
    <link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            list-style: none;
            text-decoration: none;
            font-family: 'Poppins', 'Roboto';
        }

        body {
            background-color: white;
        }

        .wrapper {
            display: flex;
            position: relative;
        }
        .cart-container {
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 800px;
            margin: 0 auto;
        }

        .cart-header h2 {
            font-size: 24px;
            margin-bottom: 20px;
            border-bottom: 2px solid #23255D;
            padding-bottom: 10px;
        }

        .cart-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .cart-table th, .cart-table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        .product-details {
            display: flex;
            align-items: center;
        }

        .product-details img {
            width: 50px;
            height: 50px;
            margin-right: 10px;
        }

        .coupon-section {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }

        .coupon-section input {
            flex: 1;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-right: 10px;
        }

        .apply-coupon {
            background-color: #23255D;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }

        .cart-totals {
            margin-bottom: 20px;
        }

        .cart-totals h2 {
            font-size: 20px;
            margin-bottom: 10px;
        }

        .cart-totals table {
            width: 100%;
            border-collapse: collapse;
        }

        .cart-totals td {
            padding: 10px;
            border: 1px solid #ddd;
        }

        .proceed-checkout {
            background-color: #23255D;
            color: #fff;
            border: none;
            padding: 15px;
            width: 100%;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin-left: 35%;
        }

        .add-subject {
            text-align: center;
            margin-top: 20px;
        }

        .add-subject button {
            background-color: #23255D;
            color: #fff;
            border: none;
            padding: 15px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        .add-subject button:hover,
        .apply-coupon:hover,
        .proceed-checkout:hover {
            background-color: #23255D;
        }

        .quantity-controls {
            display: flex;
            gap: 5px;
        }

        .quantity-controls a {
            background-color: #23255D;
            color: #fff;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="cart-container">
            <div class="cart-header">
                <h2>Cart</h2>
            </div>
            <table class="cart-table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Subtotal</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (isset($_SESSION['cart'])) {
                        foreach ($_SESSION['cart'] as $subjectCode => $item) {
                            $total += $item['subtotal'];
                            echo "<tr>
                                    <td class='product-details'>
                                        <img src='{$item['image']}' alt='Product Image'>
                                        <div class='product-info'>
                                            <p>{$item['name']}</p>
                                        </div>
                                    </td>
                                    <td>RM{$item['price']}</td>
                                    <td>
                                        <div class='quantity-controls'>
                                            <a href='cart-page.php?action=decrease&subjectCode={$subjectCode}&form={$form}'>-</a>
                                            <span>{$item['quantity']}</span>
                                            <a href='cart-page.php?action=increase&subjectCode={$subjectCode}&form={$form}'>+</a>
                                        </div>
                                    </td>
                                    <td>RM{$item['subtotal']}</td>
                                    <td>
                                        <a href='cart-page.php?action=remove&subjectCode={$subjectCode}&form={$form}' class='quantity-controls'>Remove</a>
                                    </td>
                                </tr>";
                        }
                    }
                    ?>
                </tbody>
            </table>
            <div class="coupon-section">
                <input type="text" placeholder="Coupon code">
                <button class="apply-coupon">APPLY COUPON</button>
            </div>
            <div class="cart-totals">
                <h2>Cart totals</h2>
                <table>
                    <tr>
                        <td>Subtotal</td>
                        <td>RM<?php echo number_format($total, 2); ?></td>
                    </tr>
                    <tr>
                        <td>Total</td>
                        <td>RM<?php echo number_format($total, 2); ?></td>
                    </tr>
                </table>
                <br>
                <a href="checkout-page.html" class="proceed-checkout"><strong>PROCEED TO CHECKOUT</strong></a>
            </div>
            <div class="add-subject">
                <a href="subscribe-form<?php echo $form; ?>.php"><button><strong> INGIN TAMBAH SUBJEK? KLIK DISINI</strong></button></a>
            </div>
        </div>
    </div>
</body>
</html>

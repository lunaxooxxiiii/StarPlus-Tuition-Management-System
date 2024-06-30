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
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Initialize total variables
                    $total = 0;
                    $recurringTotal = 0;

                    // Check if subjectCodes are passed through URL as an array
                    if (isset($_GET['SubjectCode']) && is_array($_GET['SubjectCode'])) {
                        $subjectCodes = $_GET['SubjectCode'];

                        // Database connection details
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

                        // Prepare SQL query to fetch subject details based on subjectCode
                        $placeholders = implode(',', array_fill(0, count($subjectCodes), '?'));
                        $sql = "SELECT * FROM subject WHERE SubjectCode IN ($placeholders)";
                        
                        // Prepare and bind parameters
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param(str_repeat('s', count($subjectCodes)), ...$subjectCodes);
                        
                        // Execute query
                        $stmt->execute();
                        $result = $stmt->get_result();

                        // Loop through each result row
                        while ($row = $result->fetch_assoc()) {
                            $productName = $row['SubjectName'];
                            $productImage = $row['subjectImage'];
                            $productPrice = $row['SubjectPrice'];

                            // Calculate subtotal
                            $subtotal = $productPrice;

                            // Update total and recurring total
                            $total += $subtotal;
                            $recurringTotal += $subtotal;

                            // Output product details in table row
                            echo "<tr>
                                    <td class='product-details'>
                                        <img src='$productImage' alt='Product Image'>
                                        <div class='product-info'>
                                            <p>$productName</p>
                                        </div>
                                    </td>
                                    <td>RM$productPrice</td>
                                    <td>1</td>
                                    <td>RM$subtotal</td>
                                </tr>";
                        }

                        // Close statement and connection
                        $stmt->close();
                        $conn->close();
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
                    <tr>
                        <td>Recurring totals</td>
                        <td>RM<?php echo number_format($recurringTotal, 2); ?> / month</td>
                    </tr>
                    <tr>
                        <td>Recurring total</td>
                        <td>RM<?php echo number_format($recurringTotal, 2); ?> / month<br></td>
                    </tr>
                </table>
                <a href="checkout-page.html" class="proceed-checkout"><strong>PROCEED TO CHECKOUT</strong></a>
            </div>
            <div class="add-subject">
                <a href="subject-page.html"><button><strong> INGIN TAMBAH SUBJEK? KLIK DISINI</strong></button></a>
            </div>
        </div>
    </div>
</body>
</html>

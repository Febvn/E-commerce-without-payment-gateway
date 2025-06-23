<?php
include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:login.php');
    exit();
}

// Handle Stock Reset
if (isset($_POST['reset_stock'])) {
    mysqli_query($conn, "UPDATE products SET initial_stocks = 0, quantity = 0") or die('query failed');
    $message[] = 'Stock data has been reset!';
}

// Handle Sold Quantity Reset
if (isset($_POST['reset_sold_quantity'])) {
    mysqli_query($conn, "UPDATE products SET initial_stocks = quantity") or die('query failed');
    $message[] = 'Sold quantity has been reset!';
}

// Handle Final Stock to Initial Stock Update
if (isset($_POST['update_final_to_initial'])) {
    mysqli_query($conn, "UPDATE products SET initial_stocks = quantity") or die('query failed');
    $message[] = 'Final stock has been updated to initial stock!';
}

// Get admin information
$select_admin = mysqli_query($conn, "SELECT * FROM users WHERE id = '$admin_id'") or die('query failed');
$admin_info = mysqli_fetch_assoc($select_admin);

if (isset($_POST['update_order'])) {
    $order_update_id = $_POST['order_id'];
    $update_payment = $_POST['update_payment'];

    // Update order status
    mysqli_query($conn, "UPDATE orders SET payment_status = '$update_payment' WHERE id = '$order_update_id'") or die('query failed');

    // If order is completed, update stock
    if ($update_payment == 'completed') {
        $order_products_query = mysqli_query($conn, "SELECT product_id, quantity FROM order_items WHERE order_id = '$order_update_id'") or die('query failed');
        if (mysqli_num_rows($order_products_query) > 0) {
            while ($order_product = mysqli_fetch_assoc($order_products_query)) {
                $product_id = $order_product['product_id'];
                $sold_quantity = $order_product['quantity'];
                mysqli_query($conn, "UPDATE products SET quantity = quantity - $sold_quantity WHERE id = '$product_id'") or die('query failed');
            }
        }
    }

    $message[] = 'Payment status has been updated!';
}

if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM orders WHERE id = '$delete_id'") or die('query failed');
    header('location:admin_orders.php');
}

// Get admin information
$select_admin = mysqli_query($conn, "SELECT * FROM users WHERE id = '$admin_id'") or die('query failed');
$admin_info = mysqli_fetch_assoc($select_admin);

// Capture the current date and time
$print_date = date('Y-m-d H:i:s');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Produk Diskon</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="assets/css/admin_style.css" rel="stylesheet">
    <style>
    table {
        width: 100%;
        border-collapse: collapse;
        background-color: #f2f2f2;
        font-size: 16px; /* Increased font size for table */
    }
    th, td {
        padding: 15px; /* Increased padding for better readability */
        border: 1px solid #ddd;
        text-align: center;
    }
    th {
        background-color: #e0e0e0; /* Slightly darker background for headers */
        font-size: 18px; /* Larger font size for headers */
    }
    td {
        font-size: 16px; /* Font size for table cells */
    }
    .print-btn {
        padding: 12px 24px; /* Increased padding for buttons */
        font-size: 16px; /* Larger font size for buttons */
    }
    .total-row {
        font-weight: bold;
        font-size: 18px; /* Larger font size for totals */
    }
    .user-info {
        margin: 20px 0;
        font-size: 16px; /* Increased font size for user info */
    }
    .print-info {
        font-size: 14px; /* Increased font size for print info */
        text-align: center;
        margin: 10px 0;
    }
    @media print {
        .no-print {
            display: none;
        }
    }
    .button-container {
        display: flex;
        gap: 10px; /* Space between buttons */
        margin-bottom: 20px;
    }
    .button-container button {
        margin: 0;
        font-size: 16px; /* Larger font size for buttons in container */
    }
</style>
</head>
<body>
    <?php include 'admin_header.php'; ?>

    <section class="sales-report">
        <h1 class="title">Laporan Produk Diskon</h1>
        <div class="user-info">
            <p style="color: grey;">User ID: <?php echo $admin_info['id']; ?></p>
            <p style="color: grey;">Username: <?php echo $admin_info['name']; ?></p>
            <p style="color: grey;">Email: <?php echo $admin_info['email']; ?></p>
            <p style="color: grey;">User Type: <?php echo $admin_info['user_type']; ?></p>
            <form method="post">
                <label for="start_datetime" style="color: grey;">Tanggal dan waktu mulai:</label>
                <input type="datetime-local" id="start_datetime" name="start_datetime" required>
                
                <label for="end_datetime" style="color: grey;">Tanggal dan waktu akhir:</label>
                <input type="datetime-local" id="end_datetime" name="end_datetime" required>
            </form>
        </div>
        <button class="print-btn no-print" onclick="window.print()">Print Laporan Diskon</button>
        
        <form method="post">
            <button type="submit" name="reset_stock" class="print-btn no-print">Reset Stock Data</button>
        </form>
        <form method="post">
            <button type="submit" name="reset_sold_quantity" class="print-btn no-print">Reset Barang Terjual</button>
        </form>
        <form method="post">
            <button type="submit" name="update_final_to_initial" class="print-btn no-print">Update Stock Akhir Menjadi Stock Awal</button>
        </form>

        <table>
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Stock Awal</th>
                    <th>Stock Akhir</th>
                    <th>Terjual</th>
                    <th>Harga Modal</th>
                    <th>Harga Asli</th>
                    <th>Harga Diskon</th>
                    <th>Diskon</th>
                    <th>Persentase Diskon</th> <!-- New column for discount percentage -->
                </tr>
            </thead>
            <tbody>
            <?php
            // Fetch all discounted products from the database
            $select_products = mysqli_query($conn, "SELECT * FROM products WHERE discount_price < price") or die('query failed');
            if (mysqli_num_rows($select_products) > 0) {
                while ($fetch_products = mysqli_fetch_assoc($select_products)) {
                    $product_id = $fetch_products['id'];

                    // Get the Initial Stock from the products table
                    $initial_stock = $fetch_products['initial_stocks'];

                    // Get the current stock
                    $final_stock = $fetch_products['quantity'];

                    // Calculate Sold Quantity as Initial Stock minus Final Stock
                    $sold_quantity = $initial_stock - $final_stock;

                    $cost_price = $fetch_products['cost_price'] ?? 0;
                    $original_price = $fetch_products['price'] ?? 0;
                    $discount_price = $fetch_products['discount_price'] ?? 0;
                    $discount_amount = ($discount_price > 0) ? ($original_price - $discount_price) : 0;
                    $discount_percentage = ($original_price > 0) ? (($discount_amount / $original_price) * 100) : 0;
                    
                    $total_cost_product = $sold_quantity * $cost_price;
                    $total_revenue_product = $sold_quantity * $discount_price;
                    $profit = $total_revenue_product - $total_cost_product;
            ?>

            <tr>
                <td><img src="uploaded_img/<?php echo $fetch_products['image']; ?>" height="100" alt=""></td>
                <td><?php echo $fetch_products['name']; ?></td>
                <td><?php echo $initial_stock; ?></td>
                <td><?php echo $final_stock; ?></td>
                <td><?php echo $sold_quantity; ?></td>
                <td>Rp. <?php echo number_format($cost_price); ?></td>
                <td>Rp. <?php echo number_format($original_price); ?></td>
                <td>Rp. <?php echo number_format($discount_price); ?></td>
                <td>Rp. <?php echo number_format($discount_amount); ?></td>
                <td><?php echo number_format($discount_percentage, 2); ?>%</td> <!-- Display discount percentage -->
            </tr>
            <?php
                }
            } else {
                echo '<tr><td colspan="10" class="empty">No discounted products available</td></tr>';
            }
            ?>
            </tbody>
        </table>
        
        <!-- Print Date Info -->
        <div class="print-info">
            <p>tanggal print: <?php echo $print_date; ?></p>
        </div>
    </section>

    <script src="js/admin_script.js"></script>
</body>
</html>

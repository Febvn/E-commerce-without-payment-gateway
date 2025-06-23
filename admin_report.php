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

// Capture the current date and time
$print_date = date('Y-m-d H:i:s');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Penjualan Seven Mart</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="assets/css/admin_style.css" rel="stylesheet">
    <style>
    table {
        width: 100%;
        border-collapse: collapse;
        background-color: #f2f2f2;
        font-size: 16px;
    }
    th, td {
        padding: 15px;
        border: 1px solid #ddd;
        text-align: center;
    }
    th {
        background-color: #e0e0e0;
        font-size: 18px;
    }
    td {
        font-size: 16px;
    }
    .print-btn {
        padding: 12px 24px;
        font-size: 16px;
    }
    .total-row {
        font-weight: bold;
        font-size: 18px;
    }
    .user-info {
        margin: 20px 0;
        font-size: 16px;
    }
    .print-info {
        font-size: 14px;
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
        gap: 10px;
        margin-bottom: 20px;
    }
    .button-container button {
        margin: 0;
        font-size: 16px;
    }
</style>
</head>
<body>
    <?php include 'admin_header.php'; ?>

    <section class="sales-report">
        <h1 class="title">Laporan Penjualan</h1>
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
        <button class="print-btn no-print" onclick="window.print()">Print Laporan</button>
        <!-- Reset Stock Button -->
        <form method="post">
            <button type="submit" name="reset_stock" class="print-btn no-print">Reset Stock Data</button>
        </form>
        <!-- Reset Sold Quantity Button -->
        <form method="post">
            <button type="submit" name="reset_sold_quantity" class="print-btn no-print">Reset Barang Terjual</button>
        </form>
        <!-- Update Final Stock to Initial Stock Button -->
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
                    <th>Barang Rusak</th>
                    <th>Harga Modal</th>
                    <th>Harga Asli</th>
                    <th>Harga Diskon</th>
                    <th>Saldo Modal</th>
                    <th>Saldo Toko</th>
                    <th>Laba</th>
                    <th>Kerugian</th>
                </tr>
            </thead>
            <tbody>
    <?php
    $total_cost = 0;
    $total_revenue = 0;
    $total_profit = 0;
    $total_loss = 0;

    // Fetch all products from the database
    $select_products = mysqli_query($conn, "SELECT * FROM products") or die('query failed');
    if (mysqli_num_rows($select_products) > 0) {
        while ($fetch_products = mysqli_fetch_assoc($select_products)) {
            $product_id = $fetch_products['id'];

            $initial_stock = $fetch_products['initial_stocks'];
            $final_stock = $fetch_products['quantity'];
            $damaged_goods = $fetch_products['damaged_goods'] ?? 0;
            $cost_price = $fetch_products['cost_price'] ?? 0;
            $original_price = $fetch_products['price'] ?? 0;
            $discount_price = $fetch_products['discount_price'] ?? 0;

            // Calculate Sold Quantity as Initial Stock minus Final Stock minus Damaged Goods
            $sold_quantity = $initial_stock - $final_stock ;

            // Calculate Total Cost and Revenue
            $total_cost_product = $sold_quantity * $cost_price;
            $total_revenue_product = $sold_quantity * ($discount_price > 0 ? $discount_price : $original_price);
            $profit = $total_revenue_product - $total_cost_product;
            $loss = $damaged_goods * $cost_price;

            $total_cost += $total_cost_product;
            $total_revenue += $total_revenue_product;
            $total_profit += $profit;
            $total_loss += $loss;

            // Adjust Final Stock to include Damaged Goods
            $adjusted_final_stock = $final_stock - $damaged_goods;

            // Calculate Saldo Toko and Saldo Modal
            $saldo_toko = $sold_quantity * ($discount_price > 0 ? $discount_price : $original_price);
            $saldo_modal = $sold_quantity * $cost_price;
    ?>

    <tr>
        <td><img src="uploaded_img/<?php echo $fetch_products['image']; ?>" height="100" alt=""></td>
        <td><?php echo $fetch_products['name']; ?></td>
        <td><?php echo $initial_stock; ?></td>
        <td><?php echo $adjusted_final_stock; ?></td>
        <td><?php echo $sold_quantity; ?></td>
        <td><?php echo $damaged_goods; ?></td>
        <td>Rp. <?php echo number_format($cost_price); ?></td>
        <td>Rp. <?php echo number_format($original_price); ?></td>
        <td>Rp. <?php echo number_format($discount_price); ?></td>
        <td>Rp. <?php echo number_format($saldo_modal); ?></td>
        <td>Rp. <?php echo number_format($saldo_toko); ?></td>
        <td>Rp. <?php echo number_format($profit); ?></td>
        <td>Rp. <?php echo number_format($loss); ?></td>
    </tr>
    <?php
        }
    } else {
        echo '<tr><td colspan="13" class="empty">No products available</td></tr>';
    }
    ?>
    </tbody>
    <tfoot>
        <?php
        $net_profit = $total_profit - $total_loss;
        ?>
        <tr class="total-row">
            <td colspan="9">Total</td>
            <td>Rp. <?php echo number_format($total_cost); ?></td>
            <td>Rp. <?php echo number_format($total_revenue); ?></td>
            <td>Rp. <?php echo number_format($total_profit); ?>  <br>__________ <br> (Laba Bersih Rp.) <?php echo number_format($net_profit); ?></td>
            <td>Rp. <?php echo number_format($total_loss); ?></td>
        </tr>
    </tfoot>
</table>
    
<!-- Print Date Info -->
<div class="print-info">
    <p>Tanggal Print: <?php echo $print_date; ?></p>
</div>
</section>

<script src="js/admin_script.js"></script>
</body>
</html>

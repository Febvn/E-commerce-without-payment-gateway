<?php
include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:login.php');
    exit();
}

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
                // Update the product quantity in the `products` table
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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Report</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="assets/css/admin_style.css" rel="stylesheet">
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #f2f2f2;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
        }
        .print-btn {
            margin: 20px 0;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            cursor: pointer;
        }
        .print-btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <?php include 'admin_header.php'; ?>

    <section class="sales-report">
        <h1 class="title">Sales Report</h1>
        <button class="print-btn" onclick="window.print()">Print Report</button>
        <table>
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Product Name</th>
                    <th>Initial Stock</th>
                    <th>Final Stock</th>
                    <th>Sold Quantity</th>
                    <th>Cost Price</th>
                    <th>Total Cost</th>
                    <th>Selling Price</th>
                    <th>Total Revenue</th>
                    <th>Profit</th>
                </tr>
            </thead>
          
            <tbody>
            <?php
// Ambil semua produk dari database
$select_products = mysqli_query($conn, "SELECT * FROM products") or die('query failed');
if (mysqli_num_rows($select_products) > 0) {
    while ($fetch_products = mysqli_fetch_assoc($select_products)) {
        $product_id = $fetch_products['id'];


        
        // Simpan nilai Initial Stock dari kolom initial_stock di tabel products
        $initial_stock = $fetch_products['initial_stocks'];

        // Dapatkan nilai stok saat ini
        $final_stock = $fetch_products['quantity'];

        // Hitung Sold Quantity dari Initial Stock dikurangi Final Stock
        $sold_quantity = $initial_stock - $final_stock;

        $cost_price = $fetch_products['cost_price'] ?? 0;
        $selling_price = $fetch_products['price'] ?? 0;
        $total_cost = $sold_quantity * $cost_price;
        $total_revenue = $sold_quantity * $selling_price;
        $profit = $total_revenue - $total_cost;
?>

<tr>
    <td><img src="uploaded_img/<?php echo $fetch_products['image']; ?>" height="100" alt=""></td>
    <td><?php echo $fetch_products['name']; ?></td>
    <td><?php echo $initial_stock; ?></td>
    <td><?php echo $final_stock; ?></td>
    <td><?php echo $sold_quantity; ?></td>
    <td>Rp. <?php echo number_format($cost_price); ?></td>
    <td>Rp. <?php echo number_format($total_cost); ?></td>
    <td>Rp. <?php echo number_format($selling_price); ?></td>
    <td>Rp. <?php echo number_format($total_revenue); ?></td>
    <td>Rp. <?php echo number_format($profit); ?></td>
</tr>
<?php
    }
} else {
    echo '<tr><td colspan="10" class="empty">No products available</td></tr>';
}
?>


</tbody>

        </table>
    </section>

    <script src="js/admin_script.js"></script>
</body>
</html>

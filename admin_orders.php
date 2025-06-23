<?php

include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:login.php');
}

if(isset($_POST['update_order'])){

   $order_update_id = $_POST['order_id'];
   $update_payment = $_POST['update_payment'];

   // Update order status
   mysqli_query($conn, "UPDATE orders SET payment_status = '$update_payment' WHERE id = '$order_update_id'") or die('query failed');

   // If order is completed, update stock
   if($update_payment == 'completed'){
      $order_products_query = mysqli_query($conn, "SELECT product_id, quantity FROM order_items WHERE order_id = '$order_update_id'") or die('query failed');
      if(mysqli_num_rows($order_products_query) > 0){
         while($order_product = mysqli_fetch_assoc($order_products_query)){
            $product_id = $order_product['product_id'];
            $sold_quantity = $order_product['quantity'];
            mysqli_query($conn, "UPDATE products SET quantity = quantity - $sold_quantity WHERE id = '$product_id'") or die('query failed');
         }
      }
   }

   $message[] = 'Payment status has been updated!';
}

if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];
   mysqli_query($conn, "DELETE FROM orders WHERE id = '$delete_id'") or die('query failed');
   header('location:admin_orders.php');
}

if(isset($_POST['delete_all'])){
   mysqli_query($conn, "DELETE FROM orders") or die('query failed');
   header('location:admin_orders.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Orders</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom admin css file link  -->
   <link href="assets/css/admin_style.css" rel="stylesheet">

   <style>
   @media print {
   body * {
      visibility: hidden;
   }
   .orders, .orders * {
      visibility: visible;
   }
   .orders {
      position: static; /* Ensure proper positioning */
      width: 102%;
      margin: 0; /* Reset margins to avoid extra space */
   }
   .box {
      page-break-inside: avoid; /* Prevent page breaks inside boxes */
      page-break-before: always; /* Ensure each box starts on a new page */
      padding: 20px;
      border: 1px solid #ccc;
      margin-bottom: 0; /* Remove bottom margin */
      position: relative;
   }
   .box:first-of-type {
      page-break-before: auto; /* Allow the first box to avoid extra page breaks */
   }
   .box:last-of-type {
      page-break-after: avoid; /* Prevent extra page break after the last box */
   }
   .delete-btn, .option-btn, .delete-all-btn, .print-btn {
      display: none; /* Hide these buttons during printing */
   }
   /* Optional: Add styles for a receipt-like appearance */
   .box p {
      margin: 0;
      padding: 5px 0;
      border-bottom: 1px dashed #000; /* Dashed lines between sections */
   }
   .box p:last-of-type {
      border-bottom: none; /* Remove border from the last item */
   }
   /* Print-specific image styles */
   .box img {
      max-width: 60px; /* Ensure the image fits within the box */
      height: 60px; /* Maintain aspect ratio */
      display: block;
      margin: 0px 0; /* Add margin around the image */
      page-break-inside: avoid; /* Prevent page breaks inside images */
   }
}

</style>

</head>
<body>
   
   <?php include 'admin_header.php'; ?>

   <section class="orders">

     

      <form action="" method="post">
      <button onclick="window.print();" class="print-btn">Print Pesanan Terjual</button>
   <input type="submit" name="delete_all" value="Delete All Pesanan" class="delete-all-btn" onclick="return confirm('Are you sure you want to delete all orders?');">
      </form>

    

      <div class="box-container">
         <?php
         $select_orders = mysqli_query($conn, "SELECT * FROM orders") or die('query failed');
         if(mysqli_num_rows($select_orders) > 0){
            while($fetch_orders = mysqli_fetch_assoc($select_orders)){
         ?>
         <div class="box">
            <p> User ID : <span><?php echo $fetch_orders['user_id']; ?></span> </p>
            <p> Pada Tanggal : <span><?php echo $fetch_orders['placed_on']; ?></span> </p>
            <p> Nama : <span><?php echo $fetch_orders['name']; ?></span> </p>
            <p> No. : <span><?php echo $fetch_orders['number']; ?></span> </p>
            <p> Email : <span><?php echo $fetch_orders['email']; ?></span> </p>
            <p> Alamat : <span><?php echo $fetch_orders['address']; ?></span> </p>
            <p> Barang: <span><?php echo $fetch_orders['total_products']; ?></span> </p>
            <p> Total Harga: <span>Rp. <?php echo $fetch_orders['total_price']; ?> </span> </p>
            <p> Total Harga setelah Diskon: <span>Rp. <?php echo number_format($fetch_orders['grand_totals'],0, ',', '.'); ?>
            <p> Metode Bayar : <span><?php echo $fetch_orders['method']; ?></span> </p>
            <p> Bukti Pembayaran :
               <?php
               if (!empty($fetch_orders['bukti_pembayaran'])) {
                  echo '<br><img src="' . $fetch_orders['bukti_pembayaran'] . '" alt="Bukti Pembayaran" onclick="expandImage(\'' . $fetch_orders['bukti_pembayaran'] . '\')">';
               } else {
                  echo 'Tidak Ada Bukti Pembayaran';
               }
               ?>
            </p>
            <form action="" method="post">
               <input type="hidden" name="order_id" value="<?php echo $fetch_orders['id']; ?>">
               <select name="update_payment">
                  <option value="" selected disabled><?php echo $fetch_orders['payment_status']; ?></option>
                  <option value="pending">Pending</option>
                  <option value="completed">Completed</option>
               </select>
               <input type="submit" value="Update" name="update_order" class="option-btn">
               <a href="admin_orders.php?delete=<?php echo $fetch_orders['id']; ?>" onclick="return confirm('Delete this order?');" class="delete-btn">Delete</a>
            </form>
         </div>
         <?php
            }
         } else {
            echo '<p class="empty">No orders placed yet!</p>';
         }
         ?>
      </div>

   </section>

   <!-- custom admin js file link  -->
   <script src="js/admin_script.js"></script>

</body>
</html>

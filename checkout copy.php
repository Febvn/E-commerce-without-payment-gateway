<?php
include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
    header('location: login.php');
    exit;
}

$message = array();

if (isset($_POST['order_btn'])) {
    // Ambil data dari form dan lakukan validasi
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $number = mysqli_real_escape_string($conn, $_POST['number']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $method = mysqli_real_escape_string($conn, $_POST['method']);
    $street = mysqli_real_escape_string($conn, $_POST['street']);
    $city = mysqli_real_escape_string($conn, $_POST['city']);
    $state = mysqli_real_escape_string($conn, $_POST['state']);
    
    // Tentukan direktori penyimpanan gambar
    $upload_dir = 'uploaded_img';
    $bukti_pembayaran_path = '';

    // Proses upload gambar jika ada yang diunggah
    if ($_FILES['bukti_pembayaran']['error'] === UPLOAD_ERR_OK) {
        $bukti_pembayaran_tmp = $_FILES['bukti_pembayaran']['tmp_name'];
        $bukti_pembayaran_name = $_FILES['bukti_pembayaran']['name'];
        $bukti_pembayaran_path = $upload_dir . '/' . $bukti_pembayaran_name;

        // Pindahkan file ke direktori upload
        if (move_uploaded_file($bukti_pembayaran_tmp, $bukti_pembayaran_path)) {
            // Hitung total belanja dari keranjang
            $cart_total = 0;
            $cart_products = array();
            $order_items = array(); // To track the quantities for stock update

            $cart_query = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('Query failed');
            if (mysqli_num_rows($cart_query) > 0) {
                while ($cart_item = mysqli_fetch_assoc($cart_query)) {
                    $cart_products[] = $cart_item['name'] . ' (' . $cart_item['quantity'] . ') ';
                    $sub_total = ($cart_item['price'] * $cart_item['quantity']);
                    $cart_total += $sub_total;

                    // Track item quantities
                    $order_items[] = array(
                        'name' => $cart_item['name'],
                        'quantity' => $cart_item['quantity']
                    );
                }
            }

            // Gabungkan produk dalam keranjang menjadi satu string
            $total_products = implode(', ', $cart_products);

    // Calculate total after discount
    $grand_totals = 0;

    $select_cart = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('Query failed');
    if (mysqli_num_rows($select_cart) > 0) {
        while ($fetch_cart = mysqli_fetch_assoc($select_cart)) {
            $product_name = $fetch_cart['name'];
            $select_products = mysqli_query($conn, "SELECT * FROM `products` WHERE name = '$product_name'") or die('Query failed');
            $fetch_products = mysqli_fetch_assoc($select_products);

            // Determine the price to display
            $prices = $fetch_products['discount_price'] > 0 ? $fetch_products['discount_price'] : $fetch_cart['price'];
            $sub_totals = $prices * $fetch_cart['quantity'];
            $grand_totals += $sub_totals;
        }
    }

           // Save order to the database
$placed_on = date('Y-m-d H:i:s');
mysqli_query($conn, "INSERT INTO `orders` (user_id, name, number, email, method, address, total_products, total_price, grand_totals, placed_on, bukti_pembayaran) VALUES ('$user_id', '$name', '$number', '$email', '$method', '$street, $city ,$state', '$total_products', '$grand_totals', '$grand_totals', '$placed_on', '$bukti_pembayaran_path')") or die('Query failed');

            
            // Hapus produk dari keranjang dan update stok
            foreach ($order_items as $item) {
                // Update the product quantity in the `products` table
                $product_name = $item['name'];
                $ordered_quantity = $item['quantity'];
                mysqli_query($conn, "UPDATE `products` SET quantity = quantity - $ordered_quantity WHERE name = '$product_name'") or die('Query failed');
            }

            mysqli_query($conn, "DELETE FROM `cart` WHERE user_id = '$user_id'") or die('Query failed');
            
            $message[] = 'Order placed successfully!';
        } else {
            $message[] = 'Failed to upload proof of payment.';
        }
    } else {
        $message[] = 'Error uploading file.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>checkout</title>
<!-- Favicons -->
<link href="assets/img/market1.png" rel="icon">
    <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">


   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">


<link href="assets/css/display.css" rel="stylesheet">
<!-- Main CSS File -->
<link href="assets/css/cek.css" rel="stylesheet">
   <!-- Main CSS File -->
<link href="assets/css/main.css" rel="stylesheet">
    <!-- Main CSS File -->
<link href="assets/css/check.css" rel="stylesheet">
      <!-- Main CSS File -->
<link href="assets/css/but.css" rel="stylesheet">


 
</head>
<body>
   
<?php include 'header.php'; ?>

<div class="heading">
   <h3>checkout</h3>
   <p> <a href="index.php">home</a> / checkout </p>
</div>

 
<section class="display-order">
<?php  
        $grand_total = 0;
        $grand_totals = 0;
        $select_cart = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
        if(mysqli_num_rows($select_cart) > 0){
            while($fetch_cart = mysqli_fetch_assoc($select_cart)){
                $total_price = ($fetch_cart['price'] * $fetch_cart['quantity']);
                $grand_total += $total_price;

                // Fetch product details and apply discount
                $product_name = $fetch_cart['name'];
                $select_products = mysqli_query($conn, "SELECT * FROM `products` WHERE name = '$product_name'") or die('query failed');
                $fetch_products = mysqli_fetch_assoc($select_products);

                // Determine the price to display
                $prices = $fetch_products['discount_price'] > 0 ? $fetch_products['discount_price'] : $fetch_cart['price'];
                $total_prices = ($fetch_products['discount_price'] * $fetch_cart['quantity']);
                $sub_totals = $prices * $fetch_cart['quantity'];
                $grand_totals += $sub_totals;
    ?>
    <div class="cart-item">
        <div class="cart-item-image">
            <img src="uploaded_img/<?php echo $fetch_cart['image']; ?>" alt="<?php echo $fetch_cart['name']; ?>">
        </div>
        <div class="cart-item-details">
            <p><?php echo $fetch_cart['name']; ?> <span>(<?php echo 'Rp. '.number_format($fetch_cart['price'], 0, ',', '.').' x '. $fetch_cart['quantity']; ?>)</span></p>
            <p>Total: <span>Rp. <?php echo number_format($total_price, 0, ',', '.'); ?></span></p>
            <p>Total setelah diskon: <span>Rp. <?php echo number_format($total_prices, 0, ',', '.'); ?></span></p>
        </div>
    </div>
    </div>
    <?php
            }
        } else {
            echo '<p class="empty">Your cart is empty</p>';
        }
    
    ?>
   <div class="grand-total">Total semua: <span>Rp. <?php echo number_format($grand_total, 0, ',', '.'); ?></span></div>
   <div class="grand-total">Total final setelah diskon: <span>Rp. <?php echo number_format($grand_totals, 0, ',', '.'); ?></span></div>
</section>

<section class="checkout">
   <form action="" method="post" enctype="multipart/form-data">
      <h3>Place Your Order</h3>
      <div class="flex">
         <div class="inputBox">
            <span>Your Name:</span>
            <input type="text" name="name" required placeholder="Enter your name">
         </div>
         <div class="inputBox">
            <span>Your Number:</span>
            <input type="text" name="number" required placeholder="Enter your number">
         </div>
         <div class="inputBox">
            <span>Your Email:</span>
            <input type="email" name="email" required placeholder="Enter your email">
         </div>
         <div class="inputBox">
            <span>Payment Method:</span>
            <select name="method">
               <option value="cash on delivery">Cash on Delivery</option>
               <option value="credit card">Credit Card</option>
               <option value="DANA">DANA</option>
               <option value="OVO">OVO</option>
            </select>
         </div>
         <div class="inputBox">
            <span>Alamat:</span>
            <input type="text" name="street" required placeholder="">
         </div>
      
         <div class="inputBox">
            <span>Kota:</span>
            <input type="text" name="city" required placeholder="Bandar Lampung">
         </div>
         <div class="inputBox">
            <span>Pulau:</span>
            <input type="text" name="state" required placeholder="Sumatera">
         </div>
         <div class="inputBox">
            <span>Proof of Payment (Image):</span>
            <input type="file" name="bukti_pembayaran" accept="image/*" required>
         </div>
      </div>
      <div class="d-grid mb-3">
         <input type="submit" value="Order Now" class="btn-order" name="order_btn">
      </div>
   </form>
</section>


<?php include 'footer.php'; ?>

<!-- custom js file link  -->
<script src="js/script.js"></script>

</body>
</html>

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
   
    // Tentukan direktori penyimpanan gambar
    $upload_dir = 'uploaded_img';
    $bukti_pembayaran_path = '';



       
            $cart_total = 0;
            $cart_products = array();
            $order_items = array(); // To track the quantities for stock update

            $cart_query = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('Query failed');
            if (mysqli_num_rows($cart_query) > 0) {
                while ($cart_item = mysqli_fetch_assoc($cart_query)) {
                    // Gunakan harga diskon jika ada, jika tidak gunakan harga asli
                    $price = ($cart_item['discount_price'] > 0) ? $cart_item['discount_price'] : $cart_item['price'];
                    $sub_total = ($price * $cart_item['quantity']);
                    $cart_total += $sub_total;

                    // Track item quantities
                    $order_items[] = array(
                        'name' => $cart_item['name'],
                        'quantity' => $cart_item['quantity'],
                        'price' => $price
                    );
                }
            }

            // Gabungkan produk dalam keranjang menjadi satu string
            $total_products = implode(', ', array_column($order_items, 'name','quantity'));
  // Simpan pesanan ke database
  $placed_on = date('Y-m-d H:i:s');
  mysqli_query($conn, "INSERT INTO `orders` (user_id, name, number, email, method, address, total_products, total_price, placed_on, bukti_pembayaran) VALUES ('$user_id', '$name', '$number', '$email', '$method', ' , ', '$total_products', '$cart_total', '$placed_on', '$bukti_pembayaran_path')") or die('Query failed');
  
            
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
          
        }
    

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Checkout</title>
   <!-- Favicons -->
   <link href="assets/img/market1.png" rel="icon">
   <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

   <!-- Font Awesome CDN Link -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- CSS Files -->
   <link href="assets/css/display.css" rel="stylesheet">
   <link href="assets/css/cek.css" rel="stylesheet">
   <link href="assets/css/main.css" rel="stylesheet">
   <link href="assets/css/check.css" rel="stylesheet">
   <link href="assets/css/but.css" rel="stylesheet">
</head>
<body>
   
<?php include 'header.php'; ?>

<div class="heading">
   <h3>Checkout</h3>
   <p><a href="index.php">Home</a> / Checkout</p>
</div>
<?php
// Format price to remove decimal places and use comma as decimal point
function formatPrice($price) {
    return number_format($price, 0, ',', '.');
}
?>

<!-- Inside the display-order section -->
<section class="display-order">
<?php
   $grand_total = 0;

   // Fetch cart items for the current user
   $select_cart = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
   if (mysqli_num_rows($select_cart) > 0) {
       while ($fetch_cart = mysqli_fetch_assoc($select_cart)) {
           // Fetch the product details from the `products` table based on the cart item's `name`
           $product_name = $fetch_cart['name'];
           $select_product = mysqli_query($conn, "SELECT * FROM `products` WHERE name = '$product_name'") or die('query failed');
           $fetch_product = mysqli_fetch_assoc($select_product);

           // Determine the price to display
           $price = $fetch_product['discount_price'] > 0 ? $fetch_product['discount_price'] : $fetch_cart['price'];
           $original_price = $fetch_cart['price'];

           // Calculate total price for this cart item
           $sub_total = $price * $fetch_cart['quantity'];
           $grand_total += $sub_total;
?>
    <div class="cart-item">
        <div class="cart-item-image">
            <img src="uploaded_img/<?php echo $fetch_cart['image']; ?>" alt="<?php echo $fetch_cart['name']; ?>">
        </div>
        <div class="cart-item-details">
            <p><?php echo $fetch_cart['name']; ?> <span>(<?php echo 'Rp. '.formatPrice($price).' x '. $fetch_cart['quantity']; ?>)</span></p>
            <p>Total: <span>Rp. <?php echo formatPrice($sub_total); ?> </span></p>
        </div>
    </div>
    <?php
           }
       } else {
           echo '<p class="empty">Your cart is empty</p>';
       }
    ?>
    <div class="grand-total">Grand Total: <span>Rp. <?php echo formatPrice($grand_total); ?> </span></div>
</section>


<section class="checkout">
   <form action="" method="post" enctype="multipart/form-data">
      <h3>Place Your Order</h3>
      <div class="flex">
         <div class="inputBox">
        
            <span>Nama Kamu:</span>
            <input type="text" name="name" required placeholder="Masukan Nama Kamu">
         </div>
         <div class="inputBox">
            <span>No. Kamu:</span>
            <input type="text" name="number" required placeholder="Masukan No. Kamu">
         </div>
         <div class="inputBox">
            <span>Email kamu:</span>
            <input type="email" name="email" required placeholder="Masukan Email Kamu">
         </div>
         <div class="inputBox">
            <span>Metode Pembyaran:</span>
            <select name="method">
               <option value="Bayar di Kasir (offline)">offline</option>
            </select>
         </div>
      </div>
      <div class="d-grid mb-3">
         <input type="submit" value="Order Now" class="btn-order" name="order_btn">
      </div>
   </form>
</section>

<?php include 'footer.php'; ?>

<!-- Custom JS File Link -->
<script src="js/script.js"></script>

</body>
</html>

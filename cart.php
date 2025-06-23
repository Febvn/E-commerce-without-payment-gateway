<?php

include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
    header('location:login.php');
}

if (isset($_POST['update_cart'])) {
    $cart_id = $_POST['cart_id'];
    $cart_quantity = $_POST['cart_quantity'];
    mysqli_query($conn, "UPDATE `cart` SET quantity = '$cart_quantity' WHERE id = '$cart_id'") or die('query failed');
    $message[] = 'Cart quantity updated!';
}

if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM `cart` WHERE id = '$delete_id'") or die('query failed');
    header('location:cart.php');
}

if (isset($_GET['delete_all'])) {
    mysqli_query($conn, "DELETE FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
    header('location:cart.php');
}
if (isset($_POST['add_to_cart'])) {
  $product_name = $_POST['product_name'];
  $product_price = $_POST['product_price'];
  $product_image = $_POST['product_image'];
  $product_quantity = $_POST['product_quantity'];

  // Fetch discount price from products table
  $select_product = mysqli_query($conn, "SELECT discount_price FROM `products` WHERE name = '$product_name'") or die('query failed');
  $fetch_product = mysqli_fetch_assoc($select_product);
  $discount_price = $fetch_product['discount_price'];

  $check_cart_numbers = mysqli_query($conn, "SELECT * FROM `cart` WHERE name = '$product_name' AND user_id = '$user_id'") or die('query failed');

  if (mysqli_num_rows($check_cart_numbers) > 0) {
      $message[] = 'already added to cart!';
  } else {
      mysqli_query($conn, "INSERT INTO `cart`(user_id, name, price, discount_price, quantity, image) VALUES('$user_id', '$product_name', '$product_price', '$discount_price', '$product_quantity', '$product_image')") or die('query failed');
      $message[] = 'product added to cart!';
  }
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Cart</title>
   <meta content="" name="description">
   <meta content="" name="keywords">

   <!-- Favicons -->
   <link href="assets/img/market1.png" rel="icon">
   <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

   <!-- Fonts -->
   <link href="https://fonts.googleapis.com" rel="preconnect">
   <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
   <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900&family=Inter:wght@100;200;300;400;500;600;700;800;900&family=Cardo:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet">

   <!-- Vendor CSS Files -->
   <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
   <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
   <link href="assets/vendor/aos/aos.css" rel="stylesheet">
   <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
   <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">
   <!-- Font Awesome CDN link -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- Custom CSS file link -->
   <link href="assets/css/cart.css" rel="stylesheet">
   <!-- Main CSS File -->
   <link href="assets/css/asep.css" rel="stylesheet">
   <!-- Main CSS File -->
   <link href="assets/css/nav.css" rel="stylesheet">
    
   <style>
       .user-box {
           display: none;
           background-color: var(--background-color);
           color: var(--default-color);
           position: absolute;
           top: 60px;
           right: 20px;
           border: 1px solid var(--accent-color);
           padding: 30px;
           justify-content: space-between;
           border-radius: 5px;
           z-index: 1000;
           box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
           width: 200px;
       }

       .user-box p {
           margin: 0 0 10px 0;
           padding: 0;
           font-size: 14px;
       }

       .user-box a {
           display: inline-block;
           margin-top: 10px;
           color: var(--default-color);
           background-color: var(--accent-color);
           padding: 5px 10px;
           border-radius: 3px;
           text-decoration: none;
           transition: background-color 0.3s;
       }

       .user-box a:hover {
           background-color: color-mix(in srgb, var(--accent-color) 90%, white 10%);
       }
       .price {
           font-size: 16px;
           color: #333;
       }

       .original-price {
           text-decoration: line-through;
           color: #888;
           margin-right: 10px;
       }

       .discount-percentage {
           color: #f00;
           font-weight: bold;
       }

       .product-image {
           position: relative;
           display: inline-block;
       }

       .discount-label {
           position: absolute;
           top: 10px;
           left: 10px;
           background-color: rgba(167, 39, 39, 0.8); /* Red background with 80% opacity */
           color: #fff;
           padding: 5px 10px;
           border-radius: 10px;
           font-weight: bold;
           font-size: 14px;
           z-index: 10;
       }
   </style>

</head>
<body>

<header id="header" class="header d-flex align-items-center sticky-top">
   <div class="container-fluid position-relative d-flex align-items-center justify-content-between">

      <a href="index.html" class="logo d-flex align-items-center me-auto me-xl-0">
         <i class="bi bi-shop"></i>
         <h1 class="sitename">SEVEN</h1>
      </a>

      <nav id="navmenu" class="navmenu">
         <ul>
            <li><a href="index.php" class="">Home</a></li>
            <li><a href="about.php">About</a></li>
            <li><a href="shop.php">shop</a></li>
            <li><a href="orders.php"><span>oders</span></i></a></li>
            <li><a href="contact.php">Contact</a></li>
         </ul>
         <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
      </nav>

      <div class="header-social-links">

         <a href="search_page.php"><i class="bi bi-search"></i></a>
         <a href="#" id="user-icon"><i class="bi bi-person-circle"></i></a>
         <?php
            $select_cart_number = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
            $cart_rows_number = mysqli_num_rows($select_cart_number); 
         ?>
         <a href="cart.php" class="active"><i class="bi bi-cart"></i><span>(<?php echo $cart_rows_number; ?>)</span></a>
         <a href="login.php"><p>LOGIN</p></a>| <a href="register.php"><p>REGISTER</p></a>
     
         <div class="user-box" id="user-box">
               <p>username : <span><?php echo $_SESSION['user_name']; ?></span></p>
               <p>email : <span><?php echo $_SESSION['user_email']; ?></span></p>
               <a href="logout.php" class="delete-btn">logout</a>
         </div>

      </div>
   </div>
</header>

<div class="heading">
   <h3>Shopping Cart</h3>
   <p><a href="index.php">Home</a> / Cart</p>
</div>

<section class="shopping-cart">

   <h1 class="title">Products Added</h1>

   <div class="box-container">
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
   <div class="box">
      <a href="cart.php?delete=<?php echo $fetch_cart['id']; ?>" class="fas fa-times" onclick="return confirm('delete this from cart?');"></a>
      <div class="product-image">
         <img src="uploaded_img/<?php echo $fetch_cart['image']; ?>" alt="" class="image">
         <?php if ($fetch_product['discount_price'] > 0): ?>
         <div class="discount-label">
            <?php 
            $discount_percentage = 100 - (($fetch_product['discount_price'] / $fetch_cart['price']) * 100);
            echo ($discount_percentage) . '% ';
            ?>
         </div>
         <?php endif; ?>
      </div>
      <div class="name"><?php echo $fetch_cart['name']; ?></div>
      <div class="price">
         <?php if ($fetch_product['discount_price'] > 0): ?>
            <span class="original-price">Rp. <?php echo number_format($original_price, 0, ',', '.'); ?></span>
            Rp. <?php echo number_format($price, 0, ',', '.'); ?>
         <?php else: ?>
            Rp. <?php echo number_format($price, 0, ',', '.'); ?>
         <?php endif; ?>
      </div>
      <form action="" method="post">
         <input type="hidden" name="cart_id" value="<?php echo $fetch_cart['id']; ?>">
         <input type="number" min="1" name="cart_quantity" value="<?php echo $fetch_cart['quantity']; ?>">
         <input type="submit" name="update_cart" value="Update" class="option-btn">
      </form>
      <div class="sub-total"> Sub Total : <span>Rp. <?php echo number_format($sub_total, 0, ',', '.'); ?></span> </div>
   </div>
   <?php
       }
   } else {
       echo '<p class="empty">Keranjang Kamu Kosong</p>';
   }
   ?>
   </div>

   <div class="cart-total">
      <p>Grand Total : <span>Rp. <?php echo number_format($grand_total, 0, ',', '.'); ?></span></p>
      <div class="flex">
         <a href="shop.php" class="option-btn">Lanjut Belanja</a>
         <a href="cart.php?delete_all" class="delete-btn" onclick="return confirm('delete all from cart?');">Delete All</a>
         <a href="checkout.php" class="btn <?php echo ($grand_total > 1) ? '' : 'disabled'; ?>">Lanjut Ke Checkout (online)</a>
        
        <a href="checkoutoffline.php" class="btn <?php echo ($grand_total > 1) ? '' : 'disabled'; ?>">Lanjut Ke Checkout (offline)</a>
    
      </div>
   </div>

</section>

<script>
   document.getElementById("user-icon").addEventListener("click", function() {
       var userBox = document.getElementById("user-box");
       userBox.style.display = (userBox.style.display === "none" || userBox.style.display === "") ? "block" : "none";
   });
</script>

</body>
</html>

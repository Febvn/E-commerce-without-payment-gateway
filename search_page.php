<?php
include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
   header('location:login.php');
  
}



if(isset($_POST['add_to_cart'])){

   $product_name = $_POST['product_name'];
   $product_price = $_POST['product_price'];
   $product_image = $_POST['product_image'];
   $product_quantity = $_POST['product_quantity'];

   $check_cart_numbers = mysqli_query($conn, "SELECT * FROM `cart` WHERE name = '$product_name' AND user_id = '$user_id'") or die('Query failed: ' . mysqli_error($conn));

   if(mysqli_num_rows($check_cart_numbers) > 0){
      $message[] = 'already added to cart!';
   }else{
      mysqli_query($conn, "INSERT INTO `cart`(user_id, name, price, quantity, image) VALUES('$user_id', '$product_name', '$product_price', '$product_quantity', '$product_image')") or die('Query failed: ' . mysqli_error($conn));
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
   <title>search page</title>
   <link href="assets/img/market1.png" rel="icon">
    <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/vendor/aos/aos.css" rel="stylesheet">
    <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
    <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">


   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- Main CSS File -->
    <link href="assets/css/asep.css" rel="stylesheet">
 <!-- Main CSS File -->
    <link href="assets/css/cart.css" rel="stylesheet">
    <!-- Main CSS File -->
    <link href="assets/css/nav.css" rel="stylesheet">

   
   <style>
        /* Gaya umum untuk tombol */
        .btn {
            display: inline-block;
            font-weight: 400;
            color: #ffffff;
            text-align: center;
            vertical-align: middle;
            user-select: none;
            background-color: var(--accent-color);
            border: 1px solid var(--accent-color);
            padding: 0.375rem 0.75rem;
            font-size: 1rem;
            line-height: 1.5;
            border-radius: 0.375rem;
            transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }

        /* Pastikan tombol terlihat secara default */
        .btn-accent {
            background-color: var(--accent-color);
            color: var(--default-color);
            border-color: var(--accent-color);
        }

        /* Gaya saat di-hover */
        .btn-accent:hover {
            background-color: color-mix(in srgb, var(--accent-color) 90%, white 10%);
            color: var(--default-color);
            font-size:1.2;  
        }

        /* Pastikan tombol terlihat */
        .btn-accent:focus,
        .btn-accent:active {
            background-color: var(--accent-color);
            color: var(--default-color);
            border-color: var(--accent-color);
            box-shadow: none;
        }
    </style>
      <style>
        .user-box {
            display: none;
            background-color: var(--background-color);
            color: var(--default-color);
            position: absolute;
            top: 60px;
            right: 20px;
            border: 1px solid var(--accent-color);
            padding: 20px;
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
        <!-- Uncomment the line below if you also wish to use an image logo -->
        <!-- <img src="assets/img/logo.png" alt=""> -->
        <i class="bi bi-shop"></i>
        <h1 class="sitename">SEVEN</h1>
      </a>

      <nav id="navmenu" class="navmenu">
        <ul>
          <li><a href="index.php" class="">Home</a></li>
          <li><a href="about.php">About</a></li>
          <li><a href="shop.php">shop</a></li>
          <li><a href="orders.php"><span>oders</span></i></a>
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
        <a href="cart.php"><i class="bi bi-cart"></i><span>(<?php echo $cart_rows_number; ?>)</span></a>
        <a href="login.php"><p>LOGIN</p></a>| <a href="register.php"><p>REGISTER</p></a>
    
        <div class="user-box" id="user-box">
                <p>username : <span><?php echo $_SESSION['user_name']; ?></span></p>
                <p>email : <span><?php echo $_SESSION['user_email']; ?></span></p>
                <a href="logout.php" class="delete-btn">logout</a>
            </div>

    </div>
  </header>



<div class="heading">
   <h3>Search Page</h3>
</div>



<section id="login" class="login section">
            <div class="container" data-aos="fade-up">
                <div class="row justify-content-center">
                    <div class="col-lg-6">                    
                        <div class="form-container bg-dark text-white p-5 rounded">
                            <form action="" method="post">
                                <p class="text-center">Cari barang Sekarang</p>
                                <div class="mb-3">
                                    <input type="text" name="search" placeholder="search Products..." required class="form-control">
                                </div>
                                <div class="d-grid mb-3 bg-danger   rounded-3 ">
                                <input type="submit" name="submit" value="search" class="btn btn-accent ">
                                </div>
                              
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>


   </form>
</section>

<section class="shopping-cart">
   <div class="box-container">
   <?php
      if(isset($_POST['submit'])){
         $search_item = $_POST['search'];
         $select_products = mysqli_query($conn, "SELECT * FROM `products` WHERE name LIKE '%{$search_item}%'") or die('query failed');
         if(mysqli_num_rows($select_products) > 0){
         while($fetch_products = mysqli_fetch_assoc($select_products)){
    // Check if discount_price exists and is greater than 0
    if ($fetch_products['discount_price'] > 0) {
        $original_price = $fetch_products['price'];
        $discount_price = $fetch_products['discount_price'];
        
        $discount_percentage = round(((($original_price - $discount_price) / $original_price) * 100), 2);
    } else {
        $discount_price = $fetch_products['price'];
        $discount_percentage = 0;
    }
  ?>
     <form action="" method="post" class="box bg-dark">
         <div class="product-item">
            <div class="product-image">
            <?php if ($discount_percentage > 0) { ?>
                            <div class="discount-label"><?php echo $discount_percentage; ?>%</div>
                        <?php } ?>
               <div class="overlay">
                  <img class="image" src="uploaded_img/<?php echo $fetch_products['image']; ?>" alt="<?php echo $fetch_products['name']; ?>">
               </div>
               <a href="uploaded_img/<?php echo $fetch_products['image']; ?>" class="glightbox" data-glightbox="gallery-item"><i class="bi bi-arrows-angle-expand"></i></a>
            </div>
            <div class="product-details">
               <div class="name"><?php echo $fetch_products['name']; ?></div>
              <div class="quantity">Barang Tersedia: <?php echo $fetch_products['quantity']; ?></div> <!-- Added Quantity Display -->
               <div class="price">
               <?php 
   if ($discount_percentage > 0) {
       echo '<span class="original-price">Rp. ' . number_format($original_price, 0, ',', '.') . '</span>  Rp. ' . number_format($discount_price, 0, ',', '.');
   } else {
       echo 'Rp. ' . number_format($discount_price, 0, ',', '.');
   }
   ?>
</div>
               <input type="number" min="1" name="product_quantity" value="1" class="qty">
               <input type="hidden" name="product_name" value="<?php echo $fetch_products['name']; ?>">
               <input type="hidden" name="product_price" value="<?php echo $fetch_products['price']; ?>">
               <input type="hidden" name="product_image" value="<?php echo $fetch_products['image']; ?>">
               <input type="submit" value="Add to Cart" name="add_to_cart" class="btn">
            </div>
         </div>
         </form>
   <?php
            }
         }else{
            echo '<p class="empty">no result found!</p>';
         }
      }else{
         echo '<p class="empty">search something!</p>';
      }
   ?>
   </div>
  

</section>









<?php include 'footer.php'; ?>
<!-- Preloader -->
<div id="preloader">
    <div class="line"></div>
  </div>

  <!-- Vendor JS Files -->
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>
  <script src="assets/vendor/aos/aos.js"></script>
  <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>

  <!-- Main JS File -->
  <script src="assets/js/main.js"></script>

<!-- custom js file link  -->
<script src="js/script.js"></script>

<!-- Main JS File -->
<script src="assets/js/drop.js"></script>

<script>
      document.getElementById('user-icon').addEventListener('click', function() {
          var userBox = document.getElementById('user-box');
          if (userBox.style.display === 'block') {
              userBox.style.display = 'none';
          } else {
              userBox.style.display = 'block';
          }
      });
  </script>

</body>

</html>


</body>
</html>
<?php
include 'config.php';
session_start();

$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
    header('location:login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <!-- Favicons -->
     <link href="assets/img/market1.png" rel="icon">
    <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

    <title>Orders</title>

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
 <!-- Custom CSS file link -->
 <link href="assets/css/cart.css" rel="stylesheet">

    <!-- Main CSS File
 -->
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
    </style>

</head>

<body class="contact-page">

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
          <li><a href="index.php" >Home</a></li>
          <li><a href="about.php">About</a></li>
          <li><a href="shop.php"  >shop</a></li>
          <li><a href="orders.php"class="active"  ><span>oders</span></i></a>
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
        <h3>Pesanan</h3>
        <p><a href="index.php">Home</a> / Orders</p>
    </div>

    <section class="placed-orders">
        <div class="container" data-aos="fade-up">
            <h1 class="title text-center">Pesanan Kamu</h1>

            <div class="box-container row justify-content-center">
                <?php
                $order_query = mysqli_query($conn, "SELECT * FROM `orders` WHERE user_id = '$user_id'") or die('Query failed');
                if (mysqli_num_rows($order_query) > 0) {
                    while ($fetch_orders = mysqli_fetch_assoc($order_query)) {
                ?>
                    <div class="box col-lg-4 col-md-6 col-sm-12 bg-dark text-white p-3 m-2 rounded">
                        <p>Pada Tanggal: <span><?php echo $fetch_orders['placed_on']; ?></span></p>
                        <p>Nama: <span><?php echo $fetch_orders['name']; ?></span></p>
                        <p>No.: <span><?php echo $fetch_orders['number']; ?></span></p>
                        <p>Email: <span><?php echo $fetch_orders['email']; ?></span></p>
                        <p>Alamat: <span><?php echo $fetch_orders['address']; ?></span></p>
                        <p>Metode Pembayaran: <span><?php echo $fetch_orders['method']; ?></span></p>
                        <p>Barang: <span><?php echo $fetch_orders['total_products']; ?></span></p>
                        <p>Total Harga: <span>Rp. <?php echo $fetch_orders['total_price']; ?> </span></p>
                        <p>Status Pembayaran: <span style="color:<?php echo ($fetch_orders['payment_status'] == 'pending') ? 'red' : 'green'; ?>;"><?php echo $fetch_orders['payment_status']; ?></span></p>
                    </div>
                <?php
                    }
                } else {
                    echo '<p class="empty text-center">No orders placed yet!</p>';
                }
                ?>
            </div>
        </div>
    </section>

    <footer id="footer" class="footer">


<div class="container">
  <div class="copyright text-center ">
    <p>© <span>Copyright</span> <strong class="px-1 sitename">SMK Negeri 7 Bandar Lampung</strong> <span>All Rights Reserved</span></p>
  </div>
  <div class="social-links d-flex justify-content-center">
    <a href=""><i class="bi bi-twitter"></i></a>
    <a href=""><i class="bi bi-facebook"></i></a>
    <a href=""><i class="bi bi-instagram"></i></a>
    
  </div>
  <div class="credits">
    Designed  <a href="">Febrivn</a>
    <!-- Scroll Top -->

</div>
</footer>



<!-- Scroll Top -->
<a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

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
<script src="assets/js/asep.js"></script>
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
</header>





</body>

</html>

<?php
if(isset($message)){
   foreach($message as $message){
      echo '
      <div class="message">
         <span>'.$message.'</span>
         <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
      </div>
      ';
   }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Index - PhotoFolio Bootstrap Template</title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <!-- Favicons -->
    <link href="assets/img/favicon.png" rel="icon">
    <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Inter:wght@100;200;300;400;500;600;700;800;900&family=Cardo:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/vendor/aos/aos.css" rel="stylesheet">
    <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
    <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

    <!-- Main CSS File -->
    <link href="assets/css/main.css" rel="stylesheet">

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

<body class="index-page">

  <header id="header" class="header d-flex align-items-center sticky-top bg-dark">
    <d class="container-fluid position-relative d-flex align-items-center justify-content-between">
      <a href="index.php" class="logo d-flex align-items-center me-auto me-xl-0">
        <i class="bi bi-shop"></i>
        <h1 class="sitename">SEVEN</h1>
      </a>
   
      <nav id="navmenu" class="navmenu">
        <ul>
      
          <li><a href="index.php" >Home</a></li>
          <li><a href="about.php">About</a></li>
          <li><a href="shop.php">shop</a></li>
          <li><a href="orders.php"><span>oders</span> <i class="bi bi-orders"></i></a>
          <li><a href="contact.php" >Contact</a></li>


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
    </div>
      </header>
      <!-- Vendor JS Files -->
      <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/vendor/php-email-form/validate.js"></script>
    <script src="assets/vendor/aos/aos.js"></script>
    <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
    <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>

    <!-- Main JS File -->
    <script src="assets/js/main.js"></script>

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

  <main class="main">
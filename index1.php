<?php
include 'config.php';
session_start();

$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
    header('location:login.php');
}

if (isset($_POST['add_to_cart'])) {
    $product_name = $_POST['product_name'];
    $product_price = $_POST['product_price'];
    $product_image = $_POST['product_image'];
    $product_quantity = $_POST['product_quantity'];

    $check_cart_numbers = mysqli_query($conn, "SELECT * FROM `cart` WHERE name = '$product_name' AND user_id = '$user_id'") or die('query failed');

    if (mysqli_num_rows($check_cart_numbers) > 0) {
        $message[] = 'already added to cart!';
    } else {
        mysqli_query($conn, "INSERT INTO `cart`(user_id, name, price, quantity, image) VALUES('$user_id', '$product_name', '$product_price', '$product_quantity', '$product_image')") or die('query failed');
        $message[] = 'product added to cart!';
    }
}

// Ambil data produk dari database
$select_products = mysqli_query($conn, "SELECT * FROM `products` LIMIT 8") or die('query failed');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>7Mart</title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <!-- Favicons -->
    <link href="assets/img/market1.png" rel="icon">
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
    <link href="assets/css/asep.css" rel="stylesheet">
    <link href="assets/css/cart.css" rel="stylesheet">
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

<body class="index-page">
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
                <li><a href="index.php" class="active">Home</a></li>
                <li><a href="about.php">About</a></li>
                <li><a href="shop.php">shop</a></li>
                <li><a href="orders.php"><span>orders</span></i></a>
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
    </div>
</header>

<main class="main">

    <!-- Hero Section -->
    <section id="hero" class="hero section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-4 text-center" data-aos="fade-up" data-aos-delay="100">
                    <h2><span>Mini Market </span><br><span class="underlight">SEVEN MART </span> <br>SMKN 7 Bandar Lampung <br> <span> </span></h2>
                    <p>Selamat datang dan Selamat Berbelanja di SEVEN Mart Mini Market SMKN 7 Bandar Lampung </p>
                    <a href="login.php" class="btn-get-started">Sign Up</a>
                </div>
            </div>
        </div>
    </section><!-- /Hero Section -->

    <section class="shopping-cart">
        <h1 class="title">Product Terbaru</h1>

        <div class="box-container">
            <?php  
            $select_products = mysqli_query($conn, "SELECT * FROM products LIMIT 5") or die('query failed');
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
                        <!-- Discount percentage label -->
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
                        <div class="quantity">Barang Tersedia: <?php echo $fetch_products['quantity']; ?></div>
                        <div class="price">
                            <?php 
                            if ($discount_percentage > 0) {
                                echo 'Rp. <span class="original-price">' . number_format($original_price, 0, ',', '.') . '</span> ke Rp. ' . number_format($discount_price, 0, ',', '.');
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
            } else {
                echo '<p class="empty">No products available!</p>';
            }
            ?>
        </div>
    </section>

</main><!-- End #main -->

<!-- Vendor JS Files -->
<script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="assets/vendor/aos/aos.js"></script>
<script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
<script src="assets/vendor/swiper/swiper-bundle.min.js"></script>

<!-- Main JS File -->
<script src="assets/js/main.js"></script>

<script>
    // Toggle user box
    document.getElementById('user-icon').addEventListener('click', function() {
        var userBox = document.getElementById('user-box');
        userBox.style.display = (userBox.style.display === 'block') ? 'none' : 'block';
    });
</script>

</body>
</html>

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
   <title>Shopping Cart</title>

   <!-- Font Awesome CDN link -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- Custom CSS file link -->
   <link href="assets/css/cart.css" rel="stylesheet">
   <!-- Main CSS File -->
   <link href="assets/css/main.css" rel="stylesheet">

   <!-- Main JS File -->
   <script src="assets/js/main.js"></script>
</head>
<body>

<?php include 'header_shop.php'; ?>

<div class="heading">
   <h3>Shopping Cart</h3>
   <p><a href="home.php">Home</a> / Cart</p>
</div>

<section class="shopping-cart">
   <h1 class="title">Products Added</h1>

   <div class="box-container">
   <?php  
$select_products = mysqli_query($conn, "SELECT * FROM `products`") or die('query failed');
if(mysqli_num_rows($select_products) > 0){
    while($fetch_products = mysqli_fetch_assoc($select_products)){
?>
   <form action="" method="post" class="box">
                        <div class="product-item">
                            <div class="product-image">
                            <div class="overlay">  <img class="image" src="uploaded_img/<?php echo $fetch_products['image']; ?>" alt="<?php echo $fetch_products['name']; ?>">
    </div>
                                <a href="uploaded_img/<?php echo $fetch_products['image']; ?>" class="glightbox" data-glightbox="gallery-item"><i class="bi bi-arrows-angle-expand"></i></a>
                            </div>
                            <div class="product-details">
                                <div class="name"><?php echo $fetch_products['name']; ?></div>
                                <div class="price">$<?php echo $fetch_products['price']; ?>/-</div>
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
    echo '<p class="empty">No products added yet!</p>';
}
?>


</section>
     


<?php include 'footer.php'; ?>
<?php include 'preload.php'; ?>
<!-- Preloader -->

</body>
</html>

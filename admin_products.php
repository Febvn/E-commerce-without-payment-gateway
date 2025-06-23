<?php

include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
   header('location:login.php');
}

// Add Product
if (isset($_POST['add_product'])) {

   $name = mysqli_real_escape_string($conn, $_POST['name']);
   $cost_price = $_POST['cost_price'];
   $price = $_POST['price'];
   $quantity = $_POST['quantity'];
   $initial_stocks = $_POST['initial_stocks'];
   $damaged_goods = $_POST['damaged_goods'];
   $discount_price = $_POST['discount_price'];
   $image = $_FILES['image']['name'];
   $image_size = $_FILES['image']['size'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folder = 'uploaded_img/' . $image;

   $select_product_name = mysqli_query($conn, "SELECT name FROM `products` WHERE name = '$name'") or die('query failed');

   if (mysqli_num_rows($select_product_name) > 0) {
      $message[] = 'product name already added';
   } else {
      $add_product_query = mysqli_query($conn, "INSERT INTO `products`(name, cost_price, price, quantity, initial_stocks, damaged_goods, discount_price, image) VALUES('$name', '$cost_price', '$price', '$quantity', '$initial_stocks', '$damaged_goods', '$discount_price', '$image')") or die('query failed');

      if ($add_product_query) {
         if ($image_size > 2000000) {
            $message[] = 'image size is too large';
         } else {
            move_uploaded_file($image_tmp_name, $image_folder);
            $message[] = 'product added successfully!';
         }
      } else {
         $message[] = 'product could not be added!';
      }
   }
}

// Delete Single Product
if (isset($_GET['delete'])) {
   $delete_id = $_GET['delete'];
   $delete_image_query = mysqli_query($conn, "SELECT image FROM `products` WHERE id = '$delete_id'") or die('query failed');
   $fetch_delete_image = mysqli_fetch_assoc($delete_image_query);
   unlink('uploaded_img/' . $fetch_delete_image['image']);
   mysqli_query($conn, "DELETE FROM `products` WHERE id = '$delete_id'") or die('query failed');
   header('location:admin_products.php');
}

// Update Product
if (isset($_POST['update_product'])) {

   $update_p_id = $_POST['update_p_id'];
   $update_name = $_POST['update_name'];
   $update_cost_price = $_POST['update_cost_price'];
   $update_price = $_POST['update_price'];
   $update_quantity = $_POST['update_quantity'];
   $update_initial_stocks = $_POST['update_initial_stocks'];
   $update_damaged_goods = $_POST['update_damaged_goods'];
   $update_discount_price = $_POST['update_discount_price'];

   mysqli_query($conn, "UPDATE `products` SET name = '$update_name', cost_price = '$update_cost_price', price = '$update_price', quantity = '$update_quantity', initial_stocks = '$update_initial_stocks', damaged_goods = '$update_damaged_goods', discount_price = '$update_discount_price' WHERE id = '$update_p_id'") or die('query failed');

   $update_image = $_FILES['update_image']['name'];
   $update_image_tmp_name = $_FILES['update_image']['tmp_name'];
   $update_image_size = $_FILES['update_image']['size'];
   $update_folder = 'uploaded_img/' . $update_image;
   $update_old_image = $_POST['update_old_image'];

   if (!empty($update_image)) {
      if ($update_image_size > 2000000) {
         $message[] = 'image file size is too large';
      } else {
         mysqli_query($conn, "UPDATE `products` SET image = '$update_image' WHERE id = '$update_p_id'") or die('query failed');
         move_uploaded_file($update_image_tmp_name, $update_folder);
         unlink('uploaded_img/' . $update_old_image);
      }
   }

   header('location:admin_products.php');
}

// Delete All Products
if (isset($_POST['delete_all'])) {
   $delete_all_query = mysqli_query($conn, "DELETE FROM `products`") or die('query failed');
   if ($delete_all_query) {
      array_map('unlink', glob("uploaded_img/*")); // Deletes all images in the folder
      $message[] = 'All products deleted successfully!';
   } else {
      $message[] = 'Failed to delete products!';
   }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>products</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom admin css file link  -->
   <link href="assets/css/admin_style.css" rel="stylesheet">

   <style>
      .delete-all-btn {
         background-color: #e74c3c; /* Red background */
         color: white; /* White text */
         margin-top: 20px; /* Add some space above the button */
      }

      .edit-product-form form {
         display: flex;
         align-items: flex-start;
         gap: 20px; /* Jarak antara gambar dan form input */
      }

      .edit-product-form img {
         max-width: 150px; /* Sesuaikan ukuran gambar */
         height: auto;
         border-radius: 5px;
      }

      .edit-product-form .box {
         width: 100%;
         margin-bottom: 10px;
      }

      .edit-product-form form .btn,
      .edit-product-form form .option-btn {
         margin-top: 20px;
      }

      .edit-product-form form input[type="file"] {
         margin-top: 10px;
      } .edit-product-form {
      font-family: Arial, sans-serif;
   }
   
   .edit-product-form label {
      color: white; /* Font putih */
      font-size: 14px; /* Ukuran font yang lebih besar */
      display: block; /* Menampilkan label di baris baru */
      margin-bottom: 0px; /* Jarak antara label dan input */
   }
   
   .edit-product-form input.box {
      width: calc(100% - 22px); /* Mengatur lebar input agar sesuai dengan label */
      margin-bottom: 15px; /* Jarak antara input dan label berikutnya */
   }


   </style>
</head>
<body>

<?php include 'admin_header.php'; ?>

<!-- product CRUD section starts  -->

<section class="add-products">

   <h1 class="title">barang SEVEN Mart</h1>

   <form action="" method="post" enctype="multipart/form-data">
      <h3>Tambahkan product</h3>
      <input type="text" name="name" class="box" placeholder="Masukan Nama Product" required>
      <input type="number" min="0" name="cost_price" class="box" placeholder="Masukan Modal Product" required>
      <input type="number" min="0" name="price" class="box" placeholder="Masukan Harga Product" required>
      <input type="number" min="0" name="quantity" class="box" placeholder="Masukan Stock Akhir" required>
      <input type="number" min="0" name="initial_stocks" class="box" placeholder="Masukan Stock Awal" required>
      <input type="number" min="0" name="damaged_goods" class="box" placeholder="Masukan Barang Rusak" required>
      <input type="number" min="0" name="discount_price" class="box" placeholder="Masukan Harga Diskon" required>
      <input type="file" name="image" accept="image/jpg, image/jpeg, image/png" class="box" required>
      <input type="submit" value="add product" name="add_product" class="btn">
   </form>

   <form action="" method="post">
      <input type="submit" value="Delete All" name="delete_all" class="btn delete-all-btn" onclick="return confirm('Are you sure you want to delete all products?');">
   </form>

</section>

<!-- product CRUD section ends -->

<!-- show products  -->

<section class="show-products">

   <div class="box-container">

      <?php
         $select_products = mysqli_query($conn, "SELECT * FROM `products`") or die('query failed');
         if(mysqli_num_rows($select_products) > 0){
            while($fetch_products = mysqli_fetch_assoc($select_products)){
      ?>
      <div class="box">
         <img src="uploaded_img/<?php echo $fetch_products['image']; ?>" alt="">
         <div class="price">Harga Modal: Rp <?php echo number_format($fetch_products['cost_price'], 0, ',', '.'); ?></div>
         <div class="price">Harga Jual: Rp <?php echo number_format($fetch_products['price'], 0, ',', '.'); ?></div>
         <div class="price">Stock Akhir: <?php echo $fetch_products['quantity']; ?></div>
         <div class="price">Stock Awal: <?php echo $fetch_products['initial_stocks']; ?></div>
         <div class="price">Barang Rusak: <?php echo $fetch_products['damaged_goods']; ?></div>
         <div class="price">Harga Diskon: Rp <?php echo number_format($fetch_products['discount_price'], 0, ',', '.'); ?></div>
         <a href="admin_products.php?update=<?php echo $fetch_products['id']; ?>" class="option-btn">update</a>
         <a href="admin_products.php?delete=<?php echo $fetch_products['id']; ?>" class="delete-btn" onclick="return confirm('delete this product?');">delete</a>
      </div>
      <?php
         }
      }else{
         echo '<p class="empty">no products added yet!</p>';
      }
      ?>
   </div>

</section>
<section class="edit-product-form">

   <?php
      if(isset($_GET['update'])){
         $update_id = $_GET['update'];
         $update_query = mysqli_query($conn, "SELECT * FROM `products` WHERE id = '$update_id'") or die('query failed');
         if(mysqli_num_rows($update_query) > 0){
            while($fetch_update = mysqli_fetch_assoc($update_query)){
   ?>
   <form action="" method="post" enctype="multipart/form-data">
      <input type="hidden" name="update_p_id" value="<?php echo $fetch_update['id']; ?>">
      <input type="hidden" name="update_old_image" value="<?php echo $fetch_update['image']; ?>">
      <img src="uploaded_img/<?php echo $fetch_update['image']; ?>" alt="">
      <div>
         <label for="update_name">Nama Product:</label>
         <input type="text" id="update_name" name="update_name" value="<?php echo $fetch_update['name']; ?>" class="box" required placeholder="Masukan Nama Product">
         
         <label for="update_cost_price">Modal Product:</label>
         <input type="number" min="0" id="update_cost_price" name="update_cost_price" value="<?php echo $fetch_update['cost_price']; ?>" class="box" required placeholder="Masukan Modal Product">
         
         <label for="update_price">Harga Product:</label>
         <input type="number" min="0" id="update_price" name="update_price" value="<?php echo $fetch_update['price']; ?>" class="box" required placeholder="Masukan Harga Product">
         
         <label for="update_quantity">Stock Akhir:</label>
         <input type="number" min="0" id="update_quantity" name="update_quantity" value="<?php echo $fetch_update['quantity']; ?>" class="box" required placeholder="Masukan Stock Akhir">
         
         <label for="update_initial_stocks">Stock Awal:</label>
         <input type="number" min="0" id="update_initial_stocks" name="update_initial_stocks" value="<?php echo $fetch_update['initial_stocks']; ?>" class="box" required placeholder="Masukan Stock Awal">
         
         <label for="update_damaged_goods">Barang Rusak:</label>
         <input type="number" min="0" id="update_damaged_goods" name="update_damaged_goods" value="<?php echo $fetch_update['damaged_goods']; ?>" class="box" required placeholder="Masukan Barang Rusak">
         
         <label for="update_discount_price">Harga Diskon:</label>
         <input type="number" min="0" id="update_discount_price" name="update_discount_price" value="<?php echo $fetch_update['discount_price']; ?>" class="box" required placeholder="Masukan Harga Diskon">
         
         <label for="update_image">Update Gambar:</label>
         <input type="file" class="box" id="update_image" name="update_image" accept="image/jpg, image/jpeg, image/png">
         
         <input type="submit" value="update" name="update_product" class="btn">
         <input type="button" value="cancel" id="close-update" class="option-btn" onclick="document.querySelector('.edit-product-form').style.display='none';">
      </div>
   </form>

   <?php
            }
         }
      }else{
         echo '<script>document.querySelector(".edit-product-form").style.display = "none";</script>';
      }
   ?>

</section>


<!-- custom admin js file link  -->
<script src="assets/js/admin_script.js"></script>

</body>
</html>

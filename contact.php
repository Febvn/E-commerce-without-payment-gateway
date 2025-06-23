<?php
include 'config.php';
session_start();
$user_id = $_SESSION['user_id'];
if (!isset($user_id)) {
    header('location:login.php');
}
if (isset($_POST['send'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $number = $_POST['number'];
    $msg = mysqli_real_escape_string($conn, $_POST['message']);
    $select_message = mysqli_query($conn, "SELECT * FROM `message` WHERE name = '$name' AND email = '$email' AND number = '$number' AND message = '$msg'") or die('query failed');
    if (mysqli_num_rows($select_message) > 0) {
        $message[] = 'message sent already!';
    } else {
        mysqli_query($conn, "INSERT INTO `message`(user_id, name, email, number, message) VALUES('$user_id', '$name', '$email', '$number', '$msg')") or die('query failed');
        $message[] = 'message sent successfully!';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>contact</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<!-- Favicons -->
<link href="assets/img/market1.png" rel="icon">
    <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">
    
  <!-- Main CSS File -->
  <link href="assets/css/asep.css" rel="stylesheet">

   <!-- Main CSS File -->
   <link href="assets/css/nav.css" rel="stylesheet">

   <style>
        /* Embed CSS styles with colors from main.css */
        body {
            font-family: var(--default-font);
            margin: 0;
            padding: 0;
            background-color: var(--background-color);
            color: var(--default-color);
        }
        .heading {
            text-align: center;
            padding: 20px;
            background-color: color-mix(in srgb, var(--background-color), var(--default-color) 10%);
        }
        .contact {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .contact form {
            background-color:rgb(39 44 48) ;
            padding: 20px;
            border-radius: 5px;
        }
        .contact .box {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid color-mix(in srgb, var(--default-color), var(--background-color) 50%);
            border-radius: 3px;
            background-color: color-mix(in srgb, var(--default-color), var(--background-color) 90%);
            color: var(--default-color);
        }
        .contact .btn {
            background-color: var(--accent-color);
            color: #ffffff;
            padding: 10px 20px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <?php include 'headercontact.php'; ?>
    <div class="heading">
        <h3>contact us</h3>
       
    </div>
    <section class="contact">
        <form action="" method="post" >
            <h3>say something!</h3>
            <input type="text" name="name" required placeholder="enter your name" class="box">
            <input type="email" name="email" required placeholder="enter your email" class="box">
            <input type="number" name="number" required placeholder="enter your number" class="box">
            <textarea name="message" class="box" placeholder="enter your message" id="" cols="30" rows="10"></textarea>
            <input type="submit" value="send message" name="send" class="btn">
        </form>
    </section>
    <?php include 'footer.php'; ?>
    <script src="js/script.js"></script>
</body>
</html>
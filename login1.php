
<?php
include 'config.php';
session_start();

if (isset($_POST['submit'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $pass = mysqli_real_escape_string($conn, md5($_POST['password']));
    $select_users = mysqli_query($conn, "SELECT * FROM `users` WHERE email = '$email' AND password = '$pass'") or die('query failed');
    if (mysqli_num_rows($select_users) > 0) {
        $row = mysqli_fetch_assoc($select_users);
        if ($row['user_type'] == 'admin') {
            $_SESSION['admin_name'] = $row['name'];
            $_SESSION['admin_email'] = $row['email'];
            $_SESSION['admin_id'] = $row['id'];

            // Update last_login timestamp
            mysqli_query($conn, "UPDATE `users` SET last_login = NOW() WHERE id = '{$row['id']}'") or die('query failed');

            header('location:admin_page.php');
        } elseif ($row['user_type'] == 'user') {
            $_SESSION['user_name'] = $row['name'];
            $_SESSION['user_email'] = $row['email'];
            $_SESSION['user_id'] = $row['id'];

            // Update last_login timestamp
            mysqli_query($conn, "UPDATE `users` SET last_login = NOW() WHERE id = '{$row['id']}'") or die('query failed');

            header('location:index.php');
        }
    } else {
        $message[] = 'Email atau password salah!';
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <!-- Favicons -->
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

    <!-- Main CSS File -->
    <link href="assets/css/asep.css" rel="stylesheet">

    <!-- Tambahkan kode CSS di sini -->
    <style>
        /* Gaya umum untuk tombol */
        .btn {
            display: inline-block;
            font-weight: 400;
            color: var(--default-color);
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
</head>

<body class="index-page">

    <header id="header" class="header d-flex align-items-center sticky-top">
        <div class="container-fluid position-relative d-flex align-items-center justify-content-between">
            <a href="index.html" class="logo d-flex align-items-center me-auto me-xl-0">
                <i class="bi bi-shop"></i>
                <h1 class="sitename">SEVEN</h1>
            </a>

            <nav id="navmenu" class="navmenu">
                <ul>
                    <li><a href="index.html"></a></li>
                    <li><a href="about.html"></a></li>
                    <li class="dropdown">
                        <a href=""><span></span> <i class="asep"></i></a>
                        <ul>
                        </ul>
                    </li>
                    <li><a href="services.html"></a></li>
                    <li><a href="contact.html"></a></li>
                </ul>
                <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
            </nav>

            <div class="header-social-links">
                <a href="#" class="twitter"><i class="bi bi-twitter"></i></a>
                <a href="#" class="facebook"><i class="bi bi-facebook"></i></a>
                <a href="#" class="instagram"><i class="bi bi-instagram"></i></a>

            </div>
        </div>
    </header>

    <main class="main">
        <section id="login" class="login section">
            <div class="container" data-aos="fade-up">
                <div class="row justify-content-center">
                    <div class="col-lg-6">
                        <?php
                        if (isset($message)) {
                            foreach ($message as $message) {
                                echo '
                                <div class="alert alert-danger">
                                    <span>' . $message . '</span>
                                    <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
                                </div>
                                ';
                            }
                        }
                        ?>
                        <div class="form-container bg-dark text-white p-5 rounded">
                            <form action="" method="post">
                                <p class="text-center">Login Sekarang</p>
                                <div class="mb-3">
                                    <input type="email" name="email" placeholder="Masukkan email Anda" required class="form-control">
                                </div>
                                <div class="mb-3">
                                    <input type="password" name="password" placeholder="Masukkan password Anda" required class="form-control">
                                </div>
                                <div class="d-grid mb-3">
                                    <input type="submit" name="submit" value="Login" class="btn btn-accent">
                                </div>
                                <p class="text-center">Belum punya akun? <a href="register.php" class="text-accent">Daftar sekarang</a></p>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <footer id="footer" class="footer">
        <div class="container">
            <div class="copyright text-center">
                <p>© <span>Hak Cipta</span> <strong class="px-1 sitename">Febrivn</strong> <span>Semua Hak Dilindungi</span></p>
            </div>
            <div class="social-links d-flex justify-content-center">
                <a href="#"><i class="bi bi-twitter"></i></a>
                <a href="#"><i class="bi bi-facebook"></i></a>
                <a href="#"><i class="bi bi-instagram"></i></a>      
            </div>
            <div class="credits">
                Dirancang oleh <a href="">Febrivn</a>
            </div>
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
    <script src="assets/js/main.js"></script>

</body>

</html>
 
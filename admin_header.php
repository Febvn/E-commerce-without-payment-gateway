<?php
if (isset($message)) {
    foreach ($message as $message) {
        echo '
        <div class="message">
            <span>' . $message . '</span>
            <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
        </div>
        ';
    }
}
?>



<!-- Main CSS File -->

<!-- Main CSS File -->

<header class="header">
    <div class="flex">
        <a href="admin_page.php" class="logo">Admin<span>Panel</span></a>

        <nav class="navbar">
            <a href="admin_page.php">home</a>
            <a href="admin_products.php">products</a>
            <a href="admin_orders.php">orders</a>
            <a href="admin_users.php">users</a>
            <a href="admin_contacts.php">messages</a>
            <a href="admin_report.php">laporan</a>
            <a href="admin_diskon.php">Laporan diskon</a>
        </nav>

      

      
          
                <a href="logout.php" class="delete-btn" style="color: white;" >logout</a>
                <a href="password.php" class="delete-btn" style="color: white;" >Registrasi</a>
            </div>
        </div>
    </div>
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

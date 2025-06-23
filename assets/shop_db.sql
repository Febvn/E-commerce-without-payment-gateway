-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 18 Agu 2024 pada 19.55
-- Versi server: 10.4.28-MariaDB
-- Versi PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `shop_db`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `cart`
--

CREATE TABLE `cart` (
  `id` int(100) NOT NULL,
  `user_id` int(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `price` int(100) NOT NULL,
  `quantity` int(100) NOT NULL,
  `image` varchar(100) NOT NULL,
  `discount_price` decimal(10,2) DEFAULT NULL,
  `discount_percentage` decimal(5,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `cart`
--

INSERT INTO `cart` (`id`, `user_id`, `name`, `price`, `quantity`, `image`, `discount_price`, `discount_percentage`) VALUES
(104, 7, 'kucing 1', 200, 1, 'download (4).jpg', NULL, NULL),
(112, 8, 'pengaris besi ', 12000, 3, 'Screenshot_2024-07-24-20-36-08-84_8b1cfbb769bd52fc36fa25a4fcc64305.jpg', NULL, NULL),
(130, 0, 'Screpes', 8000, 5, 'Screenshot_2024-07-24-20-40-00-83_8b1cfbb769bd52fc36fa25a4fcc64305.jpg', NULL, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `message`
--

CREATE TABLE `message` (
  `id` int(100) NOT NULL,
  `user_id` int(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `number` varchar(12) NOT NULL,
  `message` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `message`
--

INSERT INTO `message` (`id`, `user_id`, `name`, `email`, `number`, `message`) VALUES
(29, 1, 'Febrian Valentino Nugroho', 'anglawaeli26@gmail.com', '12345678', 'hallo min barang saya belum nyampe ');

-- --------------------------------------------------------

--
-- Struktur dari tabel `orders`
--

CREATE TABLE `orders` (
  `id` int(100) NOT NULL,
  `user_id` int(100) NOT NULL,
  `product_id` int(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `number` varchar(12) NOT NULL,
  `email` varchar(100) NOT NULL,
  `method` varchar(50) NOT NULL,
  `address` varchar(500) NOT NULL,
  `total_products` varchar(1000) NOT NULL,
  `total_price` int(100) NOT NULL,
  `grand_totals` decimal(10,2) NOT NULL,
  `placed_on` varchar(50) NOT NULL,
  `payment_status` varchar(20) NOT NULL DEFAULT 'pending',
  `bukti_pembayaran` varchar(255) DEFAULT NULL,
  `discount_price` decimal(10,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `product_id`, `name`, `number`, `email`, `method`, `address`, `total_products`, `total_price`, `grand_totals`, `placed_on`, `payment_status`, `bukti_pembayaran`, `discount_price`) VALUES
(111, 13, 0, 'Febrian Valentino Nugroho ', '12345678', 'anglawaeli26@gmail.com', 'Bayar di Kasir (offline)', '', 'pengaris plastik (4) , Peruncing (4) , pengaris besi (4) , Screpes (4) , Calcurator 8 Digit (4) ', 165400, 165400.00, '2024-08-16 17:25:23', 'completed', '', 0.00),
(112, 1, 0, 'Febrian Valentino Nugroho', '12345678', 'anglawaeli26@gmail.com', 'Bayar di Kasir (offline)', '', 'pengaris plastik (4) ', 28000, 28000.00, '2024-08-16 17:59:59', 'pending', '', 0.00),
(113, 1, 0, 'Febrian Valentino Nugroho', '12345678', 'anglawaeli26@gmail.com', 'cash on delivery', '', 'pengaris plastik (5) ', 0, 0.00, '2024-08-16 18:07:22', 'pending', 'uploaded_img/IMG_20240724_203031.jpg', 0.00),
(114, 1, 0, 'Febrian Valentino Nugroho', '12345678', 'anglawaeli26@gmail.com', 'cash on delivery', '', '', 0, 0.00, '2024-08-16 18:08:52', 'pending', 'uploaded_img/IMG_20240724_203031.jpg', 0.00),
(115, 1, 0, 'Febrian Valentino Nugroho', '12345678', 'anglawaeli26@gmail.com', 'DANA', '', 'pengaris plastik (4) , pengaris besi (3) ', 40000, 40000.00, '2024-08-16 18:10:23', 'pending', 'uploaded_img/IMG_20240724_203031.jpg', 0.00),
(116, 1, 0, 'Febrian Valentino Nugroho', '12345678', 'anglawaeli26@gmail.com', 'DANA', ',', '', 0, 0.00, '2024-08-16 18:12:34', 'pending', 'uploaded_img/IMG_20240724_203031.jpg', 0.00),
(117, 1, 0, 'Febrian Valentino Nugroho', '12345678', 'anglawaeli26@gmail.com', 'DANA', 'budiono123, bandar lampung ,sumatera', '', 0, 0.00, '2024-08-16 18:25:29', 'pending', 'uploaded_img/IMG_20240724_203031.jpg', 0.00),
(118, 1, 0, 'Febrian Valentino Nugroho', '12345678', 'anglawaeli26@gmail.com', 'cash on delivery', 'budiono, bandar lampung ,sumatera', '', 0, 0.00, '2024-08-16 18:25:46', 'pending', 'uploaded_img/IMG_20240724_203031.jpg', 0.00);

-- --------------------------------------------------------

--
-- Struktur dari tabel `order_items`
--

CREATE TABLE `order_items` (
  `id` int(100) NOT NULL,
  `order_id` int(100) NOT NULL,
  `product_id` int(100) NOT NULL,
  `quantity` int(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `products`
--

CREATE TABLE `products` (
  `id` int(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `cost_price` int(11) NOT NULL,
  `price` int(100) NOT NULL,
  `quantity` int(11) NOT NULL,
  `initial_stock` int(11) NOT NULL,
  `image` varchar(100) NOT NULL,
  `initial_stocks` int(11) NOT NULL,
  `damaged_goods` int(11) NOT NULL,
  `discount_price` decimal(10,2) NOT NULL,
  `initial_stockss` int(11) DEFAULT NULL,
  `awal_stock` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `products`
--

INSERT INTO `products` (`id`, `name`, `cost_price`, `price`, `quantity`, `initial_stock`, `image`, `initial_stocks`, `damaged_goods`, `discount_price`, `initial_stockss`, `awal_stock`) VALUES
(55, 'Peruncing', 300, 400, 291, 0, 'Screenshot_2024-07-24-20-34-11-79_8b1cfbb769bd52fc36fa25a4fcc64305.jpg', 300, 7, 350.00, NULL, NULL),
(56, 'pengaris plastik', 9000, 10000, 280, 0, 'Screenshot_2024-07-24-20-37-10-65_8b1cfbb769bd52fc36fa25a4fcc64305.jpg', 300, 2, 7000.00, NULL, NULL),
(57, 'pengaris besi', 3000, 4000, 189, 0, 'Screenshot_2024-07-24-20-36-08-84_8b1cfbb769bd52fc36fa25a4fcc64305.jpg', 200, 0, 0.00, NULL, NULL),
(58, 'Screpes', 2000, 3000, 193, 0, 'Screenshot_2024-07-24-20-40-00-83_8b1cfbb769bd52fc36fa25a4fcc64305.jpg', 200, 4, 0.00, NULL, NULL),
(60, 'Calcurator 8 Digit', 25000, 30000, 88, 0, 'Screenshot_2024-07-24-20-38-19-68_8b1cfbb769bd52fc36fa25a4fcc64305.jpg', 100, 1, 27000.00, NULL, NULL),
(61, 'gunting', 3000, 6000, 200, 0, 'Screenshot_2024-07-24-20-28-25-28_8b1cfbb769bd52fc36fa25a4fcc64305.jpg', 200, 3, 4000.00, NULL, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `user_type` varchar(20) NOT NULL DEFAULT 'user',
  `last_login` datetime DEFAULT NULL,
  `last_logout` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `user_type`, `last_login`, `last_logout`) VALUES
(1, 'Febrian Valentino Nugroho ', 'dodithalud01@gmail.com', '202cb962ac59075b964b07152d234b70', 'user', '2024-08-16 22:59:41', '2024-08-16 22:58:12'),
(2, 'Febrian Valentino Nugroho ', 'dodithalud01@gmail.com', '81dc9bdb52d04dc20036dbd8313ed055', 'user', NULL, NULL),
(3, 'Febrian Valentino Nugroho ', 'dodithalud01@gmail.com', '827ccb0eea8a706c4c34a16891f84e7b', 'admin', '2024-08-16 23:00:21', '2024-08-16 22:59:34'),
(4, 'admin', 'admin12345', 'AdminSeven123', 'admin', NULL, NULL),
(5, 'dodi', 'dodithalud02@gmail.com', 'e10adc3949ba59abbe56e057f20f883e', 'admin', NULL, NULL),
(6, 'feb', 'dodithalud01@gmail.com', 'd7b85f12bdf36266db695411a654f73f', 'user', NULL, NULL),
(7, 'refal', 'dodithalud01@gmail.com', 'e10adc3949ba59abbe56e057f20f883e', 'user', NULL, NULL),
(8, 'user123 ', '@user123', 'UserSeven123', 'user', NULL, NULL),
(9, 'Febrian Valentino Nugroho ', 'dodithalud01@gmail.com', 'ed7aa565212f7a4d20e5db029b70a39e', 'user', NULL, NULL),
(10, 'Febrian Valentino Nugroho ', 'dodithalud01@gmail.com', '0d051c112f18a20d74d994c407afe999', 'user', NULL, NULL),
(11, 'dodi', 'anglawaeli26@gmail.com', '202cb962ac59075b964b07152d234b70', 'user', '2024-08-11 16:34:53', NULL),
(12, 'admin123456789', 'admin@123456789', '25f9e794323b453885f5181f1b624d0b', 'admin', '2024-08-16 22:56:57', NULL),
(13, 'Febrian Valentino Nugroho', 'febriadmin@gmail.com', '202cb962ac59075b964b07152d234b70', 'user', '2024-08-16 22:18:07', NULL),
(14, 'budi', 'budi@gmail.com', '202cb962ac59075b964b07152d234b70', 'admin', '2024-08-16 22:56:14', NULL);

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `message`
--
ALTER TABLE `message`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_items_ibfk_1` (`order_id`),
  ADD KEY `order_items_ibfk_2` (`product_id`);

--
-- Indeks untuk tabel `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=238;

--
-- AUTO_INCREMENT untuk tabel `message`
--
ALTER TABLE `message`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT untuk tabel `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=119;

--
-- AUTO_INCREMENT untuk tabel `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `products`
--
ALTER TABLE `products`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

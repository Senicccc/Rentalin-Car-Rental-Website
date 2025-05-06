<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['id'])) {
    echo "Transaksi tidak ditemukan.";
    exit();
}

$transaksi_id = $_GET['id'];
$user_id = $_SESSION['user_id'];

$sql = "SELECT t.*, m.nama AS nama_mobil, m.id AS mobil_id, u.nama AS nama_user 
        FROM transactions t 
        JOIN mobil m ON t.mobil_id = m.id 
        JOIN users u ON t.user_id = u.id
        WHERE t.id = ? AND t.user_id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "ii", $transaksi_id, $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) === 0) {
    echo "Data transaksi tidak ditemukan.";
    exit();
}

$data = mysqli_fetch_assoc($result);

$order_id = $data['id'];
$nama_user = $data['nama_user'];
$mobil_id = $data['mobil_id'];
$nama_mobil = $data['nama_mobil'];
$tanggal_mulai = $data['tanggal_mulai'];
$tanggal_selesai = $data['tanggal_selesai'];
$total_harga = $data['total_harga'];
$created_at = $data['created_at'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Detail Transaksi</title>

    <!-- Favicon -->
    <link rel="shortcut icon" href="../img/favicon.jpg" type="image/x-icon" />

    <!-- CSS -->
    <link rel="stylesheet" href="../css/style.css" />
    <link rel="stylesheet" href="../css/phpstyle.css" />

    <!-- JS -->
    <script src="../js/script.js"></script>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Cal+Sans&family=Poppins:ital,wght@0,300;0,400;0,700&display=swap"
        rel="stylesheet" />
</head>

<body>
    <!-- Navbar start -->
    <nav class="navbar">
        <a href="#" class="navbar-logo">Rental<span>in</span></a>
        <div class="burger-menu">
            <div class="bar"></div>
            <div class="bar"></div>
            <div class="bar"></div>
        </div>
        <div class="navbar-nav">
            <a href="../index.html">Home</a>
            <a href="dashboard.php">Dashboard</a>
            <a href="rental-mobil.php">Rent a Car</a>
            <a href="../index.html#contact">Our Contact & Location</a>
        </div>
    </nav>
    <!-- Navbar end -->

    <!-- Rental Detail start -->
    <section class="details-container">
        <div class="details-card">
            <h2>Transaction Details</h2>
            <p><strong>Transaction ID:</strong> <?= htmlspecialchars($order_id) ?></p>
            <p><strong>User ID:</strong> <?= htmlspecialchars($user_id) ?></p>
            <p><strong>Username:</strong> <?= htmlspecialchars($nama_user) ?></p>
            <p><strong>Car ID:</strong> <?= htmlspecialchars($mobil_id) ?></p>
            <p><strong>Car Name:</strong> <?= htmlspecialchars($nama_mobil) ?></p>
            <p><strong>Start Date:</strong> <?= htmlspecialchars($tanggal_mulai) ?></p>
            <p><strong>End Date:</strong> <?= htmlspecialchars($tanggal_selesai) ?></p>
            <p><strong>Total Price:</strong> Rp<?= number_format($total_harga, 0, ',', '.') ?></p>
            <p><strong>Transaction Time:</strong> <?= htmlspecialchars($created_at) ?></p>
            <br>
            <a href="dashboard.php" class="btn-back">⬅ Kembali ke Dashboard</a>
        </div>
    </section>
    <!-- Rental Detail end  -->

    <!-- Footer start -->
    <footer>
        <a href="../index.html" class="navbar-logo">Rental<span>in</span></a>
        <div class="links">
            <a href="../index.html">Home</a>
            <a href="dashboard.php">Dashboard</a>
            <a href="rental-mobil.php">Rent a Car</a>
            <a href="../index.html#contact">Contact & Location</a>
            <a href="mailto:info@rentalin.id">Email</a>
            <a href="https://www.instagram.com">Instagram</a>
            <a href="https://www.facebook.com">Facebook</a>
        </div>
        <div class="credit">
            <p>PT. Rentalin Otomotif Sejahtera Bersama | &copy; 2025</p>
        </div>
    </footer>
    <!-- Footer end -->
</body>

</html>
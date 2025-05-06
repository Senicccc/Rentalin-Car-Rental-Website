<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    echo "You must log in first.";
    exit();
}

if (
    isset($_GET['status'], $_GET['order_id'], $_GET['total_harga'], 
          $_GET['mobil_id'], $_GET['tanggal_mulai'], $_GET['tanggal_selesai']) &&
    $_GET['status'] === 'success'
) {
    $order_id = $_GET['order_id'];
    $total_harga = $_GET['total_harga'];
    $mobil_id = $_GET['mobil_id'];
    $tanggal_mulai = $_GET['tanggal_mulai'];
    $tanggal_selesai = $_GET['tanggal_selesai'];
    $user_id = $_SESSION['user_id'];

    $check_query = "SELECT id FROM transactions WHERE order_id = ?";
    $check_stmt = mysqli_prepare($conn, $check_query);
    mysqli_stmt_bind_param($check_stmt, "s", $order_id);
    mysqli_stmt_execute($check_stmt);
    $check_result = mysqli_stmt_get_result($check_stmt);

    if (mysqli_num_rows($check_result) === 0) {
        $insert_query = "INSERT INTO transactions (
                            user_id, mobil_id, tanggal_mulai, tanggal_selesai, total_harga, order_id, created_at
                        ) VALUES (?, ?, ?, ?, ?, ?, NOW())";
        $insert_stmt = mysqli_prepare($conn, $insert_query);
        mysqli_stmt_bind_param($insert_stmt, "iissis", $user_id, $mobil_id, $tanggal_mulai, $tanggal_selesai, $total_harga, $order_id);
        if (!mysqli_stmt_execute($insert_stmt)) {
            echo "Gagal menyimpan transaksi: " . mysqli_error($conn);
            exit();
        }

        $update_query = "UPDATE mobil SET kuantitas = kuantitas - 1 WHERE id = ?";
        $update_stmt = mysqli_prepare($conn, $update_query);
        mysqli_stmt_bind_param($update_stmt, "i", $mobil_id);
        if (!mysqli_stmt_execute($update_stmt)) {
            echo "Gagal update stok: " . mysqli_error($conn);
            exit();
        }
    }

    $mobil_query = "SELECT nama FROM mobil WHERE id = ?";
    $mobil_stmt = mysqli_prepare($conn, $mobil_query);
    mysqli_stmt_bind_param($mobil_stmt, "i", $mobil_id);
    mysqli_stmt_execute($mobil_stmt);
    $mobil_result = mysqli_stmt_get_result($mobil_stmt);
    $nama_mobil = ($row = mysqli_fetch_assoc($mobil_result)) ? $row['nama'] : "Unknown";

    $user_query = "SELECT nama FROM users WHERE id = ?";
    $user_stmt = mysqli_prepare($conn, $user_query);
    mysqli_stmt_bind_param($user_stmt, "i", $user_id);
    mysqli_stmt_execute($user_stmt);
    $user_result = mysqli_stmt_get_result($user_stmt);
    $nama_user = ($row = mysqli_fetch_assoc($user_result)) ? $row['nama'] : "Unknown";

    $created_query = "SELECT created_at FROM transactions WHERE order_id = ?";
    $created_stmt = mysqli_prepare($conn, $created_query);
    mysqli_stmt_bind_param($created_stmt, "s", $order_id);
    mysqli_stmt_execute($created_stmt);
    $created_result = mysqli_stmt_get_result($created_stmt);
    $created_at = ($row = mysqli_fetch_assoc($created_result)) ? $row['created_at'] : date("Y-m-d H:i:s");

} else {
    echo "Invalid transaction data.";
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Transaction Success</title>

    <!-- Favicon -->
    <link rel="shortcut icon" href="../img/favicon.jpg" type="image/x-icon" />

    <!-- CSS -->
    <link rel="stylesheet" type="text/css" href="../css/style.css" />
    <link rel="stylesheet" type="text/css" href="../css/phpstyle.css" />

    <!-- Javascript -->
    <script src="../js/script.js"></script>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Cal+Sans&family=Poppins:ital,wght@0,100;0,300;0,400;0,700;1,700&family=Sigmar&display=swap"
        rel="stylesheet" />
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar">
        <a href="../index.html" class="navbar-logo">Rental<span>in</span></a>
        <div class="burger-menu">
            <div class="bar"></div>
            <div class="bar"></div>
            <div class="bar"></div>
        </div>
        <div class="navbar-nav">
            <a href="../index.html">Home</a>
            <a href="dashboard.php">Dashboard</a>
            <a href="rental-mobil.php">Rent a Car</a>
            <a href="../index.html#contact">Contact & Location</a>
        </div>
    </nav>

    <!-- Transaction Details -->
    <section class="details-container">
        <div class="details-card">
            <h2>✅ Payment Successful!</h2>
            <p>Your transaction has been successfully processed.</p>
            <p>Screenshot this page for proof.</p>
            <hr>
            <h3>Transaction Details</h3>
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
            <a href="dashboard.php" class="btn-back">⬅ Back to Dashboard</a>
        </div>
    </section>

    <!-- Footer start -->
    <footer>
        <a href="#" class="navbar-logo">Rental<span>in</span></a>
        <div class="links">
            <a href="../index.html">Home</a>
            <a href="dashboard.php">Dashboard</a>
            <a href="rental-mobil.php">Rent a Car</a>
            <a href="../index.html#contact">Our Contact & Location</a>
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
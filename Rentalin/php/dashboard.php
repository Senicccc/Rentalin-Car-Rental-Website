<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include('db.php');

if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}


if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM users WHERE id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);

$sql_transaksi = "SELECT t.*, m.nama AS nama_mobil FROM transactions t 
                  JOIN mobil m ON t.mobil_id = m.id 
                  WHERE t.user_id = ? ORDER BY t.id DESC";
$stmt_transaksi = mysqli_prepare($conn, $sql_transaksi);
mysqli_stmt_bind_param($stmt_transaksi, "i", $user_id);
mysqli_stmt_execute($stmt_transaksi);
$result_transaksi = mysqli_stmt_get_result($stmt_transaksi);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard - Rentalin</title>

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

    <style>
    html {
        scroll-behavior: smooth;
    }
    </style>
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

    <!-- Dashboard start -->
    <section class="dashboard">
        <div class="container-dashboard">
            <h1>Welcome, <?php echo htmlspecialchars($user['nama']); ?>!</h1>
            <p>Manage your profile and view your rental history below.</p>

            <!-- Account info -->
            <div class="info-box">
                <h3>Account Information</h3>
                <p><strong>Username:</strong> <?= htmlspecialchars($user['username']) ?></p>
                <p><strong>Full Name:</strong> <?= htmlspecialchars($user['nama']) ?></p>
                <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
                <p><strong>Phone:</strong> <?= htmlspecialchars($user['no_hp']) ?></p>
                <p><strong>KTP Number:</strong> <?= htmlspecialchars($user['no_ktp']) ?></p>
                <p><strong>Address:</strong> <?= htmlspecialchars($user['alamat']) ?></p>
                <p><strong>Joined At:</strong> <?= htmlspecialchars($user['created_at']) ?></p>
            </div>

            <!-- Logout Button -->
            <form action="dashboard.php" method="POST" class="logout-form">
                <button type="submit" name="logout" class="logout-button"
                    onclick="return confirm('Are you sure you want to logout?')">Logout</button>
            </form>

            <!-- Transactions History -->
            <div class="dashboard-content">
                <h2>Rental History</h2>
                <?php if (mysqli_num_rows($result_transaksi) > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Transaction ID</th>
                            <th>Car</th>
                            <th>Rental Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($transaksi = mysqli_fetch_assoc($result_transaksi)): ?>
                        <tr>
                            <td><?= htmlspecialchars($transaksi['id']) ?></td>
                            <td><?= htmlspecialchars($transaksi['nama_mobil']) ?></td>
                            <td><?= htmlspecialchars($transaksi['tanggal_mulai']) ?> -
                                <?= htmlspecialchars($transaksi['tanggal_selesai']) ?></td>
                            <td>
                                <a href="rental-detail.php?id=<?= $transaksi['id'] ?>" class="btn-detail">See Detail</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>

                <?php else: ?>
                <p><i>No transactions found.</i></p>
                <?php endif; ?>
            </div>
        </div>
    </section>
    <!-- Dashboard end -->

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
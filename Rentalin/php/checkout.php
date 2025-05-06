<?php
session_start();
include 'db.php';  

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['car_id'])) {
    echo "Mobil tidak ditemukan.";
    exit();
}

$mobil_id = $_GET['car_id'];

$query = "SELECT * FROM mobil WHERE id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $mobil_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$mobil = mysqli_fetch_assoc($result);

if (!$mobil || $mobil['kuantitas'] == 0) {
    echo "Mobil tidak tersedia atau stok habis.";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tanggal_mulai = $_POST['tanggal_mulai'];
    $tanggal_selesai = $_POST['tanggal_selesai'];

    if (strtotime($tanggal_selesai) < strtotime($tanggal_mulai)) {
        echo "Tanggal selesai tidak boleh lebih awal dari tanggal mulai.";
        exit();
    }

    $date1 = new DateTime($tanggal_mulai);
    $date2 = new DateTime($tanggal_selesai);
    $interval = $date1->diff($date2);
    $durasi_hari = $interval->days;

    $total_harga = $mobil['harga_per_hari'] * ( $durasi_hari + 1 );

    $_SESSION['transaction'] = [
        'mobil_id' => $mobil_id,
        'tanggal_mulai' => $tanggal_mulai,
        'tanggal_selesai' => $tanggal_selesai,
        'total_harga' => $total_harga,
    ];

    header("Location: payment.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Checkout - Rentalin</title>
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
    <!-- Navbar start -->
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
    <!-- Navbar end -->

    <!-- Checkout Form -->
    <section class="checkout">
        <div class="container">
            <h2> Rent - <?php echo $mobil['nama']; ?></h2>
            <form action="checkout.php?car_id=<?php echo $mobil_id; ?>" method="POST">
                <label for="tanggal_mulai">Start Date</label>
                <input type="date" id="tanggal_mulai" name="tanggal_mulai" required />

                <label for="tanggal_selesai">End Date</label>
                <input type="date" id="tanggal_selesai" name="tanggal_selesai" required />

                <p><strong>Total Price: </strong>
                    Rp<?php echo number_format($mobil['harga_per_hari'], 0, ',', '.'); ?>/day</p>
                <p><strong>Car Stock: </strong><?php echo $mobil['kuantitas']; ?> units available</p>
                <button type="submit">Confirm Rental</button>
                <p><b>Important Notice:</b> Please bring your ID card when picking up the rental car, as we use the
                    information
                    from your account
                    when you log in.</p>
            </form>
        </div>
    </section>
    <!-- Checkout Form End -->


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
            <p>PT. Rentalin Otomotif Sejahtera Bersama | &copy; 2025
            </p>
        </div>
    </footer>
    <!-- Footer end -->
</body>

</html>
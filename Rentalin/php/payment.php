<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_SESSION['transaction'])) {
    echo "No transaction found.";
    exit();
}

$transaction = $_SESSION['transaction'];  

$user_id = $_SESSION['user_id'];
$query_user = "SELECT nama, email FROM users WHERE id = ?";
$stmt_user = mysqli_prepare($conn, $query_user);
mysqli_stmt_bind_param($stmt_user, "i", $user_id);
mysqli_stmt_execute($stmt_user);
mysqli_stmt_bind_result($stmt_user, $nama, $email);
mysqli_stmt_fetch($stmt_user);
mysqli_stmt_close($stmt_user);

$mobil_id = $transaction['mobil_id'];
$query_mobil = "SELECT nama FROM mobil WHERE id = ?";
$stmt_mobil = mysqli_prepare($conn, $query_mobil);
mysqli_stmt_bind_param($stmt_mobil, "i", $mobil_id);
mysqli_stmt_execute($stmt_mobil);
mysqli_stmt_bind_result($stmt_mobil, $nama_mobil);
mysqli_stmt_fetch($stmt_mobil);
mysqli_stmt_close($stmt_mobil);

// Midtrans Snap API payload
$midtrans_payload = [
    'transaction_details' => [
        'order_id' => 'ORDER-' . uniqid(),
        'gross_amount' => (int)$transaction['total_harga'],
    ],
    'customer_details' => [
        'first_name' => $nama,
        'email' => $email,
    ],
];

// Midtrans API Request
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://app.sandbox.midtrans.com/snap/v1/transactions");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($midtrans_payload));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Accept: application/json',
    'Authorization: Basic ' . base64_encode("SB-Mid-server-7jQtDeJZL6B85zJN_tTvRrGb" . ':')
]);

$response = curl_exec($ch);

if (curl_errno($ch)) {
    echo "Curl Error: " . curl_error($ch);
    exit();
}

curl_close($ch);

$snap = json_decode($response, true);

if (!isset($snap['token'])) {
    echo "Failed to retrieve Midtrans token.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Payment</title>
    <!-- Midtrans -->
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="SB-Mid-client-f0Im8VM77Rvj_yjA">
    </script>

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

    <!-- Payment start -->
    <section class="payment-section">
        <div class="payment-container">
            <h2>Pay for Car Rental</h2>
            <p class="car-name"><?php echo $nama_mobil; ?></p>
            <p class="total-price">Total Price:
                <strong>Rp<?php echo number_format($transaction['total_harga'], 0, ',', '.'); ?></strong>
            </p>

            <button id="pay-button" <?php echo isset($snap['token']) ? '' : 'disabled'; ?>> Pay Now </button>
        </div>
    </section>

    <script type="text/javascript">
    document.getElementById('pay-button').onclick = function() {
        const token = '<?php echo isset($snap['token']) ? $snap['token'] : ''; ?>';
        if (token) {
            snap.pay(token, {
                onSuccess: function(result) {
                    const transactionData = <?php echo json_encode($transaction); ?>;
                    window.location.href = "transaction_success.php?status=success&order_id=" + result
                        .order_id +
                        "&total_harga=" + transactionData.total_harga +
                        "&mobil_id=" + transactionData.mobil_id +
                        "&tanggal_mulai=" + transactionData.tanggal_mulai +
                        "&tanggal_selesai=" + transactionData.tanggal_selesai;
                },
                onPending: function() {
                    alert("Payment is pending... Please check your email!");
                },
                onError: function() {
                    alert("Payment failed");
                }
            });
        } else {
            alert("Token not available. Please try again later.");
        }
    };
    </script>
    <!-- Payment end -->



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
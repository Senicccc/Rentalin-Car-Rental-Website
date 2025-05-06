<?php
session_start();
include('db.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $no_hp = $_POST['no_hp'];
    $no_ktp = $_POST['no_ktp'];
    $alamat = $_POST['alamat'];

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $check = mysqli_prepare($conn, "SELECT * FROM users WHERE username = ?");
    mysqli_stmt_bind_param($check, "s", $username);
    mysqli_stmt_execute($check);
    $result = mysqli_stmt_get_result($check);

    if (mysqli_num_rows($result) > 0) {
        $message = "Username already exists!";
        $status = "error";
    } else {
        $stmt = mysqli_prepare($conn, "INSERT INTO users (username, password, nama, email, no_hp, no_ktp, alamat) VALUES (?, ?, ?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "sssssss", $username, $hashed_password, $nama, $email, $no_hp, $no_ktp, $alamat);

        if (mysqli_stmt_execute($stmt)) {
            $message = "Your account has been created!";
            $status = "success";
        } else {
            $message = "Error: " . mysqli_error($conn);
            $status = "error";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Register - Rentalin</title>

    <!-- Favicon -->
    <link rel="shortcut icon" href="../img/favicon.jpg" type="image/x-icon" />

    <!-- CSS -->
    <link rel="stylesheet" type="text/css" href="../css/style.css" />
    <link rel="stylesheet" type="text/css" href="../css/phpstyle.css">

    <!-- Javascript -->
    <script src="../js/script.js"></script>

    <!-- SweetAlert2 CDN for Registered Notification -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Cal+Sans&family=Poppins:wght@300;400;700&display=swap"
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

    <!-- Register Form start -->
    <div class="register-form">
        <h2>Create an Account</h2>
        <form action="register.php" method="POST">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" required />

            <label for="password">Password</label>
            <input type="password" id="password" name="password" required />

            <label for="nama">Full Name</label>
            <input type="text" id="nama" name="nama" required />

            <label for="email">Email</label>
            <input type="email" id="email" name="email" required />

            <label for="no_hp">Phone Number</label>
            <input type="text" id="no_hp" name="no_hp" required />

            <label for="no_ktp">KTP Number</label>
            <input type="text" id="no_ktp" name="no_ktp" required />

            <label for="alamat">Address</label>
            <textarea id="alamat" name="alamat" required></textarea>

            <button type="submit">Register</button>
        </form>
        <p><b>Important Notice: </b>Please input the data exactly as it is, as any changes require contacting the admin.
        </p>
        <p>Already have an account? <a href="login.php">Login</a></p>
    </div>
    <!-- Register Form end -->

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

    <?php if (isset($message)) : ?>
    <script>
    Swal.fire({
        icon: '<?= $status ?>',
        title: '<?= $status === "success" ? "Success!" : "Oops..." ?>',
        text: '<?= $message ?>',
        showConfirmButton: false,
        timer: <?= $status === "success" ? "2000" : "3000" ?>,
        timerProgressBar: true,
        didClose: () => {
            <?php if ($status === "success") : ?>
            window.location.href = 'login.php';
            <?php endif; ?>
        }
    });
    </script>
    <?php endif; ?>
</body>

</html>
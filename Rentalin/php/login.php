<?php
session_start();
include('db.php');

$loginError = false;
$errorMessage = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            header("Location: dashboard.php");
            exit();
        } else {
            $loginError = true;
            $errorMessage = "Wrong password!";
        }
    } else {
        $loginError = true;
        $errorMessage = "Username not found!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login - Rentalin</title>

    <!-- Favicon -->
    <link rel="shortcut icon" href="../img/favicon.jpg" type="image/x-icon" />

    <!-- CSS -->
    <link rel="stylesheet" href="../css/style.css" />
    <link rel="stylesheet" href="../css/phpstyle.css" />

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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

<body class="body-login">
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

    <div class="login-wrapper">
        <div class="login-form">
            <h2>Login</h2>
            <form action="login.php" method="POST">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required />

                <label for="password">Password</label>
                <input type="password" id="password" name="password" required />

                <button type="submit">Login</button>
            </form>
            <p>Don't have an account? <a href="register.php">Register</a></p>
        </div>
    </div>

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

    <!-- SweetAlert2 error message -->
    <?php if ($loginError): ?>
    <script>
    Swal.fire({
        icon: 'error',
        title: 'Login Failed',
        text: '<?= $errorMessage ?>',
        showConfirmButton: true,
        confirmButtonColor: '#d33'
    });
    </script>
    <?php endif; ?>
</body>

</html>
<?php
include 'db.php';

$query = "SELECT * FROM mobil";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Rental Mobil - Rentalin</title>

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

    <style>
    html {
        scroll-behavior: smooth;
    }
    </style>

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
            <a href="#">Rent a Car</a>
            <a href="../index.html#contact">Contact & Location</a>
        </div>
    </nav>
    <!-- Navbar end -->

    <!-- Rental Mobil -->
    <section class="featured-cars">
        <div class="container">
            <h2 style="margin-bottom: 6rem; font-size: 4rem;">Available Cars for Rent</h2>
            <div class="car-list">
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <div class="car-card <?php echo ($row['kuantitas'] == 0) ? 'unavailable' : ''; ?>">
                    <img src="../img/<?php echo $row['foto']; ?>" alt="<?php echo $row['nama']; ?>" />
                    <h3><?php echo $row['nama']; ?></h3>
                    <p>Stock: <?php echo $row['kuantitas']; ?></p>
                    <span class="price">Rp<?php echo number_format($row['harga_per_hari'], 0, ',', '.'); ?>/day</span>
                    <br />
                    <?php if ($row['kuantitas'] > 0): ?>
                    <form action="checkout.php" method="GET">
                        <input type="hidden" name="car_id" value="<?php echo $row['id']; ?>">
                        <button type="submit" class="btn-sewa">Rent This Car</button>
                    </form>
                    <?php else: ?>
                    <button class="btn-sewa" disabled>Out of Stock</button>
                    <?php endif; ?>
                </div>
                <?php endwhile; ?>
            </div>
        </div>
    </section>

    <!-- Footer start -->
    <footer>

        <a href="#" class="navbar-logo">Rental<span>in</span></a>

        <div class="links">
            <a href="../index.html">Home</a>
            <a href="dashboard.php">Dashboard</a>
            <a href="#">Rent a Car</a>
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
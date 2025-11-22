<?php
// Admin-table.php

require_once '../PHP/Config.php';

// Start session to track the logged-in admin
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: ../login.php'); // Redirect to login if not logged in
    exit();
}

$admin_id = $_SESSION['admin_id']; // Get the admin ID from the session

// Fetch admin details from the database
$admin = getAdminById($admin_id); // Fetch the data for the logged-in admin

if ($admin === false) {
    echo "Admin not found.";
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shoprise | Simplify Supply Shoprise!</title>

    <link href="../css/home.css" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=shopping_cart" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Hanken+Grotesk:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link rel="icon" href="../pic/logonatinnobg.png" type="image/x-icon">
</head>

<body>

<!--nav bar-->
<header class="p-3 text-white Bg-briblo">
    <div class="nav-custom">
        <div class="d-flex flex-wrap align-items-center justify-content-between">
            <a href="Admin-index.php"><img class="me-5" src="../pic/logonatin.png" width="65" height="58"></a>
            
            <!-- Navbar Links -->
            <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0 ">
                <li><a href="#" class="text-decoration-none btn fw-bold btn btn-outline-info fw-bold">Home</a></li>
                <li><a href="Admin-user-manage.php" class="text-decoration-none btn fw-bold btn btn-outline-info fw-bold">User Management</a></li>
                <li><a href="Admin-product.php" class="text-decoration-none btn fw-bold btn btn-outline-info fw-bold">Products</a></li>
                <li><a href="Admin-sales.php" class="text-decoration-none btn fw-bold btn btn-outline-info fw-bold">Sales</a></li>
                <li><a href="Admin-tracker.php" class="text-decoration-none btn fw-bold btn btn-outline-info fw-bold">Order Tracker</a></li>
            </ul>

            <!-- Admin Profile and Logout -->
            <div class="d-flex align-items-center">
                <a href="Admin-profile.php" class="btn btn-outline-info fw-bold">
                    Admin
                </a>
                <a href="Admin-profile.php" class="btn rounded-2">  <img src="../pic/Profile/<?php echo htmlspecialchars($admin['profile_picture']); ?>" alt="Profile Picture" class="rounded-circle" width="50" height="50">
                </a>
            </div>
        </div>
    </div>
</header>

<!--body na-->
<!-- White Square Section Below Navbar -->
<div class="main-content">
    <!-- White Square Section -->
    <div class="white-square">
        <div class="text-content">
            <h1>Welcome Back Admin</h1>
        </div>
    </div>
</div>


<!--Information-->
<footer class="font-HK p-3 text-white Bg-briblo justify-content-center align-items-center d-flex flex-column" style="border-top: 6px solid #fde49e;">
    © Copyright SHOPPRISE 2024
</footer>

</body>

</html>
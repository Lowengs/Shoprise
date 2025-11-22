<?php

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
    <title>User Management</title>
    <link href="https://fonts.googleapis.com/css2?family=Rye&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/adminstyles.css">
    <link rel="icon" href="../pic/logonatinnobg.png" type="image/x-icon">
    
    <!-- Include jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <style>
        .huge-button {
            font-size: 24px;
            padding: 20px 40px;
            margin: 20px;
            text-align: center;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .huge-button:hover {
            background-color: #f0f0f0;
        
        }

        .btn-admin {
            background-color: #007bff;
            color: white;
        }

        .btn-user {
            background-color: #28a745;
            color: white;
        }
    </style>
</head>
<body>
<header class="p-3 text-white Bg-briblo">
    <div class="nav-custom">
        <div class="d-flex flex-wrap align-items-center justify-content-between">
            <a href="Admin-index.php"><img class="me-5" src="../pic/logonatin.png" width="65" height="58"></a>
            
            <!-- Navbar Links -->
            <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
                <li><a href="Admin-index.php" class="nav-link fw-bold text-light">Home</a></li>
                <li><a href="Admin-user-manage.php" class="nav-link fw-bold text-light">User Management</a></li>
                <li><a href="Admin-product.php" class="nav-link fw-bold text-light">Products</a></li>
                <li><a href="Admin-sales.php" class="nav-link fw-bold text-light">Sales</a></li>
                <li><a href="Admin-tracker.php" class="nav-link fw-bold text-light">Order Tracker</a></li>
            </ul>

            <!-- Admin Profile and Logout -->
            <div class="d-flex align-items-center">
    <!-- Admin text link -->
    <a href="Admin-profile.php" class="nav-link px-4 fw-bold text-light me-2">Admin</a>
    
    <!-- Admin profile image -->
    <a href="Admin-profile.php" class="btn rounded-2">  <img src="../pic/Profile/<?php echo htmlspecialchars($admin['profile_picture']); ?>" alt="Profile Picture" class="rounded-circle" width="50" height="50">
                </a>
</div>

        </div>
    </div>
</header>


<div class="container mt-5 d-flex justify-content-center align-items-center flex-column">
    <h2 class="text-center fw-bold font-HK">User Management</h2>



<!-- Huge Buttons for Admin and User -->
<div class="container mt-5 d-flex justify-content-center align-items-center flex-column">
    <!-- Buttons for Admin and User -->
    <div class="d-flex gap-3 mb-4">
        <a href="admin-table.php" class="huge-button btn-admin">Admin</a>
        <a href="users-table.php" class="huge-button btn-user">User</a>
    </div>

    <!-- Dynamic Content Area -->
    <div id="content-area" class="mt-4"></div>
</div>

</body>
</html>

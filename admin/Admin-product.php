<?php
// Include the database configuration
require_once '../PHP/Config.php';


// Fetch products from the database

// Admin-table.php


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

$products = getProducts();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shoprise | Simplify Supply Shoprise!</title>
    <link href="../css/adminstyles.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Hanken+Grotesk:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" href="../pic/logonatinnobg.png" type="image/x-icon">
</head>
<body>
    <!-- Navigation Bar -->
    <header class="p-3 text-white Bg-briblo">
    <div class="nav-custom">
        <div class="d-flex flex-wrap align-items-center justify-content-between">
            <a href="Admin-index.php"><img class="me-5" src="../pic/logonatin.png" width="65" height="58"></a>
            
            <!-- Navbar Links -->
            <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0 ">
                <li><a href="Admin-index.php" class="nav-link fw-bold text-light">Home</a></li>
                <li><a href="Admin-user-manage.php" class="nav-link fw-bold text-light">User  Management</a></li>
                <li><a href="Admin-product.php" class="nav-link fw-bold text-light">Products</a></li>
                <li><a href="Admin-sales.php" class="nav-link fw-bold text-light">Sales</a></li>
                <li><a href="Admin-tracker.php" class="nav-link fw-bold text-light">Order Tracker</a></li>
            </ul>

            <!-- Admin Profile and Logout -->
            <div class="d-flex align-items-center">
                <a href="Admin-profile.php" class="nav-link px-4 fw-bold text-light">
                    Admin
                </a>
                <a href="Admin-profile.php" class="btn rounded-2">  <img src="../pic/Profile/<?php echo htmlspecialchars($admin['profile_picture']); ?>" alt="Profile Picture" class="rounded-circle" width="50" height="50">
                </a>
            </div>
        </div>
    </div>
</header>
    <div class="mt-5 p-3">
        <!-- Centered Header -->
        <h2 class="text-center align-items-center">Manage Products</h2>
        
        <!-- Add Sales Report Button -->
        <div class="container d-flex justify-content-center mt-4">
            <a href="Admin-sales.php" class="btn btn-primary text-decoration-none me-3">Sales Report</a>
            <a href="Admin-create.php" class="btn btn-success text-decoration-none">Add Product</a>
        </div>
        

    <!-- Main Content -->
    <div class="container mt-4">
        <table class="table table-striped table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th class="text-center">Image</th>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Stocks</th>
                    <th class="text-center">Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="productTable">
                <?php if ($products && $products->num_rows > 0): ?>
                    <?php while ($row = $products->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><img src="<?php echo htmlspecialchars($row['image_url']); ?>" alt="Product Image" style="max-width: 100px;"></td>
                            <td><?php echo $row['name']; ?></td>
                            <td>₱<?php echo number_format($row['price'], 2); ?></td>
                            <td><?php echo $row['stock']; ?></td>
                            <td><?php echo htmlspecialchars($row['description']); ?></td>
                            <td class="text-center">
                                <a href="Admin-edit.php?id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm"><img src="../pic/edit.png" height="30px" width="30px" alt="Edit"></a>
                                <a href="Admin-delete.php?id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this product?')"><img src="../pic/delete.png" height="30px" width="30px" alt="Delete"></a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center">No products found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Bootstrap JS and dependencies -->

</body>
</html>
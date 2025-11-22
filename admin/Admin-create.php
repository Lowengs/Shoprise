<?php
require_once '../PHP/Config.php';

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $stock = $_POST['stocks'];
    $image = $_FILES['image'];

    // Validate form data
    if (empty($name) || empty($price) || empty($stock) || empty($image['name'])) {
        $error = "All fields are required.";
    } else {
        $result = addProduct($name, $description, $price, $stock, $image);

        if ($result === true) {
            $success = "Product added successfully.";
            header("Location: Admin-product.php");
            exit();
        } else {
            $error = $result;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product | Shoprise</title>
    <link rel="stylesheet" href="../css/adminstyles.css">
    <link rel="icon" href="../pic/logonatinnobg.png" type="image/png">
</head>
<body>
<header class="p-3 text-white Bg-briblo">
    <div class="nav-custom">
        <div class="d-flex flex-wrap align-items-center justify-content-between">
            <a href="Admin-index.php"><img class="me-5" src="../pic/logonatin.png" width="65" height="58"></a>
            
            <!-- Navbar Links -->
            <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0 ">
            <li><a href="Admin-index.php" class="nav-link  fw-bold text-light">Home</a></li>
                <li><a href="Admin-user-manage.php" class="nav-link  fw-bold text-light">User Management</a></li>
                <li><a href="Admin-product.php" class="nav-link  fw-bold text-light">Products</a></li>
                <li><a href="Admin-sales.php" class="nav-link  fw-bold text-light">Sales</a></li>
                <li><a href="Admin-tracker.php" class="nav-link  fw-bold text-light">User Tracker</a></li>
            </ul>

            <!-- Admin Profile and Logout -->
            <div class="d-flex align-items-center">
                <a href="Admin-profile.php" class="nav-link px-4 fw-bold text-light">
                    Admin
                </a>
                <a href="Admin-profile.php" class="btn rounded-2"><img src="../pic/pfp.png" width="50" height="50"></a>
            </div>
        </div>
    </div>
</header>


    <div class="container mt-5 d-flex justify-content-center">
        <div class="col-md-8">
            <h2 class="mb-4 text-center">Add New Product</h2>
            <!-- Form to add product -->
            <form method="POST" action="Admin-create.php" enctype="multipart/form-data">
                <div class="mb-3 text-start">
                    <label for="name" class="form-label">Product Name</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
                <div class="mb-3 text-start">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                </div>
                <div class="mb-3 text-start">
                    <label for="price" class="form-label">Price</label>
                    <input type="number" class="form-control" id="price" name="price" step="0.01" required>
                </div>
                <div class="mb-3 text-start">
                    <label for="stocks" class="form-label">Stocks</label>
                    <input type="number" class="form-control" id="stocks" name="stocks" required>
                </div>
                <div class="mb-3 text-start">
                    <label for="image" class="form-label">Product Image</label>
                    <input type="file" class="form-control" id="image" name="image" accept="image/*">
                </div>
                <!-- Action buttons -->
                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-success">Add Product</button>
                    <a href="Admin-product.php" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
            <?php if (isset($error)): ?>
                <div class="alert alert-danger mt-3"><?php echo $error; ?></div>
            <?php endif; ?>
            <?php if (isset($success)): ?>
                <div class="alert alert-success mt-3"><?php echo $success; ?></div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Footer -->
    <footer class="font-HK p-3 text-white Bg-briblo text-center mt-5" style="border-top: 6px solid #fde49e;">
        © Copyright SHOPPRISE 2024
    </footer>
</body>
</html>
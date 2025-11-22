<?php
require_once '../PHP/Config.php';

// Get product ID from query parameter
$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch product details
$product = getProductById($product_id);

if (!$product) {
    die("Product not found.");
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $stock = $_POST['stocks'];
    $image = $_FILES['image'];

    // Validate form data
    if (empty($name) || empty($price) || empty($stock)) {
        $error = "Name, price, and stock are required.";
    } else {
        $result = updateProduct($product_id, $name, $description, $price, $stock, $image);

        if ($result === true) {
            $success = "Product updated successfully.";
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
    <title>Edit Product | Shoprise</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/adminstyles.css" rel="stylesheet">
    <link rel="icon" href="../pic/logonatinnobg.png" type="image/x-icon">
</head>
<body>
<header class="p-3 text-white Bg-briblo">
    <div class="nav-custom">
        <div class="d-flex flex-wrap align-items-center justify-content-between">
            <a href="Admin-index.php"><img class="me-5" src="../pic/logonatin.png" width="65" height="58"></a>

            <!-- Navbar Links -->
            <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0 ">
                <li><a href="Admin-index.php" class="nav-link fw-bold text-light">Home</a></li>
                <li><a href="Admin-user-manage.php" class="nav-link fw-bold text-light">User Management</a></li>
                <li><a href="Admin-product.php" class="nav-link fw-bold text-light">Products</a></li>
                <li><a href="Admin-sales.php" class="nav-link fw-bold text-light">Sales</a></li>
                <li><a href="Admin-tracker.php" class="nav-link fw-bold text-light">Order Tracker</a></li>
            </ul>

            <!-- Admin Profile and Logout -->
            <div class="d-flex align-items-center">
                <a href="Admin-profile.php" class="nav-link px-4 fw-bold text-light">Admin</a>
                <a href="Admin-profile.php" class="btn rounded-2"><img src="../pic/pfp.png" width="50"
                        height="50"></a>
            </div>
        </div>
    </div>
</header>

<div class="container mt-5">
    <h2 class="mb-4 text-center fw-bold">Edit Product</h2>
    <form method="POST" action="Admin-edit.php?id=<?php echo $product['id']; ?>" enctype="multipart/form-data">
        <div class="row g-4">
            <!-- Product Name -->
            <div class="col-md-6">
                <label for="name" class="form-label fw-bold">Product Name</label>
                <input 
                    type="text" 
                    class="form-control" 
                    id="name" 
                    name="name" 
                    value="<?php echo htmlspecialchars($product['name']); ?>" 
                    required>
            </div>

            <!-- Description -->
            <div class="col-md-12">
                <label for="description" class="form-label fw-bold">Description</label>
                <textarea 
                    class="form-control" 
                    id="description" 
                    name="description" 
                    rows="3" 
                    placeholder="Enter product description here"
                    required><?php echo htmlspecialchars($product['description']); ?></textarea>
            </div>

            <!-- Price -->
            <div class="col-md-4">
                <label for="price" class="form-label fw-bold">Price</label>
                <input 
                    type="number" 
                    class="form-control" 
                    id="price" 
                    name="price" 
                    value="<?php echo htmlspecialchars($product['price']); ?>" 
                    step="0.01" 
                    required>
            </div>

            <!-- Stocks -->
            <div class="col-md-4">
                <label for="stocks" class="form-label fw-bold">Stocks</label>
                <input 
                    type="number" 
                    class="form-control" 
                    id="stocks" 
                    name="stocks" 
                    value="<?php echo htmlspecialchars($product['stock']); ?>" 
                    required>
            </div>

            <!-- Product Image -->
            <div class="col-md-4 text-center">
                <label for="image" class="form-label fw-bold">Product Image</label>
                <input 
                    type="file" 
                    class="form-control" 
                    id="image" 
                    name="image" 
                    accept="image/*">
                <img 
                    src="<?php echo htmlspecialchars($product['image_url']); ?>" 
                    alt="Current Product Image" 
                    class="img-thumbnail mt-3" 
                    style="max-width: 150px;">
            </div>
        </div>

        <!-- Buttons -->
        <div class="mt-4 text-center">
            <button type="submit" class="btn btn-success btn-lg me-2">
                <i class="fas fa-check-circle"></i> Update Product
            </button>
            <a href="Admin-product.php" class="btn btn-secondary btn-lg">
                <i class="fas fa-times-circle"></i> Cancel
            </a>
        </div>

        <!-- Alerts -->
        <?php if (isset($error)): ?>
            <div class="alert alert-danger mt-3"><?php echo $error; ?></div>
        <?php endif; ?>
        <?php if (isset($success)): ?>
            <div class="alert alert-success mt-3"><?php echo $success; ?></div>
        <?php endif; ?>
    </form>
</div>

    <!-- Footer -->
    <footer class="font-HK p-3 text-white Bg-briblo text-center mt-5" style="border-top: 6px solid #fde49e;">
        © Copyright SHOPPRISE 2024
    </footer>
</body>
</html>
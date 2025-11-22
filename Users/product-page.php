<?php
// Retrieve the product details from the URL
$productId = isset($_GET['product_id']) ? (int) $_GET['product_id'] : 0;
$productName = isset($_GET['product_name']) ? urldecode($_GET['product_name']) : '';
$productPrice = isset($_GET['price']) ? $_GET['price'] : '';
$productLocation = isset($_GET['location']) ? urldecode($_GET['location']) : '';
$productImage = isset($_GET['product_image']) ? urldecode($_GET['product_image']) : '';

// Ensure productPrice is a float
if (is_numeric($productPrice)) {
    $productPrice = (float) $productPrice;
} else {
    $productPrice = 0.0;
}

// Include the database configuration
require_once '../PHP/Config.php';

// Fetch product stock and description from the database
$stockQuery = "SELECT stock, description FROM products WHERE id = ?";
$stmt = $conn->prepare($stockQuery);
$stmt->bind_param("i", $productId); // 'i' denotes that the parameter is an integer
$stmt->execute();
$result = $stmt->get_result();
$productData = $result->fetch_assoc();

$stockQuantity = $productData ? $productData['stock'] : 0;
$description = $productData ? $productData['description'] : 'No description available';

// Ensure the product price is correctly set
if ($productPrice == 0.0) {
    // Fetch the price from the database if it's not set correctly
    $priceQuery = "SELECT price FROM products WHERE id = ?";
    $stmt = $conn->prepare($priceQuery);
    $stmt->bind_param("i", $productId);
    $stmt->execute();
    $result = $stmt->get_result();
    $priceData = $result->fetch_assoc();

    if ($priceData) {
        $productPrice = (float) $priceData['price'];
    } else {
        $productPrice = 0.0;
    }
}


// Start session to track the logged-in user
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php'); // Redirect to login if not logged in
    exit();
}



$user_id = $_SESSION['user_id']; // Get the user ID from the session

// Fetch user details from the database
$sql = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc(); // Fetch user details
} else {
    echo "User not found.";
    exit();
}


?>

<style>
.product-image {
    width: 400px;
    height: 400px;
    object-fit: cover; /* Ensures the image covers the area without distorting */
}

/* Styling for the product container */
.product-container {
    padding: 30px;
    background-color: #f8f9fa; /* Light background color */
    border-radius: 10px;
    box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.1);
}

.product-image {
    max-width: 100%;
    height: auto;
    transition: transform 0.3s ease-in-out;
}

.product-image:hover {
    transform: scale(1.05); /* Add zoom effect */
}

.price-container {
    font-size: 1.5rem;
    font-weight: bold;
    color: #e74c3c; /* Red for price */
}

.product-description {
    font-size: 1rem;
    color: #6c757d; /* Muted description text */
}

.stock-info {
    font-size: 1.2rem;
    font-weight: 600;
    color: #28a745; /* Green for stock info */
}

.btn-buy {
    background-color: #2d87f0; /* Custom background color for buttons */
    border: none;
    font-size: 1.1rem;
    padding: 12px 30px;
    border-radius: 5px;
    transition: background-color 0.3s ease, transform 0.2s ease;
}

.btn-buy:hover {
    background-color: #1f6eae; /* Darken button color on hover */
    transform: scale(1.05); /* Slight button scale on hover */
}

/* Responsive Design */
@media (max-width: 767px) {
    .product-container {
        padding: 20px;
    }

    .product-image {
        max-height: 350px; /* Set a max height for smaller screens */
        object-fit: cover; /* Maintain aspect ratio */
    }

    .btn-buy {
        font-size: 1rem;
        padding: 10px 20px;
    }
}
</style>

<title><?php echo htmlspecialchars($productName); ?> | Simplify Supply Shoprise!</title>

<link href="../css/home.css" rel="stylesheet">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=shopping_cart" />
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Hanken+Grotesk:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
<link rel="icon" href="../pic/logonatinnobg.png" type="image/x-icon">

<header class="p-3 text-white Bg-briblo">
    <div class="nav-custom">
        <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
            <a href="homepage.php"><img src="../pic/logonatin.png" width="65" height="58" alt="Shopprise Logo"></a>
            <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
                <li><a href="homepage.php" class="nav-link text-light fw-bold px-5" style="font-size: 30px;">Home</a></li>
                <li><a href="product.php" class="nav-link fw-bold me-2 nav-barok" style="font-size: 30px;">Products</a></li>
                <li><a href="about.php" class="nav-link fw-bold px-5 text-light" style="font-size: 30px;">About</a></li>
            </ul>
            <div class="d-flex align-items-center">
                <a href="shopcart.php" class="btn btn-outline-info fw-bold " style="box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);">
                    <span class="material-symbols-outlined" style="font-size: 30px;">
                        shopping_cart
                    </span>
                </a>
                <?php
                // Assuming the user's profile picture URL is stored in the session
           
                $defaultPfp = '../pic/pfp.png'; // Default profile picture
                $userPfp = isset($_SESSION['user_pfp']) && !empty($_SESSION['user_pfp']) ? $_SESSION['user_pfp'] : $defaultPfp;
                ?>
                <a href="profile.php" class="btn rounded-2">
                    <img src="../pic/Profile/<?php echo htmlspecialchars($user['profile_picture']); ?>" alt="Profile Picture" width="50" height="50" class="rounded-circle">
                </a>
            </div>
        </div>
    </div>
</header>

<body style="background-color: #d8c7b1; /* Darker brown color */">

<div class="container product-container mt-5 mb-5">
    <div class="row">
        <!-- Product Image -->
        <div class="col-md-6">
            <img src="../pic/<?php echo htmlspecialchars($productImage); ?>" alt="Product Image" class="product-image img-fluid rounded shadow-lg">
        </div>

        <!-- Product Details - Move text to the right side -->
        <div class="col-md-6 d-flex flex-column justify-content-center">
            <h1 class="fw-bold mb-3"><?php echo htmlspecialchars($productName); ?></h1>
            <div class="price-container mb-3">
                <span class="price display-4 text-warning fw-bold">₱<?php echo number_format($productPrice, 2); ?></span>
            </div>
            <p><strong>Description:</strong> <span class="product-description text-muted"><?php echo htmlspecialchars($description); ?></span></p>
            <p><strong>Stock:</strong> <span class="stock-info"><?php echo $stockQuantity; ?> pieces available</span></p>
            
            <div class="d-flex gap-3 mb-3">
                <form method="POST" action="shopcart.php">
                    <input type="hidden" name="product_id" value="<?php echo $productId; ?>">
                    <input type="hidden" name="quantity" value="1">
                    <button type="submit" class="btn btn-outline-info fw-bold bg-briblo mt-2" name="add_to_cart">
                        Add to Cart
                    </button>
                </form>
                <form method="POST" action="summary.php">
                    <input type="hidden" name="product_id" value="<?php echo $productId; ?>">
                    <input type="hidden" name="quantity" value="1">
                    <button type="submit" class="btn btn-outline-info fw-bold bg-briblo mt-2" name="add_to_cart">
                        Buy Now
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
</body>

<footer class="font-HK p-3 text-white Bg-briblo justify-content-center align-items-center d-flex flex-column" style="border-top: 6px solid #fde49e;">
    © Copyright SHOPPRISE 2024
</footer>
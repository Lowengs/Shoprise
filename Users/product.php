<?php
//product.php
require_once '../PHP/Config.php';

// Fetch products from the database
$products = getProducts();

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

<link href="../css/styles.css" rel="stylesheet">
<link rel="stylesheet"
    href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=shopping_cart" />
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Hanken+Grotesk:ital,wght@0,100..900;1,100..900&display=swap"
    rel="stylesheet">
<link rel="icon" href="../pic/logonatinnobg.png" type="image/x-icon">

<title>Products | Shoprise </title>

<!-- Navbar -->
<header class="p-3 text-white Bg-briblo">
    <div class="nav-custom">
        <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
            <a href="homepage.php"><img class="" src="../pic/logonatin.png" width="65" height="58"></a>
            <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
                <li><a href="homepage.php" class="nav-link text-light fw-bold px-5" style="font-size: 30px;">Home</a>
                </li>
                <li><a href="product.php" class="nav-link fw-bold px-5 nav-barok" style="font-size: 30px;">Products</a>
                </li>
                <li><a href="about.php" class="nav-link px-4 fw-bold text-light" style="font-size: 30px;">About</a></li>
            </ul>
            <!-- Inline Flex for Login, Sign-up, and Cart -->
            <div class="d-flex align-items-center">
                <a href="shopcart.php" class="btn btn-outline-info fw-bold"
                    style="box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);">
                    <span class="material-symbols-outlined" style="font-size: 30px;">shopping_cart</span>
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

<!-- Search bar -->
<div class="container d-flex justify-content-center mt-4">
    <div class="input-group w-50">
        <input id="searchInput" type="text" class="form-control" placeholder="Search for products...">
        <button id="searchButton" class="btn btn-warning">
            <img src="../pic/search.png" width="30" height="30" alt="Search">
        </button>
    </div>
</div>

<!-- Search Result Message -->
<div class="container mt-3">
    <h5 id="searchMessage" class="text-center text-info"></h5>
</div>

<!-- Add a modal for messaging -->
<div class="modal fade" id="messageModal" tabindex="-1" aria-labelledby="messageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="messageModalLabel">Send a Message</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="messageForm">
                    <input type="hidden" id="productId" name="product_id">
                    <div class="mb-3">
                        <label for="message" class="form-label">Your Message</label>
                        <textarea class="form-control" id="message" name="message" rows="4" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary w-100" style="background-color: #fde49e; color: #000;">Send Message</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Products -->
<div id="productContainer" class="main-content mt-5">
    <div class="container">
        <div class="row" id="productList">
            <?php if ($products && $products->num_rows > 0): ?>
                <?php while ($row = $products->fetch_assoc()): ?>
                    <?php
                    // Prepare product details for URL
                    $productId = $row['id']; // Assuming 'id' is the primary key for the product
                    $productName = urlencode($row['name']);
                    $productPrice = number_format($row['price'], 2);
                    $productLocation = urlencode('Pasay City');
                    $productImage = urlencode($row['image_url']); // Use the image URL or base64 encoding as needed
                    ?>

                    <div class="col-md-3 text-decoration-none">
                        <a href="product-page.php?product_id=<?php echo $productId; ?>&product_name=<?php echo $productName; ?>&price=<?php echo $productPrice; ?>&location=<?php echo $productLocation; ?>&product_image=<?php echo $productImage; ?>"
                            class="text-decoration-none text-dark">
                            <div class="product-card">
                                <img src="<?php echo $row['image_url']; ?>" alt="<?php echo htmlspecialchars($row['name']); ?>"
                                    class="img-fluid" style="object-fit: cover;">
                                <div class="d-flex justify-content-between align-items-start mt-2">
                                    <div class="pt-2" style="text-align: left;">
                                        <h5><?php echo htmlspecialchars($row['name']); ?></h5>
                                        <p>₱<?php echo number_format($row['price'], 2); ?></p>
                                        <p class="product-location"><i class="bi bi-geo-alt-fill"></i> Pasay City</p>
                                    </div>
                                    <div class="action-buttons d-flex flex-column align-items-center justify-content-center">
                                        <form method="POST" action="shopcart.php" class="d-flex flex-column align-items-center justify-content-center">
                                            <input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">
                                            <input type="hidden" name="quantity" value="1">
                                            <button type="submit" class="btn btn-outline-info fw-bold bg-briblo mt-2" name="add_to_cart">
                                                <span class="material-symbols-outlined" style="font-size: 25px;">shopping_cart</span>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="col-12 text-center">
                    <p>No products found.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<script src="../js/search.js"></script>


<footer class="font-HK p-3 text-white Bg-briblo justify-content-center align-items-center d-flex flex-column"
    style="border-top: 6px solid #fde49e;">
    © Copyright SHOPPRISE 2024
</footer>

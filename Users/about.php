<?php
require_once '../PHP/Config.php';

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

<link rel="stylesheet" href="../css/styles.css">
<link rel="icon" href="../pic/logonatinnobg.png" type="image/x-icon">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=shopping_cart" />

<title>About | Shopprise!</title>
<!-- Nav Bar -->
<header class="p-3 text-white Bg-briblo">
    <div class="nav-custom">
        <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
            <a href="homepage.php"><img src="../pic/logonatin.png" width="65" height="58" alt="Shopprise Logo"></a>
            <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
                <li><a href="homepage.php" class="nav-link text-light fw-bold px-5" style="font-size: 30px;">Home</a></li>
                <li><a href="product.php" class="nav-link px-4 fw-bold text-bri text-light" style="font-size: 30px;">Products</a></li>
                <li><a href="about.php" class="nav-link fw-bold px-5 nav-barok" style="font-size: 30px;">About</a></li>
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


<div class="container p-4 d-flex flex-column pt-5 align-items-center text-center font-HK">
    <h1 class="bg-briblo p-3 rounded-2 text-light fw-bold"> ABOUT US</h1>
</div>
<!-- Main Content -->

<div class="custom-main-content font-HK ">
    <!-- White Square Section -->
    <div class="custom-white-square text-center">
        <div class="custom-text-content">
            <h1 class="fw-bold " style="color:  #b48d00">SHOP <span class="text-briblo fw-bold">RISE</span></h1>
            <p class="fw-bold">Your one-stop solution for shop commerce in the Philippines. From barcode printers to bond paper and ink, we’ve got everything your business needs to thrive.</p>
        </div>
        <!-- Button aligned to the center -->
    </div>
</div>

<!--vision mission-->
<div class="container-fluid mb-4">
    <div class="row justify-content-center">
        <!-- Left Image Section for Vision -->
        <div class="col-md-4 p-0">
            <img src="../pic/carhl.jpg" class="w-100 h-100" style="object-fit: cover;" alt="About Us Image">
        </div>

        <!-- Right Vision Section -->
        <div class="col-md-8 d-flex align-items-center bg-briblo p-0 text-light font-HK">
            <div class="w-100 p-4 text-end">
                <div class="me-5" style="display: inline-block; text-align: right;">
                    <h1 class="fw-bold" style="color: #d8c7b1;">Vision</h1>
                    <p class="text-light">To be the leading online platform offering a diverse range of high-quality products, creating exceptional shopping experiences that surprise and delight customers worldwide.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid mb-4">
    <div class="row justify-content-center ">
        <!-- Left Mission Section -->
        <div class="col-md-8 d-flex align-items-center justify-content-center bg-briblo p-0 text-light font-HK">
            <div class="w-100 p-4 text-start">
                <div class="ms-5" style="display: inline-block; text-align: left;">
                    <h1 class="fw-bold" style="color: #d8c7b1;">Mission</h1>
                    <p class="text-light">At Shopprise, our mission is to provide customers with a seamless online shopping 
                        experience, offering carefully curated products at competitive prices. We aim to deliver excellent customer 
                        service, ensuring each shopping journey is efficient, enjoyable, and memorable. Through innovation and 
                        reliability, we strive to bring convenience, trust, and satisfaction to every shopper.</p>
                </div>
            </div>
        </div>

        <!-- Right Image Section for Mission -->
        <div class="col-md-4 p-0">
            <img src="../pic/lowe.jpg" class="w-100 h-100 object-cover" alt="About Us Image">
        </div>
    </div>
</div>

<div class="container-fluid mb-4">
    <div class="row justify-content-center">
        <!-- Left Section -->
        <div class="col-md-4 p-0">
            <!-- image or something -->
        </div>
    
        <!-- Right Section -->
        <div class="col-md-8 d-flex align-items-center bg-briblo p-0 text-light font-HK">
            <div class="w-100 p-4 text-end">
                <div class="me-5" style="display: inline-block; text-align: right;">
                    <h1 class="fw-bold" style="color: #d8c7b1;">Buy Online with Shoprise</h1>
                    <p class="text-light">Shopping online has never been easier with Shoprise! Our user-friendly platform allows you to browse and 
                        purchase products from the comfort of your home. Whether you're on your phone, tablet, or computer, you can enjoy a 
                        smooth and hassle-free shopping journey. With a few clicks, you'll have access to a wide variety of high-quality products 
                        from top brands. Enjoy a seamless checkout process, and rest assured that your orders are handled with care and precision.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<footer class="font-HK p-3 text-white Bg-briblo justify-content-center align-items-center d-flex flex-column" >
    © Copyright SHOPPRISE 2024
</footer>

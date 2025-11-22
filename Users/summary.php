<?php
session_start();
require_once '../PHP/Config.php';

// Initialize cart if not set
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Function to get the current date
function getCurrentDate()
{
    return date('d F, Y');
}

// Function to generate a unique order number
// Function to generate a unique order number
function generateOrderNumber()
{
    $orderNumber = 'ORD' . date('YmdHis');
    $_SESSION['order_number'] = $orderNumber; // Store the order number in the session
    return $orderNumber;
}

// Function to get the total price of the cart
function getCartTotal()
{
    $total = 0;
    foreach ($_SESSION['cart'] as $product_id => $quantity) {
        $product = getProductById($product_id);
        if ($product) {
            $total += $product['price'] * $quantity;
        }
    }
    return $total;
}

// Function to concatenate address components into a full address
function getFullAddress($street, $city, $province)
{
    $parts = array_filter([$street, $city, $province]); // Remove empty fields
    return implode(', ', $parts); // Join the remaining parts with a comma
}

// Retrieve user ID from session
$user_id = $_SESSION['user_id']; 

// Fetch the user's address from the database
$sql = "SELECT address AS street, city, province FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user_data = $result->fetch_assoc();
$stmt->close();

// Generate the full address using the retrieved data
$full_address = isset($user_data)
    ? getFullAddress($user_data['street'], $user_data['city'], $user_data['province'])
    : 'No address available';

    $order_date = getCurrentDate();
    $order_number = generateOrderNumber();
    $payment_method = 'Cash On Delivery';
    $shipping_address = $full_address;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation</title>
    <link rel="icon" href="../pic/logonatinnobg.png">
    <link href="../css/styles.css" rel="stylesheet">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=home" />
</head>

<body>
    <!-- Header -->
    <header class="p-2 text-white Bg-briblo">
        <div class="nav-custom d-flex justify-content-between align-items-center">
            <!-- Center Logo -->
            <a href="homepage.php">
                <div class="d-flex justify-content-center flex-grow-1 gap-3">
                    <img src="../pic/logonatinnobg.png" width="90px" height="80px" alt="Shoprise Logo">
                </div>
            </a>
        </div>
    </header>

    <!-- Main Container -->
    <div class="container my-5" style="background-color: #ffffff;">
        <!-- Order Confirmation Section -->
        <div class="text-center mb-4">
            <img src="../pic/logonatinnobg.png" alt="shoprise" height="100px" width="110px" class="m-4">
            <h4>Your order has been confirmed and will be shipping soon.</h4>
        </div>

        <!-- Order Details Row -->
        <div class="row border-top border-bottom py-3">
    <div class="col-md-3">
        <strong>Order Date</strong><br> <?php echo $order_date; ?>
    </div>
    <div class="col-md-3">
        <strong>Order Number</strong><br> <?php echo $order_number; ?>
    </div>
    <div class="col-md-3">
        <strong>Payment</strong><br> <?php echo $payment_method; ?>
    </div>
    <div class="col-md-3">
        <strong>Address</strong><br>
        <?php echo $shipping_address; ?>
    </div>
</div>

        <!-- Product Section (dynamically generated) -->
        <div class="product-list">
            <?php
            $total = 0;
            foreach ($_SESSION['cart'] as $product_id => $quantity) {
                $product = getProductById($product_id);
                if ($product) {
                    $subtotal = $product['price'] * $quantity;
                    $total += $subtotal;
                    ?>
                    <div class="row my-3">
                        <div class="col-md-2">
                            <img src="<?php echo htmlspecialchars($product['image_url']); ?>"
                                alt="<?php echo htmlspecialchars($product['name']); ?>" class="img-fluid"
                                style="max-width: 100px;">
                        </div>
                        <div class="col-md-6">
                            <strong><?php echo htmlspecialchars($product['name']); ?></strong><br>
                            ₱<?php echo number_format($product['price'], 2); ?>
                        </div>
                        <div class="col-md-2 text-center">
                            Qty: <?php echo $quantity; ?>
                        </div>
                        <div class="col-md-2 text-end">
                            ₱<?php echo number_format($subtotal, 2); ?>
                        </div>
                    </div>
                    <?php
                }
            }
            ?>
        </div>

        <!-- Pricing Details -->
        <div class="row">
            <div class="col-md-8"></div>
            <div class="col-md-4">
                <ul class="list-unstyled">
                    <li class="d-flex justify-content-between">
                        <span>Subtotal</span>
                        <span id="subtotal">₱<?php echo number_format($total, 2); ?></span>
                    </li>
                    <li class="d-flex justify-content-between">
                        <span>Express Shipping</span>
                        <span>₱200.00</span>
                    </li>
                    <li class="border-top pt-2 d-flex justify-content-between">
                        <strong>Total</strong>
                        <strong id="total-price">₱<?php echo number_format($total + 200, 2); ?></strong>
                    </li>
                </ul>

                <!-- Confirmation Button under Total -->
                <div class="text-center my-4">
                    <a href="confirm.php" class="btn btn-primary btn-lg w-100"
                        style="background-color: #fde49e; color: #000;">
                        Confirm Order
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="font-HK p-3 text-white Bg-briblo justify-content-center align-items-center d-flex flex-column"
        style="border-top: 6px solid #fde49e;">
        © Copyright SHOPPRISE 2024
    </footer>
</body>

</html>
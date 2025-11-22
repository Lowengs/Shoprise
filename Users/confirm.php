<?php
session_start();
require_once '../PHP/Config.php';

// Function to get the total price of the cart
function getCartTotal($cart)
{
    $total = 0;
    foreach ($cart as $product_id => $quantity) {
        $product = getProductById($product_id); // Assume this function fetches product details
        if ($product) {
            $total += $product['price'] * $quantity;
        }
    }
    return $total;
}

$order_number = $_SESSION['order_number'];

// Fetch user's cart from the session
$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

// Calculate totals
$subtotal = getCartTotal($cart);
$shipping_cost = 200; // Example flat shipping fee
$total = $subtotal + $shipping_cost;

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

// Insert the order into the database
$order_date = date('Y-m-d H:i:s'); // Use a format suitable for MySQL datetime
$payment_method = 'Cash On Delivery';
$shipping_address = $full_address;

// Prepare the SQL insert statement
$sql_insert_order = "INSERT INTO orders (user_id, order_number, total, address, payment_method, created_at) VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql_insert_order);
$stmt->bind_param("isssss", $user_id, $order_number, $total, $shipping_address, $payment_method, $order_date);

// Execute the statement and check for success
if ($stmt->execute()) {
    // Clear the cart after displaying the order summary
    unset($_SESSION['cart']);
} else {
    // Handle error
    echo "Error: " . $stmt->error;
}

$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Order Confirmed | Shoprise</title>
    <link rel="icon" href="../pic/logonatinnobg.png">
    <link href="../css/styles.css" rel="stylesheet">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=home" />
</head>

<body>
    <!-- Header -->
    <header class="p-2 text-white Bg-briblo">
        <div class="nav-custom d-flex justify-content-between align-items-center">
            <a href="../homepage.php">
                <img src="../pic/logonatinnobg.png" width="90px" height="80px" alt="Shoprise Logo">
            </a>
        </div>
    </header>

    <!-- Main Container -->
    <div class="container my-5 text-center" style="background-color: #ffffff;">
        <!-- Thank You Message -->
        <div class="mb-4">
            <img src="../pic/logonatinnobg.png" alt="Shoprise Logo" height="100px" width="110px" class="m-4">
            <h2>Thank You for Your Order!</h2>
            <p class="mt-3">Your order has been confirmed. We will notify you once it ships.</p>
        </div>

        <!-- Order Summary -->
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="border rounded p-3">
                    <h4 class="mb-3">Order Summary</h4>
                    <ul class="list-unstyled">
                        <li class="d-flex justify-content-between">
                            <span>Order Number</span>
                            <span><?php echo $order_number; ?></span>
                        </li>
                        <li class="d-flex justify-content-between">
                            <span>Order Date</span>
                            <span><?php echo date('d F, Y', strtotime($order_date)); ?></span>
                        </li>
                        <li class="d-flex justify-content-between">
                            <span>Payment</span>
                            <span><?php echo $payment_method; ?></span>
                        </li>
                        <li class="d-flex justify-content-between">
                            <span>Shipping Address</span>
                            <span><?php echo $shipping_address; ?></span>
                        </li>
                    </ul>

                    <!-- Dynamically display the items in the cart -->
                    <h5 class="mt-4">Items in Your Order</h5>
                    <div class="product-list">
                        <?php if (!empty($cart)) : ?>
                            <?php foreach ($cart as $product_id => $quantity) : ?>
                                <?php
                                $product = getProductById($product_id); // Fetch product details
                                if ($product) :
                                    $subtotal = $product['price'] * $quantity;
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
                                <?php endif; ?>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <p>No items in your cart.</p>
                        <?php endif; ?>
                    </div>

                    <!-- Pricing Details -->
                    <ul class="list-unstyled">
                        <li class="d-flex justify-content-between">
                            <span>Subtotal</span>
                            <span>₱<?php echo number_format($subtotal, 2); ?></span>
                        </li>
                        <li class="d-flex justify-content-between">
                            <span>Express Shipping</span>
                            <span>₱<?php echo number_format($shipping_cost, 2); ?></span>
                        </li>
                        <li class="border-top pt-2 d-flex justify-content-between">
                            <strong>Total</strong>
                            <strong>₱<?php echo number_format($total, 2); ?></strong>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Navigation Buttons -->
        <div class="mt-4">
            <a href="homepage.php" class="btn btn-primary btn-lg" style="background-color: #fde49e; color: #000;">Back
                to Homepage</a>
            <a href="product.php" class="btn btn-secondary btn-lg ms-2" style="background-color: #d8c7b1; color: #000;">Continue
                Shopping</a>
        </div>
    </div>

    <!-- Footer -->
    <footer class="font-HK p-3 text-white Bg-briblo justify-content-center align-items-center d-flex flex-column"
        style="border-top: 6px solid #fde49e;">
        © Copyright SHOPPRISE 2024
    </footer>
</body>

</html>
<?php
// add_to_cart.php
require_once 'Config.php';

// Get the raw POST data from the request
$data = json_decode(file_get_contents('php://input'), true);

// Validate incoming data
if (isset($data['product_id']) && isset($data['quantity'])) {
    $product_id = $data['product_id'];
    $quantity = $data['quantity'];
    $product_name = $data['product_name'];
    $product_price = $data['product_price'];
    $product_location = $data['product_location'];

    // Get user ID (assuming user is logged in)
    // This would typically come from the session or a JWT token
    $user_id = 1; // For the sake of this example, using a fixed user ID

    // Generate order number (example: order1234)
    $order_number = 'order' . rand(1000, 9999); 

    // Calculate total price (this is just an example, can be more complex)
    $total = $product_price * $quantity;

    // Insert the order into the database
    $order_id = addOrder($user_id, $order_number, $total, $product_location, 'cash'); // Example payment method

    // Add the item to the order
    if ($order_id) {
        $cart_item_added = addOrderItems($order_id, [$product_id => $quantity]);
        if ($cart_item_added) {
            // Return the updated cart
            $cart = getOrderItemsByOrderId($order_id);
            echo json_encode(['success' => true, 'cart' => $cart]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error adding item to cart.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Error creating order.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid data.']);
}
?>

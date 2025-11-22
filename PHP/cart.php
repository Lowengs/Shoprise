<?php
session_start();

// Check if the cart session exists, if not create it
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Handle the POST request to add the item to the cart
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    if ($data && isset($data['product_id'])) {
        // Add product to the session cart (basic example)
        $product = [
            'product_id' => $data['product_id'],
            'product_name' => $data['product_name'],
            'product_price' => $data['product_price'],
            'product_location' => $data['product_location'],
            'product_image' => $data['product_image'],
            'quantity' => $data['quantity']
        ];

        // Check if the product already exists in the cart
        $found = false;
        foreach ($_SESSION['cart'] as &$item) {
            if ($item['product_id'] === $data['product_id']) {
                $item['quantity'] += $data['quantity']; // Increase quantity if product exists
                $found = true;
                break;
            }
        }

        // If not found, add new product to the cart
        if (!$found) {
            $_SESSION['cart'][] = $product;
        }

        // Respond with a success message
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid product data.']);
    }
}
?>

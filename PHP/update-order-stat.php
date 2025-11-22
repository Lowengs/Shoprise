<?php
require_once 'Config.php'; // Database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order_id = $_POST['order_id'] ?? null;
    $status = $_POST['status'] ?? null;

    if ($order_id && $status) {
        // Update the order status in the database
        $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $status, $order_id);

        if ($stmt->execute()) {
            // Check if the status is now 'delivered' or 'cancelled'
            if ($status === 'delivered' || $status === 'cancelled') {
                // Optionally: Delete the record from a temporary pending orders table
                // If you're using the same orders table, you don't need to delete it here
                // $conn->query("DELETE FROM pending_orders WHERE id = $order_id");
            }

            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to update the order status.']);
        }

        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid order ID or status.']);
    }

    $conn->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}
?>

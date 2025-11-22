<?php
require_once '../PHP/Config.php';

// Check if the request is a POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the order ID and status from the request body
    $data = json_decode(file_get_contents('php://input'), true);
    $order_id = $data['orderId'];
    $status = $data['status'];

    // Update the status in the database
    $sql = "UPDATE orders SET status = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $status, $order_id);

    if ($stmt->execute()) {
        $stmt->close();
        echo json_encode(['success' => true]);
    } else {
        $stmt->close();
        echo json_encode(['success' => false, 'error' => $stmt->error]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request method']);
}

$conn->close();
?>
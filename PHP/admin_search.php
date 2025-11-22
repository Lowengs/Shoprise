<?php
// Return JSON response
header('Content-Type: application/json');

// Include your database configuration file
require_once 'Config.php';

// Get the POST data
$input = file_get_contents('php://input');
$data = json_decode($input, true);

// Check if a search query exists
$search = isset($data['search']) ? $data['search'] : '';

// Base SQL query
$sql = "SELECT id, image_url, name, price, stock FROM products";

// If there's a search query, add a WHERE clause
if (!empty($search)) {
    $sql .= " WHERE name LIKE ? OR description LIKE ?";
}

// Prepare the SQL statement
$stmt = $conn->prepare($sql);

if (!empty($search)) {
    $searchTerm = "%{$search}%";
    $stmt->bind_param('ss', $searchTerm, $searchTerm);
}

// Execute the statement
$stmt->execute();
$result = $stmt->get_result();

// Prepare the results
$products = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $products[] = [
            'id' => $row['id'],
            'image_url' => base64_encode($row['image_url']), // Assuming `image_url` is stored as binary data
            'name' => $row['name'],
            'price' => (float)$row['price'],
            'stock' => (int)$row['stock']
        ];
    }
}

// Close the statement and connection
$stmt->close();
$conn->close();

// Return the products as JSON
echo json_encode(['products' => $products]);
?>

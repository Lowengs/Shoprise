<?php
header('Content-Type: application/json');
require_once 'Config.php';
// Fetch the data sent by the client
$data = json_decode(file_get_contents('php://input'), true);
$search = $data['search'] ?? '';  // Set search query to empty string if not provided

// Fetch products based on the search query
if (!empty($search)) {
    $query = $conn->prepare("SELECT name, price, image_url FROM products WHERE name LIKE ?");
    $searchParam = "%" . $search . "%";
    $query->bind_param("s", $searchParam);
} else {
    $query = $conn->prepare("SELECT name, price, image_url FROM products"); // Fetch all products
}

if ($query->execute()) {
    $result = $query->get_result();
    $products = [];

    while ($row = $result->fetch_assoc()) {
        $products[] = [
            'name' => htmlspecialchars($row['name']),
            'price' => (float) $row['price'],
            'image_url' => base64_encode($row['image_url']) // Assuming binary image stored
        ];
    }

    echo json_encode(['products' => $products]);
} else {
    echo json_encode(['error' => 'Database query failed']);
}

$conn->close();
?>

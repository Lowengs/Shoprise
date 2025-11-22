<?php
// shoprise_config.php
// Unified configuration and database setup for Shoprise

// =======================
// Database Configuration
// =======================
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "shoprise";

$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Increase timeouts
$conn->options(MYSQLI_OPT_CONNECT_TIMEOUT, 300);
$conn->query("SET SESSION wait_timeout = 28800");

// Create database if it doesn't exist
if ($conn->query("CREATE DATABASE IF NOT EXISTS $dbname") !== TRUE) {
    die("Error creating database: " . $conn->error);
}

// Select the database
$conn->select_db($dbname);

// =======================
// Helper Function
// =======================
function checkConnection($conn) {
    if (!$conn->ping()) {
        die("Connection lost: " . $conn->error);
    }
}

// =======================
// Ensure Directories Exist
// =======================
$dirs = [
    "../pic/products/",
    "../pic/Profile/admin/"
];
foreach ($dirs as $dir) {
    if (!is_dir($dir)) mkdir($dir, 0777, true);
}

// =======================
// Product Functions
// =======================
function addProduct($name, $description, $price, $stock, $image) {
    global $conn;
    $target_dir = "../pic/products/";
    $target_file = $target_dir . basename($image['name']);

    if (move_uploaded_file($image['tmp_name'], $target_file)) {
        $stmt = $conn->prepare("INSERT INTO products (name, description, price, stock, image_url) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssdis", $name, $description, $price, $stock, $target_file);
        $res = $stmt->execute();
        $stmt->close();
        return $res ? true : "Error: " . $conn->error;
    }
    return "Error uploading image.";
}

function getProducts() {
    global $conn;
    return $conn->query("SELECT * FROM products");
}

function getProductById($id) {
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    return $result->num_rows ? $result->fetch_assoc() : false;
}

function updateProduct($id, $name, $description, $price, $stock, $image) {
    global $conn;
    $target_dir = "../pic/products/";
    if ($image['name']) {
        $target_file = $target_dir . basename($image['name']);
        if (move_uploaded_file($image['tmp_name'], $target_file)) {
            $stmt = $conn->prepare("UPDATE products SET name=?, description=?, price=?, stock=?, image_url=? WHERE id=?");
            $stmt->bind_param("ssdisi", $name, $description, $price, $stock, $target_file, $id);
        } else return "Error uploading image.";
    } else {
        $stmt = $conn->prepare("UPDATE products SET name=?, description=?, price=?, stock=? WHERE id=?");
        $stmt->bind_param("ssdsi", $name, $description, $price, $stock, $id);
    }
    $res = $stmt->execute();
    $stmt->close();
    return $res ? true : "Error: " . $conn->error;
}

function deleteProduct($id) {
    global $conn;
    $product = getProductById($id);
    if ($product && file_exists($product['image_url'])) unlink($product['image_url']);
    $stmt = $conn->prepare("DELETE FROM products WHERE id=?");
    $stmt->bind_param("i", $id);
    $res = $stmt->execute();
    $stmt->close();
    return $res;
}

// =======================
// Order Functions
// =======================
function addOrder($user_id, $order_number, $total, $address, $payment_method) {
    global $conn;
    $stmt = $conn->prepare("INSERT INTO orders (user_id, order_number, total, address, payment_method, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
    $stmt->bind_param("isdss", $user_id, $order_number, $total, $address, $payment_method);
    $stmt->execute();
    $id = $stmt->insert_id;
    $stmt->close();
    return $id;
}

function addOrderItems($order_id, $cart) {
    global $conn;
    foreach ($cart as $product_id => $quantity) {
        $product = getProductById($product_id);
        if ($product) {
            $stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("iidi", $order_id, $product_id, $quantity, $product['price']);
            $stmt->execute();
            $stmt->close();
        }
    }
    return true;
}

// =======================
// Admin Functions
// =======================
function addAdmin($username, $full_name, $email, $password, $profile_picture, $address, $city, $province, $contact_number) {
    global $conn;
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO admins (username, full_name, email, password, profile_picture, address, city, province, contact_number) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssssss", $username, $full_name, $email, $hashed_password, $profile_picture, $address, $city, $province, $contact_number);
    $res = $stmt->execute();
    $stmt->close();
    return $res;
}

function getAdmins() {
    global $conn;
    return $conn->query("SELECT * FROM admins");
}

function deleteAdmin($id) {
    global $conn;
    $admin = getAdminById($id);
    if ($admin && file_exists($admin['profile_picture'])) unlink($admin['profile_picture']);
    $stmt = $conn->prepare("DELETE FROM admins WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    return true;
}

function getAdminById($id) {
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM admins WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    return $result->num_rows ? $result->fetch_assoc() : false;
}


function getusers(){
     global $conn;
    return $conn->query("SELECT * FROM users");

}

?>

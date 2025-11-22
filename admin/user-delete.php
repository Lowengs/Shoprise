<?php
include('../PHP/config.php');

// Check if 'id' is set in the query string
if (isset($_GET['id'])) {
    $userId = $_GET['id'];

    // Prepare the delete query
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $userId);

    if ($stmt->execute()) {
        echo "<script>alert('User deleted successfully'); window.location.href = 'Admin-users.php';</script>";
    } else {
        echo "<script>alert('Error deleting user: " . $stmt->error . "'); window.location.href = 'Admin-users.php';</script>";
    }

    $stmt->close();
} else {
    echo "<script>alert('Invalid user ID'); window.location.href = 'Admin-users.php';</script>";
}

$conn->close();
?>

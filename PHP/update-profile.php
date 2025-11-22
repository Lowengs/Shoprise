<?php
require_once '../PHP/Config.php';

// Start session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $number = $_POST['number'];
    $address = $_POST['address'];
    $profilePicture = $_FILES['pfp'];

    // Validate inputs (add more validation as needed)
    if (empty($username) || empty($email)) {
        echo "Username and email are required!";
        exit();
    }

    // Handle profile picture upload
    if ($profilePicture['error'] === UPLOAD_ERR_OK) {
        $allowedExtensions = ['jpeg', 'jpg', 'png'];
        $fileExtension = strtolower(pathinfo($profilePicture['name'], PATHINFO_EXTENSION));
        if (!in_array($fileExtension, $allowedExtensions)) {
            echo "Invalid file type. Only JPEG, JPG, and PNG are allowed.";
            exit();
        }

        if ($profilePicture['size'] > 1048576) {
            echo "File size must not exceed 1 MB.";
            exit();
        }

        $targetDir = "../pic/Profile/";
        $targetFile = $targetDir . "user_" . $user_id . "." . $fileExtension;

        if (!move_uploaded_file($profilePicture['tmp_name'], $targetFile)) {
            echo "Failed to upload profile picture.";
            exit();
        }

        $profilePicturePath = "user_" . $user_id . "." . $fileExtension;
    } else {
        $profilePicturePath = null; // No file uploaded
    }

    // Update user details in the database
    $sql = "UPDATE users SET username = ?, full_name = ?, email = ?, contact_number = ?, address = ?" .
           ($profilePicturePath ? ", profile_picture = ?" : "") .
           " WHERE id = ?";
    $stmt = $conn->prepare($sql);

    if ($profilePicturePath) {
        $stmt->bind_param(
            "ssssssi",
            $username,
            $name,
            $email,
            $number,
            $address,
            $profilePicturePath,
            $user_id
        );
    } else {
        $stmt->bind_param(
            "sssssi",
            $username,
            $name,
            $email,
            $number,
            $address,
            $user_id
        );
    }

    if ($stmt->execute()) {
        echo "<script>alert('Profile updated successfully!'); window.location.href = '../Users/profile.php';</script>";
    } else {
        echo "Error updating profile: " . $conn->error;
    }
}
?>

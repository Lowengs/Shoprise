<?php
// update-profile.php

require_once '../PHP/Config.php';

// Start session to track the logged-in admin
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php'); // Redirect to login if not logged in
    exit();
}

$admin_id = $_SESSION['admin_id']; // Get the admin ID from the session

// Fetch the existing admin details
$admin = getAdminById($admin_id);

if ($admin === false) {
    echo "Admin not found.";
    exit();
}

// Get form data
$username = $_POST['username'];
$full_name = $_POST['name'];
$email = $_POST['email'];
$contact_number = $_POST['number'];
$address = $_POST['address'];
$city = $_POST['city'];
$province = $_POST['province'];

// Check if a new profile picture was uploaded
$profile_picture = $admin['profile_picture']; // Default to current profile picture

if (isset($_FILES['pfp']) && $_FILES['pfp']['error'] === UPLOAD_ERR_OK) {
    $file_tmp = $_FILES['pfp']['tmp_name'];
    $file_name = $_FILES['pfp']['name'];
    $file_ext = pathinfo($file_name, PATHINFO_EXTENSION);
    $file_name_new = uniqid('', true) . '.' . $file_ext;
    $file_dest = '../pic/Profile/' . $file_name_new;

    // Move the uploaded file to the destination folder
    if (move_uploaded_file($file_tmp, $file_dest)) {
        $profile_picture = $file_name_new; // Set new profile picture name
    } else {
        echo "Error uploading file.";
        exit();
    }
}

// Update admin details in the database
$sql = "UPDATE admins SET username = ?, full_name = ?, email = ?, contact_number = ?, address = ?, city = ?, province = ?, profile_picture = ? WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssssssi", $username, $full_name, $email, $contact_number, $address, $city, $province, $profile_picture, $admin_id);

if ($stmt->execute()) {
    // Redirect to the profile page after successful update
    header('Location: ../admin/Admin-profile.php');
    exit();
} else {
    echo "Error updating profile: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>

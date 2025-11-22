<?php
// admin-delete.php
require_once '../PHP/Config.php';

if (isset($_GET['id'])) {
    $id = (int) $_GET['id'];
    $result = deleteAdmin($id);

    if ($result === true) {
        header("Location: Admin-table.php"); // Redirect to admin management page
        exit(); // Stop the script to prevent further execution
    } else {
        die("Error: " . $result);
    }
} else {
    die("Invalid request.");
}
?>
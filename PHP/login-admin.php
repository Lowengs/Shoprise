<?php
session_start();
require_once '../PHP/config.php'; // Database configuration file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Query database to validate admin credentials
    $stmt = $conn->prepare("SELECT * FROM admins WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $admin = $result->fetch_assoc();
        
        // Verify password
        if (password_verify($password, $admin['password'])) {
            // Set session variables
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_email'] = $admin['email'];

            // Redirect to admin dashboard
            header("Location: ../admin/Admin-index.php");
            exit();
        } else {
            $error = "Invalid password.";
        }
    } else {
        $error = "No admin account found with this email.";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | Shoprise</title>
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="icon" href="../pic/logonatinnobg.png" type="image/x-icon">
</head>
<body style="background-color: #d8c7b1;">

<div class="container my-5">
    <div class="row align-items-center">
        <!-- Left Image Section -->
        <div class="col-md-6 d-flex justify-content-center">
            <img src="../pic/logonatinnobg.png" alt="Shoprise Logo" class="img-fluid" style="max-width: 80%;">
        </div>

        <!-- Right Login Section -->
        <div class="col-md-6 d-flex align-items-center justify-content-center auth-container">
            <div class="login-container">
                <img src="../pic/logonatinnobg.png" alt="Logo" width="110" height="100" class="logo mb-1">
                <h3 class="mb-4">ADMIN LOGIN</h3>
                <form action="../PHP/login-admin.php" method="post" onsubmit="return validateAdminLogin()">
                    <div class="mb-3">
                        <label for="loginEmail" class="form-label">Admin Email</label>
                        <input type="email" class="form-control" id="loginEmail" name="email" placeholder="Email" required>
                    </div>
                    <div class="mb-3">
                        <label for="loginPassword" class="form-label">Password</label>
                        <input type="password" class="form-control" id="loginPassword" name="password" placeholder="Password" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 mb-3">Log in</button>
                    <div id="error-message" class="text-danger mt-3"> <?php echo $error ?? "Login Error"; ?></div>
                </form>
            </div>
        </div>
    </div>
</div>

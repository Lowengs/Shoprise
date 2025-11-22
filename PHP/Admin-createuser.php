<?php
// Admin-createuser.php
require_once '../PHP/Config.php';

$error = $success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['signUpUsername']);
    $full_name = trim($_POST['signUpName']);
    $email = trim($_POST['signUpEmail']);
    $password = trim($_POST['signUpPassword']);
    $confirm_password = trim($_POST['signUpConfirmPassword']);
    $address = trim($_POST['address']);
    $city = trim($_POST['city']);
    $province = trim($_POST['state']);
    $contact_number = trim($_POST['contact']);
    $profile_picture = $_FILES['profilePicture']['name'];

    // Validate inputs
    if (empty($username) || empty($full_name) || empty($email) || empty($password) ||
        empty($confirm_password) || empty($address) || empty($city) || empty($province) || empty($contact_number) || empty($profile_picture)) {
        $error = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email address.";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } elseif (strlen($password) < 8) {
        $error = "Password must be at least 8 characters long.";
    } else {
        // Check if username or email already exists
        $stmt = $conn->prepare("SELECT id FROM admins WHERE username = ? OR email = ?");
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $error = "Username or email already exists.";
        } else {
            // Upload profile picture
            $target_dir = "../pic/Profile/admin/";
            $target_file = $target_dir . basename($profile_picture);
            if (move_uploaded_file($_FILES["profilePicture"]["tmp_name"], $target_file)) {
                // Add admin to the database
                $result = addAdmin($username, $full_name, $email, $password, $target_file, $address, $city, $province, $contact_number);

                if ($result === true) {
                    $success = "Admin registered successfully.";
                    header("Location: ../admin/admin-trakcer.php"); // Redirect to admin management page
                    exit(); // Stop the script to prevent further execution
                } else {
                    $error = "Error: " . $result;
                }
            } else {
                $error = "Error uploading profile picture.";
            }
        }
        $stmt->close();
    }
}

$conn->close();
ob_end_flush(); // End output buffering
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Rye&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/adminstyles.css">
    <link rel="icon" href="../pic/logonatinnobg.png" type="image/x-icon">
</head>
<body>
    <header class="p-3 text-white Bg-briblo">
        <div class="nav-custom">
            <div class="d-flex flex-wrap align-items-center justify-content-between">
                <a href="http://localhost/ProjectSemarylowe2/admin/Admin-index.php"><img class="me-5" src="../pic/logonatin.png" width="65" height="58"></a>
                
                <!-- Admin Profile and Logout -->
                <div class="d-flex align-items-center">
                    <!-- Admin text link -->
                    <a href="http://localhost/ProjectSemarylowe2/admin/Admin-profile.php" class="nav-link px-4 fw-bold text-light me-2">Admin</a>
                    
                    <!-- Admin profile image -->
                    <a href="http://localhost/ProjectSemarylowe2/admin/Admin-profile.php" class="btn rounded-2">
                        <img src="../pic/pfp.png" width="50" height="50" alt="Admin Profile">
                    </a>
                </div>
            </div>
        </div>
    </header>

    <div class="container mt-5 d-flex justify-content-center align-items-center flex-column">
        <h2 class="text-center fw-bold font-HK">Create Admin</h2>
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        <?php if (!empty($success)): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>
        <form action="http://localhost/ProjectSemarylowe2/PHP/Admin-createuser.php" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="signUpUsername" class="form-label">Username</label>
                <input type="text" class="form-control" id="signUpUsername" name="signUpUsername" required>
            </div>
            <div class="mb-3">
                <label for="signUpName" class="form-label">Full Name</label>
                <input type="text" class="form-control" id="signUpName" name="signUpName" required>
            </div>
            <div class="mb-3">
                <label for="signUpEmail" class="form-label">Email</label>
                <input type="email" class="form-control" id="signUpEmail" name="signUpEmail" required>
            </div>
            <div class="mb-3">
                <label for="signUpPassword" class="form-label">Password</label>
                <input type="password" class="form-control" id="signUpPassword" name="signUpPassword" required>
            </div>
            <div class="mb-3">
                <label for="signUpConfirmPassword" class="form-label">Confirm Password</label>
                <input type="password" class="form-control" id="signUpConfirmPassword" name="signUpConfirmPassword" required>
            </div>
            <div class="mb-3">
                <label for="address" class="form-label">Address</label>
                <input type="text" class="form-control" id="address" name="address" required>
            </div>
            <div class="mb-3">
                <label for="city" class="form-label">City</label>
                <input type="text" class="form-control" id="city" name="city" required>
            </div>
            <div class="mb-3">
                <label for="state" class="form-label">Province/State</label>
                <input type="text" class="form-control" id="state" name="state" required>
            </div>
            <div class="mb-3">
                <label for="contact" class="form-label">Contact Number</label>
                <input type="text" class="form-control" id="contact" name="contact" required>
            </div>
            <div class="mb-3">
                <label for="profilePicture" class="form-label">Profile Picture</label>
                <input type="file" class="form-control" id="profilePicture" name="profilePicture" required>
            </div>
            <button type="submit" class="btn btn-primary">Create Admin</button>
        </form>
    </div>
</body>
</html>
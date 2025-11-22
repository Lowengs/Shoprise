<?php
// admin-edit.php
require_once '../PHP/Config.php';

if (isset($_GET['id'])) {
    $id = (int) $_GET['id'];
    $admin = getAdminById($id);

    if (!$admin) {
        die("Admin not found.");
    }
} else {
    die("Invalid request.");
}

$error = $success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $address = trim($_POST['address']);
    $city = trim($_POST['city']);
    $province = trim($_POST['province']);
    $contact_number = trim($_POST['contact_number']);
    $profile_picture = $_FILES['profilePicture']['name'];

    // Validate inputs
    if (empty($username) || empty($full_name) || empty($email) || empty($address) || empty($city) || empty($province) || empty($contact_number)) {
        $error = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email address.";
    } else {
        // Check if username or email already exists for other admin
        $stmt = $conn->prepare("SELECT id FROM admins WHERE (username = ? OR email = ?) AND id != ?");
        $stmt->bind_param("ssi", $username, $email, $id);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $error = "Username or email already exists.";
        } else {
            // Upload profile picture if provided
            if (!empty($profile_picture)) {
                $target_dir = "../pic/Profile/admin/";
                $target_file = $target_dir . basename($profile_picture);
                if (move_uploaded_file($_FILES["profilePicture"]["tmp_name"], $target_file)) {
                    $profile_picture = $target_file;
                } else {
                    $error = "Error uploading profile picture.";
                }
            } else {
                $profile_picture = $admin['profile_picture'];
            }

            // Update admin in the database
            $result = updateAdmin($id, $username, $full_name, $email, $profile_picture, $address, $city, $province, $contact_number);

            if ($result === true) {
                $success = "Admin updated successfully.";
                header("Location: Admin-table.php"); // Redirect to admin management page
                exit(); // Stop the script to prevent further execution
            } else {
                $error = "Error: " . $result;
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
    <title>Edit Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Rye&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/adminstyles.css">
    <link rel="icon" href="../pic/logonatinnobg.png" type="image/x-icon">
</head>
<body>
    <header class="p-3 text-white Bg-briblo">
        <div class="nav-custom">
            <div class="d-flex flex-wrap align-items-center justify-content-between">
                <a href="Admin-index.php"><img class="me-5" src="../pic/logonatin.png" width="65" height="58"></a>
                
                <!-- Admin Profile and Logout -->
                <div class="d-flex align-items-center">
                    <!-- Admin text link -->
                    <a href="Admin-profile.php" class="nav-link px-4 fw-bold text-light me-2">Admin</a>
                    
                    <!-- Admin profile image -->
                    <a href="Admin-profile.php" class="btn rounded-2">
                        <img src="../pic/pfp.png" width="50" height="50" alt="Admin Profile">
                    </a>
                </div>
            </div>
        </div>
    </header>

    <div class="container mt-5 d-flex justify-content-center align-items-center flex-column">
        <h2 class="text-center fw-bold font-HK">Edit Admin</h2>
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        <?php if (!empty($success)): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>
        <form action="admin-editacc.php?id=<?php echo $id; ?>" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" value="<?php echo $admin['username']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="full_name" class="form-label">Full Name</label>
                <input type="text" class="form-control" id="full_name" name="full_name" value="<?php echo $admin['full_name']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo $admin['email']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="address" class="form-label">Address</label>
                <input type="text" class="form-control" id="address" name="address" value="<?php echo $admin['address']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="city" class="form-label">City</label>
                <input type="text" class="form-control" id="city" name="city" value="<?php echo $admin['city']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="province" class="form-label">Province/State</label>
                <input type="text" class="form-control" id="province" name="province" value="<?php echo $admin['province']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="contact_number" class="form-label">Contact Number</label>
                <input type="text" class="form-control" id="contact_number" name="contact_number" value="<?php echo $admin['contact_number']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="profilePicture" class="form-label">Profile Picture</label>
                <input type="file" class="form-control" id="profilePicture" name="profilePicture">
            </div>
            <button type="submit" class="btn btn-primary">Update Admin</button>
        </form>
    </div>
</body>
</html>
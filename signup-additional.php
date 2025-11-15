<?php
require_once 'PHP/config.php';


// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $profile_picture = $_FILES['profilePicture']['name'];
    $address = $_POST['address']; // Renamed from 'addressLine1' to 'address'
    $city = $_POST['city'];
    $province = $_POST['state']; // Renamed from 'state' to 'province'
    $contact_number = $_POST['contact'];

    // Validate form data
    if (empty($address) || empty($city) || empty($province) || empty($contact_number)) {
        $error = "All fields are required.";
    } else {
        // Upload profile picture
        $target_dir = "pic/Profile/";
        $target_file = $target_dir . basename($profile_picture);

        if (move_uploaded_file($_FILES["profilePicture"]["tmp_name"], $target_file)) {
            // Update user details in the database
            $stmt = $conn->prepare("UPDATE users SET profile_picture = ?, address = ?, city = ?, province = ?, contact_number = ? WHERE email = ?");
            $stmt->bind_param("ssssss", $target_file, $address, $city, $province, $contact_number, $email);

            if ($stmt->execute()) {
                echo "";
                header("Refresh:3; url=login.php");
                exit();
            } else {
                $error = "Error: " . $stmt->error;
            }
        } else {
            $error = "Error uploading profile picture.";
        }

        $stmt->close();
    }
}

// Get email from query parameter
$email = isset($_GET['email']) ? $_GET['email'] : '';

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - Profile Details | Shoprise</title>
    <link rel="icon" href="pic/logonatinnobg.png" type="image/x-icon">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=home" />
    <link rel="stylesheet" href="css/styles.css">
</head>
<body style="background-color: #d8c7b1;">
    <!-- Loading Screen -->
    <div id="loadingScreen" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.8); z-index: 1000; text-align: center; color: white;">
        <div style="position: relative; top: 50%; transform: translateY(-50%);">
            <h2>Loading...</h2>
            <div class="spinner"></div>
        </div>
    </div>
    
    <!-- Navbar -->
    <header class="p-2 text-white Bg-briblo">
        <div class="nav-custom d-flex justify-content-between align-items-center">
            <div class="d-flex justify-content-center flex-grow-1 gap-3"></div>
        </div>
    </header>
    <div class="container my-5">
        <div class="row align-items-center justify-content-center">
            <!-- Left Form Section -->
            <div class="col-md-6 order-2 order-md-1">
                <div class="auth-container">
                    <h3 class="header-text text-center">Complete Your Profile</h3>
                    <form action="signup-additional.php" method="POST" enctype="multipart/form-data" onsubmit="return validateAdditionalForm()">
                        <input type="hidden" name="email" value="<?php echo htmlspecialchars($email); ?>">
                        <div class="mb-3">
                            <label for="profilePicture" class="form-label">Profile Picture</label>
                            <input type="file" class="form-control" id="profilePicture" name="profilePicture" accept="image/*" required>
                        </div>
                       
                        <div class="mb-3">
                            <label for="address" class="form-label">Street Address</label>
                            <input type="text" class="form-control" id="address" name="address" placeholder="Street Address" required>
                        </div>
                        <div class="mb-3">
                            <label for="city" class="form-label">City</label>
                            <input type="text" class="form-control" id="city" name="city" placeholder="City" required>
                        </div>
                        <div class="mb-3">
                            <label for="state" class="form-label">Province</label>
                            <input type="text" class="form-control" id="state" name="state" placeholder="Province" required>
                        </div>
                        <div class="mb-3">
                            <label for="contact" class="form-label">Contact Number</label>
                            <input type="text" class="form-control" id="contact" name="contact" placeholder="Contact Number" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Proceed to Login</button>
                        <div class="switch-link text-center mt-3">
                            <p><a href="signup.php">Go Back</a></p>
                        </div>
                    </form>
                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger mt-3"><?php echo $error; ?></div>
                    <?php endif; ?>
                </div>
            </div>
            <!-- Right Image Section -->
            <div class="col-md-6 d-flex justify-content-center align-items-center order-1 order-md-2">
                <img src="pic/logonatinnobg.png" class="img-fluid" alt="Shoprise Logo">
            </div>
        </div>
    </div>
    <footer class="font-HK p-1 text-white Bg-briblo justify-content-center align-items-center d-flex flex-column">
        © Copyright SHOPPRISE 2024
    </footer>
    <script src="js/loading-screen.js"></script>
    <script src="js/validation.js"></script>
</body>
</html>
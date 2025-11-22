<?php
require_once '../PHP/Config.php';

// Start session to track the logged-in user
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php'); // Redirect to login if not logged in
    exit();
}



$user_id = $_SESSION['user_id']; // Get the user ID from the session

// Fetch user details from the database
$sql = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc(); // Fetch user details
} else {
    echo "User not found.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shoprise | Edit Profile</title>
    <link href="../css/styles.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Hanken+Grotesk:ital,wght@0,100..900;1,100..900&display=swap">
    <link rel="icon" href="../pic/logonatinnobg.png" type="image/x-icon">
</head>
<body>

<!-- Header -->
<header class="p-3 text-white Bg-briblo">
    <div class="nav-custom">
        <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
            <a href="homepage.php"><img class="" src="../pic/logonatin.png" width="65" height="58"></a>
        </div>
    </div>
</header>
 <!-- My Orders Button -->
    <div class="mt-5 text-center">
        <a href="shopcart.php" class="btn btn-primary btn-lg">My Orders</a>
    </div>
</div>
<!-- Main Content -->
<div class="container my-5">
    <form action="../PHP/update-profile.php" method="POST" enctype="multipart/form-data" onsubmit="showLoadingScreen()">
        <div class="row">
            <h3 class="mb-4 fw-bold">My Profile</h3>
            <p class="text-muted">Manage and protect your account</p>
            
            <!-- Left Column -->
            <div class="col-md-8">
                <!-- Username -->
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" name="username" placeholder="<?php echo htmlspecialchars($user['username']); ?>" value="" required>
                    <small class="text-muted">Username can only be changed once.</small>
                </div>

                <!-- Name -->
                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control" id="name" name="name" placeholder="<?php echo htmlspecialchars($user['full_name']); ?>" value="">
                </div>

                <!-- Email -->
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="<?php echo htmlspecialchars($user['email']); ?>" value="">
                </div>

                <!-- Phone Number -->
                <div class="mb-3">
                    <label for="phone" class="form-label">Phone Number</label>
                    <input type="text" class="form-control" id="number" name="number" placeholder="<?php echo htmlspecialchars($user['contact_number']); ?>" value="">
                </div>

                <!-- Address -->
                <div class="mb-3">
                    <label for="address" class="form-label">Address</label>
                    <input type="text" class="form-control" id="address" name="address" placeholder="<?php echo htmlspecialchars($user['address']); ?>" value="">
                </div>

               <!-- Gender -->


            </div>

            <!-- Right Column -->
            <div class="col-md-3 text-center">
            <img src="../pic/Profile/<?php echo htmlspecialchars($user['profile_picture']); ?>" alt="Profile Picture" class="img-thumbnail rounded-circle mb-3" width="150">
            <br>
                <input type="file" class="btn btn-light" id="pfp" name="pfp" placeholder="Select Image" required>
                <p class="text-muted mt-3">File size: maximum 1 MB<br>File extension: .JPEG, .PNG</p>
            </div>
        </div>
         

        <!-- Save Button -->
        <div class="d-flex justify-content-between mt-4">
            <div>
                <button type="submit" class="btn btn-info btn-lg">Save</button>
                <a href="homepage.php" class="btn btn-danger btn-lg">Cancel</a>
            </div>
            <a href="../logout.php"  class="btn btn-danger btn-lg">Logout</a>

        </div>
    </form>
</div>



<!-- Footer -->
<footer class="font-HK p-3 text-white Bg-briblo text-center" style="border-top: 6px solid #fde49e;">
    © Copyright SHOPPRISE 2024
</footer>

</body>
</html>

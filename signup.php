<?php
require_once 'PHP/config.php';


// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = $_POST['signUpName'];
    $email = $_POST['signUpEmail'];
    $username = $_POST['signUpUsername'];  // Get the username
    $password = $_POST['signUpPassword'];
    $confirm_password = $_POST['signUpConfirmPassword'];

    // Validate form data
    if (empty($full_name) || empty($email) || empty($username) || empty($password) || empty($confirm_password)) {
        $error = "All fields are required.";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } else {
        // Check if email or username already exists
        $stmt = $conn->prepare("SELECT email, username FROM users WHERE email = ? OR username = ?");
        $stmt->bind_param("ss", $email, $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $error = "Email or Username already exists.";
        } else {
            // Hash the password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insert user into the database
            $stmt = $conn->prepare("INSERT INTO users (full_name, email, username, password) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $full_name, $email, $username, $hashed_password);

            if ($stmt->execute()) {
                // Redirect to additional profile details page
                header("Location: signup-additional.php?email=" . urlencode($email));
                exit();
            } else {
                $error = "Error: " . $stmt->error;
            }

            $stmt->close();
        }
    }
}


$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up | Shoprise</title>
    <link rel="icon" href="pic/logonatinnobg.png" type="image/x-icon">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=home" />
    <link rel="stylesheet" href="css/styles.css">
</head>

<body style="background-color: #d8c7b1;">
    <!-- Navbar -->
    <header class="p-2 text-white Bg-briblo">
        <div class="nav-custom d-flex justify-content-between align-items-center">
            <!-- Center Logo -->
            <div class="d-flex justify-content-center flex-grow-1 gap-3"></div>
        </div>
    </header>
    <div class="container my-5">
        <div class="row align-items-center justify-content-center">
            <!-- Left Sign-Up Form Section -->
            <div class="col-md-6 order-2 order-md-1">
                <div class="auth-container">
                    <h3 class="header-text text-center">Sign Up</h3>
                    <form action="signup.php" method="POST" onsubmit="return validateSignUpForm()">
                        <div class="mb-3">
                            <label for="signUpUsername" class="form-label">Username</label>
                            <input type="text" class="form-control" id="signUpUsername" name="signUpUsername"
                                placeholder="Enter your username" required>
                        </div>
                        <div class="mb-3">
                            <label for="signUpName" class="form-label">Full Name</label>
                            <input type="text" class="form-control" id="signUpName" name="signUpName"
                                placeholder="Enter your full name" required>
                        </div>
                        <div class="mb-3">
                            <label for="signUpEmail" class="form-label">Email Address</label>
                            <input type="email" class="form-control" id="signUpEmail" name="signUpEmail"
                                placeholder="Enter your email" required>
                        </div>
                        <div class="mb-3">
                            <label for="signUpPassword" class="form-label">Password</label>
                            <input type="password" class="form-control" id="signUpPassword" name="signUpPassword"
                                placeholder="Enter your password" required>
                        </div>
                        <div class="mb-3">
                            <label for="signUpConfirmPassword" class="form-label">Confirm Password</label>
                            <input type="password" class="form-control" id="signUpConfirmPassword"
                                name="signUpConfirmPassword" placeholder="Confirm your password" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Sign Up</button>
                        <div class="switch-link text-center mt-3">
                            <p>Already have an account? <a href="login.php">Log In</a></p>
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
    <script src="js/validation.js"></script>
</body>

</html>
<?php
require_once 'PHP/config.php';

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['loginEmail'];
    $password = $_POST['Pass'];

    // Validate form data
    if (empty($email) || empty($password)) {
        die("Email and password are required.");
    }

    // Fetch user from the database
    $stmt = $conn->prepare("SELECT id, full_name, email, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($user_id, $full_name, $email, $hashed_password);
    $stmt->fetch();

    if ($stmt->num_rows > 0) {
        // Verify password
        if (password_verify($password, $hashed_password)) {
            // Password is correct, start session and redirect to homepage
            session_start();
            $_SESSION['user_id'] = $user_id;
            $_SESSION['full_name'] = $full_name;
            $_SESSION['email'] = $email;
            header("Location: Users/homepage.php");
            exit();
        } else {
            $error = "Invalid password.";
        }
    } else {
        $error = "Email not found.";
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log In | Shoprise</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="icon" href="pic/logonatinnobg.png" type="image/x-icon">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=home" />
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
        <div class="row align-items-center">
            <!-- Left Image Section -->
            <div class="col-md-6 d-flex justify-content-center">
                <img src="pic/logonatinnobg.png" alt="Shoprise Logo" class="img-fluid" style="max-width: 80%;">
            </div>
            <!-- Right Login Section -->
            <div class="col-md-6 d-flex align-items-center justify-content-center auth-container">
                <div class="login-container">
                    <img src="pic/logonatinnobg.png" alt="Logo" width="110" height="100" class="logo mb-1">
                    <h3 class="mb-4">Login</h3>
                    <form action="login.php" method="post" onsubmit="showSpinningLoadingScreen()">
                        <div class="mb-3">
                            <label for="loginEmail" class="form-label">Enter an email address</label>
                            <input type="email" class="form-control" id="loginEmail" name="loginEmail" placeholder="Email" required>
                        </div>
                        <div class="mb-3">
                            <label for="Pass" class="form-label">Enter Password</label>
                            <input type="password" class="form-control" id="Pass" name="Pass" placeholder="Password" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 mb-3">Log in</button>
                        <div class="switch-link text-center mt-3">
                            <p>Continue as <a href="guest/homepage.php">Guest</a></p>
                            <p>No account yet? <a href="signup.php">Create one now!</a></p>
                        </div>
                    </form>
                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger mt-3"><?php echo $error; ?></div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <footer class="font-HK mt-5 p-3 text-white Bg-briblo justify-content-center align-items-center d-flex flex-column">
        © Copyright SHOPPRISE 2024
    </footer>
<script>scr= "js/loadingscreen.js"</script>
    <script src="js/validation.js"></script>
</body>
</html>
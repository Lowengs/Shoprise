<?php
// Admin-login.php
require_once '../PHP/Config.php';

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Validate inputs
    if (empty($username) || empty($password)) {
        $error = "All fields are required.";
    } else {
        // Check if the admin exists
        $stmt = $conn->prepare("SELECT id, username, password FROM admins WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($id, $username, $hashed_password);
            $stmt->fetch();

            // Verify the password
            if ($password === $hashed_password) {

                // Password is correct, start the session
                session_start();
                $_SESSION['admin_id'] = $id;
                $_SESSION['admin_username'] = $username;

                // Redirect to the admin dashboard
                header("Location: Admin-index.php");
                exit();
            } else {
                $error = "Invalid password.";
            }
        } else {
            $error = "Admin not found.";
        }

        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Hanken+Grotesk:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
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
                    <a href="Admin-profile.php" class="btn btn-outline-info fw-bold">
                        Admin
                    </a>
                    <a href="Admin-profile.php" class="btn rounded-2"><img src="../pic/pfp.png" width="50" height="50"></a>
                </div>
            </div>
        </div>
    </header>

    <div class="container mt-5 d-flex justify-content-center align-items-center">
        <div class="login-form p-4 bg-white rounded shadow-lg">
            <h2 class="text-center mb-4">Admin Login</h2>
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>
            <form action="Admin-login.php" method="POST">
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" name="username" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Login</button>
            </form>
        </div>
    </div>

</body>
</html>
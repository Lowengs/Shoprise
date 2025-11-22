


<title>Admin | Management</title>
    <link href="https://fonts.googleapis.com/css2?family=Rye&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/home.css">
    <link rel="icon" href="../pic/logonatinnobg.png" type="image/x-icon">


<header class="p-3 text-white Bg-briblo">
    <div class="nav-custom">
        <div class="d-flex flex-wrap align-items-center justify-content-between">
            <a href="Admin-index.php"><img class="me-5" src="../pic/logonatin.png" width="65" height="58"></a>
            
            <!-- Navbar Links -->
            <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
                <li><a href="Admin-index.php" class="nav-link fw-bold text-light">Home</a></li>
                <li><a href="Admin-user-manage.php" class="nav-link fw-bold text-light">User Management</a></li>
                <li><a href="Admin-product.php" class="nav-link fw-bold text-light">Products</a></li>
                <li><a href="Admin-sales.php" class="nav-link fw-bold text-light">Sales</a></li>
                <li><a href="Admin-tracker.php" class="nav-link fw-bold text-light">User Tracker</a></li>
            </ul>

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
<body>
    <div class="container mt-5">
        <h2>Admin Signup</h2>
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php elseif (!empty($success)): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>
        <form method="POST" action="../PHP/Admin-createuser.php" enctype="multipart/form-data">
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
                <input type="password" class="form-control" id="signUpConfirmPassword" name="signUpConfirmPassword"
                    placeholder="Confirm your password" required>
            </div>
            <div class="mb-3">
                <label for="profilePicture" class="form-label">Profile Picture</label>
                <input type="file" class="form-control" id="profilePicture" name="profilePicture" accept="image/*" required>

            </div>

            <div class="mb-3">
                <label for="address" class="form-label">Street Address</label>
                <input type="text" class="form-control" id="address" name="address" placeholder="Street Address"
                    required>
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
                <input type="text" class="form-control" id="contact" name="contact" placeholder="Contact Number"
                    required>
            </div>
            <button type="submit" class="btn btn-primary">Create Admin</button>
        </form>
    </div>
</body>

</html>
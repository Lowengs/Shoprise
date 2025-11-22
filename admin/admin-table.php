<?php
// Admin-table.php
require_once '../PHP/Config.php';
session_start();
// Fetch all users from the database

// Fetch all admins from the database
$result_admins = getadmins();

// Close connection



// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: ../login.php'); // Redirect to login if not logged in
    exit();
}

$admin_id = $_SESSION['admin_id']; // Get the admin ID from the session

// Fetch admin details from the database
$admin = getAdminById($admin_id); // Fetch the data for the logged-in admin

if ($admin === false) {
    echo "Admin not found.";
    exit();
}

$conn->close();

?>

<title>Admin | Management</title>
<link href="https://fonts.googleapis.com/css2?family=Rye&display=swap" rel="stylesheet">
<link rel="stylesheet" href="../css/adminstyles.css">
<link rel="icon" href="../pic/logonatinnobg.png" type="image/x-icon">

<header class="p-3 text-white Bg-briblo">
        <div class="nav-custom">
            <div class="d-flex flex-wrap align-items-center justify-content-between">
                <a href="Admin-index.php"><img class="me-5" src="../pic/logonatin.png" width="65" height="58"></a>

                <!-- Navbar Links -->
                <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0 ">
                    <li><a href="Admin-index.php" class="nav-link fw-bold text-light">Home</a></li>
                    <li><a href="Admin-user-manage.php" class="nav-link fw-bold text-light">User Management</a></li>
                    <li><a href="Admin-product.php" class="nav-link fw-bold text-light">Products</a></li>
                    <li><a href="Admin-sales.php" class="nav-link fw-bold text-light">Sales</a></li>
                    <li><a href="Admin-tracker.php" class="nav-link fw-bold text-light">Order Tracker</a></li>
                </ul>

                <!-- Admin Profile and Logout -->
                <div class="d-flex align-items-center">
                    <a href="Admin-profile.php" class="nav-link px-4 fw-bold text-light">Admin</a>
                    <a href="Admin-profile.php" class="btn rounded-2"><img src="../pic/pfp.png" width="50"
                            height="50"></a>
                </div>
            </div>
        </div>
    </header>
<div class="container mt-5 d-flex justify-content-center align-items-center flex-column">
    <h2 class="text-center fw-bold font-HK">Admin Management</h2>

    <!-- Create Admin Button -->
    <div class="d-flex justify-content-end w-100 mb-3">
        <a href="adminuser-create.php" class="btn btn-success btn-lg">Create Admin</a>
    </div>

    <!-- Admin Table -->
    <div class="table-responsive mt-4 w-100">
        <h3 class="text-center">Admins</h3>
        <table class="table table-striped table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Full Name</th>
                    <th>Email</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result_admins && $result_admins->num_rows > 0) {
                    while ($row = $result_admins->fetch_assoc()) {
                        echo "<tr>
                                <td>" . $row["id"] . "</td>
                                <td>" . $row['username'] . "</td>
                                <td>" . $row["full_name"] . "</td>
                                <td>" . $row["email"] . "</td>
                                <td>
                                    <a href='admin-editacc.php?id=" . $row["id"] . "' class='btn btn-warning btn-sm'>Edit</a>
                                    <a href='admin-delete.php?id=" . $row["id"] . "' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure you want to delete this admin?\")'>Delete</a>
                                </td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='5' class='text-center'>No admins found</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>
<?php
//Admin-tracker
session_start();
require_once '../PHP/Config.php';

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: Admin-login.php");
    exit();
}

// Fetch all orders from the database
// Fetch all pending orders
$sql_orders = "SELECT o.id AS order_id, o.created_at AS order_date, o.order_number, o.total, o.address, o.payment_method, u.full_name, o.status
               FROM orders o
               JOIN users u ON o.user_id = u.id
               WHERE o.status = 'pending' 
               AND NOT EXISTS (SELECT 1 FROM orders WHERE id = o.id AND status IN ('delivered', 'cancelled'))
               ORDER BY o.created_at DESC";
$result_orders = $conn->query($sql_orders);
if (!$result_orders) {
    die("Query failed: " . $conn->error);
}

// Fetch completed orders (excluding those already completed or cancelled)
$sql_completed_orders = "SELECT o.id AS order_id, o.created_at AS order_date, o.order_number, o.total, o.address, o.payment_method, u.full_name, o.status
                         FROM orders o 
                         JOIN users u ON o.user_id = u.id
                         WHERE o.status = 'delivered' 
                         ORDER BY o.created_at DESC";
$result_completed_orders = $conn->query($sql_completed_orders);

// Fetch cancelled orders (excluding those already completed or pending)
$sql_cancelled_orders = "SELECT o.id AS order_id, o.created_at AS order_date, o.order_number, o.total, o.address, o.payment_method, u.full_name, o.status 
                         FROM orders o
                         JOIN users u ON o.user_id = u.id
                         WHERE o.status = 'cancelled' 
                         ORDER BY o.created_at DESC";
$result_cancelled_orders = $conn->query($sql_cancelled_orders);


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


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shoprise | Order Tracker</title>
    <link href="../css/adminstyles.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" href="../pic/logonatinnobg.png" type="image/x-icon">
    <script>
        function updateStatus(orderId, newStatus) {
            // Send an AJAX request to update the order status in the database
            const xhr = new XMLHttpRequest();
            xhr.open("POST", "../PHP/update-order-stat.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    const response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        // Move the row to the appropriate section
                        const row = document.getElementById('order-' + orderId);
                        if (newStatus === 'delivered') {
                            document.getElementById('completed-orders').appendChild(row);
                        } else if (newStatus === 'cancelled') {
                            document.getElementById('cancelled-orders').appendChild(row);
                        } else {
                            const statusCell = row.querySelector('.status-cell');
                            statusCell.innerHTML = newStatus;
                        }
                    } else {
                        alert("Failed to update order status: " + response.message);
                    }
                }
            };
            xhr.send("order_id=" + orderId + "&status=" + newStatus);
        }

    </script>
</head>

<body>

    <!-- Navbar -->
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
                      <!-- Admin profile image -->
    <a href="Admin-profile.php" class="btn rounded-2">  <img src="../pic/Profile/<?php echo htmlspecialchars($admin['profile_picture']); ?>" alt="Profile Picture" class="rounded-circle" width="50" height="50">
                </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Order Tracker Section -->
    <div class="container mt-5 d-flex justify-content-center align-items-center flex-column">
        <h1 class="text-center fw-bold font-HK">Order Tracker</h1>

        <!-- Orders Table -->
        <h3 class="mt-5">Pending</h3>
        <div class="table-responsive mt-4 w-100">

            <table class="table table-striped table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>Order Date</th>
                        <th>Order Number</th>
                        <th>Customer/User Name</th>
                        <th>Shipping Address</th>
                        <th>Payment Method</th>
                        <th>Total</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result_orders && $result_orders->num_rows > 0) {
                        while ($row = $result_orders->fetch_assoc()) {
                            echo "<tr id='order-" . $row['order_id'] . "'>
                                    <td>" . date('d F, Y', strtotime($row['order_date'])) . "</td>
                                    <td>" . htmlspecialchars($row['order_number']) . "</td>
                                    <td>" . htmlspecialchars($row['full_name']) . "</td>
                                    <td>" . htmlspecialchars($row['address']) . "</td>
                                    <td>" . htmlspecialchars($row['payment_method']) . "</td>
                                    <td>$" . number_format($row['total'], 2) . "</td>
                                    <td class='status-cell'>
                                        <select onchange='updateStatus(" . $row['order_id'] . ", this.value)'>
                                            <option value='pending' " . ($row['status'] == 'pending' ? 'selected' : '') . ">Pending</option>
                                            <option value='cancelled' " . ($row['status'] == 'cancelled' ? 'selected' : '') . ">Cancelled</option>
                                            <option value='delivered' " . ($row['status'] == 'delivered' ? 'selected' : '') . ">Delivered</option>
                                        </select>
                                    </td>
                                  </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='7' class='text-center'>No orders found</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <!-- Completed Orders Section -->
        <h3 class="mt-5">Completed Orders</h3>
        <div class="table-responsive w-100">
            <table class="table table-striped table-bordered" id="completed-orders">
                <thead class="table-dark">
                    <tr>
                        <th>Order Date</th>
                        <th>Order Number</th>
                        <th>Customer/User Name</th>
                        <th>Shipping Address</th>
                        <th>Payment Method</th>
                        <th>Total</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result_completed_orders && $result_completed_orders->num_rows > 0) {
                        while ($row = $result_completed_orders->fetch_assoc()) {
                            echo "<tr>
                                    <td>" . date('d F, Y', strtotime($row['order_date'])) . "</td>
                                    <td>" . htmlspecialchars($row['order_number']) . "</td>
                                    <td>" . htmlspecialchars($row['full_name']) . "</td>
                                    <td>" . htmlspecialchars($row['address']) . "</td>
                                    <td>" . htmlspecialchars($row['payment_method']) . "</td>
                                    <td>$" . number_format($row['total'], 2) . "</td>
                                    <td class='status-cell'>
                                        <p style='color: green;'>Delivered</p>
                                    </td>
                                  </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='7' class='text-center'>No completed orders found</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <!-- Cancelled Orders Section -->
        <h3 class="mt-5">Cancelled Orders</h3>
        <div class="table-responsive w-100">
            <table class="table table-striped table-bordered" id="cancelled-orders">
                <thead class="table-dark">
                    <tr>
                        <th>Order Date</th>
                        <th>Order Number</th>
                        <th>Customer/User Name</th>
                        <th>Shipping Address</th>
                        <th>Payment Method</th>
                        <th>Total</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result_cancelled_orders && $result_cancelled_orders->num_rows > 0) {
                        while ($row = $result_cancelled_orders->fetch_assoc()) {
                            echo "<tr>
                <td>" . date('d F, Y', strtotime($row['order_date'])) . "</td>
                <td>" . htmlspecialchars($row['order_number']) . "</td>
                <td>" . htmlspecialchars($row['full_name']) . "</td>
                <td>" . htmlspecialchars($row['address']) . "</td>
                <td>" . htmlspecialchars($row['payment_method']) . "</td>
                <td>$" . number_format($row['total'], 2) . "</td>
                <td class='status-cell'>
                    <p style='color: red;'>Cancelled</p>
                </td>
              </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='7' class='text-center'>No cancelled orders found</td></tr>";
                    }
                    ?>

                </tbody>
            </table>
        </div>
    </div>

    <!-- Footer -->
    <footer class="font-HK p-3 text-white Bg-briblo text-center mt-5" style="border-top: 6px solid #fde49e;">
        © Copyright SHOPPRISE 2024
    </footer>

</body>

</html>
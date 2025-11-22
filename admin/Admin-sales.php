<?php
session_start();
require_once '../PHP/Config.php';

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: Admin-login.php");
    exit();
}

// Fetch all orders from the database
$sql_orders = "SELECT o.id AS order_id, o.created_at AS order_date, o.order_number, o.total, o.address, o.payment_method, u.full_name 
               FROM orders o 
               JOIN users u ON o.user_id = u.id
               ORDER BY o.created_at DESC";
$result_orders = $conn->query($sql_orders);
if (!$result_orders) {
    die("Query failed: " . $conn->error);
}

// Calculate total sales
$total_sales = 0;
if ($result_orders && $result_orders->num_rows > 0) {
    while ($row = $result_orders->fetch_assoc()) {
        $total_sales += $row['total'];
    }
    // Re-execute the query to fetch the rows again for display
    $result_orders = $conn->query($sql_orders);
}
$order_dates = [];
$order_totals = [];

// Fetch completed orders and populate chart data
if ($result_orders && $result_orders->num_rows > 0) {
    while ($row = $result_orders->fetch_assoc()) {
        // Push the order date and total into the arrays
        $order_dates[] = date('d F, Y', strtotime($row['order_date']));
        $order_totals[] = $row['total'];
    }
}

// Fetch only completed (delivered) orders from the database
$sql_orders = "SELECT o.id AS order_id, o.created_at AS order_date, o.order_number, o.total, o.address, o.payment_method, u.full_name 
               FROM orders o 
               JOIN users u ON o.user_id = u.id
               WHERE o.status = 'delivered' 
               ORDER BY o.created_at DESC";
$result_orders = $conn->query($sql_orders);
if (!$result_orders) {
    die("Query failed: " . $conn->error);
}

// Calculate total sales for completed orders
$total_sales = 0;
if ($result_orders && $result_orders->num_rows > 0) {
    while ($row = $result_orders->fetch_assoc()) {
        $total_sales += $row['total'];
    }
    // Re-execute the query to fetch the rows again for display
    $result_orders = $conn->query($sql_orders);
}

// Initialize arrays to hold chart data



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
    <title>Shoprise | Sales Report</title>
    <link href="../css/adminstyles.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" href="../pic/logonatinnobg.png" type="image/x-icon">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
                    <li><a href="Admin-user-manage.php" class="nav-link fw-bold text-light">User  Management</a></li>
                    <li><a href="Admin-product.php" class="nav-link fw-bold text-light">Products</a></li>
                    <li><a href="Admin-sales.php" class="nav-link fw-bold text-light">Sales</a></li>
                    <li><a href="Admin-tracker.php" class="nav-link fw-bold text-light">Order Tracker</a></li>
                </ul>

                <!-- Admin Profile and Logout -->
                <div class="d-flex align-items-center">
                    <a href="Admin-profile.php" class="nav-link px-4 fw-bold text-light">
                        Admin
                    </a>
                        <a href="Admin-profile.php" class="btn rounded-2">  <img src="../pic/Profile/<?php echo htmlspecialchars($admin['profile_picture']); ?>" alt="Profile Picture" class="rounded-circle" width="50" height="50">
                </a></div>
            </div>
        </div>
    </header>

    <!-- Sales Report Section -->
    <div class="container mt-5 d-flex justify-content-center align-items-center flex-column">
        <h2 class="text-center fw-bold font-HK">Sales Report</h2>

        <!-- Total Sales -->
        <div class="alert alert-info text-center w-100 fw-bold mt-3">
            Total Sales: ₱<?php echo number_format($total_sales, 2); ?>
        </div>

        <!-- Sales Table -->
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
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result_orders && $result_orders->num_rows > 0) {
                        while ($row = $result_orders->fetch_assoc()) {
                            echo "<tr>
                                    <td>" . date('d F, Y', strtotime($row['order_date'])) . "</td>
                                    <td>" . htmlspecialchars($row['order_number']) . "</td>
                                    <td>" . htmlspecialchars($row['full_name']) . "</td>
                                    <td>" . htmlspecialchars($row['address']) . "</td>
                                    <td>" . htmlspecialchars($row['payment_method']) . "</td>
                                    <td>₱" . number_format($row['total'], 2) . "</td>
                                  </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='6' class='text-center'>No sales found</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>


    <script>
    // Prepare arrays for chart data
    var orderDates = <?php echo json_encode($order_dates); ?>;
    var orderTotals = <?php echo json_encode($order_totals); ?>;

    // Create the chart
    var ctx = document.getElementById('salesChart').getContext('2d');
    var salesChart = new Chart(ctx, {
        type: 'bar',  // You can change this to 'line' if you prefer a line chart
        data: {
            labels: orderDates,  // Dates on the x-axis
            datasets: [{
                label: 'Total Sales',
                data: orderTotals,  // Sales amounts on the y-axis
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>

<!-- Sales Chart -->
<div class="container mt-5">
    <h3 class="text-center">Sales Over Time</h3>
    <canvas id="salesChart"></canvas>
</div>

</body>
</html>

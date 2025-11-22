<?php
//shopcart.php
session_start();
require_once '../PHP/Config.php';


// Initialize cart if not set
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Handle adding a product to the cart
if (isset($_POST['add_to_cart'])) {
    $product_id = intval($_POST['product_id']);
    $quantity = intval($_POST['quantity']);

    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] += $quantity;
    } else {
        $_SESSION['cart'][$product_id] = $quantity;
    }

    header("Location: shopcart.php");
    exit();
}

// Handle removing a product from the cart
if (isset($_POST['remove_from_cart'])) {
    $product_id = intval($_POST['product_id']);
    unset($_SESSION['cart'][$product_id]);
    header("Location: shopcart.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart | Shoprise</title>
    <link rel="icon" href="../pic/logoorange.png" type="image/x-icon">
    <link href="../css/styles.css" rel="stylesheet">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=delete" />
    <link rel="icon" href="../pic/logonatinnobg.png" type="image/x-icon">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        .quantity-input {
            width: 60px;
            text-align: center;
            padding: 5px;
            font-size: 16px;
        }
    </style>
</head>

<body>
    <header class="p-2 text-white Bg-briblo" style="border-bottom: 6px solid #fde49e;">
        <div class="nav-custom d-flex justify-content-between align-items-center">
            <!-- Login and Sign-up Buttons (Center) -->
            <div class="d-flex justify-content-center flex-grow-1 gap-3">
                <img src="../pic/logonatin.png" width="110px" height="100px" alt="">
            </div>
        </div>
    </header>

    <div class="container mt-5">
        <!-- Shopping Cart Header -->
        <h2 class="text-center mb-4">Shopping Cart</h2>
        <div class="row">
            <div class="col-12">
                <!-- Cart Table -->
                <table class="table table-bordered">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col">Product</th>
                            <th scope="col">Price</th>
                            <th scope="col">Quantity</th>
                            <th scope="col">Total</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $total = 0;
                        foreach ($_SESSION['cart'] as $product_id => $quantity) {
                            $product = getProductById($product_id);
                            if ($product) {
                                $subtotal = $product['price'] * $quantity;
                                $total += $subtotal;
                                ?>
                                <tr data-product-id="<?php echo $product_id; ?>">
                                    <td>
                                        <img src="<?php echo htmlspecialchars($product['image_url']); ?>"
                                            alt="<?php echo htmlspecialchars($product['name']); ?>" class="img-fluid"
                                            style="object-fit: cover; width: 80px;">
                                        <span class="ms-2"><?php echo htmlspecialchars($product['name']); ?></span>
                                    </td>
                                    <td>₱<?php echo number_format($product['price'], 2); ?></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <button class="btn btn-outline-secondary" type="button"
                                                onclick="updateQuantity(<?php echo $product_id; ?>, -1)">-</button>
                                            <input type="number" class="form-control mx-2 quantity-input"
                                                value="<?php echo $quantity; ?>" min="1" name="quantity"
                                                oninput="updateQuantity(<?php echo $product_id; ?>, 0)">
                                            <button class="btn btn-outline-secondary" type="button"
                                                onclick="updateQuantity(<?php echo $product_id; ?>, 1)">+</button>
                                        </div>
                                    </td>
                                    <td class="total-price">₱<?php echo number_format($subtotal, 2); ?></td>
                                    <td>
                                        <form method="POST" action="shopcart.php">
                                            <button class="btn btn-danger" type="submit" name="remove_from_cart" value="1">
                                                <span class="material-symbols-outlined">delete</span>
                                            </button>
                                            <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
                                        </form>
                                    </td>
                                </tr>
                                <?php
                            }
                        }
                        if (empty($_SESSION['cart'])) {
                            echo '<tr><td colspan="5" class="text-center">Your cart is empty.</td></tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Cart Summary -->
        <div class="row mt-4 mb-5">
            <div class="col-12 d-flex justify-content-end">
                <div class="card" style="width: 18rem;">
                    <div class="card-body">
                        <h5 class="card-title">Cart Summary</h5>
                        <p class="card-text">Total: <strong
                                id="total-price">₱<?php echo number_format($total, 2); ?></strong></p>
                        <a href="summary.php" id="checkout-button" class="btn btn-primary w-100 
                    <?php echo empty($_SESSION['cart']) ? 'disabled' : ''; ?>">Proceed to Checkout</a>
                        <a href="product.php" class=" btn btn-warning w-100 mt-3">Continue Shopping</a>

                    </div>
                </div>
            </div>
        </div>

    </div>

    <script>
        // Function to update quantity and total price
        function updateQuantity(product_id, change) {
            const row = $(`tr[data-product-id="${product_id}"]`);
            const quantityInput = row.find('.quantity-input');
            let quantity = parseInt(quantityInput.val());

            // Update quantity
            if (change !== 0) {
                quantity += change;
            }
            if (quantity < 1) {
                quantity = 1; // Prevent quantity from going below 1
            }
            quantityInput.val(quantity);

            // Update the total price
            const price = parseFloat(row.find('td:nth-child(2)').text().replace('₱', '').replace(',', ''));
            const totalPrice = row.find('.total-price');
            totalPrice.text('₱' + (price * quantity).toFixed(2));

            // Update the cart summary total
            updateCartTotal();

            // Send AJAX request to update the cart
            $.ajax({
                url: 'update_cart.php',
                type: 'POST',
                data: {
                    product_id: product_id,
                    quantity: quantity
                },
                success: function (response) {
                    console.log('Cart updated successfully');
                },
                error: function (error) {
                    console.error('Error updating cart', error);
                }
            });
        }

        // Function to update the cart summary total
        function updateCartTotal() {
            const totalPrices = $('.total-price');
            let grandTotal = 0;
            totalPrices.each(function () {
                grandTotal += parseFloat($(this).text().replace('₱', '').replace(',', ''));
            });

            $('#total-price').text('₱' + grandTotal.toFixed(2));
        }

        // Initial update of the cart summary total
        updateCartTotal();

        function updateCartTotal() {
            const totalPrices = $('.total-price');
            let grandTotal = 0;
            totalPrices.each(function () {
                grandTotal += parseFloat($(this).text().replace('₱', '').replace(',', ''));
            });

            $('#total-price').text('₱' + grandTotal.toFixed(2));

            // Enable or disable the checkout button based on cart contents
            if (grandTotal === 0) {
                $('#checkout-button').addClass('disabled');
            } else {
                $('#checkout-button').removeClass('disabled');
            }
        }

    </script>

    <footer class="font-HK p-3 text-white Bg-briblo justify-content-center align-items-center d-flex flex-column"
        style="border-top: 6px solid #fde49e;">
        © Copyright SHOPPRISE 2024
    </footer>
</body>

</html>
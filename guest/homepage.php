<?php
//product.php
require_once '../PHP/Config.php';

// Fetch products from the database
$products = getProducts();
?>
<title>Shoprise | Simplify Supply Shoprise!</title>

<link href="../css/home.css" rel="stylesheet">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=shopping_cart" />
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Hanken+Grotesk:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
<link rel="icon" href="../pic/logonatinnobg.png" type="image/x-icon"> 

<!--nav bar-->
<header class="p-3 text-white Bg-briblo">
    <div class="nav-custom">
        <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
            <a href="homepage.php"><img class="" src="../pic/logonatin.png" width="65" height="58"></a>
            <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
                <li><a href="#" class="btn px-5 fw-bold me-2 nav-barok" style=" font-size: 30px;">Home</a></li>
                <li><a href="product.php" class="nav-link px-3 fw-bold text-bri text-light" style="font-size: 30px;">Products</a></li>
                <li><a href="about.php" class="nav-link px-4 fw-bold text-light" style="font-size: 30px;">About</a></li>
            </ul>
            
            <div class="d-flex align-items-center">
              <a href="../login.php" class=" btn btn-outline-info me-2 fw-bold" style="font-size: 25px; box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);">Login</a>
              <a href="../signup.php" class="btn btn-outline-info fw-bold me-2" style="font-size: 25px; box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);">Sign-up</a>
              <a href="../login.php" class="btn btn-outline-info fw-bold " style="box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);">
                  <span class="material-symbols-outlined" style="font-size: 30px;">
                      shopping_cart
                  </span>
              </a>
          </div>
        </div>
    </div>
</header>

<!--body na-->
<!-- White Square Section Below Navbar -->
<div class="main-content">
    <!-- White Square Section -->
    <div class="white-square">
      <div class="text-content font-HK">
        <h1>SHOP <span  style="color:  #b48d00">RISE</span></h1>
        <p>Your one stop shop for copy paper, barcode printers, thermal paper, and many more; tailored for small business owners and business chain owners seeking individual or bulk purchases.</p>
      </div>

      <!-- Button aligned to the right -->
      <a class="text-decoration-none btn btn-custom" href="../login.php">Get started</a>
    </div>
</div>


<h1 class="text-center"> Featured Product </h1>
<!-- Overview of Products -->
<div class="container mt-5 mb-5">
    <div class="row">
        <?php
        $counter = 0; // Initialize counter
        foreach ($products as $product) {
            // Stop loop after 4 products
            if ($counter >= 4) {
                break;
            }
            $productImage = $product['image_url'];
            $productName = $product['name'];
            $productPrice = $product['price'];
            $description = $product['description'];
            $stockQuantity = $product['stock'];
            $productId = $product['id'];
        ?>
            <div class="col-md-3">
                <div class="product-container shadow-lg rounded p-3">
                    <img src="../pic/<?php echo htmlspecialchars($productImage); ?>" alt="Product Image" class="img-fluid rounded">
                    <h2 class="fw-bold mt-2"><?php echo htmlspecialchars($productName); ?></h2>
                    <p class="text-warning fw-bold">₱<?php echo number_format($productPrice, 2); ?></p>
                    <p class="text-muted"><strong>Description:</strong> <?php echo htmlspecialchars($description); ?></p>
                    <p><strong>Stock:</strong> <?php echo $stockQuantity; ?> available</p>
                    <div class="d-flex gap-2">
               
                    </div>
                </div>
            </div>
        <?php
            $counter++; // Increment counter
        }
        ?>
    </div>
</div>

<!-- Delivery Process Section -->
<div class="container my-5">
    <h2 class="text-center mb-4 fw-bold mb-5 bg-briblo rounded"
        style="color: #d8c7b1; font-size:40px; padding: 20px; display: inline-block;">
        How We Handle Deliveries
    </h2>

    <div class="row g-4">
        <!-- Step 1 -->
        <div class="col-md-4">
            <div class="text-center bg-briblo p-4 rounded shadow-sm">
                <img src="../pic/place.png" alt="Place an Order" class="img-fluid mb-3"
                    style="object-fit: cover; border-radius: 5px;">
                <h4 class="fw-bold text-light">Step 1: Place Your Order</h4> <!-- Change to text-dark -->
                <p class="text-light">Browse our wide selection of products and place an order with ease using our
                    online store. Select the items you need and proceed to checkout.</p> <!-- Change to text-dark -->
            </div>
        </div>

        <!-- Step 2 -->
        <div class="col-md-4">
            <div class=" text-center bg-briblo p-4 rounded shadow-sm">
                <img src="../pic/process.png" alt="Order Processing" class="img-fluid mb-3"
                    style="object-fit: cover; border-radius: 5px;">
                <h4 class="fw-bold text-light">Step 2: Order Processing</h4> <!-- Change to text-dark -->
                <p class="text-light">Once your order is placed, our team will prepare and package your products. You
                    will receive a confirmation email with the order details and estimated delivery date.</p>
                <!-- Change to text-dark -->
            </div>
        </div>

        <!-- Step 3 -->
        <div class="col-md-4">
            <div class=" text-center bg-briblo p-4 rounded shadow-sm">
                <img src="../pic/Delivery.png" alt="Delivery" class="img-fluid mb-3"
                    style="object-fit: cover; border-radius: 5px;">
                <h4 class="fw-bold text-light">Step 3: Delivery to Your Door</h4> <!-- Change to text-dark -->
                <p class="text-light">After your order is processed, our trusted delivery partners will bring your items
                    to your door. We ensure timely and safe delivery for your convenience.</p>
                <!-- Change to text-dark -->
            </div>
        </div>
    </div>
</div>



<!-- Contact Us Section -->
<div class="container my-5">
    <h2 class="text-center mb-4 fw-bold mb-5 bg-briblo rounded"
        style="color: #d8c7b1; font-size:40px; padding: 20px; display: inline-block;">
        Contact us
    </h2>
    <!-- Contact Information -->
    <div class="row g-4">
        <div class="col-md-6">
            <div class="bg-briblo p-4 rounded shadow-sm">
                <h4 class="fw-bold text-light">Get in Touch</h4>
                <p class="text-light">Feel free to reach out to us for any inquiries, issues, or feedback. Our team is
                    ready to assist you!</p>

                <ul class="list-unstyled text-light">
                    <li><strong>Email:</strong> Support@shopprise.com</li>
                    <li><strong>Phone:</strong> +123 456 7890</li>
                    <li><strong>Address:</strong> wlaa pa address</li>
                </ul>
            </div>
        </div>

        <!-- Contact Form -->
        <div class="col-md-6">
            <div class="bg-briblo p-4 rounded shadow-sm">
                <h4 class="fw-bold text-light">Send Us a Message</h4>
                <form action="submit_contact.php" method="post">
                    <div class="mb-3">
                        <label for="name" class="form-label text-light">Full Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label text-light">Email Address</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>

                    <div class="mb-3">
                        <label for="message" class="form-label text-light">Message</label>
                        <textarea class="form-control" id="message" name="message" rows="4" required></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary w-100"
                        style="background-color: #fde49e; color: #000;">Send Message</button>
                </form>

            </div>
        </div>
    </div>
</div>

<div class="container bg-briblo mb-5">
        <h2 class="fw-bold p-5 text-light ">Simplify <span style="color: #d8c7b1;">Supply</span> <span style="color:  #b48d00">  Shoprise</span></h2>
      
    </div>


<!-- Footer -->
<footer class="font-HK p-3 text-white Bg-briblo d-flex justify-content-between align-items-center" style="border-top: 6px solid #fde49e;">
    <div>
        © Copyright SHOPPRISE 2024
    </div>
    <!-- Admin Sign Up Link -->
    <div class="ms-auto">
        <a href="../admin/Admin-login.php" class="btn btn-outline-light btn-sm fw-bold" style="font-size:20px;">
            Admin Login
        </a>
    </div>
</footer>

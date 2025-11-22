// Add to Cart Button Action
function addToCart(productId, productName, productPrice, productLocation, productImage) {
    // Send AJAX request to add item to cart
    const data = {
        product_id: productId,
        quantity: 1,
        product_name: productName,
        product_price: productPrice,
        product_location: productLocation,
        product_image: productImage
    };

    fetch('add_to_cart.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Show the notification box with the success message
            const notificationBox = document.getElementById('notificationBox');
            const notificationMessage = document.getElementById('notificationMessage');
            notificationMessage.textContent = 'You have added an item to the cart!';
            notificationBox.style.display = 'block';  // Show the notification

            // Hide the notification box after 3 seconds
            setTimeout(() => {
                notificationBox.style.display = 'none';
            }, 3000);
        } else {
            alert('Error adding product to cart');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('There was an error.');
    });
}

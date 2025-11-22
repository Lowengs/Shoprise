// Add to Cart Button Action
function addToCart(productId, productName, productPrice, productLocation, productImage) {
    const data = {
        product_id: productId,
        quantity: 1,  // Add 1 item to the cart by default
        product_name: productName,
        product_price: productPrice,
        product_location: productLocation,
        product_image: productImage
    };

    // Send an AJAX request to add the item to the cart
    fetch('../Users/shopcart.php', {
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

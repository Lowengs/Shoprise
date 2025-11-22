// search.js

document.getElementById('searchButton').addEventListener('click', function () {
    const query = document.getElementById('searchInput').value;

    console.log("Search query:", query);  // Log search query

    fetch('../PHP/search_products.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ search: query })
    })
    .then(response => response.json())
    .then(data => {
        console.log("Search response:", data);  // Log the response data

        const productList = document.getElementById('productList');
        const searchMessage = document.getElementById('searchMessage');

        // Update the search message
        searchMessage.textContent = query ? `You have searched for "${query}"` : "";

        productList.innerHTML = ''; // Clear current content

        if (data.products && data.products.length > 0) {
            data.products.forEach(product => {
                const productCard = `
                    <div class="col-md-3 text-decoration-none">
                        <a href="product-page.php?product_id=${product.id}&product_name=${product.name}&price=${product.price}&location=${product.location}&product_image=${product.image_url}"
                            class="text-decoration-none text-dark">
                            <div class="product-card">
                                <img src="${product.image_url}" alt="${product.name}"
                                    class="img-fluid" style="object-fit: cover;">
                                <div class="d-flex justify-content-between align-items-start mt-2">
                                    <div class="pt-2" style="text-align: left;">
                                        <h5>${product.name}</h5>
                                        <p>₱${product.price}</p>
                                        <p class="product-location"><i class="bi bi-geo-alt-fill"></i> ${product.location}</p>
                                    </div>
                                    <div class="action-buttons d-flex flex-column align-items-center justify-content-center">
                                        <form method="POST" action="shopcart.php" class="d-flex flex-column align-items-center justify-content-center">
                                            <input type="hidden" name="product_id" value="${product.id}">
                                            <input type="hidden" name="quantity" value="1">
                                            <button type="submit" class="btn btn-outline-info fw-bold bg-briblo mt-2" name="add_to_cart">
                                                <span class="material-symbols-outlined" style="font-size: 25px;">shopping_cart</span>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                `;
                productList.innerHTML += productCard;
            });
        } else {
            productList.innerHTML = `<div class="col-12 text-center"><p>No products found.</p></div>`;
        }
    })
    .catch(error => console.error('Error fetching products:', error));
});
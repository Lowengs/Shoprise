
document.addEventListener('DOMContentLoaded', () => {
    const searchInput = document.getElementById('searchInput');
    const searchButton = document.getElementById('searchButton');
    const productTable = document.getElementById('productTable');
    const searchMessage = document.getElementById('searchMessage');

    // Function to fetch products
    const fetchProducts = (query = '') => {
        productTable.innerHTML = `<tr><td colspan="6" class="text-center">Loading...</td></tr>`; // Show loading message
        searchMessage.innerHTML = ''; // Clear the search message

        fetch('../PHP/admin_search.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ search: query })
        })
            .then(response => response.json())
            .then(data => {
                productTable.innerHTML = ''; // Clear the table

                // Update search message
                if (query) {
                    searchMessage.innerHTML = `You have searched for "<strong>${query}</strong>"`;
                }

                if (data.products && data.products.length > 0) {
                    // Render each product
                    data.products.forEach(product => {
                        const row = `
                            <tr>
                                <td>${product.id}</td>
                               <td><img src="data:image/png;base64,${product.image_url}" alt="Product Image" style="max-width: 100px; max-height: 100px; width: auto; height: auto;"> </td>
<td>${product.name}</td>
                                <td>₱${product.price.toFixed(2)}</td>
                                <td>${product.stock}</td>
                                <td class="text-center">
                                    <a href="Admin-edit.php?id=${product.id}" class="btn btn-warning btn-sm"><img src="../pic/edit.png" height="30px" width="30px" alt="Edit"></a>
                                    <a href="Admin-delete.php?id=${product.id}" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this product?')"><img src="../pic/delete.png" height="30px" width="30px" alt="Delete"></a>
                                    <a href="Admin-message.php?id=${product.id}" class="btn btn-info btn-sm"><img src="../pic/chat.png" height="30px" width="30px" alt="Message"></a>
                                </td>
                            </tr>
                        `;
                        productTable.innerHTML += row;
                    });
                } else {
                    productTable.innerHTML = `<tr><td colspan="6" class="text-center">No products found.</td></tr>`;
                }
            })
            .catch(error => {
                console.error('Error fetching products:', error);
                productTable.innerHTML = `<tr><td colspan="6" class="text-center">Error loading products. Please try again later.</td></tr>`;
            });
    };

    // Load all products on page load
    fetchProducts();

    // Search button click event
    searchButton.addEventListener('click', () => {
        const query = searchInput.value.trim();
        fetchProducts(query);
    });

    // Optional: Search on Enter key
    searchInput.addEventListener('keyup', (event) => {
        if (event.key === 'Enter') {
            const query = searchInput.value.trim();
            fetchProducts(query);
        }
    });
});
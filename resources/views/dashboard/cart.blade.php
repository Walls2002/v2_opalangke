<!DOCTYPE html>
<html lang="en">
    <!DOCTYPE html>
    <html lang="en">
        @include('layout.head')
    <body class="nav-fixed">
        @include('layout.topnav')
        <div id="layoutSidenav">
            @include('layout.sidenav')
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-xl px-4 mt-5">
                        <!-- Custom page header alternative example-->
                        <div class="d-flex justify-content-between align-items-sm-center flex-column flex-sm-row mb-4">
                            <div class="me-4 mb-3 mb-sm-0">
                                <h1 class="mb-0">Cart</h1>
                            </div>
                            <!-- clear all-->
                            <div class="">
                                <button class="btn btn-danger shadow" id="clearCartBtn">Clear Cart</button>
                            </div>
                        </div>
                        <!-- Illustration dashboard card example-->
                        <div id="cart-container" class="mb-4 mt-5">
                            <!-- Cart items will be injected here dynamically -->
                        </div>
                                         
                    </div>
                </main>
                @include('layout.footer')
            </div>
        </div>
        
        <!-- Modal for checkout -->
            <div class="modal fade" id="checkoutModal" tabindex="-1" aria-labelledby="checkoutModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="checkoutModalLabel">Confirm Checkout</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="locationForm">
                                <div class="mb-3">
                                    <label for="addressInput" class="form-label">Address<span class="text-danger">*</span></label>
                                    <input type="text" id="addressInput" class="form-control address-input" placeholder="Enter address" required />
                                </div>
                                <div class="mb-3">
                                    <label for="barangayInput" class="form-label">Barangay<span class="text-danger">*</span></label>
                                    <input type="text" id="barangayInput" class="form-control barangay-input" placeholder="Enter barangay" required />
                                </div>
                                <div class="mb-3">
                                    <label for="cityInput" class="form-label">City<span class="text-danger">*</span></label>
                                    <input type="text" id="cityInput" class="form-control city-input" placeholder="Enter city" required />
                                </div>
                                <div class="mb-3">
                                    <label for="noteInput" class="form-label">Note</label>
                                    <input type="text" id="noteInput" class="form-control note-input" placeholder="Enter note (optional)" />
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Confirm Checkout</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

    
        @include('layout.scripts')

        <script>
            $(document).ready(function() {
                // Function to fetch cart data
                function fetchCart() {
                    $.ajax({
                        url: '/api/cart',
                        type: 'GET',
                        headers: {
                                Authorization: `Bearer ${localStorage.getItem('token')}`, // Include token if required
                            },
                        success: function(response) {
                                let cartContent = '';
                                if (response.cart.length === 0) {
                                    cartContent = `
                                        <div class="text-center mt-5">
                                            <h4>No items available on cart.</h4>
                                        </div>
                                    `;
                                } else{
                                // Loop through each store in the cart
                                response.cart.forEach(store => {
                                    cartContent += `
                                        <div class="card mb-4">
                                            <div class="card-body p-5">
                                                <h5 class="pb-2 text-primary">Store: ${store.name}</h5>
                                                <div class="p-3" style="border: 1px solid rgb(184, 184, 184)">
                                    `;
                                    
                                    // Loop through each product in the store
                                    store.products.forEach(product => {
                                        cartContent += `
                                            <div class="d-flex justify-content-between align-items-sm-center flex-column flex-sm-row text-dark mb-3">
                                                <div class="me-4 mb-3 mb-sm-0">
                                                    <p class="mb-0 text-primary">${product.name} - ₱${parseFloat(product.total_cost).toFixed(2)}</p>
                                                    <small>Quantity: ${product.selected_qty}</small>
                                                </div>
                                                <!-- Product quantity controls -->
                                                <div class="">
                                                    <button class="btn btn-outline-primary btn-sm decrease" data-product-id="${product.id}">-</button>
                                                    <button class="btn btn-outline-primary btn-sm increase" data-product-id="${product.id}">+</button>
                                                </div>
                                            </div>
                                            <hr>
                                        `;
                                    });

                                    cartContent += `
                                                <p class="fw-bold">Total Price: ₱${store.total_price}</p>
                                                <button class="btn btn-primary btn-sm btn-checkout" data-store-id="${store.id}">Checkout</button>
                                                </div>
                                            </div>
                                        </div>
                                    `;
                                });
                            }
                                

                                // Inject cart content into the container
                                $('#cart-container').html(cartContent);
                        },
                        error: function() {
                            alert('Failed to fetch cart data');
                        }
                    });
                }

                // Call fetchCart when the page is ready
                fetchCart();

                // Handle clicking the checkout button
                $(document).on('click', '.btn-checkout', function() {
                    const storeId = $(this).data('store-id');
                    
                    // Store the storeId in the modal for use during checkout
                    $('#checkoutModal').data('store-id', storeId);
                    
                    // Show the modal
                    $('#checkoutModal').modal('show');
                });

                // Handle form submission for checkout
                $('#locationForm').on('submit', function(event) {
                    event.preventDefault();

                    // Get values from the form
                    const address = $('.address-input').val();
                    const barangay = $('.barangay-input').val();
                    const city = $('.city-input').val();
                    const note = $('.note-input').val();

                    // Combine the address fields
                    const fullAddress = `${address}, ${barangay}, ${city}`;

                    // Get storeId from the modal data
                    const storeId = $('#checkoutModal').data('store-id');

                    // Send the data to the API for checkout
                    $.ajax({
                        url: `/api/cart/checkout/${storeId}`,
                        type: 'POST',
                        headers: {
                                Authorization: `Bearer ${localStorage.getItem('token')}`, // Include token if required
                            },
                        data: {
                            address: fullAddress,  // Address combined from form fields
                            note: note            // Optional note
                        },
                        success: function(response) {
                                // Close the modal
                                $('#checkoutModal').modal('hide');

                                // Optionally, reload the cart or redirect to another page
                                alert('Checkout successful!');
                                fetchCart();
                        },
                        error: function() {
                            alert('Failed to process checkout.');
                        }
                    });
                });
                
                // Event delegation for increase and decrease buttons
                $(document).on('click', '.increase, .decrease', function() {
                    const productId = $(this).data('product-id');
                    const action = $(this).hasClass('increase') ? 'increase' : 'decrease';
                    const url = action === 'increase' ? `/api/cart/${productId}` : `/api/cart/${productId}?clear=0`;

                    // Make the API call to update the cart
                    $.ajax({
                        url: url,
                        type: action === 'increase' ? 'POST' : 'PUT', // Use PUT for decrease
                        headers: {
                                Authorization: `Bearer ${localStorage.getItem('token')}`, // Include token if required
                            },
                        success: function(response) {
                                fetchCart();
                        },
                        error: function() {
                            alert('Failed to update cart');
                        }
                    });
                });
            });

        </script>

        <script>
            $(document).ready(function() {
                // When the "Clear Cart" button is clicked
                $('#clearCartBtn').on('click', function() {
                    // Ask the user to confirm if they want to clear the cart
                    if (confirm('Are you sure you want to clear all items from your cart?')) {
                        // If confirmed, make an AJAX request to clear the cart
                        $.ajax({
                            url: '/api/cart',  // API endpoint to clear the cart
                            method: 'DELETE',   // DELETE method
                            headers: {
                                'Authorization': 'Bearer ' + localStorage.getItem('token')  // Include token if necessary
                            },
                            success: function(response) {
                                // Handle successful response (e.g., show a message or update the UI)
                                alert('Your cart has been cleared.');
                                // Optionally, refresh the cart UI or do other necessary actions
                                location.reload();  // This will reload the page and reset the cart UI
                            },
                            error: function(xhr, status, error) {
                                // Handle error response
                                alert('An error occurred while clearing the cart. Please try again.');
                            }
                        });
                    }
                });
            });
        </script>
    </body>
</html>
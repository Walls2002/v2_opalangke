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
                                <label for="noteInput" class="form-label">Note</label>
                                <input type="text" id="noteInput" class="form-control note-input" placeholder="Enter note (optional)" />
                            </div>

                            <div class="mb-3">
                                <label for="voucher_code" class="form-label">Voucher Code</label>
                                <input type="text" id="voucher_code" class="form-control voucher_code" placeholder="Enter voucher (optional)" />
                            </div>

                            <hr>

                            <!-- Order Preview Section -->
                            <div id="orderPreview" class="d-none">
                                <h5>Order Summary</h5>
                                <p><strong>Subtotal:</strong> ₱<span id="totalItemPrice"></span></p>
                                <p><strong>Delivery Fee:</strong> ₱<span id="shippingFee"></span></p>
                                <p><strong>Discount:</strong> -₱<span id="discount"></span></p>
                                <p class="fw-bold"><strong>Total:</strong> ₱<span id="finalPrice"></span></p>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                <button type="button" id="previewCheckoutBtn" class="btn btn-info">Preview Order</button>
                                <button type="submit" id="confirmCheckoutBtn" class="btn btn-primary d-none">Confirm Checkout</button>
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
                                        let kiloMeasurementText = "";
    
                                        if (product.kilo_measurement) {
                                            let kiloValue = parseFloat(product.kilo_measurement);
                                            let displayValue = kiloValue === 0.25 ? "1/4" :
                                                            kiloValue === 0.5 ? "1/2" :
                                                            kiloValue; // Keep other values as is

                                            kiloMeasurementText = `<small id="kilo-${product.id}">${displayValue} kilos</small><br>`;
                                        }

                                        cartContent += `
                                            <div class="d-flex justify-content-between align-items-sm-center flex-column flex-sm-row text-dark mb-3">
                                                <div class="me-4 mb-3 mb-sm-0">
                                                    <p class="mb-0 text-primary">${product.name} - ₱${product?.total_cost}</p>
                                                    <small>Quantity: ${product.selected_qty}</small><br>
                                                    ${kiloMeasurementText}
                                                </div>
                                                <!-- Product quantity controls -->
                                                <div class="">
                                                    <button class="btn btn-outline-primary btn-sm decrease" data-product-id="${product.id}">-</button>
                                                    <button class="btn btn-outline-primary btn-sm add-increase" data-product-id="${product.id}">+</button>
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

                $('#previewCheckoutBtn').on('click', function() {
                    // Get user inputs
                    const address = $('.address-input').val();
                    const note = $('.note-input').val();
                    const voucher_code = $('.voucher_code').val();
                    const storeId = $('#checkoutModal').data('store-id');

                    // Ensure storeId exists
                    if (!storeId) {
                        alert("Store ID is missing!");
                        return;
                    }

                    // Call checkout preview API
                    $.ajax({
                        url: `/api/cart/checkout-preview/${storeId}`,
                        type: 'POST',
                        headers: {
                            Authorization: `Bearer ${localStorage.getItem('token')}`,
                        },
                        data: { address, note, voucher_code },
                        success: function(response) {
                            if (response.order) {
                                // Update UI with order preview
                                $('#totalItemPrice').text(response.order.total_item_price);
                                $('#shippingFee').text(response.order.shipping_fee);
                                $('#discount').text(response.order.discount);
                                $('#finalPrice').text(response.order.final_price);

                                // Show order preview and confirm button
                                $('#orderPreview').removeClass('d-none');
                                $('#confirmCheckoutBtn').removeClass('d-none');
                            }
                        },
                        error: function() {
                            alert("Failed to fetch checkout preview.");
                        }
                    });
                });

                // Handle final checkout after preview
                $('#locationForm').on('submit', function(event) {
                    event.preventDefault();

                    const address = $('.address-input').val();
                    const note = $('.note-input').val();
                    const voucher_code = $('.voucher_code').val();
                    const storeId = $('#checkoutModal').data('store-id');

                    // Proceed with actual checkout
                    $.ajax({
                        url: `/api/cart/checkout/${storeId}`,
                        type: 'POST',
                        headers: {
                            Authorization: `Bearer ${localStorage.getItem('token')}`,
                        },
                        data: { address, note, voucher_code },
                        success: function(response) {
                            $('#checkoutModal').modal('hide');
                            alert('Checkout successful!');
                            fetchCart(); // Reload cart
                        },
                        error: function() {
                            alert('Failed to process checkout.');
                        }
                    });
                });

                
                
                // Event delegation for decrease buttons
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

                $(document).on('click', '.add-increase', function() {
                    const productId = $(this).data('product-id');
                    const token = localStorage.getItem('token');

                    // Get the kilo measurement using its unique ID
                    const kiloElement = $(`#kilo-${productId}`);
                    let kiloMeasurement = null;

                    if (kiloElement.length) { // Check if the product has a kilo measurement
                        const value = kiloElement.text().split(' ')[0]; // Extract number part
                        kiloMeasurement = value === "1/4" ? 0.25 :
                                        value === "1/2" ? 0.5 :
                                        parseFloat(value); // Convert to number
                    }

                    // Prepare request data
                    const requestData = kiloMeasurement ? { kilo_measurement: kiloMeasurement } : { quantity: '' };

                    // Send the API request
                    $.ajax({
                        url: `/api/cart/${productId}`,
                        type: 'POST',
                        headers: {
                            Authorization: `Bearer ${token}`, 
                            'Content-Type': 'application/json'
                        },
                        data: JSON.stringify(requestData),
                        success: function(response) {
                            fetchCart(); // Refresh cart after update
                        },
                        error: function() {
                            alert('Failed to update quantity');
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
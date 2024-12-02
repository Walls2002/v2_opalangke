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
                                <h1 class="mb-0">Delivered</h1>
                            </div>
                            <!-- clear all-->
                            <div class="">
                                <select id="store-selector" class="form-control">
                                    <option value="" disabled selected>Select a Store</option>
                                </select>
                            </div>
                        </div>
                        <!-- Illustration dashboard card example-->
                        <div id="cart-container" class="mb-4 mt-5">
                            <div class="text-center mt-5 select-store-first">
                                <h4>Select a store first.</h4>
                            </div>
                            <!-- Cart items will be injected here dynamically -->
                        </div>
                                         
                    </div>
                </main>
                @include('layout.footer')
            </div>
        </div>

        <!-- Proof of Delivery Modal -->
        <div class="modal fade" id="deliveryImageModal" tabindex="-1" aria-labelledby="deliveryImageModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deliveryImageModalLabel">Proof of Delivery</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center">
                        <img src="" alt="Proof of Delivery" class="img-fluid" style="max-height: 400px; object-fit: contain;">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>


    
        @include('layout.scripts')

        <script>
            $(document).ready(function() {
                // Global storeId
                let storeId = null;
        
                // Function to fetch stores
                function fetchStores() {
                    $.ajax({
                        url: '/api/stores',
                        type: 'GET',
                        headers: {
                            Authorization: `Bearer ${localStorage.getItem('token')}`,
                        },
                        success: function(response) {
                            const storeSelector = $('#store-selector');
                            response.stores.forEach(store => {
                                storeSelector.append(`<option value="${store.id}">${store.store_name}</option>`);
                            });
                        },
                        error: function() {
                            alert('Failed to fetch stores');
                        }
                    });
                }
        
                // Function to fetch cart data
                function fetchCart() {
                    if (!storeId) {
                        alert('Please select a store first.');
                        return;
                    }

                    const apiUrl = `/api/vendor-orders/${storeId}?show_delivered=1`;

                    $.ajax({
                        url: apiUrl,
                        type: 'GET',
                        headers: {
                            Authorization: `Bearer ${localStorage.getItem('token')}`,
                        },
                        success: function (response) {
                            let cartContent = '';

                            // Check if there are any orders
                            if (response.orders.length === 0) {
                                cartContent = `
                                    <div class="text-center mt-5">
                                        <h4>No orders available for this store.</h4>
                                        <p>Please check back later.</p>
                                    </div>
                                `;
                            } else {
                                response.orders.forEach(order => {
                                    cartContent += `
                                        <div class="card mb-4">
                                            <div class="card-body p-5">
                                                <h5 class="pb-2 text-primary">${order.customer.name}</h5>
                                                <p>${order.customer.contact} | ${order.customer.email}</p>
                                                <p>Address: ${order?.address}</p>
                                                <p>Note: ${order?.note}</p>
                                                <p class="pt-3">Assigned Rider: ${order?.rider?.name || 'No rider assigned'} ${order?.rider?.contact_number || ''}</p>
                                                <div class="p-3" style="border: 1px solid rgb(184, 184, 184)">
                                    `;

                                    order.items.forEach(product => {
                                        cartContent += `
                                            <div class="d-flex justify-content-between align-items-sm-center flex-column flex-sm-row text-dark mb-3">
                                                <div class="me-4 mb-3 mb-sm-0">
                                                    <p class="mb-0 text-primary">${product.name} - ₱${parseFloat(product.unit_price).toFixed(2)}</p>
                                                    <small>Quantity: ${product.quantity}</small>
                                                </div>
                                            </div>
                                            <hr>
                                        `;
                                    });

                                    cartContent += `
                                            <p class="fw-bold">Total Price: ₱${order.total_price}</p>
                                            </div><br>
                                            <button class="btn btn-primary btn-sm view-proof-btn" data-delivery-image="${order.delivery_image}">View Proof of Delivery</button>
                                        </div>
                                    </div>
                                    `;
                                });
                            }

                            // Inject cart content or message into the container
                            $('#cart-container').html(cartContent);

                            // Attach click event to dynamically added buttons
                            $('.view-proof-btn').on('click', function () {
                                const deliveryImage = $(this).data('delivery-image');
                                if (deliveryImage) {
                                    // Set the image in the modal
                                    $('#deliveryImageModal img').attr('src', `storage/${deliveryImage}`);
                                    // Show the modal
                                    $('#deliveryImageModal').modal('show');
                                } else {
                                    alert('No proof of delivery available.');
                                }
                            });
                        },
                        error: function () {
                            alert('Failed to fetch cart data');
                        }
                    });
                }

                // Handle store selection
                $('#store-selector').on('change', function() {
                    storeId = $(this).val();
                    $('.select-store-first').addClass('d-none');
                    if (storeId) {
                        fetchCart();
                    }
                });
        
                // Confirm order
                $(document).on('click', '.btn-confirm', function() {
                    const orderId = $(this).data('order-id');
        
                    if (!storeId) {
                        alert('Please select a store first.');
                        return;
                    }
        
                    $.ajax({
                        url: `/api/vendor-orders/${orderId}/confirm`,
                        type: 'PUT',
                        headers: {
                            Authorization: `Bearer ${localStorage.getItem('token')}`,
                        },
                        success: function(response) {
                            alert('Order has been successfully confirmed. Please check the confirmed page.');
                            fetchCart();
                        },
                        error: function() {
                            alert('Failed to process confirmation.');
                        }
                    });
                });

                $(document).on('click', '.btn-cancel', function() {
                    const cancelId = $(this).data('cancel-id');
        
                    if (!storeId) {
                        alert('Please select a store first.');
                        return;
                    }
        
                    $.ajax({
                        url: `/api/vendor-orders/${cancelId}/cancel`,
                        type: 'DELETE',
                        headers: {
                            Authorization: `Bearer ${localStorage.getItem('token')}`,
                        },
                        success: function(response) {
                            alert('Order has been successfully canceled. Please check the cancel page.');
                            fetchCart();
                        },
                        error: function() {
                            alert('Failed to process confirmation.');
                        }
                    });
                });
        
                // Fetch stores on page load
                fetchStores();
            });
        </script>
        
    </body>
</html>
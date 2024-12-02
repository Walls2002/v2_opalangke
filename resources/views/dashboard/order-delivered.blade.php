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
                // Function to fetch cart data
                function fetchCart() {
                    $.ajax({
                        url: '/api/customer-orders?show_delivered=1',
                        type: 'GET',
                        headers: {
                            Authorization: `Bearer ${localStorage.getItem('token')}`, // Include token if required
                        },
                        success: function(response) {
                            let cartContent = '';

                            if (response.orders.length === 0) {
                                cartContent = `
                                    <div class="text-center mt-5">
                                        <h4>No orders available.</h4>
                                    </div>
                                `;
                            } else{
                            // Loop through each order in the response
                            response.orders.forEach(order => {
                                // Add store information
                                cartContent += `
                                    <div class="card mb-4">
                                        <div class="card-body p-5">
                                            <h5 class="pb-2 text-primary">Store: ${order.store.store_name} - ${order.store.contact_number}</h5>
                                            <p class="pt-3">Assigned Rider: ${order?.rider?.name || 'No rider assigned'} ${order?.rider?.contact_number || ''}</p>
                                            <div class="p-3" style="border: 1px solid rgb(184, 184, 184)">
                                `;
                                
                                // Loop through each product in the order
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

                                // Close the store section
                                cartContent += `
                                        <p class="fw-bold">Total Price: ₱${order.total_price}</p>
                                        </div><br>
                                            <button class="btn btn-primary btn-sm view-proof-btn" data-delivery-image="${order.delivery_image}">View Proof of Delivery</button>
                                    </div>
                                </div>
                                `;
                            });
                        }
                            // Inject cart content into the container
                            $('#cart-container').html(cartContent);

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
                        error: function() {
                            alert('Failed to fetch cart data');
                        }
                    });
                }

                // Call fetchCart when the page is ready
                fetchCart();
            });
        </script>
    </body>
</html>
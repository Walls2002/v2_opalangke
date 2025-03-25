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
                                <h1 class="mb-0">Canceled</h1>
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

    
        @include('layout.scripts')

        <script>
            $(document).ready(function() {
                // Function to fetch cart data
                function fetchCart() {
                    $.ajax({
                        url: '/api/customer-orders?show_canceled=1',
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
                                        <h5>Order Summary</h5>
                                        <p class="fw-bold">Subtotal: ₱${order.total_item_price}</p>
                                        <p class="fw-bold">Delivery Fee: ₱${order.shipping_fee}</p>
                                        <p class="fw-bold">Discount: ₱${order.shipping_fee}</p>
                                        <p class="fw-bold">Total: ₱${order.final_price}</p>
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
            });
        </script>
    </body>
</html>
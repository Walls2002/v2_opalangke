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
                                <h1 class="mb-0">For Delivery</h1>
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

        <!-- Delivery Image Modal -->
        <div class="modal fade" id="deliveryImageModal" tabindex="-1" aria-labelledby="deliveryImageModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deliveryImageModalLabel">Upload Delivery Proof</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="deliveryForm">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="deliveryImageInput" class="form-label">Select Image</label>
                                <input type="file" class="form-control" id="deliveryImageInput" accept="image/*" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>


    
        @include('layout.scripts')

        <script>
            $(document).ready(function () {
                // Fetch cart data
                function fetchCart() {
                    const apiUrl = `/api/rider-orders`;
        
                    $.ajax({
                        url: apiUrl,
                        type: 'GET',
                        headers: {
                            Authorization: `Bearer ${localStorage.getItem('token')}`,
                        },
                        success: function (response) {
                            let cartContent = '';
        
                            if (response.orders.length === 0) {
                                cartContent = `
                                    <div class="text-center mt-5">
                                        <h4>No delivery available</h4>
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
                                                <p class="pt-3">Store: ${order?.store?.store_name} - ${order?.store?.contact_number}</p>
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
                                            <button class="btn btn-primary btn-sm btn-confirm" data-order-id="${order.id}">Marked Delivered</button>
                                            </div>
                                        </div>
                                    </div>
                                    `;
                                });
                            }
        
                            $('#cart-container').html(cartContent);
                        },
                        error: function () {
                            alert('Failed to fetch cart data');
                        }
                    });
                }
        
                // Handle "Marked Delivered" button click
                $(document).on('click', '.btn-confirm', function () {
                    const orderId = $(this).data('order-id');
                    $('#deliveryImageModal').data('order-id', orderId).modal('show');
                });
        
                // Submit delivery image
                $('#deliveryForm').on('submit', function (e) {
                    e.preventDefault();
        
                    const orderId = $('#deliveryImageModal').data('order-id');
                    const formData = new FormData();
                    const imageFile = $('#deliveryImageInput')[0].files[0];
        
                    if (!imageFile) {
                        alert('Please select an image.');
                        return;
                    }
        
                    formData.append('image', imageFile);
        
                    $.ajax({
                        url: `/api/rider-orders/${orderId}/deliver`,
                        type: 'POST',
                        headers: {
                            Authorization: `Bearer ${localStorage.getItem('token')}`,
                        },
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function () {
                            alert('Order marked as delivered successfully.');
                            $('#deliveryImageModal').modal('hide');
                            fetchCart(); // Refresh the cart
                        },
                        error: function () {
                            alert('Failed to mark order as delivered.');
                        }
                    });
                });
        
                // Fetch cart on page load
                fetchCart();
            });
        </script>
        
        
    </body>
</html>
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
                                <h1 class="mb-0">Confirmed</h1>
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

    
        @include('layout.scripts')

        <script>
            $(document).ready(function() {
                // Global variables
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
        
                    const apiUrl = `/api/vendor-orders/${storeId}?show_confirmed=1&show_assigned=1&show_dispatched=1`;
        
                    $.ajax({
                        url: apiUrl,
                        type: 'GET',
                        headers: {
                            Authorization: `Bearer ${localStorage.getItem('token')}`,
                        },
                        success: function(response) {
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
                                    const riderSelectId = `rider-selector-${order.id}`;
        
                                    cartContent += `
                                        <div class="card mb-4">
                                            <div class="card-body p-5">
                                                <h5 class="pb-2 text-primary">${order.customer.first_name} ${order.customer.middle_name} ${order.customer.last_name}</h5>
                                                <p>${order.customer.contact} | ${order.customer.email}</p>
                                                <p>Address: ${order?.address}</p>
                                                <p>Note: ${order?.note ? order.note : ''}</p>

                                                <p class="pt-3">Rider: ${order?.rider?.user?.first_name || 'Currently waiting for a rider to accept delivery.'} ${order?.rider?.user?.middle_name || ''} ${order?.rider?.user?.last_name || ''} ${order?.rider?.user?.contact || ''}</p>
                                                <hr>
                                                
                                                ${order.rider_team_only != null ? '' : `
                                                        <div class="row">
                                                            <div class="col-8">
                                                                <select id="rider_team_only" class="form-control rider-selector">
                                                                    <option value="1">Dispatch to Store Riders Only</option>
                                                                    <option value="0">Dispatch to All Riders in the Area</option>
                                                                </select>    
                                                            </div>
                                                            <div class="col-4">
                                                                <button class="btn btn-primary btn-confirm" data-order-id="${order.id}">Dispatch Order</button>    
                                                            </div>
                                                        </div>
                                                    `}
                                                <br>
                                                <div class="p-3" style="border: 1px solid rgb(184, 184, 184)">
                                    `;
        
                                    order.items.forEach(product => {
                                        cartContent += `
                                            <div class="d-flex justify-content-between align-items-sm-center flex-column flex-sm-row text-dark mb-3">
                                                <div class="me-4 mb-3 mb-sm-0">
                                                    <p class="mb-0 text-primary">${product.name} - ₱${product?.total_cost}</p>
                                                    <small>Quantity: ${product.quantity}</small>
                                                </div>
                                            </div>
                                            <hr>
                                        `;
                                    });
        
                                    cartContent += `
                                            <h5>Order Summary</h5>
                                            <p class="fw-bold">Subtotal: ₱${order.total_item_price}</p>
                                            <p class="fw-bold">Delivery Fee: ₱${order.shipping_fee}</p>
                                            <p class="fw-bold">Discount: ₱${order.discount}</p>
                                            <p class="fw-bold">Total: ₱${order.final_price}</p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    `;
                                });
                            }
        
                            // Inject cart content or message into the container
                            $('#cart-container').html(cartContent);
        
                            // Populate all rider selectors
                            $('.rider-selector').each(function() {
                                const selectorId = $(this).attr('id');
                                populateRiderSelector(selectorId);
                            });
                        },
                        error: function() {
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

                document.addEventListener("click", function (event) {
                    if (event.target.classList.contains("btn-confirm")) {
                        const orderId = event.target.getAttribute("data-order-id");
                        const riderTeamOnly = document.getElementById("rider_team_only").value;
                        const token = localStorage.getItem("token");

                        axios.post(`/api/vendor-orders/${orderId}/dispatch`, 
                            { rider_team_only: riderTeamOnly }, 
                            {
                                headers: {
                                    "Authorization": `Bearer ${token}`,
                                    "Content-Type": "application/json"
                                }
                            }
                        )
                        .then(response => {
                            alert("Order dispatched successfully!");
                            console.log(response.data);
                            fetchCart();
                        })
                        .catch(error => {
                            alert("Failed to dispatch order.");
                            console.error(error);
                        });
                    }
                });

        
                // Fetch stores on page load
                fetchStores();
            });
        </script>
        
        
    </body>
</html>
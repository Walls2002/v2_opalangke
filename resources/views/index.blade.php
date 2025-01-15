<!DOCTYPE html>
<html lang="en">
    @include('layout.head')
    <body>
        <!-- Navigation-->
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container px-4 px-lg-5">
                <a class="navbar-brand pe-3 ps-4 ps-lg-2 text-primary" href="/">
                    <img src="logo.png" alt="Logo" style="height: 32px; width: auto; margin-right: 8px;">
                    Palengke</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-4">
                        
                    </ul>
                    <form class="d-flex">
                        <button class="btn btn-outline-primary add-to-cart-btn role-customer">
                            <i class="bi-cart-fill me-1"></i>
                            Cart
                        </button> &nbsp;
                        <a id="loginButton" class="btn btn-primary" href="/login">
                            <i class="bi bi-box-arrow-in-right me-1"></i>
                            Login
                        </a>
                        <a id="homeButton" class="btn btn-primary" href="/home">
                            <i class="bi bi-box-arrow-in-right me-1"></i>
                            Home
                        </a>                        
                    </form>
                </div>
            </div>
        </nav>
        <!-- Header-->
        <header class="bg-dark bg-white">
            <div class="container">
                <div class="text-center">
                    <img src="img/hero.png" class="img-fluid" alt="Responsive image">
                </div>
            </div>
        </header>
        <!-- Section-->
        <section class="py-5">
            <div class="mx-5">
                <label for="locationDropdown" class="form-label">Choose a Location:</label>
                <select id="locationDropdown" class="form-select">
                    <option value="">Select Location</option>
                </select>
            </div>
            <div class="container px-4 px-lg-5 mt-5">
                <div class="row gx-4 gx-lg-5 row-cols-2 row-cols-md-3 row-cols-xl-4 justify-content-center" id="productContainer">
                    
                </div>
            </div>
        </section>
        <!-- Footer-->
        <footer class="py-5 bg-primary">
            <div class="container"><p class="m-0 text-center text-white">Copyright &copy; Palengke 2024</p></div>
        </footer>
        @include('layout.scripts')

        <script>
            $(document).ready(function () {
                const locationDropdown = $('#locationDropdown');
                const productContainer = $('#productContainer');
                
                // Fetch locations for the dropdown
                $.ajax({
                    url: '/api/locations',
                    method: 'GET',
                    dataType: 'json',
                    success: function (locations) {
                        // Populate the dropdown with locations
                        locations.forEach(location => {
                            const option = `<option value="${location.id}">${location.barangay}, ${location.city}, ${location.province}</option>`;
                            locationDropdown.append(option);
                        });
                    },
                    error: function (error) {
                        console.error('Error fetching locations:', error);
                    }
                });

                // Fetch and display products based on the selected location
                function fetchProducts(locationId) {
                    const apiEndpoint = locationId ? `/api/catalog?location_id=${locationId}` : '/api/catalog';

                    $.ajax({
                        url: apiEndpoint,
                        method: 'GET',
                        dataType: 'json',
                        success: function (products) {
                            // Clear existing products
                            productContainer.empty();

                            // Create product cards
                            products.forEach(product => {
                                const productCard = `
                                    <div class="col mb-5">
                                        <div class="card h-100">
                                            <!-- Product image -->
                                            <img class="card-img-top border" 
                                                src="${product.image ? 'storage/' + product.image : 'no-image.jpg'}" 
                                                alt="${product.name}" />
                                            <!-- Product details -->
                                            <div class="card-body p-4">
                                                <div class="text-center">
                                                    <h5 class="fw-bolder text-primary">${product.name}</h5>
                                                    <small>Stocks: ${product.quantity}</small>
                                                    <h4 class="pt-3">₱<span class="product-price" id="product-price-${product.id}">${parseFloat(product.price).toFixed(2)}</span></h4>
                                                    <small class="text-primary">Store: ${product.store.store_name}</small><br>
                                                    <small>${product.store.street}, ${product.store.location.barangay}, 
                                                    ${product.store.location.city}, ${product.store.location.province} | 
                                                    ${product.store.contact_number}</small>
                                                </div>
                                            </div>
                                            <!-- Product actions -->
                                            <div class="card-footer p-4 pt-0 border-top-0 bg-transparent">
                                                <div class="text-center">
                                                    <!-- Measurement Type Selection -->
                                                    <label for="measurement_type_${product.id}">Measurement Type</label>
                                                    <select id="measurement_type_${product.id}" class="form-select form-select-sm" data-product-id="${product.id}" data-price="${product.price}">
                                                        <option value="" selected disabled>Select Measurement</option>
                                                        <option value="kg">kg</option>
                                                        <option value="piece">Piece</option>
                                                    </select><br>

                                                    <!-- Measurement Value - will show based on selection -->
                                                    <div id="kg_options_${product.id}" class="kg-options mb-3" style="display:none;">
                                                        <label for="quantity_kg_${product.id}">Select Weight</label>
                                                        <select class="form-select form-select-sm" id="quantity_kg_${product.id}" data-product-id="${product.id}">
                                                            <option value="1">1 kg</option>
                                                            <option value="0.5">1/2 kg</option>
                                                            <option value="0.25">1/4 kg</option>
                                                        </select>
                                                    </div>

                                                    <div id="piece_options_${product.id}" class="piece-options mb-3" style="display:none;">
                                                        <label for="quantity_piece_${product.id}">Enter Quantity</label>
                                                        <input type="number" class="form-control form-control-sm" id="quantity_piece_${product.id}" data-product-id="${product.id}" />
                                                    </div>

                                                    <button class="btn btn-outline-primary mt-auto add-to-cart role-customer" data-product-id="${product.id}">Add to cart</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                `;

                                productContainer.append(productCard);

                                // Show/Hide options based on the selected measurement type
                                $(`#measurement_type_${product.id}`).change(function () {
                                    if ($(this).val() === 'kg') {
                                        $(`#kg_options_${product.id}`).show();
                                        $(`#piece_options_${product.id}`).hide();
                                    } else {
                                        $(`#kg_options_${product.id}`).hide();
                                        $(`#piece_options_${product.id}`).show();
                                    }
                                });
                            });
                        },
                        error: function (error) {
                            console.error('Error fetching products:', error);
                        }
                    });
                }

                // Fetch products when a location is selected
                locationDropdown.change(function () {
                    const locationId = $(this).val();
                    fetchProducts(locationId);
                });

                document.addEventListener('change', function (event) {
                    // Check if the target is a measurement type (select) or quantity (input/select)
                    const productId = event.target.getAttribute('data-product-id'); // Get product ID from the element that triggered the event

                    if (!productId) return; // If no product ID is found, exit

                    // Get the unit price from the data-price attribute of the measurement type select
                    const measurementTypeSelect = document.querySelector(`#measurement_type_${productId}`);
                    if (!measurementTypeSelect) return; // If measurement type select is not found, exit

                    const unitPrice = parseFloat(measurementTypeSelect.getAttribute('data-price')); // Retrieve the unit price

                    let quantity = 0;

                    // Handle different measurement types
                    if (event.target.id.startsWith('quantity_kg')) {
                        // Get the selected kg value
                        quantity = parseFloat(event.target.value);
                    } else if (event.target.id.startsWith('quantity_piece')) {
                        // Get the entered piece quantity
                        quantity = parseFloat(document.getElementById(`quantity_piece_${productId}`).value);
                    }

                    // If quantity is not valid, return early
                    if (isNaN(quantity) || quantity <= 0) {
                        return;
                    }

                    // Calculate the new price based on the quantity and unit price
                    const totalPrice = unitPrice * quantity;

                    // Update the price display
                    const priceDisplay = document.getElementById(`product-price-${productId}`);
                    if (priceDisplay) {
                        priceDisplay.textContent = totalPrice.toFixed(2); // Update the displayed price
                    }
                });

                document.addEventListener('click', function (event) {
                    if (event.target.classList.contains('add-to-cart')) {
                        event.preventDefault();

                        const token = localStorage.getItem('token');

                        if (!token) {
                            alert('You need to login first to add items to your cart.');
                            window.location.href = '/login';
                        } else {
                            const productId = event.target.getAttribute('data-product-id');
                            const measurementType = $(`#measurement_type_${productId}`).val();

                            let quantity = 0;

                            // If the user selected 'kg'
                            if (measurementType === 'kg') {
                                quantity = parseFloat($(`#quantity_kg_${productId}`).val());
                            }
                            // If the user selected 'piece'
                            else if (measurementType === 'piece') {
                                quantity = parseFloat($(`#quantity_piece_${productId}`).val());
                            }

                            if (!quantity || isNaN(quantity) || quantity <= 0) {
                                alert('Please enter a valid quantity.');
                                return;
                            }

                            // Make a POST request to add the product to the cart
                            fetch(`/api/cart/${productId}`, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'Authorization': `Bearer ${token}`
                                },
                                body: JSON.stringify({
                                    measurement_type: measurementType,
                                    quantity: quantity
                                })
                            })
                            .then(response => {
                                if (!response.ok) {
                                    throw new Error(`HTTP error! status: ${response.status}`);
                                }
                                return response.json();
                            })
                            .then(data => {
                                alert('Product successfully added to the cart!');
                                console.log('Response from server:', data);
                            })
                            .catch(error => {
                                console.error('Error adding product to cart:', error);
                                alert('Failed to add product to cart. Please try again.');
                            });
                        }
                    }
                });


                // Initial load of all products
                fetchProducts();
            });
        </script>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                // Check for token in local storage
                const token = localStorage.getItem('token');

                const loginButton = document.getElementById('loginButton');
                const homeButton = document.getElementById('homeButton');

                if (token) {
                    // If token exists, hide the login button
                    loginButton.style.display = 'none';
                    homeButton.style.display = 'inline-block';
                } else {
                    // If no token, show the login button and hide the home button
                    loginButton.style.display = 'inline-block';
                    homeButton.style.display = 'none';
                }
            });
        </script>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
            // Select all "Add to cart" buttons
            const addToCartButtons = document.querySelectorAll('.add-to-cart-btn');

            // Attach click event listeners to each button
            addToCartButtons.forEach(button => {
                button.addEventListener('click', function (event) {
                    event.preventDefault(); // Prevent default action of the link

                    // Check for token in local storage
                    const token = localStorage.getItem('token');

                    if (!token) {
                        // Redirect to login if no token exists
                        alert('You need to login first to view your cart.');
                        window.location.href = '/login';
                    } else {
                        // Console log for debugging (or your actual logic)
                        window.location.href = '/cart';
                    }
                });
            });
        });
        </script>

        <script>
            $(document).ready(function () {
                function checkUserRole() {
                    const user = JSON.parse(localStorage.getItem('user'));

                    if (user && user.role !== 'customer') {
                        $('.role-customer').addClass('d-none');
                    }
                }

                // Initial role check for elements already present in the DOM
                checkUserRole();

                // Example: Re-run the check after dynamic content is loaded
                $(document).ajaxComplete(function () {
                    checkUserRole();
                });
            });
        </script>
    </body>
</html>
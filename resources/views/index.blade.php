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
                        <button class="btn btn-outline-primary" type="submit">
                            <i class="bi-cart-fill me-1"></i>
                            Cart
                            <span class="badge bg-primary text-white ms-1 rounded-pill">0</span>
                        </button> &nbsp;
                        <a class="btn btn-primary" href="/login">
                            <i class="bi bi-box-arrow-in-right me-1"></i>
                            Login
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
                                                    <!-- Product qty -->
                                                    <small>Stocks: ${product.quantity}</small>
                                                    <!-- Product price -->
                                                    <h4 class="pt-3">₱${parseFloat(product.price).toFixed(2)}</h4>
                                                    <small class="text-primary">Store: ${product.store.store_name}</small><br>
                                                    <small>${product.store.street}, ${product.store.location.barangay}, 
                                                    ${product.store.location.city}, ${product.store.location.province} | 
                                                    ${product.store.contact_number}</small>
                                                </div>
                                            </div>
                                            <!-- Product actions -->
                                            <div class="card-footer p-4 pt-0 border-top-0 bg-transparent">
                                                <div class="text-center">
                                                    <a class="btn btn-outline-primary mt-auto" href="#">Add to cart</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                `;
                                productContainer.append(productCard);
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

                // Initial load of all products
                fetchProducts();
            });
        </script>
    </body>
</html>
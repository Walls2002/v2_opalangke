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
                            <h1 class="mb-0">Home</h1>
                        </div>
                        <div class="">
                            <a class="btn btn-primary shadow" href="/home">Go Back to Home</a>
                        </div>
                    </div>

                    <!-- Product section-->
                    <section class="py-5">
                        <div class="container px-4 px-lg-5 my-5">
                            <div class="row gx-4 gx-lg-5 align-items-center">
                                <div class="col-md-6">
                                    <img id="product-image" class="card-img-top mb-5 mb-md-0 border" width="450" height="450" src="/no-image.jpg" alt="Product Image" />
                                </div>
                                <div class="col-md-6">
                                    <h1 class="display-5 fw-bolder" id="product-name">Loading...</h1>
                                    <div class="fs-2 mb-5">
                                        <span id="product-price"></span>
                                    </div>
                                    <p class="lead" id="product-description">Loading description...</p>


                                    <div class="d-flex align-items-end gap-2">
                                        <!-- Quantity Input -->
                                        <div>
                                            <label for="inputQuantity" class="form-label d-block">Quantity:</label>
                                            <input class="form-control text-center" id="inputQuantity" type="number" value="1" min="1" max="10000" style="max-width: 7rem; height: 43px;" />
                                        </div>

                                        <!-- Kilo Measurement Dropdown (Hidden by Default) -->
                                        <div id="kiloContainer" style="display: none;">
                                            <label for="kiloMeasurement" class="form-label d-block">Weight:</label>
                                            <select class="form-control" id="kiloMeasurement" style="max-width: 6rem; height: 43px;">
                                                <option value="0.25">1/4 kilo</option>
                                                <option value="0.50">1/2 kilo</option>
                                                <option value="1">1 kilo</option>
                                            </select>
                                        </div>

                                        <div class="d-flex align-items-end">
                                            <button class="btn btn-outline-primary flex-shrink-0" type="button" id="addToCartBtn" style="height: 43px;">
                                                <i class="bi-cart-fill me-1"></i>
                                                Add to cart
                                            </button>
                                        </div>
                                    </div>

                                    <p id="cart-message" class="mt-3 text-success" style="display: none;">Item added to cart!</p>
                                </div>
                            </div>
                        </div>
                    </section>
            </main>
            @include('layout.footer')
        </div>
    </div>

    @include('layout.scripts')

    <script>
        document.addEventListener("DOMContentLoaded", async function() {
            const product_id = JSON.parse(localStorage.getItem('product_id'));

            if (!product_id) {
                console.error("No product_id found in localStorage.");
                return;
            }

            $.ajax({
                url: `/api/catalog/products/${product_id}`,
                method: 'GET',
                dataType: 'json',
                headers: {
                    "Content-Type": "application/json",
                    Authorization: `Bearer ${localStorage.getItem("token")}`
                },
                success: function(response) {
                    if (response.product) {
                        $("#product-name").text(response.product.name);
                        $("#product-price").text(`₱${parseFloat(response.product.price).toFixed(2)}`);
                        $("#product-description").text(`Available: ${response.product.quantity} ${response.product.measurement}`);

                        if (response.product.image) {
                            $("#product-image").attr("src", "/storage/" + response.product.image);
                        }

                        // Show kilo selection only if the product is measured in kilos
                        if (response.product.measurement.toLowerCase() === "kilo") {
                            $("#kiloContainer").show();
                        } else {
                            $("#kiloContainer").hide();
                        }

                        if (response.product.quantity <= 0) {
                            $("#addToCartBtn").prop("disabled", true).text("Out of Stock");
                            $("#addToCartBtn").addClass("btn-outline-danger").removeClass("btn-outline-primary");
                            $("#inputQuantity").prop("disabled", true);
                        } else {
                            $("#addToCartBtn").prop("disabled", false).text("Add to Cart");
                            $("#addToCartBtn").removeClass("btn-outline-danger").addClass("btn-outline-primary");
                            $("#inputQuantity").prop("disabled", false);
                        }


                        // Attach event listener to Add to Cart button
                        $("#addToCartBtn").on("click", function() {
                            addToCart(response.product);
                        });
                    }
                },
                error: function(error) {
                    console.error('Error fetching product:', error);
                }
            });

            function addToCart(product) {
                const quantity = parseInt($("#inputQuantity").val()) || 1;
                let data = {
                    quantity: quantity
                };

                // If product is measured in kilos, include kilo_measurement
                if (product.measurement.toLowerCase() === "kilo") {
                    const kilo_measurement = $("#kiloMeasurement").val();
                    data.kilo_measurement = parseFloat(kilo_measurement);
                }
                if (product.quantity <= 0) {
                    alert("Product is out of stock.");
                    return;
                }

                let totalRequested = quantity;
                if (product.measurement.toLowerCase() === "kilo") {
                    const kilo_measurement = parseFloat($("#kiloMeasurement").val());
                    totalRequested = quantity * kilo_measurement;
                }
                if (totalRequested > product.quantity) {
                    alert(`Only ${product.quantity} ${product.measurement} available in stock.`);
                    $("#inputQuantity").val(1);
                    return;
                }


                $.ajax({
                    url: `/api/cart/${product.id}`,
                    method: 'POST',
                    dataType: 'json',
                    contentType: 'application/json',
                    headers: {
                        Authorization: `Bearer ${localStorage.getItem("token")}`
                    },
                    data: JSON.stringify(data),
                    success: function(response) {
                        $("#cart-message").text("Item added to cart!").fadeIn().delay(2000).fadeOut();
                    },
                    error: function(error) {
                        console.error("Error adding to cart:", error);
                        alert("Failed to add item to cart.");
                    }
                });
            }
        });
    </script>

</body>

</html>

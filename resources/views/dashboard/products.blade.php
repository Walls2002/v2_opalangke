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
                            <h1 class="mb-0">Products</h1>
                        </div>
                        <!-- Date range picker example-->
                        <div class="">
                            <button class="btn btn-primary shadow" data-bs-toggle="modal" data-bs-target="#createModal">Create New Product</button>
                        </div>
                    </div>
                    <!-- Illustration dashboard card example-->
                    <div class="card mb-4 mt-5">
                        <div class="card-body p-5">
                            <div class="table-responsive">
                                <table id="usersTable" class="table">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Store</th>
                                            <th>Image</th>
                                            <th>Name</th>
                                            <th>Price</th>
                                            <th>Quantity</th>
                                            <th>Actions</th>
                                        
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Data will be populated here by DataTables -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>                    
                </div>
            </main>
            @include('layout.footer')
        </div>
    </div>

    {{-- modals --}}

    <!-- Create Store Modal -->
        <div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="createModalLabel">Create New Store</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    
                    <!-- Form for creating a new store -->
                    <div class="modal-body">
                        <form id="createForm" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="product_name" class="form-label">Name<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="product_name" placeholder="Enter product name" required />
                            </div>
                            
                            <div class="mb-3">
                                <label for="image" class="form-label">Product Image</label>
                                <input type="file" class="form-control" id="image" accept="image/*" />
                            </div>

                            <div class="mb-3">
                                <label for="price" class="form-label">Price<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="price" placeholder="Enter price" required />
                            </div>

                            <div class="mb-3">
                                <label for="quantity" class="form-label">Quantity<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="quantity" placeholder="Enter quantity" required />
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Store Modal -->
        <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel">Edit Store</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    
                    <!-- Form for editing an existing store -->
                    <div class="modal-body">
                        <form id="editForm" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="product_name" class="form-label">Name<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="edit_product_name" placeholder="Enter product name" required />
                            </div>
                            
                            <div class="mb-3">
                                <label for="image" class="form-label">Product Image</label>
                                <input type="file" class="form-control" id="edit_image" accept="image/*" />
                            </div>

                            <div class="mb-3">
                                <label for="price" class="form-label">Price<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="edit_price" placeholder="Enter price" required />
                            </div>

                            <div class="mb-3">
                                <label for="quantity" class="form-label">Quantity<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="edit_quantity" placeholder="Enter quantity" required />
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>    


    @include('layout.scripts')

    <script>
        $(document).ready(function () {

            const store_id = localStorage.getItem('store_id')
                // Initialize DataTable
                $('#usersTable').DataTable({
                    ajax: {
                        url: '/api/products?store_id='+store_id, // Your API endpoint for fetching users
                        type: 'GET',
                        headers: {
                                Authorization: `Bearer ${localStorage.getItem('token')}`, // Include token if required
                            },
                        dataSrc: '' // Adjust based on the response structure ('' if data is a direct array)
                    },
                    columns: [
                        { data: 'id' },
                        { data: 'store.store_name' },
                        { 
                            data: 'image',
                            render: function(data, type, row) {
                                // Check if image exists and return the img tag, else show a default placeholder
                                const imageUrl = data ? 'storage/' + data : 'no-image.jpg';
                                return `<img src="${imageUrl}" alt="${row.store_name}" class="img-thumbnail" style="width: 50px; height: 50px;">`;
                            }
                        },
                        { data: 'name' },
                        { data: 'price' },
                        { data: 'quantity' },
                        {
                            data: null,
                            render: function (data, type, row) {
                                return `
                                    <button class="btn btn-warning btn-sm" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#editModal" 
                                        data-row='${JSON.stringify(row)}'
                                        onclick="editUser(this)">Edit</button>

                                    <button class="btn btn-danger btn-sm" onclick="deleteUser(${row.id})">Delete</button>
                                `;
                            }
                        }
                    ],
                    dom: 'lBfrtip', // Enable buttons for export functionality
                    buttons: [
                        'copy', 'csv', 'excel', 'pdf', 'print'
                    ]
                });
            });

            function viewProducts(id){
                localStorage.setItem('store_id', id);

                window.location.href = '/products';
            }

            // Function to delete a user
            async function deleteUser(userId) {
                if (confirm("Are you sure you want to delete this product?")) {
                    try {
                        // Send DELETE request to the API
                        await axios.delete(`/api/products/${userId}`, {
                            headers: {
                                Authorization: `Bearer ${localStorage.getItem('token')}`, // Include token if required
                            },
                        });

                        // Show success message
                        alert("Product deleted successfully!");

                        // Reload the DataTable to reflect changes
                        $('#usersTable').DataTable().ajax.reload();
                    } catch (error) {
                        // Handle error response
                        if (error.response) {
                            alert(error.response.data.message || "Failed to delete user. Please try again.");
                        } else {
                            alert("An error occurred. Please check your connection and try again.");
                        }
                    }
                }
            }
    </script>

        <script>
            // Function to populate the edit form with existing store data
            function editUser(button) {
                // Get the row data from the data-row attribute
                const rowData = JSON.parse(button.getAttribute('data-row'));

                // Populate the form fields
                document.getElementById("edit_product_name").value = rowData.name;
                document.getElementById("edit_price").value = rowData.price;
                document.getElementById("edit_quantity").value = rowData.quantity;

                // Store the product id for later use when submitting the form
                document.getElementById("editForm").dataset.storeId = rowData.id;
            }

            // Handle form submission for editing a store
            document.getElementById("editForm").addEventListener("submit", async function (event) {
                event.preventDefault(); // Prevent default form submission behavior

                const storeId = document.getElementById("editForm").dataset.storeId;
                const name = document.getElementById("edit_product_name").value.trim();
                const price = document.getElementById("edit_price").value.trim();
                const quantity = document.getElementById("edit_quantity").value;
                const image = document.getElementById("edit_image").files[0]; // Get the selected image file (optional)

                // Basic validation
                if (!name || !price || !quantity) {
                    alert("Please fill in all required fields.");
                    return;
                }

                const formData = new FormData();
                formData.append("name", name);
                formData.append("price", price);
                formData.append("quantity", quantity);
                if (image) {
                    formData.append("image", image); // Add the image if present
                }

                try {
                    // Send POST request to update the store (sending store ID in the body)
                    const response = await axios.post(`/api/products/`+storeId+"?_method=PUT", formData, {
                        headers: {
                            Authorization: `Bearer ${localStorage.getItem('token')}`, // Include token if required
                            "Content-Type": "multipart/form-data" // Important for uploading files
                        },
                    });

                    // Show success message
                    alert("Product updated successfully!");

                    // Close modal
                    const modal = bootstrap.Modal.getInstance(document.getElementById("editModal"));
                    modal.hide();

                    // Reload the DataTable to reflect changes
                    $('#usersTable').DataTable().ajax.reload();
                } catch (error) {
                    // Handle error response
                    if (error.response) {
                        alert(error.response.data.message || "Failed to update product. Please try again.");
                    } else {
                        alert("An error occurred. Please check your connection and try again.");
                    }
                }
            });
        </script>


        <script>
            // Handle form submission
            document.getElementById("createForm").addEventListener("submit", async function (event) {
                event.preventDefault(); // Prevent default form submission behavior

                // Get form input values
                const name = document.getElementById("product_name").value.trim();
                const price = document.getElementById("price").value.trim();
                const quantity = document.getElementById("quantity").value.trim();
                const image = document.getElementById("image").files[0]; // Get the selected image file
                const store_id = JSON.parse(localStorage.getItem('store_id'));

                // Basic validation
                if (!name || !price || !quantity) {
                    alert("Please fill in all required fields.");
                    return;
                }

                const formData = new FormData();
                formData.append("store_id", store_id);
                formData.append("name", name);
                formData.append("price", price);
                formData.append("quantity", quantity);
                if (image) {
                    formData.append("image", image);
                }

                try {
                    // Send POST request to create store
                    const response = await axios.post('/api/products', formData, {
                        headers: {
                            Authorization: `Bearer ${localStorage.getItem('token')}`, // Include token if required
                            "Content-Type": "multipart/form-data" // Important for uploading files
                        },
                    });

                    // Show success message
                    alert("Product created successfully!");

                    // Close modal
                    const modal = bootstrap.Modal.getInstance(document.getElementById("createModal"));
                    modal.hide();

                    // Reset form
                    event.target.reset();

                    // Reload the DataTable to reflect changes
                    $('#usersTable').DataTable().ajax.reload();
                } catch (error) {
                    // Handle error response
                    if (error.response) {
                        alert(error.response.data.message || "Failed to create product. Please try again.");
                    } else {
                        alert("An error occurred. Please check your connection and try again.");
                    }
                }
            });
        </script>
    
</body>
</html>
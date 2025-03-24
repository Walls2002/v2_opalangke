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
                            <h1 class="mb-0">Stores</h1>
                        </div>
                        <!-- Date range picker example-->
                        <div class="">
                            <button class="btn btn-primary shadow" data-bs-toggle="modal" data-bs-target="#createModal">Create New Store</button>
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
                                            <th>Image</th>
                                            <th>Name</th>
                                            <th>Contact Number</th>
                                            <th>Address</th>
                                            <th>Shipping Fee</th>
                                            <th>Status</th>
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
                                <label for="store_name" class="form-label">Store Name<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="store_name" placeholder="Enter store name" required />
                            </div>
                            
                            <div class="mb-3">
                                <label for="image" class="form-label">Store Image</label>
                                <input type="file" class="form-control" id="image" accept="image/*" />
                            </div>

                            <div class="mb-3">
                                <label for="street" class="form-label">Street<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="street" placeholder="Enter street address" required />
                            </div>
                            
                            <div class="mb-3">
                                <label for="location_id" class="form-label">Location<span class="text-danger">*</span></label>
                                <select class="form-select" id="location_id" required>
                                    <option value="">Select Location</option>
                                    <!-- Locations will be populated dynamically here -->
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label for="contact_number" class="form-label">Contact Number<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="contact_number" placeholder="Enter contact number" required />
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
                                <label for="edit_store_name" class="form-label">Store Name<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="edit_store_name" placeholder="Enter store name" required />
                            </div>
                            
                            <div class="mb-3">
                                <label for="edit_image" class="form-label">Store Image</label>
                                <input type="file" class="form-control" id="edit_image" accept="image/*" />
                            </div>

                            <div class="mb-3">
                                <label for="edit_street" class="form-label">Street<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="edit_street" placeholder="Enter street address" required />
                            </div>
                            
                            <div class="mb-3">
                                <label for="edit_location_id" class="form-label">Location<span class="text-danger">*</span></label>
                                <select class="form-select" id="edit_location_id" required>
                                    <option value="">Select Location</option>
                                    <!-- Locations will be populated dynamically here -->
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label for="edit_contact_number" class="form-label">Contact Number<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="edit_contact_number" placeholder="Enter contact number" required />
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Save Changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>    


    @include('layout.scripts')

    <script>
        $(document).ready(function () {
                // Initialize DataTable
                $('#usersTable').DataTable({
                    ajax: {
                        url: '/api/stores', // Your API endpoint for fetching users
                        type: 'GET',
                        headers: {
                                Authorization: `Bearer ${localStorage.getItem('token')}`, // Include token if required
                            },
                        dataSrc: 'stores' // Adjust based on the response structure ('' if data is a direct array)
                    },
                    columns: [
                        { data: 'id' },
                        { 
                            data: 'image',
                            render: function(data, type, row) {
                                // Check if image exists and return the img tag, else show a default placeholder
                                const imageUrl = data ? '/storage/' + data : '/no-image.jpg';
                                return `<img src="${imageUrl}" alt="${row.store_name}" class="img-thumbnail" style="width: 50px; height: 50px;">`;
                            }
                        },
                        { data: 'store_name' },
                        { data: 'contact_number' },
                        { 
                            data: 'street',
                            render: function(data, type, row) {
                                // Concatenate street, province, city, and barangay to show the full address
                                return `${data}, ${row.location.barangay}, ${row.location.city}, ${row.location.province}`;
                            }
                        },
                        { data: 'location.shipping_fee' },
                        {
                            data: 'is_verified',
                            render: function (data, type, row) {
                                return data ? 'Approved' : 'Pending';
                            }
                        },
                        {
                            data: null,
                            render: function (data, type, row) {
                                return `
                                    <button class="btn btn-primary btn-sm" onclick="viewProducts(${row.id})">View Products</button>

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
                if (confirm("Are you sure you want to delete this store?")) {
                    try {
                        // Send DELETE request to the API
                        await axios.delete(`/api/stores/${userId}`, {
                            headers: {
                                Authorization: `Bearer ${localStorage.getItem('token')}`, // Include token if required
                            },
                        });

                        // Show success message
                        alert("Store deleted successfully!");

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
            // Fetch locations data for the location select dropdown
            document.addEventListener("DOMContentLoaded", async function () {
                try {
                    const response = await axios.get('/api/locations', {
                        headers: {
                            Authorization: `Bearer ${localStorage.getItem('token')}`, // Include token if required
                        },
                    });

                    const locations = response.data;
                    const locationSelect = document.getElementById("edit_location_id");

                    // Populate the location select dropdown
                    locations.forEach(location => {
                        const option = document.createElement("option");
                        option.value = location.id;
                        option.textContent = `${location.barangay}, ${location.city}, ${location.province}`;
                        locationSelect.appendChild(option);
                    });
                } catch (error) {
                    console.error("Failed to fetch locations:", error);
                    alert("An error occurred while loading locations. Please try again.");
                }
            });

            // Function to populate the edit form with existing store data
            function editUser(button) {
                // Get the row data from the data-row attribute
                const rowData = JSON.parse(button.getAttribute('data-row'));

                // Populate the form fields
                document.getElementById("edit_store_name").value = rowData.store_name;
                document.getElementById("edit_street").value = rowData.street;
                document.getElementById("edit_contact_number").value = rowData.contact_number;
                document.getElementById("edit_location_id").value = rowData.location_id; // Preselect the location

                // Store the store id for later use when submitting the form
                document.getElementById("editForm").dataset.storeId = rowData.id;
            }

            // Handle form submission for editing a store
            document.getElementById("editForm").addEventListener("submit", async function (event) {
                event.preventDefault(); // Prevent default form submission behavior

                const storeId = document.getElementById("editForm").dataset.storeId;
                const user = JSON.parse(localStorage.getItem('user'));
                const store_name = document.getElementById("edit_store_name").value.trim();
                const street = document.getElementById("edit_street").value.trim();
                const location_id = document.getElementById("edit_location_id").value;
                const contact_number = document.getElementById("edit_contact_number").value.trim();
                const image = document.getElementById("edit_image").files[0]; // Get the selected image file (optional)

                // Basic validation
                if (!store_name || !street || !location_id || !contact_number) {
                    alert("Please fill in all required fields.");
                    return;
                }

                const formData = new FormData();
                formData.append("vendor_id", user.id);
                formData.append("store_name", store_name);
                formData.append("street", street);
                formData.append("location_id", location_id);
                formData.append("contact_number", contact_number);
                formData.append("id", storeId);
                if (image) {
                    formData.append("image", image); // Add the image if present
                }

                try {
                    // Send POST request to update the store (sending store ID in the body)
                    const response = await axios.post(`/api/stores`, formData, {
                        headers: {
                            Authorization: `Bearer ${localStorage.getItem('token')}`, // Include token if required
                            "Content-Type": "multipart/form-data" // Important for uploading files
                        },
                    });

                    // Show success message
                    alert("Store updated successfully!");

                    // Close modal
                    const modal = bootstrap.Modal.getInstance(document.getElementById("editModal"));
                    modal.hide();

                    // Reload the DataTable to reflect changes
                    $('#usersTable').DataTable().ajax.reload();
                } catch (error) {
                    // Handle error response
                    if (error.response) {
                        alert(error.response.data.message || "Failed to update store. Please try again.");
                    } else {
                        alert("An error occurred. Please check your connection and try again.");
                    }
                }
            });
        </script>


        <script>
            document.addEventListener("DOMContentLoaded", async function () {
                // Fetch locations data from the API
                try {
                    const response = await axios.get('/api/locations', {
                        headers: {
                            Authorization: `Bearer ${localStorage.getItem('token')}`, // Include token if required
                        },
                    });

                    const locations = response.data;

                    // Populate the location select dropdown
                    const locationSelect = document.getElementById("location_id");
                    locations.forEach(location => {
                        const option = document.createElement("option");
                        option.value = location.id;
                        option.textContent = `${location.barangay}, ${location.city}, ${location.province}`;
                        locationSelect.appendChild(option);
                    });

                } catch (error) {
                    console.error("Failed to fetch locations:", error);
                    alert("An error occurred while loading locations. Please try again.");
                }
            });

            // Handle form submission
            document.getElementById("createForm").addEventListener("submit", async function (event) {
                event.preventDefault(); // Prevent default form submission behavior

                // Get form input values
                const store_name = document.getElementById("store_name").value.trim();
                const street = document.getElementById("street").value.trim();
                const location_id = document.getElementById("location_id").value;
                const contact_number = document.getElementById("contact_number").value.trim();
                const image = document.getElementById("image").files[0]; // Get the selected image file
                const user = JSON.parse(localStorage.getItem('user'));

                // Basic validation
                if (!store_name || !street || !location_id || !contact_number) {
                    alert("Please fill in all required fields.");
                    return;
                }

                const formData = new FormData();
                formData.append("vendor_id", user.id);
                formData.append("store_name", store_name);
                formData.append("street", street);
                formData.append("location_id", location_id);
                formData.append("contact_number", contact_number);
                formData.append("image", image); // Add the image to the form data

                try {
                    // Send POST request to create store
                    const response = await axios.post('/api/stores/self-register', formData, {
                        headers: {
                            Authorization: `Bearer ${localStorage.getItem('token')}`, // Include token if required
                            "Content-Type": "multipart/form-data" // Important for uploading files
                        },
                    });

                    // Show success message
                    alert("Store created successfully!");

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
                        alert(error.response.data.message || "Failed to create store. Please try again.");
                    } else {
                        alert("An error occurred. Please check your connection and try again.");
                    }
                }
            });
        </script>

    
    <script>
        // Submit handler for updating the user
        document.getElementById('editUserForm').addEventListener('submit', async function (event) {
            event.preventDefault(); // Prevent default form submission behavior

            const userId = document.getElementById('editID').value.trim();

            // Get updated data from form inputs
            const name = document.getElementById('editName').value.trim();
            const email = document.getElementById('editEmail').value.trim();
            const contact_number = document.getElementById('editContact').value.trim();
            const plate_number = document.getElementById('editPlateNumber').value.trim();
            const license_number = document.getElementById('editLicenseNumber').value;

            // Basic validation
            if (!name || !email || !contact_number || !plate_number || !license_number) {
                alert("Please fill in all required fields.");
                return;
            }

            try {
                // Send PUT request to update the user
                const response = await axios.put(`/api/riders/${userId}`, {
                    name: name,
                    email: email,
                    contact_number: contact_number,
                    plate_number: plate_number || null, // Optional field
                    license_number: license_number || null
                }, {
                    headers: {
                        Authorization: `Bearer ${localStorage.getItem('token')}`, // Include token if required
                    },
                });

                // Show success message
                alert("Rider updated successfully!");

                // Close modal
                const modal = bootstrap.Modal.getInstance(document.getElementById('editUserModal'));
                modal.hide();

                // Reload the DataTable
                $('#usersTable').DataTable().ajax.reload();
            } catch (error) {
                // Handle error response
                if (error.response) {
                    alert(error.response.data.message || "Failed to update user. Please try again.");
                } else {
                    alert("An error occurred. Please check your connection and try again.");
                }
            }
        });
    </script>
    
</body>
</html>
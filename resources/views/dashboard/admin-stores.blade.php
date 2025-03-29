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
                            <button class="btn btn-primary shadow" data-bs-toggle="modal" data-bs-target="#createUserModal">Create New Store</button>
                        </div>
                    </div>
                    <!-- Illustration dashboard card example-->
                    <div class="card mb-4 mt-5">
                        <div class="card-body p-5">
                            <div class="table-responsive">
                                <table id="usersTable" class="table">
                                    <thead>
                                        <tr>
                                            <th>Store Name</th>
                                            <th>Contact Number</th>
                                            <th>Vendor</th>
                                            <th>Address</th>
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

    <!-- Create User Modal -->
    <div class="modal fade" id="createUserModal" tabindex="-1" aria-labelledby="createUserModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createUserModalLabel">Create New Store</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <!-- Form for creating a new user -->
                    <div class="modal-body">
                        <form id="createDataForm" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label class="form-label">Store Image</label>
                                <input type="file" class="form-control" id="image" accept="image/*" />
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Store Name<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="store_name" placeholder="Enter store name" required />
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Contact Number<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="contact_number" placeholder="Enter contact number" required />
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Vendor<span class="text-danger">*</span></label>
                                <select id="vendorDropdown" class="form-select" required>
                                    <option value="">Select Vendor</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Street<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="street" placeholder="Enter street" required />
                            </div>
                            <div class="mb-3">
                                <label for="locationDropdown" class="form-label">Choose a Location<span class="text-danger">*</span></label>
                                <select id="locationDropdown" class="form-select" required>
                                    <option value="">Select Location</option>
                                </select>
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

    <!-- Edit User Modal -->
    <div class="modal fade" id="editDataModal" tabindex="-1" aria-labelledby="editDataModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editDataModalLabel">Edit Store</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editDataForm" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label class="form-label">Store Image</label>
                            <input type="file" class="form-control" id="update_image" accept="image/*" />
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Store Name<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="update_store_name" placeholder="Enter store name" required />
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Contact Number<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="update_contact_number" placeholder="Enter contact number" required />
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Vendor<span class="text-danger">*</span></label>
                            <select id="vendorDropdown2" class="form-select" required>
                                <option value="">Select Vendor</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Street<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="update_street" placeholder="Enter street" required />
                        </div>
                        <div class="mb-3">
                            <label for="locationDropdown" class="form-label">Choose a Location<span class="text-danger">*</span></label>
                            <select id="locationDropdown2" class="form-select" required>
                                <option value="">Select Location</option>
                            </select>
                        </div>
                    
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Update</button>
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
                        url: '/api/admin/stores', // Your API endpoint for fetching users
                        type: 'GET',
                        headers: {
                                Authorization: `Bearer ${localStorage.getItem('token')}`, // Include token if required
                            },
                        dataSrc: 'stores' // Adjust based on the response structure ('' if data is a direct array)
                    },
                    columns: [
                        { 
                            data: null,
                            render: function (data, type, row) {
                                let imageUrl = row.image ? `/storage/${row.image}` : '/no-image.jpg'; // Default image if none
                                return `
                                    <div class="d-flex align-items-center">
                                        <img src="${imageUrl}" alt="Store Image" class="" style="width: 40px; height: 40px; object-fit: cover; margin-right: 10px;">
                                        <span>${row.store_name}</span>
                                    </div>
                                `;
                            }
                        },
                        { data: 'contact_number' },
                        { 
                            data: null, 
                            render: function (data, type, row) {
                                return `${row.vendor.first_name} ${row.vendor.middle_name ? row.vendor.middle_name + ' ' : ''}${row.vendor.last_name}`;
                            } 
                        },
                        { 
                            data: null, 
                            render: function (data, type, row) {
                                return `${row.street} ${row.location.barangay} ${row.location.city} ${row.location.province}`;
                            } 
                        },
                        {
                            data: 'is_verified',
                            render: function (data, type, row) {
                                return data ? 'Approved' : 'Pending';
                            }
                        },
                        {
                            data: null,
                            render: function (data, type, row) {
                                let buttons = '';
                                
                                // Display "Approve" button only if is_verified is null
                                if (!row.is_verified) {
                                    buttons += `<button class="btn btn-secondary btn-sm m-1" onclick="approveData(${row.id})">Approve</button> `;
                                }

                                // Always display "Edit" and "Delete" buttons
                                buttons += `
                                    <button class="btn btn-primary btn-sm m-1" onclick="viewProducts(${row.id})">View Products</button>
                                    <button class="btn btn-primary btn-sm m-1" onclick="viewOrders(${row.id})">View Orders</button>
                                    <button class="btn btn-warning btn-sm m-1" data-bs-toggle="modal" data-bs-target="#editDataModal" onclick="editData(${row.id})">Edit</button>
                                    <button class="btn btn-danger btn-sm m-1" onclick="deleteData(${row.id})">Delete</button>
                                `;

                                return buttons;
                            },
                        }
                    ],
                    dom: 'lBfrtip', // Enable buttons for export functionality
                    buttons: [
                        'copy', 'csv', 'excel', 'pdf', 'print'
                    ],
                    order: [[0, 'desc']]
                });
            });

            function viewProducts(id){
                localStorage.setItem('store_id', id);

                window.location.href = '/admin/products';
            }

            function viewOrders(id){
                localStorage.setItem('store_id', id);

                window.location.href = '/admin/orders';
            }

            // Function to populate the edit form with existing user data
            async function editData(userId) {
                try {
                    // Fetch store details using the store ID
                    const response = await axios.get(`/api/admin/stores/${userId}`, {
                        headers: {
                            Authorization: `Bearer ${localStorage.getItem('token')}`, // Include token if required
                        },
                    });

                    const store = response.data.stores;

                    // Populate form fields with store data
                    document.getElementById('update_store_name').value = store.store_name || '';
                    document.getElementById('update_contact_number').value = store.contact_number || '';
                    document.getElementById('update_street').value = store.street || '';
                    document.getElementById('vendorDropdown2').value = store.vendor_id || '';
                    document.getElementById('locationDropdown2').value = store.location_id || '';

                    document.getElementById('editDataForm').dataset.userId = userId;
                } catch (error) {
                    console.error('Error fetching store details:', error); // Debugging output
                    alert('Failed to fetch details. Please try again.');
                }
            }


            // Function to delete a user
            async function deleteData(userId) {
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

            async function approveData(userId) {
                if (confirm("Are you sure you want to approve this store?")) {
                    $.ajax({
                        url: `/api/stores/${userId}/verify`,
                        type: 'PUT',
                        headers: {
                            Authorization: `Bearer ${localStorage.getItem('token')}`,
                        },
                        success: function(response) {
                            alert('The store has been approved successfully.');
                            $('#usersTable').DataTable().ajax.reload();
                        },
                        error: function() {
                            alert('Failed to process approval.');
                        }
                    });
                }
            }
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", async function () {
            const locationDropdown = $('#locationDropdown');
            const locationDropdown2 = $('#locationDropdown2');

            $.ajax({
                    url: '/api/locations',
                    method: 'GET',
                    dataType: 'json',
                    success: function (locations) {
                        // Populate the dropdown with locations
                        locations.forEach(location => {
                            const option = `<option value="${location.id}">${location.barangay}, ${location.city}, ${location.province}</option>`;
                            locationDropdown.append(option);
                            locationDropdown2.append(option);
                        });
                    },
                    error: function (error) {
                        console.error('Error fetching locations:', error);
                    }
                });
            });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", async function () {
            const vendorDropdown = $('#vendorDropdown');
            const vendorDropdown2 = $('#vendorDropdown2');

            $.ajax({
                    url: '/api/users',
                    method: 'GET',
                    dataType: 'json',
                    success: function (vendors) {
                        // Populate the dropdown with vendors
                        vendors.forEach(ven => {
                            const option = `<option value="${ven.id}">${ven.first_name} ${ven.middle_name} ${ven.last_name}</option>`;
                            vendorDropdown.append(option);
                            vendorDropdown2.append(option);
                        });
                    },
                    error: function (error) {
                        console.error('Error fetching:', error);
                    }
                });
            });
    </script>

    <script>
        document.getElementById("createDataForm").addEventListener("submit", async function (event) {
            event.preventDefault(); // Prevent default form submission

            // Create FormData object to handle file uploads
            let formData = new FormData();

            const imageInput = document.getElementById("image");

            if (imageInput.files.length > 0) {
                formData.append("image", imageInput.files[0]); // Append only if a file is selected
            }
            
            // Append form data
            formData.append("store_name", document.getElementById("store_name").value.trim());
            formData.append("contact_number", document.getElementById("contact_number").value.trim());
            formData.append("vendor_id", document.getElementById("vendorDropdown").value.trim());
            formData.append("street", document.getElementById("street").value.trim());
            formData.append("location_id", document.getElementById("locationDropdown").value.trim());

            try {
                // Send POST request using axios
                const response = await axios.post('/api/stores', formData, {
                    headers: {
                        "Content-Type": "multipart/form-data",
                        Authorization: `Bearer ${localStorage.getItem('token')}`
                    }
                });

                // Show success message
                alert("Store created successfully!");

                // Close modal
                const modal = bootstrap.Modal.getInstance(document.getElementById("createUserModal"));
                modal.hide();

                // Reset form
                event.target.reset();

                // Reload the DataTable
                $('#usersTable').DataTable().ajax.reload();
            } catch (error) {
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
        document.getElementById('editDataForm').addEventListener('submit', async function (event) {
            event.preventDefault(); // Prevent default form submission

            // Create FormData object to handle file uploads
            let formData = new FormData();

            const imageInput = document.getElementById("update_image");

            if (imageInput.files.length > 0) {
                formData.append("image", imageInput.files[0]); // Append only if a file is selected
            }
            
            const userId = this.dataset.userId;
            // Append form data
            formData.append("store_name", document.getElementById("update_store_name").value.trim());
            formData.append("contact_number", document.getElementById("update_contact_number").value.trim());
            formData.append("vendor_id", document.getElementById("vendorDropdown2").value.trim());
            formData.append("street", document.getElementById("update_street").value.trim());
            formData.append("location_id", document.getElementById("locationDropdown2").value.trim());
            formData.append("id", userId);

            try {
                // Send POST request using axios
                const response = await axios.post('/api/stores', formData, {
                    headers: {
                        "Content-Type": "multipart/form-data",
                        Authorization: `Bearer ${localStorage.getItem('token')}`
                    }
                });

                // Show success message
                alert("Store updated successfully!");

                // Close modal
                const modal = bootstrap.Modal.getInstance(document.getElementById("editDataModal"));
                modal.hide();

                // Reset form
                event.target.reset();

                // Reload the DataTable
                $('#usersTable').DataTable().ajax.reload();
            } catch (error) {
                if (error.response) {
                    alert(error.response.data.message || "Failed to create store. Please try again.");
                } else {
                    alert("An error occurred. Please check your connection and try again.");
                }
            }
        });
    </script>
    
</body>
</html>
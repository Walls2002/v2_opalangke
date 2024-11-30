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
                            <h1 class="mb-0">Riders</h1>
                        </div>
                        <!-- Date range picker example-->
                        <div class="">
                            <button class="btn btn-primary shadow" data-bs-toggle="modal" data-bs-target="#createModal">Create New Rider</button>
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
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Contact</th>
                                            <th>Plate Number</th>
                                            <th>License</th>
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
    <div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createModalLabel">Create New Rider</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <!-- Form for creating a new user -->
                    <div class="modal-body">
                        <form id="createUserForm">
                        <div class="mb-3">
                            <label for="createUserName" class="form-label">Name<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="createUserName" placeholder="Enter full name" required />
                        </div>
                        <div class="mb-3">
                            <label for="createUserEmail" class="form-label">Email<span class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="createUserEmail" placeholder="Enter email address" required />
                        </div>
                        <div class="mb-3">
                            <label for="createUserContact" class="form-label">Contact Number<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="createUserContact" placeholder="Enter contact number" required />
                        </div>
                        <div class="mb-3">
                            <label for="createUserPlateNumber" class="form-label">Plate Number<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="createUserPlateNumber" placeholder="Enter plate number" required />
                        </div>
                        <div class="mb-3">
                            <label for="createUserLicenseNumber" class="form-label">License Number<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="createUserLicenseNumber" placeholder="Enter license number" required />
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
    <div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editUserModalLabel">Edit Rider</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editUserForm">
                        <input type="text" class="d-none" id="editID">
                        <div class="mb-3">
                            <label for="editName" class="form-label">Name<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="editName" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="editEmail" class="form-label">Email<span class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="editEmail" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="editContact" class="form-label">Contact<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="editContact" name="contact" required>
                        </div>
                        <div class="mb-3">
                            <label for="editPlateNumber" class="form-label">Plate Number<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="editPlateNumber" name="plate_number" placeholder="Enter plate number" required>
                        </div>

                        <div class="mb-3">
                            <label for="editPlateNumber" class="form-label">License Number<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="editLicenseNumber" name="plate_number" placeholder="Enter license number" required>
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
                        url: '/api/riders', // Your API endpoint for fetching users
                        type: 'GET',
                        headers: {
                                Authorization: `Bearer ${localStorage.getItem('token')}`, // Include token if required
                            },
                        dataSrc: 'riders' // Adjust based on the response structure ('' if data is a direct array)
                    },
                    columns: [
                        { data: 'id' },
                        { data: 'name' },
                        { data: 'email' },
                        { data: 'contact_number' },
                        { data: 'plate_number' },
                        { data: 'license_number' },
                        {
                            data: null,
                            render: function (data, type, row) {
                                return `
                                    <button class="btn btn-warning btn-sm" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#editUserModal" 
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

            // Function to populate the edit form with existing user data
            function editUser(button) {
                // Get the row data from the data-row attribute
                const rowData = JSON.parse(button.getAttribute('data-row'));

                // Access the data and populate the form fields
                console.log(rowData); // Log the row data to verify it's passed correctly

                document.getElementById("editID").value = rowData.id;
                document.getElementById("editName").value = rowData.name;
                document.getElementById("editEmail").value = rowData.email;
                document.getElementById("editContact").value = rowData.contact_number;
                document.getElementById("editPlateNumber").value = rowData.plate_number;
                document.getElementById("editLicenseNumber").value = rowData.license_number;
            }


            // Function to delete a user
            async function deleteUser(userId) {
                if (confirm("Are you sure you want to delete this rider?")) {
                    try {
                        // Send DELETE request to the API
                        await axios.delete(`/api/riders/${userId}`, {
                            headers: {
                                Authorization: `Bearer ${localStorage.getItem('token')}`, // Include token if required
                            },
                        });

                        // Show success message
                        alert("Rider deleted successfully!");

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
        //CREATE USER
        document.getElementById("createUserForm").addEventListener("submit", async function (event) {
                event.preventDefault(); // Prevent default form submission behavior

                // Get form input values
                const user = JSON.parse(localStorage.getItem('user'));
                
                const name = document.getElementById("createName").value.trim();
                const email = document.getElementById("createEmail").value.trim();
                const contact_number = document.getElementById("createContact").value.trim();
                const plate_number = document.getElementById("createPlateNumber").value.trim();
                const license_number = document.getElementById("createLicenseNumber").value;

                // Basic validation
                if (!name || !email || !contact_number || !plate_number || !license_number) {
                    alert("Please fill in all required fields.");
                    return;
                }

                try {
                    const token = localStorage.getItem('token');
                    // Send POST request to create user
                    const response = await axios.post('/api/riders', {
                        vendor_id: user.id,
                        name: name,
                        email: email,
                        contact_number: contact_number,
                        plate_number: plate_number || null, // Optional field
                        license_number: license_number || null,
                        password: 'password'
                    }, {
                        headers: {
                            Authorization: `Bearer ${token}` // Add Bearer token in the header
                        }
                    });

                    // Show success message
                    alert("Rider created successfully!");

                    // Close modal
                    const modal = bootstrap.Modal.getInstance(document.getElementById("createModal"));
                    modal.hide();

                    // Reset form
                    event.target.reset();

                    // Reload the DataTable
                    $('#usersTable').DataTable().ajax.reload();
                } catch (error) {
                    // Handle error response
                    if (error.response) {
                        alert(error.response.data.message || "Failed to create user. Please try again.");
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
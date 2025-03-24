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
                            <button class="btn btn-primary shadow" data-bs-toggle="modal" data-bs-target="#addModal">Add Existing Rider</button>
                            <button class="btn btn-primary shadow" data-bs-toggle="modal" data-bs-target="#createUserModal">Create New Rider</button>
                        </div>
                    </div>
                    <!-- Illustration dashboard card example-->
                    <div class="card mb-4 mt-5">
                        <div class="card-body p-5">
                            <div class="table-responsive">
                                <table id="usersTable" class="table">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Contact</th>
                                            <th>License Number</th>
                                            <th>Plate Number</th>
                                            <th>Rating</th>
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
                    <h5 class="modal-title" id="createUserModalLabel">Create New Rider</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <!-- Form for creating a new user -->
                    <div class="modal-body">
                        <form id="createUserForm">
                            <div class="mb-3">
                                <label for="createUserName" class="form-label">First Name<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="createUserFirstName" placeholder="Enter first name" required />
                            </div>
                            <div class="mb-3">
                                <label for="createUserName" class="form-label">Middle Name</label>
                                <input type="text" class="form-control" id="createUserMiddleName" placeholder="Enter middle name" />
                            </div>
                            <div class="mb-3">
                                <label for="createUserName" class="form-label">Last Name<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="createUserLastName" placeholder="Enter last name" required />
                            </div>
                            <div class="mb-3">
                                <label for="createUserEmail" class="form-label">Email<span class="text-danger">*</span></label>
                                <input type="email" class="form-control" id="createUserEmail" placeholder="Enter email address" required />
                            </div>
                            <div class="mb-3">
                                <label for="createUserContact" class="form-label">Contact<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="createUserContact" placeholder="Enter contact number" required />
                            </div>
                            <div class="mb-3">
                                <label class="form-label">License Number<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="license_number" placeholder="Enter license number" required />
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Plate Number<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="plate_number" placeholder="Enter plate number" required />
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

    <!-- Create User Modal -->
    <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addModalLabel">Add Existing Rider</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <!-- Form for creating a new user -->
                    <div class="modal-body">
                        <form id="addForm">
                            <div class="mb-3">
                                <label  class="form-label">Rider Email<span class="text-danger">*</span></label>
                                <input type="email" class="form-control" id="user_email" placeholder="Enter rider email" required />
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

            const store_id = JSON.parse(localStorage.getItem('store_id'));
                // Initialize DataTable
                $('#usersTable').DataTable({
                    ajax: {
                        url: `/api/store-riders/${store_id}/team`, // Your API endpoint for fetching users
                        type: 'GET',
                        headers: {
                                Authorization: `Bearer ${localStorage.getItem('token')}`, // Include token if required
                            },
                        dataSrc: 'store_riders' // Adjust based on the response structure ('' if data is a direct array)
                    },
                    columns: [
                        { 
                            data: null, 
                            render: function (data, type, row) {
                                return `${row.user.first_name} ${row.user.middle_name ? row.user.middle_name + ' ' : ''}${row.user.last_name}`;
                            } 
                        },
                        { data: 'user.email' },
                        { data: 'user.contact' },
                        { data: 'license_number' },
                        { data: 'plate_number' },
                        { data: 'rating' },
                        {
                            data: 'user.email_verified_at',
                            render: function (data, type, row) {
                                return data ? 'Approved' : 'Pending';
                            }
                        },
                        {
                            data: null,
                            render: function (data, type, row) {
                                let buttons = '';

                                // Always display "Edit" and "Delete" buttons
                                buttons += `
                                    <button class="btn btn-danger btn-sm" onclick="deleteUser(${row.id})">Delete</button>
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
        document.getElementById("createUserForm").addEventListener("submit", async function (event) {
            event.preventDefault(); // Prevent default form submission behavior

            // Get form input values
            const first_name = document.getElementById("createUserFirstName").value.trim();
            const middle_name = document.getElementById("createUserMiddleName").value.trim();
            const last_name = document.getElementById("createUserLastName").value.trim();
            const email = document.getElementById("createUserEmail").value.trim();
            const contact_number = document.getElementById("createUserContact").value.trim();
            const license_number = document.getElementById("license_number").value.trim();
            const plate_number = document.getElementById("plate_number").value.trim();
            const store_id = JSON.parse(localStorage.getItem('store_id'));

            try {
                // Send POST request to create user
                const response = await axios.post(
                    `/api/store-riders/${store_id}/register`, 
                    {
                        first_name: first_name,
                        middle_name: middle_name,
                        last_name: last_name,
                        email: email,
                        contact: contact_number,
                        password: "password",
                        license_number: license_number,
                        plate_number: plate_number
                    }, 
                    {
                        headers: {
                            "Content-Type": "application/json",
                            Authorization: `Bearer ${localStorage.getItem("token")}`
                        }
                    }
                );

                // Show success message
                alert("Rider created successfully!");

                // Close modal
                const modal = bootstrap.Modal.getInstance(document.getElementById("createUserModal"));
                modal.hide();

                // Reset form
                event.target.reset();

                // Reload the DataTable
                $("#usersTable").DataTable().ajax.reload();
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
    document.getElementById("addForm").addEventListener("submit", async function (event) {
        event.preventDefault(); // Prevent default form submission behavior

        // Get form input values
        const store_id = JSON.parse(localStorage.getItem('store_id'));
        const user_email = document.getElementById("user_email").value.trim();

        try {
            // Send POST request to create user
            const response = await axios.post(
                `/api/store-riders/${store_id}`, 
                {
                    user_email: user_email,
                }, 
                {
                    headers: {
                        "Content-Type": "application/json",
                        Authorization: `Bearer ${localStorage.getItem("token")}`
                    }
                }
            );

            // Show success message
            alert("Rider added successfully!");

            // Close modal
            const modal = bootstrap.Modal.getInstance(document.getElementById("addModal"));
            modal.hide();

            // Reset form
            event.target.reset();

            // Reload the DataTable
            $("#usersTable").DataTable().ajax.reload();
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
    
</body>
</html>
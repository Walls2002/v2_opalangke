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
                            <h1 class="mb-0">Update Profile</h1>
                        </div>
                        <!-- Date range picker example-->
                        <div class="">
                        </div>
                    </div>
                    <!-- Illustration dashboard card example-->
                    <div class="row">
                        <div class="col-12 col-lg-6 mb-3">
                            <div class="card">
                                <div class="card-body p-5">
                                    <form id="updateProfile">
                                        <div class="mb-3">
                                            <label for="createUserName" class="form-label">Name<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="name" placeholder="Enter full name" required />
                                        </div>
                                        <div class="mb-3">
                                            <label for="createUserEmail" class="form-label">Email<span class="text-danger">*</span></label>
                                            <input type="email" class="form-control" id="email" placeholder="Enter email address" required />
                                        </div>
                                        <div class="mb-3">
                                            <label for="createUserContact" class="form-label">Contact<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="contact" placeholder="Enter contact number" required />
                                        </div>
                                    
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-primary">Update Details</button>
                                        </div>
                                    </form>
                                </div>
                            </div>  
                        </div>

                        <div class="col-12 col-lg-6 mb-3">
                            <div class="card">
                                <div class="card-body p-5">
                                    <form id="updatePassword">
                                        <div class="mb-3">
                                            <label class="form-label">Old Password</label>
                                            <div class="input-group">
                                                <input class="form-control" id="old_password" type="password" placeholder="Enter old password" required />
                                                <button type="button" class="btn btn-outline-primary toggle-password">Show</button>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Password</label>
                                            <div class="input-group">
                                                <input class="form-control" id="new_password" type="password" placeholder="Enter password" required />
                                                <button type="button" class="btn btn-outline-primary toggle-password">Show</button>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Confirm New Password</label>
                                            <div class="input-group">
                                                <input class="form-control" id="new_password_confirmation" type="password" placeholder="Confirm password" required />
                                                <button type="button" class="btn btn-outline-primary toggle-password">Show</button>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-primary">Update Password</button>
                                        </div>
                                    </form>                                    
                                </div>
                            </div>  
                        </div>
                    </div>
                </div>
            </main>
            @include('layout.footer')
        </div>
    </div>

    @include('layout.scripts')

    <script>
        $(document).ready(function () {
            // Get user details from localStorage
            const user = JSON.parse(localStorage.getItem('user'));
            const userId = user.id;

            // Fetch user details using AJAX
            $.ajax({
                url: `/api/users/${userId}`, // API endpoint
                type: 'GET',
                headers: {
                    Authorization: `Bearer ${localStorage.getItem('token')}` // Include token if required
                },
                success: function (response) {
                    // Populate the form fields with the user details
                    $('#name').val(response.name);
                    $('#email').val(response.email);
                    $('#contact').val(response.contact);
                },
                error: function (xhr, status, error) {
                    console.error('Error fetching user details:', error);
                    alert('Failed to load user details. Please try again.');
                }
            });
        });
    </script>

    <script>
        // Submit handler for updating the user
        document.getElementById('updateProfile').addEventListener('submit', async function (event) {
            event.preventDefault(); // Prevent default form submission behavior

            // Get updated data from form inputs
            const name = document.getElementById('name').value.trim();
            const email = document.getElementById('email').value.trim();
            const contact = document.getElementById('contact').value.trim();

            // Basic validation
            if (!name || !email || !contact) {
                alert("Please fill in all required fields.");
                return;
            }

            try {
                // Send PUT request to update the user
                const response = await axios.put(`/api/profile/update`, {
                    name: name,
                    email: email,
                    contact: contact
                }, {
                    headers: {
                        Authorization: `Bearer ${localStorage.getItem('token')}`, // Include token if required
                    },
                });

                // Update the user data in localStorage
                const updatedUser = {
                    ...JSON.parse(localStorage.getItem('user')), // Get the existing user object
                    name: response.data.user.name,                  // Update the name
                    email: response.data.user.email,                // Update the email
                    contact: response.data.user.contact             // Update the contact
                };
                localStorage.setItem('user', JSON.stringify(updatedUser));

                // Show success message
                alert("Profile has been updated successfully!");
                location.reload()

            } catch (error) {
                // Handle error response
                if (error.response) {
                    alert(error.response.data.message || "Failed to update profile. Please try again.");
                } else {
                    alert("An error occurred. Please check your connection and try again.");
                }
            }
        });
    </script>

    <script>
        $(document).ready(function () {
            // Add toggle for showing/hiding passwords inside input groups
            $('.input-group').each(function () {
                const input = $(this).find('input');
                const toggleBtn = $(this).find('.toggle-password');

                toggleBtn.on('click', function () {
                    if (input.attr('type') === 'password') {
                        input.attr('type', 'text');
                        $(this).text('Hide');
                    } else {
                        input.attr('type', 'password');
                        $(this).text('Show');
                    }
                });
            });

            // Handle form submission
            $('#updatePassword').on('submit', async function (e) {
                e.preventDefault(); // Prevent default form submission

                const oldPassword = $('#old_password').val();
                const newPassword = $('#new_password').val();
                const confirmPassword = $('#new_password_confirmation').val();

                if (newPassword !== confirmPassword) {
                    alert('Passwords do not match!');
                    return;
                }

                try {
                    // Submit the form using Axios
                    const response = await axios.put('/api/profile/change-password', {
                        old_password: oldPassword,
                        new_password: newPassword,
                        new_password_confirmation: confirmPassword
                    }, {
                        headers: {
                            Authorization: `Bearer ${localStorage.getItem('token')}` // Include token if required
                        }
                    });

                    // Show success message and clear form
                    alert('Password updated successfully!');
                    $('#updatePassword')[0].reset();
                } catch (error) {
                    console.error('Error updating password:', error);
                    alert('Failed to update password. Please try again.');
                }
            });
        });
    </script>
    
</body>
</html>
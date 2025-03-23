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
                        <div class="col-12 col-lg-12 mb-3">
                            <div class="card">
                                <div class="card-body p-5 text-center">
                                    <!-- Profile Picture Display -->
                                    <img id="profilePicture" src="" alt="Profile Picture" class="img-fluid rounded-circle" width="150" height="150">
                        
                                    <!-- File Upload Input -->
                                    <input type="file" id="profilePicInput" class="form-control mt-3" accept="image/*">
                                    
                                    <!-- Update Button -->
                                    <button id="updateProfilePicBtn" class="btn btn-primary mt-3">Change Profile Picture</button>
                                </div>
                            </div>  
                        </div>

                        <div class="col-12 col-lg-6 mb-3">
                            <div class="card">
                                <div class="card-body p-5">
                                    <form id="updateProfile">
                                        <div class="mb-3">
                                            <label for="createUserName" class="form-label">First Name<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="first_name" placeholder="Enter first name" required />
                                        </div>
                                        <div class="mb-3">
                                            <label for="createUserName" class="form-label">Middle Name<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="middle_name" placeholder="Enter middle name" required />
                                        </div>
                                        <div class="mb-3">
                                            <label for="createUserName" class="form-label">Last Name<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="last_name" placeholder="Enter last name" required />
                                        </div>
                                        <div class="mb-3">
                                            <label for="createUserEmail" class="form-label">Email<span class="text-danger">*</span></label>
                                            <input type="email" class="form-control" id="email" placeholder="Enter email address" required />
                                        </div>
                                        <div class="mb-3">
                                            <label for="createUserContact" class="form-label">Contact<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="contact" placeholder="Enter contact number" required />
                                        </div>
                                        <div class="mb-3">
                                            <label for="locationDropdown" class="form-label">Choose a Location<span class="text-danger">*</span></label>
                                            <select id="locationDropdown" class="form-select" required>
                                                <option value="">Select Location</option>
                                            </select>
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
            const token = localStorage.getItem('token');
    
            // Fetch user details using AJAX
            $.ajax({
                url: `/api/users/${userId}`, // API endpoint
                type: 'GET',
                headers: {
                    Authorization: `Bearer ${token}` // Include token
                },
                success: function (response) {
                    // Populate form fields
                    $('#first_name').val(response.first_name);
                    $('#middle_name').val(response.middle_name);
                    $('#last_name').val(response.last_name);
                    $('#email').val(response.email);
                    $('#contact').val(response.contact);
                    $('#locationDropdown').val(response.location_id);
    
                    // Set profile picture
                    const profilePicUrl = response.profile_picture 
                        ? `/storage/${response.profile_picture}` 
                        : '/default-avatar.png'; // Fallback image
                    $('#profilePicture').attr('src', profilePicUrl);

                    localStorage.setItem("user", JSON.stringify(response));
                },
                error: function (xhr, status, error) {
                    console.error('Error fetching user details:', error);
                    alert('Failed to load user details. Please try again.');
                }
            });
    
            // Handle Profile Picture Upload
            $('#updateProfilePicBtn').on('click', async function () {
                const fileInput = $('#profilePicInput')[0].files[0];
    
                if (!fileInput) {
                    alert("Please select an image to upload.");
                    return;
                }
    
                let formData = new FormData();
                formData.append("image", fileInput);
    
                try {
                    const response = await axios.post(`/api/profile/change-profile-picture`, formData, {
                        headers: {
                            Authorization: `Bearer ${token}`,
                            "Content-Type": "multipart/form-data"
                        },
                    });
    
                    alert("Profile picture updated successfully!");
                    location.reload()
    
                } catch (error) {
                    console.error("Error uploading profile picture:", error);
                    alert("Failed to update profile picture. Please try again.");
                }
            });
        });
    </script>

    <script>
        // Submit handler for updating the user
        document.getElementById('updateProfile').addEventListener('submit', async function (event) {
            event.preventDefault(); // Prevent default form submission behavior

            // Get updated data from form inputs
            const first_name = document.getElementById("first_name").value.trim();
            const middle_name = document.getElementById("middle_name").value.trim();
            const last_name = document.getElementById("last_name").value.trim();
            const email = document.getElementById('email').value.trim();
            const contact = document.getElementById('contact').value.trim();
            const location_id = document.getElementById("locationDropdown").value.trim();
            const token = localStorage.getItem('token'); // Retrieve the token

            try {
                // Send PUT request to update the user profile
                const response = await axios.put(`/api/profile/update`, {
                    first_name,
                    middle_name,
                    last_name,
                    email,
                    contact,
                }, {
                    headers: {
                        Authorization: `Bearer ${token}`, // Include token if required
                    },
                });

                // Update the user data in localStorage
                const updatedUser = {
                    ...JSON.parse(localStorage.getItem('user')), // Get the existing user object
                    first_name: response.data.user.first_name,
                    email: response.data.user.email,
                    contact: response.data.user.contact
                };
                localStorage.setItem('user', JSON.stringify(updatedUser));

                // After success, update the location_id
                await axios.put(`/api/profile/change-location`, {
                    location_id: location_id
                }, {
                    headers: {
                        Authorization: `Bearer ${token}`, // Include token
                    },
                });

                // Show success message and reload the page
                alert("Profile and location have been updated successfully!");
                location.reload();

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


    //password
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

    <script>
        document.addEventListener("DOMContentLoaded", async function () {
            const locationDropdown = $('#locationDropdown');
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
            });
    </script>
    
</body>
</html>
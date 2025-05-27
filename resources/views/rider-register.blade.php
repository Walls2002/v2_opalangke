<!DOCTYPE html>
<html lang="en">
    @include('layout.head')
    <body class="bg-primary">
        <div id="layoutAuthentication">
            <div id="layoutAuthentication_content">
                <main>
                    <div class="container-xl px-4">
                        <div class="row justify-content-center">
                            <div class="col-lg-7">
                                <!-- Basic registration form-->
                                <div class="card shadow-lg border-0 rounded-lg mt-5">
                                    <div class="card-header justify-content-center">
                                        <h3 class="fw-light my-4">Create Rider Account</h3>
                                    </div>
                                    <div class="card-body">
                                        <!-- Registration form-->
                                        <form id="registrationForm">
                                            <!-- Form Row-->
                                            <div class="row gx-3">
                                                <div class="col-4">
                                                    <!-- Form Group (name)-->
                                                    <div class="mb-3">
                                                        <label class="small mb-1" for="inputName">First Name</label>
                                                        <input class="form-control" id="inputFirstName" type="text" placeholder="Enter first name" required />
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <!-- Form Group (name)-->
                                                    <div class="mb-3">
                                                        <label class="small mb-1" for="inputName">Middle Name</label>
                                                        <input class="form-control" id="inputMiddleName" type="text" placeholder="Enter middle name" required />
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <!-- Form Group (name)-->
                                                    <div class="mb-3">
                                                        <label class="small mb-1" for="inputName">Last Name</label>
                                                        <input class="form-control" id="inputLastName" type="text" placeholder="Enter last name" required />
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Form Group (email address) -->
                                            <div class="row gx-3">
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="small mb-1" for="inputEmail">Email</label>
                                                        <input class="form-control" id="inputEmail" type="email" placeholder="Enter email address" required />
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="small mb-1" for="inputContact">Contact Number</label>
                                                        <input class="form-control" id="inputContact" type="text" placeholder="Enter contact number" required />
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="mb-3">
                                                <label for="locationDropdown" class="form-label">Choose a Location<span class="text-danger">*</span></label>
                                                <select id="locationDropdown" class="form-select" required>
                                                    <option value="">Select Location</option>
                                                </select>
                                            </div>

                                            <div class="row gx-3">
                                                <div class="col-md-6">
                                                    <!-- Form Group (plate number)-->
                                                    <div class="mb-3">
                                                        <label class="small mb-1" for="inputPlateNumber">Plate Number</label>
                                                        <input class="form-control" id="inputPlateNumber" type="text" placeholder="Enter plate number" required />
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <!-- Form Group (license number)-->
                                                    <div class="mb-3">
                                                        <label class="small mb-1" for="inputLicenseNumber">License Number</label>
                                                        <input class="form-control" id="inputLicenseNumber" type="text" placeholder="Enter license number" required />
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Form Row -->
                                            <div class="row gx-3">
                                                <div class="col-md-6">
                                                    <!-- Form Group (password)-->
                                                    <div class="mb-3">
                                                        <label class="small mb-1" for="inputPassword">Password</label>
                                                        <input class="form-control" id="inputPassword" type="password" placeholder="Enter password" required />
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <!-- Form Group (confirm password)-->
                                                    <div class="mb-3">
                                                        <label class="small mb-1" for="inputConfirmPassword">Confirm Password</label>
                                                        <input class="form-control" id="inputConfirmPassword" type="password" placeholder="Confirm password" required />
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Form Group (create account submit) -->
                                            <button type="submit" class="btn btn-primary btn-block">Create Account</button>
                                        </form>
                                    </div>
                                    <div class="card-footer text-center">
                                        <div class="small"><a href="/login">Have an account? Go to login</a></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
        </div>
        @include('layout.scripts')

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
                // Set default value for contact number to '09'
                const contactInput = document.getElementById('inputContact');
                if (contactInput && !contactInput.value) {
                    contactInput.value = '09';
                }
                });
        </script>

        <script>
            document.getElementById('registrationForm').addEventListener('submit', async function (event) {
                event.preventDefault(); // Prevent default form submission

                // Get form data
                const first_name = document.getElementById('inputFirstName').value;
                const middle_name = document.getElementById('inputMiddleName').value;
                const last_name = document.getElementById('inputLastName').value;
                const email = document.getElementById('inputEmail').value;
                const contact = document.getElementById('inputContact').value;
                const password = document.getElementById('inputPassword').value;
                const confirmPassword = document.getElementById('inputConfirmPassword').value;
                const location_id = document.getElementById("locationDropdown").value.trim();
                const plate_number = document.getElementById('inputPlateNumber').value;
                const license_number = document.getElementById('inputLicenseNumber').value;
                const store_id = JSON.parse(localStorage.getItem('store_id'));

                // Simple password match validation
                if (password !== confirmPassword) {
                    alert('Passwords do not match!');
                    return;
                }

                // Password strength validation
                const passwordRegex = /^(?=.*[A-Z])(?=.*\d).{8,}$/;
                if (!passwordRegex.test(password)) {
                    alert('Password must be at least 8 characters long, contain at least one uppercase letter, and one number.');
                    return;
                }

                // Contact number validation
                const contactRegex = /^09\d{9}$/;
                if (!contactRegex.test(contact)) {
                    alert('Invalid contact number format');
                    return;
                }

                // API call to register rider
                try {
                    const response = await axios.post(
                        `/api/store-riders/${store_id}/register`,
                        {
                            first_name: first_name,
                            middle_name: middle_name,
                            last_name: last_name,
                            email: email,
                            contact: contact,
                            password: password,
                            license_number: license_number,
                            plate_number: plate_number,
                            location_id: location_id
                        },
                        {
                            headers: {
                                'Content-Type': 'application/json',
                                // Add Authorization if needed: 'Authorization': `Bearer ${localStorage.getItem('token')}`
                            }
                        }
                    );
                    alert('Rider registered successfully!');
                    event.target.reset();
                } catch (error) {
                    if (error.response) {
                        alert(error.response.data.message || 'Failed to register rider. Please try again.');
                    } else {
                        alert('An error occurred. Please check your connection and try again.');
                    }
                }
            });
            // Enforce '09' prefix in contact number field
            const contactInput = document.getElementById('inputContact');
            contactInput.addEventListener('input', function (e) {
                // Remove all non-digit characters
                let digits = this.value.replace(/\D/g, '');
                if (!digits.startsWith('09')) {
                    digits = '09' + digits.replace(/^0+/, '').replace(/^9+/, '');
                }
                // Limit to 11 digits
                if (digits.length > 11) {
                    digits = digits.slice(0, 11);
                }
                this.value = digits;
            });
            contactInput.addEventListener('keydown', function (e) {
                if ((this.selectionStart <= 2) && (e.key === 'Backspace' || e.key === 'Delete')) {
                    e.preventDefault();
                }
            });
        </script>
    </body>
</html>

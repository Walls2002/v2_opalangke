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
                                    <h3 class="fw-light my-4">Create Vendor Account</h3>
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
                                        <div class="mb-3">
                                            <label class="small mb-1" for="inputEmail">Email</label>
                                            <input class="form-control" id="inputEmail" type="email" placeholder="Enter email address" required />
                                        </div>
                                        <!-- Form Group (contact number) -->
                                        <div class="mb-3">
                                            <label class="small mb-1" for="inputContact">Contact Number</label>
                                            <input class="form-control" id="inputContact" type="text" placeholder="Enter contact number" required />
                                        </div>

                                        <div class="mb-3">
                                            <label for="locationDropdown" class="form-label">Choose a Location<span class="text-danger">*</span></label>
                                            <select id="locationDropdown" class="form-select" required>
                                                <option value="">Select Location</option>
                                            </select>
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
                                        <button type="submit" id="createAccBtn" class="btn btn-primary btn-block"><span id="createAccSpinner" class="spinner-border spinner-border-sm me-2 d-none" role="status" aria-hidden="true"></span>
                                            Create Account</button>
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

    <!-- Modal -->
    <div class="modal fade" id="otpVerifyModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Verify Email</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="text-center">Enter the 6-digit OTP sent to your email:</p>
                    <div class="d-flex justify-content-center gap-2">
                        <input type="text" class="form-control text-center otp-input" maxlength="1" style="width: 50px;" />
                        <input type="text" class="form-control text-center otp-input" maxlength="1" style="width: 50px;" />
                        <input type="text" class="form-control text-center otp-input" maxlength="1" style="width: 50px;" />
                        <input type="text" class="form-control text-center otp-input" maxlength="1" style="width: 50px;" />
                        <input type="text" class="form-control text-center otp-input" maxlength="1" style="width: 50px;" />
                        <input type="text" class="form-control text-center otp-input" maxlength="1" style="width: 50px;" />
                    </div>
                    <p class="text-center mt-3">
                        <a href="#" onclick="resendOtp()" id="resendOtpLink">Resend OTP</a>
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button onclick="verifyEmail()" id="verifyOtpBtn" type="button" class="btn btn-primary"><span id="modalSpinner" class="spinner-border spinner-border-sm me-2 d-none" role="status" aria-hidden="true"></span>
                        Verify Email</button>
                </div>
            </div>
        </div>
    </div>

    @include('layout.scripts')

    <script>
        document.addEventListener("DOMContentLoaded", async function() {
            const locationDropdown = $('#locationDropdown');
            $.ajax({
                url: '/api/locations',
                method: 'GET',
                dataType: 'json',
                success: function(locations) {
                    // Populate the dropdown with locations
                    locations.forEach(location => {
                        const option = `<option value="${location.id}">${location.barangay}, ${location.city}, ${location.province}</option>`;
                        locationDropdown.append(option);
                    });
                },
                error: function(error) {
                    console.error('Error fetching locations:', error);
                }
            });
            // Set default value for contact number to '09'
            const contactInput = document.getElementById('inputContact');
            if (contactInput && !contactInput.value) {
                contactInput.value = '09';
            }
        });

        // Enforce '09' prefix in contact number field
        const contactInput = document.getElementById('inputContact');
        contactInput.addEventListener('input', function(e) {
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
        contactInput.addEventListener('keydown', function(e) {
            if ((this.selectionStart <= 2) && (e.key === 'Backspace' || e.key === 'Delete')) {
                e.preventDefault();
            }
        });
    </script>


    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const otpInputs = document.querySelectorAll(".otp-input");

            otpInputs.forEach((input, index) => {
                input.addEventListener("input", function() {
                    if (input.value.length === 1 && index < otpInputs.length - 1) {
                        otpInputs[index + 1].focus(); // Focus on the next input
                    }
                });

                input.addEventListener("keydown", function(event) {
                    if (event.key === "Backspace" && input.value === "" && index > 0) {
                        otpInputs[index - 1].focus(); // Focus on the previous input
                    }
                });
            });
        });






        document.getElementById('registrationForm').addEventListener('submit', async function(event) {
            event.preventDefault(); // Prevent default form submission

            // Get form data
            const createAccSpinner = document.getElementById("createAccSpinner");
            const createAccBtn = document.getElementById("createAccBtn");

            const first_name = document.getElementById('inputFirstName').value;
            const middle_name = document.getElementById('inputMiddleName').value;
            const last_name = document.getElementById('inputLastName').value;
            const email = document.getElementById('inputEmail').value;
            const contact = document.getElementById('inputContact').value;
            const password = document.getElementById('inputPassword').value;
            const confirmPassword = document.getElementById('inputConfirmPassword').value;
            const location_id = document.getElementById("locationDropdown").value.trim();
            const myModal = new bootstrap.Modal(document.getElementById('otpVerifyModal'));


            // Simple password match validation
            if (password !== confirmPassword) {
                alert('Passwords do not match!');
                return;
            }
            createAccBtn.disabled = true;
            createAccSpinner.classList.remove("d-none");

            try {
                const response = await fetch('/api/customer/verify-email', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        email: email
                    }),
                });

                if (!response.ok) {
                    throw new Error('Failed to verify email.');
                }

                const data = await response.json();
                const isEmailExists = data.exists;

                if (isEmailExists) {
                    alert('The email has already been taken. Please use a different email address.');
                    return;
                } else {
                    const sendingOtp = await sendOtp();
                    if (sendingOtp === 200) {
                        myModal.show();
                    } else {
                        alert('Failed to send OTP. Please try again later.');
                        return;
                    }
                }
            } catch (error) {
                console.error('Error verifying email:', error);
                alert('An error occurred while verifying the email. Please try again later.');
                return;
            } finally {
                createAccSpinner.classList.add("d-none");
                createAccBtn.disabled = false;
            }
        });

        async function resendOtp() {
            const email = document.getElementById('inputEmail').value;
            const sendingOtp = await sendOtp();
            if (sendingOtp !== 200) {
                alert('Failed to resend OTP. Please try again later.');
                return;
            } else {
                alert('OTP has been resent to your email address. Please check your inbox.');
                return 200;
            }
        }


        async function sendOtp() {
            const email = document.getElementById('inputEmail').value;
            try {
                const otpResponse = await fetch('/api/send-otp', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        email: email
                    }),
                });
                if (!otpResponse.ok) {
                    throw new Error('Failed to send OTP.');
                }
                const otpData = await otpResponse.json();
                if (otpData.code === 200) {
                    return 200;
                } else {
                    return 500;
                }
            } catch (error) {
                return 500;
            }
        }



        async function verifyEmail() {

            const modalSpinner = document.getElementById("modalSpinner");
            const verifyOtpBtn = document.getElementById("verifyOtpBtn");
            // Get form data
            const first_name = document.getElementById('inputFirstName').value;
            const middle_name = document.getElementById('inputMiddleName').value;
            const last_name = document.getElementById('inputLastName').value;
            const email = document.getElementById('inputEmail').value;
            const contact = document.getElementById('inputContact').value;
            const password = document.getElementById('inputPassword').value;
            const confirmPassword = document.getElementById('inputConfirmPassword').value;
            const location_id = document.getElementById("locationDropdown").value.trim();

            // Prepare payload
            const payload = {
                first_name: first_name,
                last_name: last_name,
                middle_name: middle_name,
                email: email,
                password: password,
                contact: contact,
                location_id: location_id,
            };



            const otpInputs = document.querySelectorAll(".otp-input");

            let otp = '';
            otpInputs.forEach(input => {
                otp += input.value;
            });

            if (otp.length < 6) {
                alert('Please enter a valid 6-digit OTP.');
                return;
            }
            modalSpinner.classList.remove("d-none");
            verifyOtpBtn.disabled = true;
            try {
                const response = await fetch('/api/verify-otp', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        email: email,
                        otp: otp
                    }),
                });




                const data = await response.json();

                if (data.code === 200) {


                    // Make AJAX request
                    try {


                        const createAcctResponse = await fetch('/api/users/vendor-register', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify(payload),
                        })

                        const createAcctData = await createAcctResponse.json();
                        if (createAcctData.code === 200 || createAcctData.code === 201) {
                            alert('OTP verified and Vendor account created successfully!');
                            window.location.href = '/login';
                        } else {
                            console.error('Failed to create account:', createAcctData.code, +' ' + payload);
                            alert('Failed to create account. Please try again later.');
                        }
                    } catch (error) {
                        console.error('Error creating account:', error);
                        alert('An error occurred while creating the account. Please try again later.' + JSON.stringify(error) + ' ' + JSON.stringify(payload));
                    }




                    // .then((response) => {
                    //     if (!response.ok) {
                    //         throw new Error(response);
                    //     }
                    //     return response.json();
                    // })
                    // .then((data) => {
                    //     // Success handling
                    //     alert('OTP verified and account created successfully!');

                    //     window.location.href = '/login';
                    // })
                    // .catch((error) => {
                    //     console.error('Error creating account:', error.message);
                    //     alert('An error occurred while creating the account. Please try again later.' + error + ' ' + email);
                    // });
                } else {
                    alert('Invalid OTP. Please try again.');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('An error occurred while verifying the OTP. Please try again later.' + error + ' ' + email);
            } finally {
                modalSpinner.classList.add("d-none");
                verifyOtpBtn.disabled = false;
            }
        }
    </script>







    <!-- <script>
        document.getElementById('registrationForm').addEventListener('submit', function(event) {
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

            // Prepare payload
            const payload = {
                first_name: first_name,
                last_name: last_name,
                middle_name: middle_name,
                email: email,
                password: password,
                contact: contact,
                location_id: location_id,
            };

            // Make AJAX request
            fetch('/api/users/vendor-register', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(payload),
                })
                .then((response) => {
                    if (!response.ok) {
                        throw new Error(response);
                    }
                    return response.json();
                })
                .then((data) => {
                    // Success handling
                    alert('Account created successfully!');
                    console.log(data);
                    location.reload();
                })
                .catch((error) => {
                    // Error handling
                    console.error('Error:', error);
                    alert('The email has already been taken.');
                });
        });
    </script> -->
</body>

</html>
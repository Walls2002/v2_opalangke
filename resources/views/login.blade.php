<!DOCTYPE html>
<html lang="en">
@include('layout.head')

<body class="bg-primary">
    <div id="layoutAuthentication">
        <div id="layoutAuthentication_content">
            <main>
                <div class="container-xl px-4">
                    <div class="row justify-content-center">
                        <div class="col-lg-5">
                            <div class="card shadow-lg border-0 rounded-lg mt-5">
                                <div class="card-header bg-white border-0 text-center">
                                    <h3 class="fw-bold my-3 text-primary">Login</h3>
                                </div>
                                <div class="card-body p-4">
                                    <form id="loginForm">
                                        <div class="mb-4">
                                            <label class="small mb-2 fw-semibold" for="inputEmailAddress">Email</label>
                                            <input class="form-control form-control-lg" id="inputEmailAddress" type="email" placeholder="Enter email address" required />
                                        </div>
                                        <div class="mb-2 position-relative">
                                            <label class="small mb-2 fw-semibold" for="inputPassword">Password</label>
                                            <input class="form-control form-control-lg pr-5" id="inputPassword" type="password" placeholder="Enter password" required />
                                            <span class="position-absolute end-0 pe-3" style="padding-top: 2rem; top:0; bottom:0; height:100%; display:flex; align-items:center; cursor:pointer;" onclick="togglePasswordVisibility('inputPassword', 'togglePasswordIcon')">
                                                <i id="togglePasswordIcon" class="bi bi-eye-slash fs-5"></i>
                                            </span>
                                        </div>
                                        <div class="mb-3 text-end">
                                            <a href="/forgot-password" class="text-decoration-none small text-primary fw-semibold">Forgot Password?</a>
                                        </div>
                                        <div class="mb-3 form-check">
                                            <input class="form-check-input" type="checkbox" id="termsCheck" required />
                                            <label class="form-check-label small" for="termsCheck">
                                                I agree to the <a href="#" class="text-primary" data-bs-toggle="modal" data-bs-target="#termsModal">Terms and Conditions</a>
                                            </label>
                                        </div>
                                        <div class="d-grid gap-2 mt-4 mb-0">
                                            <button type="button" class="btn btn-primary btn-lg shadow-sm" onclick="login()">Login</button>
                                        </div>
                                    </form>
                                </div>
                                <div class="card-footer text-center bg-light border-0">
                                    <div class="small">
                                        <p class="mb-0">Need an account? Sign up as
                                            <span><a href="/register" class="fw-semibold text-primary">Customer</a></span>,
                                            <span><a href="/vendor-register" class="fw-semibold text-primary">Vendor</a></span> or
                                            <span><a href="/rider-register" class="fw-semibold text-primary">Rider</a></span>
                                        </p>
                                    </div>
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
        async function login() {
            const email = document.getElementById("inputEmailAddress").value.trim();
            const password = document.getElementById("inputPassword").value.trim();
            const termsChecked = document.getElementById("termsCheck").checked;

            // Basic validation
            if (!email || !password) {
                alert('Please fill in both email and password.');
                return;
            }
            if (!termsChecked) {
                alert('You must agree to the Terms and Conditions to continue.');
                return;
            }

            try {
                const response = await axios.post('/api/login', {
                    email: email,
                    password: password
                });

                // Store token and user details
                localStorage.setItem('token', response.data.access_token);
                localStorage.setItem('user', JSON.stringify(response.data.user));
                localStorage.setItem('user_type', JSON.stringify(response.data.user_type));

                window.location.href = '/home';

                // Redirect based on role (optional)
                // const user = response.data.user;
                // if (user.role === 'admin') {
                //     window.location.href = '/admin-dashboard';
                // } else {
                //     window.location.href = '/home';
                // }
            } catch (error) {
                // Improved error handling
                if (error.response) {
                    alert(error.response.data.message || 'Invalid credentials. Please try again.');
                } else {
                    alert('Network error. Please check your connection and try again.');
                }
            }
        }

        function togglePasswordVisibility(inputId, iconId) {
            const passwordInput = document.getElementById(inputId);
            const icon = document.getElementById(iconId);
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            }
        }

        $(document).ready(function() {
            // Check the value of "status" in local storage
            var token = localStorage.getItem('token');

            if (token) {
                // Redirect to index.php if there is no value
                window.location.href = '/home';
            }
        });
    </script>

    <!-- Terms and Conditions Modal -->
    <div class="modal fade" id="termsModal" tabindex="-1" aria-labelledby="termsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header justify-content-center">
                    <h5 class="modal-title text-center w-100" id="termsModalLabel">Terms and Conditions</h5>
                    <!-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> -->
                </div>
                <div class="modal-body">
                    <p>Welcome to our platform. Please read these Terms and Conditions carefully before using our services.</p>
                    <h6>1. Acceptance of Terms</h6>
                    <p>By accessing or using our website, you agree to be bound by these Terms and Conditions and our Privacy Policy.</p>
                    <h6>2. User Responsibilities</h6>
                    <ul>
                        <li>Provide accurate and up-to-date information during registration and use of the platform.</li>
                        <li>Maintain the confidentiality of your account credentials.</li>
                        <li>Comply with all applicable laws and regulations.</li>
                    </ul>
                    <h6>3. Prohibited Activities</h6>
                    <ul>
                        <li>Do not use the platform for unlawful or fraudulent purposes.</li>
                        <li>Do not attempt to gain unauthorized access to other accounts or systems.</li>
                        <li>Do not post or transmit harmful, offensive, or inappropriate content.</li>
                    </ul>
                    <h6>4. Limitation of Liability</h6>
                    <p>We are not liable for any damages arising from your use of the platform. Use the services at your own risk.</p>
                    <h6>5. Changes to Terms</h6>
                    <p>We reserve the right to update or modify these Terms and Conditions at any time. Continued use of the platform constitutes acceptance of the new terms.</p>
                    <h6>6. Contact Us</h6>
                    <p>If you have any questions about these Terms and Conditions, please contact us through our support page.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
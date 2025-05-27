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
                                        <div class="mb-2">
                                            <label class="small mb-2 fw-semibold" for="inputPassword">Password</label>
                                            <input class="form-control form-control-lg" id="inputPassword" type="password" placeholder="Enter password" required />
                                        </div>
                                        <div class="mb-3 text-end">
                                            <a href="/forgot-password" class="text-decoration-none small text-primary fw-semibold">Forgot Password?</a>
                                        </div>
                                        <div class="d-grid gap-2 mt-4 mb-0">
                                            <button type="button" class="btn btn-primary btn-lg shadow-sm" onclick="login()">Login</button>
                                        </div>
                                    </form>
                                </div>
                                <div class="card-footer text-center bg-light border-0">
                                    <div class="small">
                                        <p class="mb-0">Need an account? Sign up as
                                            <span><a href="/register" class="fw-semibold text-primary">Customer</a></span> or
                                            <span><a href="/vendor-register" class="fw-semibold text-primary">Vendor</a></span>
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

            // Basic validation
            if (!email || !password) {
                alert('Please fill in both email and password.');
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
    </script>



    <script>
        $(document).ready(function() {
            // Check the value of "status" in local storage
            var token = localStorage.getItem('token');

            if (token) {
                // Redirect to index.php if there is no value
                window.location.href = '/home';
            }
        });
    </script>
</body>

</html>
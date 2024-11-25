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
                                    <div class="card-header justify-content-center"><h3 class="fw-light my-4">Login</h3></div>
                                    <div class="card-body">
                                        <form id="loginForm">
                                            <div class="mb-3">
                                                <label class="small mb-1" for="inputEmailAddress">Email</label>
                                                <input class="form-control" id="inputEmailAddress" type="email" placeholder="Enter email address" required />
                                            </div>
                                            <div class="mb-3">
                                                <label class="small mb-1" for="inputPassword">Password</label>
                                                <input class="form-control" id="inputPassword" type="password" placeholder="Enter password" required />
                                            </div>
                                            <div class="d-flex align-items-center justify-content-between mt-4 mb-0">
                                                <button type="button" class="btn btn-primary" onclick="login()">Login</button>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="card-footer text-center">
                                        <div class="small"><a href="/register">Need an account? Sign up!</a></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
            @include('layout.footer')
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
    </body>
</html>

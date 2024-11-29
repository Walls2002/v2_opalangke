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
                                        <h3 class="fw-light my-4">Create Account</h3>
                                    </div>
                                    <div class="card-body">
                                        <!-- Registration form-->
                                        <form id="registrationForm">
                                            <!-- Form Row-->
                                            <div class="row gx-3">
                                                <div class="col-12">
                                                    <!-- Form Group (name)-->
                                                    <div class="mb-3">
                                                        <label class="small mb-1" for="inputName">Name</label>
                                                        <input class="form-control" id="inputName" type="text" placeholder="Enter name" required />
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
            @include('layout.footer')
        </div>
        @include('layout.scripts')

        <script>
            document.getElementById('registrationForm').addEventListener('submit', function (event) {
                event.preventDefault(); // Prevent default form submission

                // Get form data
                const name = document.getElementById('inputName').value;
                const email = document.getElementById('inputEmail').value;
                const contact = document.getElementById('inputContact').value;
                const password = document.getElementById('inputPassword').value;
                const confirmPassword = document.getElementById('inputConfirmPassword').value;

                // Simple password match validation
                if (password !== confirmPassword) {
                    alert('Passwords do not match!');
                    return;
                }

                // Prepare payload
                const payload = {
                    name: name,
                    email: email,
                    password: password,
                    contact: contact,
                };

                // Make AJAX request
                fetch('/api/customers', {
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
        </script>
    </body>
</html>

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
                                    <div class="card-header justify-content-center"><h3 class="fw-light my-4">Create Account</h3></div>
                                    <div class="card-body">
                                        <!-- Registration form-->
                                        <form>
                                            <!-- Form Row-->
                                            <div class="row gx-3">
                                                <div class="col-12">
                                                    <!-- Form Group (first name)-->
                                                    <div class="mb-3">
                                                        <label class="small mb-1" for="inputFirstName">Name</label>
                                                        <input class="form-control" id="inputFirstName" type="text" placeholder="Enter name" />
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Form Group (email address)            -->
                                            <div class="mb-3">
                                                <label class="small mb-1" for="inputEmailAddress">Email</label>
                                                <input class="form-control" id="inputEmailAddress" type="email" aria-describedby="emailHelp" placeholder="Enter email address" />
                                            </div>
                                            <!-- Form Group (contact number)            -->
                                            <div class="mb-3">
                                                <label class="small mb-1" for="inputEmailAddress">Contact Number</label>
                                                <input class="form-control" id="inputEmailAddress" type="text" aria-describedby="emailHelp" placeholder="Enter contact number" />
                                            </div>
                                            <!-- Form Row    -->
                                            <div class="row gx-3">
                                                <div class="col-md-6">
                                                    <!-- Form Group (password)-->
                                                    <div class="mb-3">
                                                        <label class="small mb-1" for="inputPassword">Password</label>
                                                        <input class="form-control" id="inputPassword" type="password" placeholder="Enter password" />
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <!-- Form Group (confirm password)-->
                                                    <div class="mb-3">
                                                        <label class="small mb-1" for="inputConfirmPassword">Confirm Password</label>
                                                        <input class="form-control" id="inputConfirmPassword" type="password" placeholder="Confirm password" />
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Form Group (create account submit)-->
                                            <a class="btn btn-primary btn-block" href="auth-login-basic.html">Create Account</a>
                                        </form>
                                    </div>
                                    <div class="card-footer text-center">
                                        <div class="small"><a href="/">Have an account? Go to login</a></div>
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
    </body>
</html>

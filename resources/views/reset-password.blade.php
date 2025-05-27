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
                                    <h3 class="fw-bold my-3 text-primary">Set New Password</h3>
                                </div>
                                <div class="card-body p-4">
                                    <form id="setNewPasswordForm">
                                        <div class="mb-4">
                                            <label class="small mb-2 fw-semibold" for="inputNewPassword">New Password</label>
                                            <input class="form-control form-control-lg" id="inputNewPassword" type="password" placeholder="Enter your new password" required />
                                        </div>
                                        <div class="mb-4">
                                            <label class="small mb-2 fw-semibold" for="inputConfirmPassword">Confirm Password</label>
                                            <input class="form-control form-control-lg" id="inputConfirmPassword" type="password" placeholder="Confirm your new password" required />
                                        </div>
                                        <div class="d-grid gap-2 mt-4 mb-0">
                                            <button type="button" class="btn btn-primary btn-lg shadow-sm" onclick="setNewPassword()">Reset Password</button>
                                        </div>
                                    </form>
                                </div>
                                <div class="card-footer text-center bg-light border-0">
                                    <div class="small">
                                        <a href="/login" class="fw-semibold text-primary">Back to Login</a>
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
        async function setNewPassword() {
            const newPassword = document.getElementById("inputNewPassword").value.trim();
            const confirmPassword = document.getElementById("inputConfirmPassword").value.trim();
            const email = new URLSearchParams(window.location.search).get('email');
            if (!newPassword || !confirmPassword) {
                alert('Please fill out all fields.');
                return;
            }

            if (newPassword !== confirmPassword) {
                alert('Passwords do not match. Please try again.');
                return;
            }

            try {
                const response = await fetch('/api/reset-password', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        newPassword: newPassword,
                        email: email
                    }),
                });

                if (!response.ok) {
                    throw new Error('Failed to reset password.');
                }

                const data = await response.json();
                if (data.code === 200) {
                    alert('Your password has been reset successfully. You can now log in with your new password.');
                    window.location.href = '/login';
                } else {
                    console.error('Failed to reset password:', data);
                    alert('Failed to reset password. Please try again later.');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('An error occurred while resetting the password. Please try again later.');
            }
        }
    </script>
</body>

</html>
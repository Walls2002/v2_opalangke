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
                                    <h3 class="fw-bold my-3 text-primary">Reset Password</h3>
                                </div>
                                <div class="card-body p-4">
                                    <form id="resetPasswordForm">
                                        <div class="mb-4">
                                            <label class="small mb-2 fw-semibold" for="inputEmailAddress">Email Address</label>
                                            <input class="form-control form-control-lg" id="inputEmailAddress" type="email" placeholder="Enter your email address" required />
                                        </div>
                                        <div class="d-grid gap-2 mt-4 mb-0">
                                            <button type="button" id="sendOtpBtn" class="btn btn-primary btn-lg shadow-sm" onclick="resetPassword()">
                                                <span id="sendOtpBtnSpinner" class="spinner-border spinner-border-sm me-2 d-none" role="status" aria-hidden="true"></span>
                                                Send OTP
                                            </button>
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
        async function resetPassword() {
            const email = document.getElementById("inputEmailAddress").value.trim();
            const spinner = document.getElementById("sendOtpBtnSpinner");
            const sendOtpBtn = document.getElementById("sendOtpBtn");
            let isEmailExists = false;



            if (!email) {
                alert('Please enter your email address.');
                return;
            }

            try {

                // Show spinner
                spinner.classList.remove("d-none");
                sendOtpBtn.disabled = true;


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

                // alert('is email exist? ' + isEmailExists);


                if (isEmailExists) {
                    // Email exists, proceed to send OTP
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
                        alert('OTP has been sent to your email address. Please check your inbox.');
                        window.location.href = '/verify-otp?email=' + encodeURIComponent(email);
                    } else {
                        console.error('Failed to send OTP:' + otpData);
                        alert('Failed to send OTP. Please try again later.');
                    }

                } else {
                    alert('Email does not exist. Please check your email address.');
                    return;
                }
            } catch (error) {
                console.error('Error:', error);
                alert('An error occurred while sending the OTP. Please try again later.');
            } finally {

                spinner.classList.add("d-none");
                sendOtpBtn.disabled = false;

            }


        }
    </script>
</body>

</html>
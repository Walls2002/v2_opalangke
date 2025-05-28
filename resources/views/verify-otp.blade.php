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
                                    <h3 class="fw-bold my-3 text-primary">Verify OTP</h3>
                                </div>
                                <div class="card-body p-4">
                                    <form id="verifyOtpForm">
                                        <div class="mb-4 text-center">
                                            <label class="small mb-2 fw-semibold" for="otpInputs">Enter OTP</label>
                                            <div id="otpInputs" class="d-flex justify-content-center gap-2">
                                                <input class="form-control text-center" type="text" maxlength="1" oninput="moveToNext(this, 'otp2')" id="otp1" required />
                                                <input class="form-control text-center" type="text" maxlength="1" oninput="moveToNext(this, 'otp3')" id="otp2" required />
                                                <input class="form-control text-center" type="text" maxlength="1" oninput="moveToNext(this, 'otp4')" id="otp3" required />
                                                <input class="form-control text-center" type="text" maxlength="1" oninput="moveToNext(this, 'otp5')" id="otp4" required />
                                                <input class="form-control text-center" type="text" maxlength="1" oninput="moveToNext(this, 'otp6')" id="otp5" required />
                                                <input class="form-control text-center" type="text" maxlength="1" oninput="moveToNext(this, null)" id="otp6" required />
                                            </div>
                                        </div>
                                        <div class="d-grid gap-2 mt-4 mb-0">

                                            <button type="button" id="verifyOtpBtn" class="btn btn-primary btn-lg shadow-sm" onclick="verifyOtp()"> <span id="sendOtpBtnSpinner" class="spinner-border spinner-border-sm me-2 d-none" role="status" aria-hidden="true"></span>
                                                Verify OTP</button>
                                        </div>
                                    </form>
                                </div>
                                <div class="card-footer text-center bg-light border-0">
                                    <div class="small">
                                        <a href="/forgot-password" class="fw-semibold text-primary">Back to Reset Password</a>
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
        function moveToNext(current, nextId) {
            if (current.value.length === 1 && nextId) {
                document.getElementById(nextId).focus();
            }
        }

        async function verifyOtp() {
            const spinner = document.getElementById("sendOtpBtnSpinner");
            const verifyOtpBtn = document.getElementById("verifyOtpBtn");

            const otp = Array.from({
                length: 6
            }, (_, i) => document.getElementById(`otp${i + 1}`).value).join('');
            const email = new URLSearchParams(window.location.search).get('email');

            if (otp.length !== 6) {
                alert('Please enter the complete OTP.');
                return;
            }



            try {
                spinner.classList.remove("d-none");
                verifyOtpBtn.disabled = true;

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


                if (!response.ok) {
                    // Handle non-200 HTTP responses
                    const errorText = await response.text();
                    console.error('Error response:', errorText);
                    alert('An error occurred: ' + response.status + ' ' + response.statusText + ' ' + errorText + ' ' + email + ' ' + otp);
                    return;
                }


                const data = await response.json();

                if (data.code === 200) {
                    alert('OTP verified successfully. You can now reset your password.');
                    window.location.href = '/reset-password?email=' + encodeURIComponent(email);
                } else {
                    alert('Invalid OTP. Please try again.');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('An error occurred while verifying the OTP. Please try again later.' + error + ' ' + email);
            } finally {
                spinner.classList.add("d-none");
                verifyOtpBtn.disabled = false;
            }
        }
    </script>
</body>

</html>
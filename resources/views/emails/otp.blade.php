<!DOCTYPE html>
<html>

<head>
    <title>Your OTP</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .email-header {
            background-color: #337037;
            color: #ffffff;
            text-align: center;
            padding: 20px;
        }

        .email-header h1 {
            margin: 0;
            font-size: 24px;
        }

        .email-body {
            padding: 20px;
            color: #333333;
            line-height: 1.6;
        }

        .otp-code {
            font-size: 28px;
            font-weight: bold;
            color: #337037;
            text-align: center;
            margin: 20px 0;
        }

        .email-footer {
            background-color: #f8f9fa;
            text-align: center;
            padding: 10px;
            font-size: 12px;
            color: #666666;
        }
    </style>
</head>

<body>
    <div class="email-container">
        <div class="email-header">
            <h1>Your OTP Code</h1>
        </div>
        <div class="email-body">
            <p>Hello,</p>
            <p>Your One-Time Password (OTP) is:</p>
            <div class="otp-code">{{ $otp }}</div>
            <p>Please do not share this code with anyone. If you did not request this code, please ignore this email.</p>
        </div>
        <div class="email-footer">
            <p>&copy; {{ date('Y') }} OPalengke. All rights reserved.</p>
        </div>
    </div>
</body>

</html>
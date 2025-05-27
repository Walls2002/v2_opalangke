<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\OtpMail;
use Illuminate\Support\Facades\DB;

class OtpController extends Controller
{
    public function sendOtp(Request $request)
    {
        // Validate incoming email
        $request->validate([
            'email' => 'required|email',
        ]);

        // Generate OTP
        $otp = rand(100000, 999999);
        $email = $request->input('email');

        try {
            // Send OTP email
            Mail::to($email)->send(new OtpMail($otp));


            // Remove any existing OTP for this email
            DB::table('otp')->where('email', $email)->delete();

            // Insert new OTP
            DB::table('otp')->insert([
                'email' => $email,
                'otp' => $otp,
                'created_at' => now(),
                'updated_at' => now(),
            ]);



            return response()->json([
                'code' => 200,
                'message' => 'OTP has been sent to your email.'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'code' => 500,
                'message' => 'Failed to send OTP.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function verifyOtp(Request $request)
    {

        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|integer|digits:6',
        ]);

        $email = $request->input('email');
        $otp = $request->input('otp');


        $exists = DB::table('otp')
            ->where('email', $email)
            ->where('otp', $otp)
            ->where('created_at', '>=', now()->subMinutes(50)) // Check if OTP is within 50 minutes
            ->exists();


        if (!$exists) {
            return response()->json([
                'code' => 400,
                'message' => 'Invalid or expired OTP.'
            ], 400);
        } else {
            // If OTP is valid, delete it from the database
            DB::table('otp')->where('email', $email)->delete();

            return response()->json([
                'code' => 200,
                'message' => 'OTP verified successfully.'
            ], 200);
        }
    }
}

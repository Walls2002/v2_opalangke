<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('location_id');
            $table->string('last_name');
            $table->string('first_name');
            $table->string('middle_name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('profile_picture')->nullable(); // Add contact column
            $table->string('contact')->nullable(); // Add contact column
            $table->enum('role', ['admin', 'vendor', 'customer', 'rider'])->default('customer'); // Add role column
            $table->dateTime('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRidersTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('riders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id'); // Foreign key to vendors (users with role 'vendor')
            $table->string('license_number')->unique();
            $table->string('plate_number')->unique();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            // Foreign key constraint
            // $table->foreign('vendor_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('riders');
    }
}

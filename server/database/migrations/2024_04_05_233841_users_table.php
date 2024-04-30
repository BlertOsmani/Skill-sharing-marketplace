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
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email', 255)->unique();
            $table->string('username', 255)->unique();
            $table->string('password');
            $table->string('password_salt');
            $table->text('verification_token')->nullable();
            $table->text('verification_status')->nullable();
            $table->text('auth_token')->nullable();
            $table->text('auth_enabled')->nullable();
            $table->text('bio')->nullable();
            $table->text('profile_picture')->nullable();
            $table->text('is_active')->nullable();
            $table->timestamps();
        });
        
        //
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        //
    }
};

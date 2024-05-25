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
            $table->string('first_name', 40);
            $table->string('last_name', 40);
            $table->string('email', 150)->unique();
            $table->string('username', 50)->unique();
            $table->string('password',255);
            $table->string('password_salt',255);
            $table->text('verification_token')->nullable();
            $table->text('verification_status')->nullable();
            $table->text('auth_token', 6)->nullable();
            $table->text('auth_enabled', 20)->nullable();
            $table->text('bio', 250)->nullable();
            $table->text('profile_picture')->nullable();
            $table->text('is_active', 10)->nullable();
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

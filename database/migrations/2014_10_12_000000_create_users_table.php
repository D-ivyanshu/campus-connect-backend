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
            $table->string('name');
            $table->string('email')->unique();
            $table->string('about')->nullable();
            $table->string('course')->nullable();
            $table->string('year')->nullable();
            $table->string('branch')->nullable();
            
            $table->json('social_links')->nullable();
            $table->json('notifications_configuration')->nullable();
            

            $table->string('avatar')->nullable()->default('https://res.cloudinary.com/duwukinfy/image/upload/v1712040648/wcnopwl0tepuzcsdnfr9.jpg');
            $table->string('banner')->nullable()->default('https://res.cloudinary.com/duwukinfy/image/upload/v1712140547/cmrqynqsd7y9nnazkp8h.jpg');

            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
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

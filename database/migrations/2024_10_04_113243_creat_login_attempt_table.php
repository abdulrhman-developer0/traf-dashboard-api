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
        Schema::create('login_attempts', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->nullable()->index();
            $table->string('email');

            $table->ipAddress('ip');
            $table->string('country')->nullable();
            $table->string('city')->nullable();

            $table->string('platform')->nullable();
            $table->string('platform_version')->nullable();

            $table->string('browser')->nullable();
            $table->string('browser_version')->nullable();

            $table->boolean('is_desktop')->default(false);
            $table->boolean('is_phone')->default(false);
            $table->boolean('is_robot')->default(false);

            $table->string('device_name')->nullable();

            $table->text('user_agent');
            
            $table->boolean('is_success')->default(false);
            $table->boolean('is_active')->default(false);

            $table->timestamp('banned_until')->nullable();

            $table->timestamps();

        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('login_attempts');
    }
};

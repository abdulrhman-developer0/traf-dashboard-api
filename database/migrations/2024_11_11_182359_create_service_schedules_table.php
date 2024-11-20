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
    
        Schema::create('service_schedules', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('partner_service_provider_id')->unsigned(); 
            $table->bigInteger('service_id')->unsigned(); 
            $table->enum('schedule_pattern', ['daily', 'every_two_days', 'weekly', 'manual'])->default('manual'); // Store the schedule pattern
            $table->json('dates'); 
            $table->enum('status', ['available', 'off', 'booked'])->default('available'); 
            $table->timestamps(); 
        
            $table->foreign('partner_service_provider_id')->references('id')->on('service_provider_partners')->onDelete('cascade');
            $table->foreign('service_id')->references('id')->on('services')->onDelete('cascade');
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_schedules');
    }
};

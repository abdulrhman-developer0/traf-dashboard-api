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
            $table->date('date'); 
            $table->time('time'); 
            $table->enum('status', ['available', 'off', 'booked'])->default('available'); 
            
            $table->timestamps(); 

            $table->foreign('partner_service_provider_id')->references('id')->on('partner_service_providers')->onDelete('cascade');
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

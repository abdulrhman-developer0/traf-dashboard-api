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
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_category_id')->unsigned()->references('id')->on('service_categories')->onDelete('cascade');
            $table->foreignId('service_provider_id')->unsigned()->references('id')->on('service_providers')->onDelete('cascade');
            $table->string('name');
            $table->integer('duration');
            $table->longText('description')->nullable();
            $table->decimal('rating')->default(0.00);
            $table->decimal('price_before', 10, 2);
            $table->decimal('price_after', 10, 2)->nullable(); 
            $table->text('address')->nullable();
            $table->boolean('is_home_service')->default(false);
            $table->boolean('is_offer')->default(false);
            $table->timestamps();
        
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};

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
            $table->bigInteger('service_category_id')->unsigned();
            $table->bigInteger('partner_service_provider_id')->unsigned();
            $table->string('name');
            $table->integer('duration');
            $table->longText('description')->nullable();
            $table->float('rating')->nullable();
            $table->decimal('price_before', 10, 2);
            $table->boolean('is_offer')->default(false);
            $table->timestamps();
            $table->foreign('service_category_id')->references('id')->on('service_categories')->onDelete('cascade');
            $table->foreign('partner_service_provider_id')->references('id')->on('service_provider_partners')->onDelete('cascade');
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

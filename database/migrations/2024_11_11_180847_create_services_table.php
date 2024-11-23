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
            $table->string('name');
            $table->integer('duration');
            $table->longText('description')->nullable();
            $table->float('rating')->nullable();
            $table->decimal('price_before', 10, 2);
            $table->decimal('price_after', 10, 2)->nullable(); 
            $table->text('address')->default('');
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

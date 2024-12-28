<?php

use App\Models\Ad;
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
        Schema::create('ads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_provider_id')->references('id')->on('service_providers');
            $table->foreignId('ad_price_id')->references('id')->on('ad_prices');
            $table->integer('duration_in_days');
            $table->float('total_price');
            $table->enum('status', Ad::STATUSES);
            $table->text('notes')->nullable();
            $table->datetime('start_date')->nullable();
            $table->datetime('end_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ads');
    }
};

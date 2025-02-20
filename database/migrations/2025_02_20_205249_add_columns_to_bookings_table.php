<?php

use App\Models\Booking;
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
        Schema::table('bookings', function (Blueprint $table) {
            //
            $table->string('payment_method')->nullable();
            $table->enum('payment_status', Booking::PAYMENT_SATUSES)->default('pending');
            $table->string('reference_payment')->nullable();
            $table->float('payment_amount')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            //
            $table->dropColumn('payment_method');
            $table->dropColumn('payment_status');
            $table->dropColumn('reference_payment');
            $table->dropColumn('payment_amount');
        });
    }
};

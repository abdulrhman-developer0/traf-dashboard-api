<?php

use App\Enums\TransactionStatus;
use App\Enums\TransactionType;
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
        Schema::create('ransactions', function (Blueprint $table) {
            $table->id();
            $table->morphs('transactionable');
            $table->enum('transaction_type', TransactionType::values());
            $table->decimal('amount', 10, 2);
            $table->enum('status', TransactionStatus::values())->default(TransactionStatus::PENDING);
            $table->string('reference_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ransactions');
    }
};

<?php

use App\Enums\TransactionStatus;
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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dedication_id')->constrained('dedications')->onDelete('cascade');
            $table->integer('amount');
            $table->string('phone_payment');
            $table->string('status', 10)->default(TransactionStatus::PENDING->value);
            $table->string('mode_paiement', 20);
            $table->string('transaction_ref', 255)->unique();
            $table->string('bill_id', 255)->unique();
            $table->timestamps();
        });

        DB::statement("ALTER TABLE transactions ADD CONSTRAINT chk_status CHECK (status IN ('" . implode("','", TransactionStatus::values()) . "'))");
        DB::statement("ALTER TABLE transactions ADD CONSTRAINT chk_mode_paiement CHECK (mode_paiement IN ('" . implode("','", \App\Enums\PaymentMethod::values()) . "'))");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};

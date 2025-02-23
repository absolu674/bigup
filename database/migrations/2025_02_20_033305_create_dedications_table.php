<?php

use App\Enums\DedicationState;
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
        Schema::create('dedications', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->foreignId('dedication_type_id')->constrained('dedication_types')->noActionOnDelete();
            $table->foreignId('user_id')->constrained('users')->noActionOnDelete();
            $table->foreignId('artist_id')->constrained('users')->noActionOnDelete();
            $table->string('state', 10)->default(DedicationState::PENDING->value);
            $table->boolean('is_self')->default(false);
            $table->string('firstname')->nullable();
            $table->string('lastname')->nullable();
            $table->string('phone_payment')->nullable();
            $table->string('message');
            $table->string('message_rejected')->nullable();
            $table->string('dedication_video_path')->nullable();
            $table->timestamps();
        });

        DB::statement("ALTER TABLE dedications ADD CONSTRAINT chk_state CHECK (state IN ('" . implode("','", DedicationState::values()) . "'))");

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dedications');
    }
};

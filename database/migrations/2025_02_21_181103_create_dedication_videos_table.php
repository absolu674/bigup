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
        Schema::create('dedication_videos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dedication_id')->constrained('dedications')->noActionOnDelete();
            $table->foreignId('fichier_id')->constrained('fichiers')->noActionOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dedication_videos');
    }
};

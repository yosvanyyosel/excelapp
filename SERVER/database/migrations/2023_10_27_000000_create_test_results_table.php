<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('test_results');
        Schema::create('test_results', function (Blueprint $table) {
            $table->id();
            $table->string('user_name');     // Nombre del participante
            $table->string('pair_name')->nullable();     // Nombre de su pareja
            $table->string('center_name')->nullable();   // Centro de descubrimiento
            $table->string('test_type');     // 'mbti' o 'dones'
            $table->timestamp('completed_at');
            $table->json('answers');         // Las respuestas en JSON
            $table->json('metadata')->nullable(); // Resultados calculados (scores, rankings)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('test_results');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        // Tabla para fortalezas, áreas de crecimiento y sugerencias individuales
        Schema::create('evaluation_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->enum('type', ['strength', 'growth', 'suggestion']);
            $table->text('content');
            $table->timestamps();
        });

        // Tabla para la decisión final de la pareja
        Schema::create('pair_evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('center_id')->constrained('discovery_centers')->onDelete('cascade');
            $table->string('pair_name');
            $table->enum('decision', ['green', 'yellow', 'red'])->nullable();
            $table->string('decision_text')->nullable(); // Ej: "Recomendado"
            $table->dateTime('visible_at')->nullable();
            $table->timestamps();

            $table->unique(['center_id', 'pair_name']);
        });
    }

    public function down(): void {
        Schema::dropIfExists('pair_evaluations');
        Schema::dropIfExists('evaluation_items');
    }
};

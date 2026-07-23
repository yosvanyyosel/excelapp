<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        // Centros de Descubrimiento
        Schema::create('discovery_centers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('banner_photo')->nullable();
            $table->integer('quiz_timer')->default(15); // Segundos configurables
            $table->timestamps();
        });

        // Usuarios (Master Admin, Admins, Participantes)
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('username')->unique();
            $table->string('password');
            $table->enum('role', ['master', 'admin', 'participant'])->default('participant');
            $table->foreignId('center_id')->nullable()->constrained('discovery_centers');
            $table->string('pair_name')->nullable(); // Nombre de la pareja
            $table->string('pair_photo')->nullable(); // Foto compartida
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('users');
        Schema::dropIfExists('discovery_centers');
    }
};

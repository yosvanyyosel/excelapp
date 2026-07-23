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

        // Tokens de reseteo de contraseña
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        // Sesiones
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    public function down(): void {
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('users');
        Schema::dropIfExists('discovery_centers');
    }
};

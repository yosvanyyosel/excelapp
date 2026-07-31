<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('users', function (Blueprint $table) {
            $table->string('staff_title')->nullable()->after('role'); // Ej: Coordinador, Mentor, etc.
        });

        Schema::create('notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('author_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('center_id')->constrained('discovery_centers')->onDelete('cascade');
            $table->text('content');
            $table->boolean('is_public')->default(true); // Pública al centro o privada para el staff
            $table->foreignId('tagged_user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('tagged_pair_name')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('notes');
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('staff_title');
        });
    }
};

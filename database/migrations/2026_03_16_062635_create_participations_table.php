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
        Schema::create('participations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cheikh_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->foreignId('competition_id')->constrained('competitions')->nullOnDelete();
            $table->unique(['student_id', 'competition_id']);
            $table->decimal('note_tajwid', 4, 2)->nullable();
            $table->decimal('note_hifz', 4, 2)->nullable();
            $table->string('remarque')->nullable();
            $table->enum('statut',['en_attente', 'accepte', 'refuse', 'valide']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('participations');
    }
};

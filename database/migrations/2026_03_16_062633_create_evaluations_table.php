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
        Schema::create('evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cheikh_id')->constrained('users');
            $table->foreignId('student_id')->constrained('students');
            $table->foreignId('halaqa_id')->constrained('halaqas')->cascadeOnDelete();
            $table->string('du_sourate')->nullable();
            $table->string('au_sourate')->nullable();
            $table->integer('hizb')->nullable();
            $table->integer('du_aya')->nullable();
            $table->integer('au_aya')->nullable();
            $table->decimal('note', 5, 2)->nullable();
            $table->text('remarque')->nullable();
            $table->enum('presence', ['present', 'absent', 'retard']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluations');
    }
};

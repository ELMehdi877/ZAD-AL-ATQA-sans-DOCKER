<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('meetings', function (Blueprint $table) {
            $table->id();
            $table->string('meeting_name')->unique(); // nom room daily
            $table->string('url')->unique();  // url complète
            $table->foreignId('cheikh_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('halaqa_id')->constrained('halaqas')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('meetings');
    }
};

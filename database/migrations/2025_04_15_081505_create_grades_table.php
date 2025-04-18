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
        Schema::create('grades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('cohort_id')->constrained()->onDelete('cascade');
            $table->foreignId('teacher_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('title');
            $table->float('value')->default(0); // Renommé de 'score' à 'value' pour correspondre au contrôleur
            $table->date('evaluation_date')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
            
            // Unique constraint to prevent duplicate grades for same user and cohort
            $table->unique(['user_id', 'cohort_id', 'title']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grades');
    }
};

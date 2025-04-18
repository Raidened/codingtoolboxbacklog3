<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRetrosTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('retros', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('cohort_id');
            $table->unsignedBigInteger('user_id');
            $table->timestamps();

            $table->foreign('cohort_id')->references('id')->on('cohorts')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('retros');
    }
}

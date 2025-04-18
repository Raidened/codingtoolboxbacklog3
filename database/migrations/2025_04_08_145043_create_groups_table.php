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
        Schema::create('groups', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->id();
            $table->unsignedBigInteger('cohort_id');
            $table->string('name');
            $table->text('description')->nullable();
            $table->boolean('is_auto_generated')->default(false);
            $table->json('generation_params')->nullable();
            $table->timestamps();

            $table->foreign('cohort_id')->references('id')->on('cohorts')
                ->onDelete('cascade');
        });

        Schema::create('users_groups', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('group_id');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')
                ->onDelete('cascade');
            $table->foreign('group_id')->references('id')->on('groups')
                ->onDelete('cascade');
                
            // Contrainte d'unicité pour éviter qu'un utilisateur soit plusieurs fois dans le même groupe
            $table->unique(['user_id', 'group_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users_groups');
        Schema::dropIfExists('groups');
    }
};

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
        Schema::create('course_schedules', function (Blueprint $table) {
            $table->id();
            $table->date('day');
            $table->time('hour_Start'); // heureDebut (Time)
            $table->time('hour_End'); // heureFin (Time)
            $table->unsignedBigInteger('user_id'); // Clé étrangère vers `users`
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('course_id')->nullable(); // Clé étrangère vers `professor`
            $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');
            $table->unsignedBigInteger('department_id'); // Filière associée
            $table->unsignedBigInteger('level_education_id'); // Niveau associé

             // Définir les clés étrangères
            $table->foreign('department_id')->references('id')->on('departments')->onDelete('cascade');
            $table->foreign('level_education_id')->references('id')->on('level_education')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_schedules');
    }
};

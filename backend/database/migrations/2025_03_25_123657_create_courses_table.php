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
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('subject_taught'); // intitule (String)
            $table->integer('duration');
            $table->string('description'); // description (String)

            $table->unsignedBigInteger('user_id')->unique(); // Clé étrangère vers `users`
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->unsignedBigInteger('department_id'); // Filière associée
            $table->unsignedBigInteger('level_education_id'); // Niveau associé

             // Définir les clés étrangères
            $table->foreign('department_id')->references('id')->on('departments')->onDelete('cascade');
            $table->foreign('level_education_id')->references('id')->on('level_education')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};

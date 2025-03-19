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
            $table->time('duration'); // durée (Time)
            $table->string('description'); // description (String)

            $table->unsignedBigInteger('professor_id')->unique(); // Clé étrangère vers `courses`
            $table->foreign('professor_id')->references('id')->on('professors')->onDelete('cascade');
            $table->timestamps();


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

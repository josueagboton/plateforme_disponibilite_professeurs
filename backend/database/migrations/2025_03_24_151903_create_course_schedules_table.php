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
            $table->string('event')->nullable();
            $table->time('hour_Start'); // heureDebut (Time)
            $table->time('hour_End'); // heureFin (Time)
            $table->unsignedBigInteger('professor_id')->nullable(); // Clé étrangère vers `professor`
            $table->foreign('professor_id')->references('id')->on('professors')->onDelete('cascade');
            $table->unsignedBigInteger('course_id')->nullable(); // Clé étrangère vers `professor`
            $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');
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

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
        Schema::create('availabilities', function (Blueprint $table) {
            $table->id();
            $table->date('day'); // jour (Date)
            $table->time('hour_Start'); // heureDebut (Time)
            $table->time('hour_End'); // heureFin (Time)
            $table->timestamps();
            $table->unsignedBigInteger('professor_id')->unique(); 

            $table->foreign('professor_id')->references('id')->on('professors')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('availabilities');
    }
};

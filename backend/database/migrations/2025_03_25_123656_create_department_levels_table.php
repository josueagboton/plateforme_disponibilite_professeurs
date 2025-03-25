<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Création de la table pivot entre départements et niveaux d'éducation
        Schema::create('department_level', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('department_id');
            $table->unsignedBigInteger('level_education_id');
            $table->foreign('department_id')->references('id')->on('departments')->onDelete('cascade');
            $table->foreign('level_education_id')->references('id')->on('level_education')->onDelete('cascade');
            $table->timestamps();
        });

        // Insertion des niveaux d'études
        $levels = [
            ['name' => 'Licence 1', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Licence 2', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Licence 3', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Master 1', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Master 2', 'created_at' => now(), 'updated_at' => now()],
        ];

        DB::table('level_education')->insert($levels);

        // Récupération des IDs des niveaux
        $levels = DB::table('level_education')->get();

        // Insertion des départements
        $departments = ['Génie Logiciel', 'Sécurité Informatique', 'IA'];
        $data = [];

        foreach ($departments as $department) {
            $departmentId = DB::table('departments')->insertGetId([
                'name' => $department,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Association avec chaque niveau dans la table pivot
            foreach ($levels as $level) {
                DB::table('department_level')->insert([
                    'department_id' => $departmentId,
                    'level_education_id' => $level->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('department_level');
    }
};

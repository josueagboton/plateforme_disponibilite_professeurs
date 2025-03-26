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

    $departmentsWithLevels = [
        'Génie Logiciel' => ['Licence 1', 'Licence 2', 'Licence 3', 'Master 1', 'Master 2'],
        'Sécurité Informatique' => ['Licence 1', 'Licence 2', 'Licence 3', 'Master 1', 'Master 2'],
        'IA' => ['Licence 1', 'Licence 2', 'Licence 3', 'Master 1', 'Master 2']
    ];

    // Insertion des départements et récupération de leurs IDs
    foreach ($departmentsWithLevels as $departmentName => $levels) {
        // Insérer le département
        $departmentId = DB::table('departments')->insertGetId([
            'name' => $departmentName,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Insertion des niveaux d'éducation associés à chaque département
        foreach ($levels as $levelName) {
            // Vérifier si le niveau existe déjà, sinon l'insérer
            $level = DB::table('level_education')->where('name', $levelName)->first();

            if (!$level) {
                // Si le niveau n'existe pas, l'insérer
                $levelId = DB::table('level_education')->insertGetId([
                    'name' => $levelName,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                // Si le niveau existe, récupérer son ID
                $levelId = $level->id;
            }

            // Associer le département avec le niveau d'éducation dans la table pivot
            DB::table('department_level')->insert([
                'department_id' => $departmentId,
                'level_education_id' => $levelId,
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

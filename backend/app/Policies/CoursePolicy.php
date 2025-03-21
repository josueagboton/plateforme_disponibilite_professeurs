<?php

    namespace App\Policies;

    use App\Models\Courses;
    use App\Models\User;

    class CoursePolicy
    {
        /**
         * Définir si l'utilisateur peut créer un cours.
         */
        public function create(User $user)
        {
            return $user->role === 'administrator';
        }

        /**
         * Définir si l'utilisateur peut modifier un cours.
         */
        public function update(User $user, Courses $course)
        {
            return $user->role === 'administrator';
        }

        /**
         * Définir si l'utilisateur peut supprimer un cours.
         */
        public function delete(User $user, Courses $course)
        {
            return $user->role === 'administrator';
        }
    }


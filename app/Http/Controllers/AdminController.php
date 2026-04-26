<?php

namespace App\Http\Controllers;

use App\Models\Competition;
use App\Models\Halaqa;
use App\Models\Student;
use App\Models\User;

class AdminController extends Controller
{
    /**
     * --- ACTEUR : ADMIN ---
     * Affiche le tableau de bord principal de l'administrateur avec les statistiques globales.
     */
    public function index()
    {
        // Statistiques globales des utilisateurs
        $usersCount = User::count();
        $activeUsersCount = User::where('statut', 'active')->count();
        $inactiveUsersCount = User::where('statut', 'inactive')->count();

        // Statistiques par rôle
        $cheilhsCount = User::where('role', 'cheikh')->count();
        $studentsCount = Student::count();
        $parentsCount = User::where('role', 'parent')->count();

        // Statistiques des activités
        $halaqasCount = Halaqa::count();
        $competitionsCount = Competition::count();

        return view('admin.dashboard', compact(
            'usersCount', 
            'activeUsersCount', 
            'inactiveUsersCount', 
            'cheilhsCount', 
            'studentsCount', 
            'parentsCount', 
            'halaqasCount', 
            'competitionsCount'
        ));
    }
}
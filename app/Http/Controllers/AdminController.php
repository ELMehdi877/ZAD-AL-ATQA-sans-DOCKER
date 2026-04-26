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
    public function dashboard()
    {
        $usersCount = User::count();
        $halaqasCount = Halaqa::count();
        $competitionsCount = Competition::count();
        $studentsCount = Student::count();

        return view('admin.dashboard', compact('usersCount', 'halaqasCount', 'competitionsCount', 'studentsCount'));
    }
}
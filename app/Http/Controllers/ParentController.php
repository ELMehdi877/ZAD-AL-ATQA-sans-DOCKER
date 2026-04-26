<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ParentController extends Controller
{
    /**
     * --- ACTEUR : PARENT ---
     * Affiche le tableau de bord avec la liste des enfants associés au compte parent.
     */
    public function index()
    {
        $students = Student::where('parent_id', Auth::id())->with('user')->get();

        return view('parent.dashboard', compact('students'));
    }
}

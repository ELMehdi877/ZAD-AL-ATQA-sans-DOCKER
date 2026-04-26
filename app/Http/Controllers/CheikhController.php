<?php

namespace App\Http\Controllers;

use App\Models\Competition;
use App\Models\Halaqa;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheikhController extends Controller
{
    /**
     * --- ACTEUR : CHEIKH ---
     * Affiche le tableau de bord du Cheikh avec ses statistiques personnelles.
     */
    public function index()
    {
        $cheikhId = Auth::id();
        $halaqasCount = Halaqa::where('cheikh_id', $cheikhId)->count();
        
        $studentsCount = Student::whereHas('halaqas', function($query) use ($cheikhId) {
            $query->where('cheikh_id', $cheikhId)->where('memberships.statut', 'active');
        })->count();

        $evaluationsCount = \App\Models\Evaluation::where('cheikh_id', $cheikhId)->count();
        $competitionsCount = Competition::where('statut', 'active')->count();

        return view('cheikh.dashboard', compact('halaqasCount', 'studentsCount', 'evaluationsCount', 'competitionsCount'));
    }

    /**
     * --- ACTEUR : CHEIKH ---
     * Affiche l'interface de gestion en direct d'une Halaqa (Appel et Évaluations).
     */
    public function showHalaqa(Halaqa $halaqa)
    {
        if ($halaqa->cheikh_id !== Auth::id()) {
            abort(403, 'Cette Halaqa ne vous est pas attribuée.');
        }

        $students = $halaqa->students()
            ->wherePivot('statut', 'active')
            ->with('user')
            ->orderBy('id')
            ->get();

        $evaluations = $halaqa->evaluations()
            ->whereDate('created_at', now()->toDateString())
            ->get()
            ->keyBy('student_id');

        return view('cheikh.halaqas.show', compact('halaqa', 'students', 'evaluations'));
    }

    /**
     * --- ACTEUR : CHEIKH ---
     * Met à jour le niveau de mémorisation (Hifz) d'un étudiant.
     */
    public function updateEtudiantHifz(Request $request, Halaqa $halaqa, Student $student)
    {
        $request->validate([
            'nombre_hifz' => 'required|integer|min:0|max:60'
        ]);

        if ($halaqa->cheikh_id !== Auth::id()) {
            abort(403);
        }

        $student->update([
            'nombre_hifz' => $request->nombre_hifz
        ]);

        return back()->with('success', 'Niveau de Hifz mis à jour.');
    }
}

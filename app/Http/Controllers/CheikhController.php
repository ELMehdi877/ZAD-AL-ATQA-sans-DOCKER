<?php

namespace App\Http\Controllers;

use App\Models\Competition;
use App\Models\Halaqa;
use App\Models\Student;
use App\Models\Evaluation;
use App\Models\Participation;
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
        $cheikh = Auth::user();
        $cheikhId = $cheikh->id;

        // Statistiques des Halaqas
        $halaqas = Halaqa::where('cheikh_id', $cheikhId)->with('students')->get();
        $halaqasCount = $halaqas->count();
        
        // Statistiques des Étudiants
        $totalActiveStudents = Student::whereHas('halaqas', function($query) use ($cheikhId) {
            $query->where('cheikh_id', $cheikhId)->where('memberships.statut', 'active');
        })->count();

        // Statistiques des Évaluations (Halaqa)
        $evaluationsToday = Evaluation::where('cheikh_id', $cheikhId)
            ->whereDate('created_at', now()->toDateString())
            ->count();
        
        $evaluationsThisMonth = Evaluation::where('cheikh_id', $cheikhId)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        // Statistiques des Compétitions
        $competitionsEvaluated = Participation::where('cheikh_id', $cheikhId)
            ->distinct('competition_id')
            ->count();

        $participationsEvaluated = Participation::where('cheikh_id', $cheikhId)->count();

        return view('cheikh.dashboard', compact(
            'cheikh',
            'halaqas',
            'halaqasCount', 
            'totalActiveStudents', 
            'evaluationsToday', 
            'evaluationsThisMonth',
            'competitionsEvaluated',
            'participationsEvaluated'
        ));
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

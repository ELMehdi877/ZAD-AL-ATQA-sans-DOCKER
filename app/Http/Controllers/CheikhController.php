<?php

namespace App\Http\Controllers;

use App\Models\Cheikh;
use App\Http\Requests\StoreCheikhRequest;
use App\Http\Requests\StoreEvaluationRequest;
use App\Http\Requests\StoreOrUpdateEvaluationParticipationCompetitionRequest;
use App\Http\Requests\UpdateEvaluationRequest;
use App\Models\Competition;
use App\Models\Evaluation;
use App\Models\Halaqa;
use App\Models\Participation;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\HttpCache\Store;

class CheikhController extends Controller
{
    /**
     * Affiche le tableau de bord principal du Cheikh.
     */
    public function index()
    {
        $cheikh = User::findOrFail(Auth::id());

        $halaqas = $cheikh->halaqas()
            ->withCount(['students as active_students_count' => function ($query) {
                $query->where('memberships.statut', 'active');
            }])
            ->orderBy('id', 'asc')
            ->get();

        $halaqasCount = $halaqas->count();
        $totalActiveStudents = $halaqas->sum('active_students_count');

        $evaluationsToday = Evaluation::where('cheikh_id', Auth::id())
            ->whereDate('created_at', today())
            ->count();

        $evaluationsThisMonth = Evaluation::where('cheikh_id', Auth::id())
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        $competitionsEvaluated = Participation::where('cheikh_id', Auth::id())
            ->distinct('competition_id')
            ->count('competition_id');

        $participationsEvaluated = Participation::where('cheikh_id', Auth::id())
            ->whereNotNull('note_hifz')
            ->count();
        
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
     * Affiche une Halaqa spécifique pour la prise de notes/présence.
     */
    public function showHalaqa(Halaqa $halaqa)
    {
        // Verifier que la halaqa appartient au cheikh connecte
        if ($halaqa->cheikh_id !== Auth::id()) {
            abort(403);
        }

        // 1) Recuperer les etudiants de la halaqa
        $students = $halaqa->students()
            ->wherePivot('statut', 'active')
            ->with(['user', 'evaluations' => function($query) use ($halaqa) {
                    $query->where('halaqa_id', $halaqa->id)
                        ->where('cheikh_id', Auth::id())
                        ->whereDate('created_at', today())
                        ->orderBy('created_at', 'desc');
                }])
            ->orderBy('students.id', 'asc')
            ->get();

        $activeStudentIds = $students->pluck('id');

        $allEvaluations = $halaqa->evaluations()
            ->whereIn('student_id', $activeStudentIds)
            ->where('cheikh_id', Auth::id())
            ->get();

        $todayEvaluations = $halaqa->evaluations()
            ->whereIn('student_id', $activeStudentIds)
            ->where('cheikh_id', Auth::id())
            ->whereDate('created_at', today())
            ->get();

        $moyenneHalaqa = $allEvaluations->avg('note');

        $presentAujourdhuiCount = $todayEvaluations->where('presence', 'present')->count();
        $retardAujourdhuiCount = $todayEvaluations->where('presence', 'retard')->count();
        $absentAujourdhuiCount = $todayEvaluations->where('presence', 'absent')->count();
        $meilleureNoteAujourdhui = $todayEvaluations->max('note');

        return view('cheikh.halaqas.show', compact(
            'halaqa',
            'students',
            'moyenneHalaqa',
            'presentAujourdhuiCount',
            'retardAujourdhuiCount',
            'absentAujourdhuiCount',
            'meilleureNoteAujourdhui'
        ));
    }

    /**
     * Enregistre l'évaluation journalière d'un étudiant.
     */
    public function storeEvaluation(StoreEvaluationRequest $request)
    {
        $data = $request->validated();
        $data['cheikh_id'] = Auth::id();

        $halaqa = Halaqa::findOrFail($data['halaqa_id']);

        // Verifier que la halaqa appartient au cheikh connecte
        if ($halaqa->cheikh_id !== Auth::id()) {
            abort(403);
        }

        // Verifier que l'etudiant appartient bien a cette halaqa
        $studentInHalaqa = $halaqa->students()
            ->where('students.id', $data['student_id'])
            ->wherePivot('statut', 'active')
            ->exists();

        if (! $studentInHalaqa) {
            abort(403, 'Etudiant non inscrit dans cette halaqa.');
        }

        // Creer l'evaluation
        Evaluation::create($data);

        return back()->with('success', 'Evaluation enregistree avec succes.');
    }

    /**
     * Met à jour une évaluation existante.
     */
    public function updateEvaluation(UpdateEvaluationRequest $request, Evaluation $evaluation)
    {
        $data = $request->validated();

        // Verifier que l'evaluation appartient au cheikh connecte
        if($evaluation->cheikh_id !== Auth::id()) {
            abort(403);
        }

        $evaluation->update($data);

        return back()->with('success', 'Evaluation mise a jour avec succes.');
    }

    /**
     * Supprime une évaluation.
     */
    public function deleteEvaluation(Evaluation $evaluation)
    {
        // Verifier que l'evaluation appartient au cheikh connecte
        if($evaluation->cheikh_id !== Auth::id()) {
            abort(403);
        }

        $evaluation->delete();

        return back()->with('success', 'Evaluation supprimee avec succes.');
    }

    /**
     * Affiche l'historique des évaluations d'un étudiant précis.
     */
    public function historiqueEvaluationsStudent(Halaqa $halaqa, Student $student)
    {
        // Verifier que la halaqa appartient au cheikh connecte
        if ($halaqa->cheikh_id !== Auth::id()) {
            abort(403);
        }

        // Verifier que l'etudiant appartient a la halaqa
        $studentInHalaqa = $halaqa->students()
            ->where('students.id', $student->id)
            ->exists();

        if (! $studentInHalaqa) {
            abort(403, 'Etudiant non inscrit dans cette halaqa.');
        }

        // Historique des evaluations de cet etudiant faites par le cheikh connecte
        $evaluations = $student->evaluations()
            ->where('halaqa_id', $halaqa->id)
            ->where('cheikh_id', Auth::id())
            ->orderByDesc('created_at')
            ->get();

        $evaluationsByDay = $evaluations->groupBy(fn ($e) => $e->created_at->format('Y-m-d'));

        return view('cheikh.evaluations.historique', compact('student', 'halaqa', 'evaluations'));
        
    }

    /**
     * Met à jour le niveau de mémorisation (Hifz) d'un étudiant.
     */
    public function updateEtudiantHifz(Halaqa $halaqa, Student $student, Request $request)
    {
        $data = $request->validate([
            'nombre_hifz' => 'required|integer|min:0|max:60',
        ]);

        if ($halaqa->cheikh_id !== Auth::id()) {
            abort(403);
        }

        $studentInHalaqa = $halaqa->students()
            ->where('students.id', $student->id)
            ->exists();

        if (! $studentInHalaqa) {
            abort(403, 'Etudiant non inscrit dans cette halaqa.');
        }

        $student->update([
            'nombre_hifz' => $data['nombre_hifz'],
        ]);
        
        return back()->with('success', 'Nombre de hifz mis a jour avec succes.');
    }

    /**
     * Affiche l'historique de toutes les évaluations d'une Halaqa.
     */
    public function historiqueEvaluationsHalaqa(Halaqa $halaqa)
    {
        if ($halaqa->cheikh_id !== Auth::id()) {
            abort(403);
        }

        $students = $halaqa->students()
            ->with('user')
            ->orderBy('id')
            ->get();

        $evaluations = $halaqa->evaluations()
            ->where('cheikh_id', Auth::id())
            ->with(['student.user'])
            ->orderBy('created_at', 'desc')
            ->get();

        $evaluationsByDay = $evaluations->groupBy(function ($evaluation) {
            return $evaluation->created_at->format('Y-m-d');
        });

        return view('cheikh.halaqas.historique', compact('halaqa', 'students', 'evaluationsByDay'));
    }

    /**
     * Recherche dans l'historique d'une Halaqa via des filtres.
     */
    public function searchByDateOrNomOrPrenom(Halaqa $halaqa, Request $request)
    {
        $data = $request->validate([
            'date' => 'nullable|date',
            'nom' => 'nullable|string',
            'prenom' => 'nullable|string',
            'presence' => 'nullable|string',
        ]);

        if ($halaqa->cheikh_id !== Auth::id()) {
            abort(403);
        }

        $selectedDate = $data['date'] ?? null;
        $selectedNom = $data['nom'] ?? null;
        $selectedPrenom = $data['prenom'] ?? null;
        $selectedPresence = $data['presence'] ?? null;

        $students = $halaqa->students()
            ->with('user')
            ->orderBy('id')
            ->get();

        $evaluations = $halaqa->evaluations()
            ->where('cheikh_id', Auth::id())
            ->with(['student.user'])
            ->orderBy('created_at', 'desc');

        if ($selectedDate) {
            $evaluations->whereDate('created_at', $selectedDate);
        }

        if ($selectedNom || $selectedPrenom) {
            $evaluations->whereHas('student.user', function ($query) use ($selectedNom, $selectedPrenom) {
                if ($selectedNom) {
                    $query->where('nom', 'like', '%' . $selectedNom . '%'); 
                }
                if ($selectedPrenom) {
                    $query->where('prenom', 'like', '%' . $selectedPrenom . '%');
                }
            });
        }

        if ($selectedPresence) {
            $evaluations->where('presence', $selectedPresence);
        }

        $evaluations = $evaluations->get();

        $evaluationsByDay = $evaluations->groupBy(fn ($e) => $e->created_at->format('Y-m-d'));

        return view('cheikh.halaqas.historique', compact('halaqa', 'evaluationsByDay', 'selectedDate', 'selectedNom', 'selectedPrenom', 'selectedPresence', 'students'));
    }

    /**
     * Ajoute ou met à jour l'évaluation d'un étudiant pour une compétition.
     */
    public function evaluationStudentCompetition(StoreOrUpdateEvaluationParticipationCompetitionRequest $request, Competition $competition, Student $student)
    {
        $data = $request->validated();

        $evaluationParticipation = Participation::where('student_id', $student->id)
            ->where('competition_id', $competition->id)
            ->first();

        if (! $evaluationParticipation) {
            abort(404, 'Participation introuvable pour cet etudiant et cette competition.');
        }

        $data['cheikh_id'] = Auth::id();

        $cheikh = User::where('id', Auth::id())->get();


        $evaluationParticipation->update($data);

        return back()->with('success', 'Evaluation de la participation a la competition mise a jour avec succes.');

    }

    /**
     * Supprime la note/évaluation d'un étudiant dans une compétition.
     */
    public function deleteEvaluationStudentCompetition(Competition $competition, Student $student)
    {
        $evaluationParticipation = Participation::where('student_id', $student->id)
            ->where('competition_id', $competition->id)
            ->first();

        if (! $evaluationParticipation) {
            abort(404, 'Participation introuvable pour cet etudiant et cette competition.');
        }

        $evaluationParticipation->update([
            'cheikh_id' => null,
            'note_tajwid' => null,
            'note_hifz' => null,
            'remarque' => null,
        ]);

        return back()->with('success', 'Evaluation de la participation a la competition supprimee avec succes.');
    }
}

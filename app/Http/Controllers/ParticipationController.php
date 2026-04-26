<?php

namespace App\Http\Controllers;

use App\Models\Participation;
use App\Models\Student;
use App\Models\Competition;
use App\Models\User;
use App\Http\Requests\UpdateParticipationRequest;
use App\Http\Requests\StoreOrUpdateEvaluationParticipationCompetitionRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ParticipationController extends Controller
{
    /**
     * --- ACTEUR : ÉTUDIANT ---
     * Affiche la liste des compétitions auxquelles l'étudiant participe ou a participé.
     */
    public function studentParticipations()
    {
        $student = Student::where('user_id', Auth::id())->firstOrFail();
        $participations = $student->competitions()->withPivot('statut')->get();
        return view('student.participations.index', compact('participations'));
    }

    /**
     * --- ACTEUR : ÉTUDIANT ---
     * Inscrit l'étudiant à une compétition (statut 'en_attente' par défaut).
     */
    public function participate(Competition $competition)
    {
        $student = Student::where('user_id', Auth::id())->firstOrFail();
        
        if ($student->competitions()->where('competition_id', $competition->id)->exists()) {
            return back()->with('error', 'Vous avez déjà participé à cette compétition.');
        }

        $student->competitions()->syncWithoutDetaching([
            $competition->id => ['statut' => 'en_attente'],
        ]);

        return back()->with('success', 'Votre participation pour la compétition '.$competition->titre.' a été enregistrée.');
    }

    /**
     * --- ACTEUR : ÉTUDIANT ---
     * Annule l'inscription de l'étudiant à une compétition.
     */
    public function cancel(Competition $competition)
    {
        $student = Student::where('user_id', Auth::id())->firstOrFail();
        $student->competitions()->detach($competition->id);
        
        return back()->with('success', 'Votre participation pour la compétition '.$competition->titre .' a été annulée.');
    }

    /**
     * --- ACTEUR : ÉTUDIANT ---
     * Affiche ses propres notes et remarques obtenues lors d'une compétition.
     */
    public function showStudentEvaluation(Competition $competition)
    {
        $student = Student::where('user_id', Auth::id())->firstOrFail();
        
        $participation = Participation::where('competition_id', $competition->id)
            ->where('student_id', $student->id)
            ->with('cheikh')
            ->firstOrFail();

        return view('student.competitions.evaluation', compact('student', 'competition', 'participation'));
    }

    /**
     * --- ACTEUR : PARENT ---
     * Affiche la liste des compétitions et statuts de participation d'un enfant.
     */
    public function showChildCompetitions(Student $student)
    {
        if ($student->parent_id !== Auth::id()) { 
            abort(403, 'Action non autorisée.'); 
        }

        $competitions = $student->competitions()->withPivot('statut')->get();
        return view('parent.children.competitions', compact('student', 'competitions'));
    }

    /**
     * --- ACTEUR : PARENT ---
     * Affiche les notes de compétition détaillées (Hifz/Tajwid) d'un enfant.
     */
    public function showChildParticipations(Student $student, Competition $competition)
    {
        if ($student->parent_id !== Auth::id()) { 
            abort(403, 'Action non autorisée.'); 
        }

        $participation = Participation::where('student_id', $student->id)
            ->where('competition_id', $competition->id)
            ->with('cheikh')
            ->firstOrFail();

        return view('parent.children.participations', compact('student', 'participation', 'competition'));
    }

    /**
     * --- ACTEUR : CHEIKH ---
     * Évalue la prestation d'un étudiant lors d'une compétition (Notes et Remarques).
     */
    public function evaluateStudent(StoreOrUpdateEvaluationParticipationCompetitionRequest $request, Competition $competition, Student $student)
    {
        $data = $request->validated();
        
        $participation = Participation::where('student_id', $student->id)
            ->where('competition_id', $competition->id)
            ->firstOrFail();

        $data['cheikh_id'] = Auth::id();
        $participation->update($data);

        return back()->with('success', 'Évaluation de la compétition mise à jour avec succès.');
    }

    /**
     * --- ACTEUR : CHEIKH ---
     * Supprime les notes d'évaluation d'un étudiant pour une compétition donnée.
     */
    public function deleteEvaluation(Competition $competition, Student $student)
    {
        $participation = Participation::where('student_id', $student->id)
            ->where('competition_id', $competition->id)
            ->firstOrFail();

        $participation->update([
            'cheikh_id' => null,
            'note_tajwid' => null,
            'note_hifz' => null,
            'remarque' => null,
        ]);

        return back()->with('success', 'Évaluation de la compétition supprimée.');
    }

    /**
     * --- ACTEUR : ADMIN ---
     * Affiche la liste globale de toutes les participations du site pour modération.
     */
    public function index()
    {
        $participations = Participation::with(['student.user', 'cheikh', 'competition'])->orderBy('id', 'asc')->get();
        return view('participations.index', compact('participations'));
    }

    /**
     * --- ACTEUR : ADMIN ---
     * Valide ou refuse une demande de participation d'un étudiant à une compétition.
     */
    public function acceptParticipation(UpdateParticipationRequest $request, Participation $participation)
    {
        $data = $request->validated();
        $statut = $data['statut'];

        if ($statut === 'en_attente') {
            $participation->update(['statut' => 'en_attente']);
        } elseif ($statut === 'valide') {
            $participation->update(['statut' => 'valide']);
        } elseif ($statut === 'refuse') {
            $participation->update(['statut' => 'refuse']);
        }

        return back()->with('success', 'Le statut de la participation a été mis à jour.');
    }
}

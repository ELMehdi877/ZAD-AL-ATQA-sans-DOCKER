<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEvaluationRequest;
use App\Http\Requests\UpdateEvaluationRequest;
use App\Models\Evaluation;
use App\Models\Halaqa;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EvaluationController extends Controller
{
    /**
     * --- ACTEUR : PARENT ---
     * Affiche les évaluations d'un enfant spécifique pour une Halaqa donnée.
     */
    public function showChildEvaluations(Student $student, Halaqa $halaqa)
    {
        if ($student->parent_id !== Auth::id()) {
            abort(403, 'Action non autorisée sur cet étudiant.');
        }

        $evaluations = $student->evaluations()
            ->where('halaqa_id', $halaqa->id)
            ->with('cheikh')
            ->orderByDesc('created_at')
            ->get();

        $evaluationsByDay = $evaluations->groupBy(fn($e) => $e->created_at->format('Y-m-d'));

        return view('parent.children.evaluations', compact('student', 'halaqa', 'evaluationsByDay'));
    }

    /**
     * --- ACTEUR : PARENT ---
     * Affiche l'historique complet de toutes les évaluations (toutes halaqas confondues) d'un enfant.
     */
    public function showChildEvaluationsHistorique(Student $student)
    {
        if ($student->parent_id !== Auth::id()) {
            abort(403, 'Action non autorisée sur cet étudiant.');
        }

        $evaluations = $student->evaluations()
            ->with(['cheikh', 'halaqa'])
            ->orderByDesc('created_at')
            ->get();
        
        $evaluationsByDay = $evaluations->groupBy(fn($e) => $e->created_at->format('Y-m-d'));

        return view('parent.children.evaluations_historique', compact('student', 'evaluationsByDay'));
    }

    /**
     * --- ACTEUR : PARENT ---
     * Recherche filtrée (sourate/date) dans les évaluations d'un enfant au sein d'une Halaqa.
     */
    public function searchChildEvaluations(Request $request, Student $student, Halaqa $halaqa)
    {
        if ($student->parent_id !== Auth::id()) {
            abort(403);
        }

        $data = $request->validate([
            'du_sourate' => 'nullable|string|max:255',
            'date' => 'nullable|date',
        ]);

        $evaluations = $student->evaluations()
            ->where('halaqa_id', $halaqa->id)
            ->with('cheikh')
            ->orderByDesc('created_at');

        if ($data['du_sourate'] ?? null) {
            $evaluations->where('du_sourate', 'like', '%' . $data['du_sourate'] . '%');
        }

        if ($data['date'] ?? null) {
            $evaluations->whereDate('created_at', $data['date']);
        }

        $evaluationsByDay = $evaluations->get()->groupBy(fn($e) => $e->created_at->format('Y-m-d'));

        return view('parent.children.evaluations', compact('student', 'halaqa', 'evaluationsByDay'))
            ->with(['date' => $data['date'] ?? null, 'du_sourate' => $data['du_sourate'] ?? null]);
    }

    /**
     * --- ACTEUR : PARENT ---
     * Recherche filtrée (sourate/date) dans l'historique global des évaluations d'un enfant.
     */
    public function searchChildEvaluationsHistorique(Request $request, Student $student)
    {
        if ($student->parent_id !== Auth::id()) {
            abort(403);
        }

        $data = $request->validate([
            'du_sourate' => 'nullable|string|max:255',
            'date' => 'nullable|date',
        ]);

        $evaluations = $student->evaluations()
            ->with(['cheikh', 'halaqa'])
            ->orderByDesc('created_at');

        if ($data['du_sourate'] ?? null) {
            $evaluations->where('du_sourate', 'like', '%' . $data['du_sourate'] . '%');
        }

        if ($data['date'] ?? null) {
            $evaluations->whereDate('created_at', $data['date']);
        }

        $evaluationsByDay = $evaluations->get()->groupBy(fn($e) => $e->created_at->format('Y-m-d'));

        return view('parent.children.evaluations_historique', compact('student', 'evaluationsByDay'))
            ->with(['date' => $data['date'] ?? null, 'du_sourate' => $data['du_sourate'] ?? null]);
    }

    /**
     * --- ACTEUR : ÉTUDIANT ---
     * Affiche l'historique global de toutes ses propres évaluations.
     */
    public function historiqueForStudent()
    {
        $student = Student::where('user_id', Auth::id())->firstOrFail();

        $halaqas = $student->halaqas()
            ->withPivot('statut')
            ->with('cheikh')
            ->orderBy('memberships.updated_at', 'desc')
            ->get();

        $evaluations = $student->evaluations()
            ->with(['cheikh', 'halaqa'])
            ->orderBy('created_at', 'desc')
            ->get();

        $evaluationsByHalaqa = $evaluations->groupBy('halaqa_id');

        return view('student.evaluations.historique', compact('student', 'halaqas', 'evaluationsByHalaqa'));
    }

    /**
     * --- ACTEUR : ÉTUDIANT ---
     * Recherche filtrée (sourate/date) dans son propre historique d'évaluations.
     */
    public function searchForStudent(Request $request)
    {
        $student = Student::where('user_id', Auth::id())->firstOrFail();

        $data = $request->validate([
            'du_sourate' => 'nullable|string|max:255',
            'date' => 'nullable|date',
        ]);

        $du_sourate = $data['du_sourate'] ?? null;
        $date = $data['date'] ?? null;

        $evaluations = $student->evaluations()
            ->with(['cheikh', 'halaqa'])
            ->orderBy('created_at', 'desc');

        if ($du_sourate) {
            $evaluations->where('du_sourate', 'like', '%' . $du_sourate . '%');
        }

        if ($date) {
            $evaluations->whereDate('created_at', $date);
        }

        $evaluations = $evaluations->get();
        $evaluationsByDate = $evaluations->groupBy(fn($e) => $e->created_at->format('Y-m-d'));

        return view('student.evaluations.historique', compact('student', 'evaluationsByDate', 'date', 'du_sourate'));
    }

    /**
     * --- ACTEUR : CHEIKH ---
     * Enregistre l'évaluation journalière d'un étudiant dans une Halaqa donnée.
     */
    public function store(StoreEvaluationRequest $request)
    {
        $data = $request->validated();
        $data['cheikh_id'] = Auth::id();

        $halaqa = Halaqa::findOrFail($data['halaqa_id']);

        if ($halaqa->cheikh_id !== Auth::id()) {
            abort(403, 'Vous ne pouvez noter que vos propres halaqas.');
        }

        $studentInHalaqa = $halaqa->students()
            ->where('students.id', $data['student_id'])
            ->wherePivot('statut', 'active')
            ->exists();

        if (!$studentInHalaqa) {
            abort(403, 'Étudiant non inscrit dans cette halaqa.');
        }

        Evaluation::create($data);

        return back()->with('success', 'Évaluation enregistrée avec succès.');
    }

    /**
     * --- ACTEUR : CHEIKH ---
     * Met à jour une évaluation existante (notes, sourates, présence).
     */
    public function update(UpdateEvaluationRequest $request, Evaluation $evaluation)
    {
        $data = $request->validated();

        if ($evaluation->cheikh_id !== Auth::id()) {
            abort(403, 'Vous ne pouvez modifier que vos propres évaluations.');
        }

        $evaluation->update($data);

        return back()->with('success', 'Évaluation mise à jour avec succès.');
    }

    /**
     * --- ACTEUR : CHEIKH ---
     * Supprime définitivement une évaluation.
     */
    public function destroy(Evaluation $evaluation)
    {
        if ($evaluation->cheikh_id !== Auth::id()) {
            abort(403, 'Vous ne pouvez supprimer que vos propres évaluations.');
        }

        $evaluation->delete();

        return back()->with('success', 'Évaluation supprimée avec succès.');
    }

    /**
     * --- ACTEUR : CHEIKH ---
     * Affiche l'historique des évaluations d'un étudiant précis au sein d'une Halaqa.
     */
    public function historiqueStudent(Halaqa $halaqa, Student $student)
    {
        if ($halaqa->cheikh_id !== Auth::id()) {
            abort(403);
        }

        $studentInHalaqa = $halaqa->students()
            ->where('students.id', $student->id)
            ->exists();

        if (!$studentInHalaqa) {
            abort(403, 'Étudiant non inscrit dans cette halaqa.');
        }

        $evaluations = $student->evaluations()
            ->where('halaqa_id', $halaqa->id)
            ->where('cheikh_id', Auth::id())
            ->orderByDesc('created_at')
            ->get();

        return view('cheikh.evaluations.historique', compact('student', 'halaqa', 'evaluations'));
    }

    /**
     * --- ACTEUR : CHEIKH ---
     * Affiche l'historique global de toutes les évaluations effectuées dans une Halaqa donnée.
     */
    public function historiqueHalaqa(Halaqa $halaqa)
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

        $evaluationsByDay = $evaluations->groupBy(fn($e) => $e->created_at->format('Y-m-d'));

        return view('cheikh.halaqas.historique', compact('halaqa', 'students', 'evaluationsByDay'));
    }

    /**
     * --- ACTEUR : CHEIKH ---
     * Recherche filtrée (nom/sourate/date/présence) dans l'historique d'une Halaqa.
     */
    public function search(Halaqa $halaqa, Request $request)
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

        $query = $halaqa->evaluations()
            ->where('cheikh_id', Auth::id())
            ->with(['student.user'])
            ->orderBy('created_at', 'desc');

        if ($selectedDate) {
            $query->whereDate('created_at', $selectedDate);
        }

        if ($selectedNom || $selectedPrenom) {
            $query->whereHas('student.user', function ($q) use ($selectedNom, $selectedPrenom) {
                if ($selectedNom) {
                    $q->where('nom', 'like', '%' . $selectedNom . '%');
                }
                if ($selectedPrenom) {
                    $q->where('prenom', 'like', '%' . $selectedPrenom . '%');
                }
            });
        }

        if ($selectedPresence) {
            $query->where('presence', $selectedPresence);
        }

        $evaluations = $query->get();
        $evaluationsByDay = $evaluations->groupBy(fn($e) => $e->created_at->format('Y-m-d'));

        return view('cheikh.halaqas.historique', compact(
            'halaqa', 
            'evaluationsByDay', 
            'selectedDate', 
            'selectedNom', 
            'selectedPrenom', 
            'selectedPresence', 
            'students'
        ));
    }
}

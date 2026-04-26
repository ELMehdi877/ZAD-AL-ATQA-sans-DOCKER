<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreHalaqaRequest;
use App\Http\Requests\UpdateHalaqaRequest;
use App\Models\Halaqa;
use App\Models\Membership;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HalaqaController extends Controller
{
    /**
     * --- ACTEUR : ÉTUDIANT ---
     * Affiche la Halaqa active à laquelle l'étudiant appartient actuellement.
     */
    public function currentHalaqa()
    {
        $student = Student::where('user_id', Auth::id())->firstOrFail();

        $halaqas = $student->halaqas()
            ->wherePivot('statut', 'active')
            ->with('cheikh')
            ->orderBy('halaqas.id', 'desc')
            ->get();

        $evaluationsByDate = collect();

        if ($halaqas->isNotEmpty()) {
            $halaqaIds = $halaqas->pluck('id');

            $evaluations = $student->evaluations()
                ->whereIn('halaqa_id', $halaqaIds)
                ->with('halaqa')
                ->orderByDesc('created_at')
                ->get();

            $evaluationsByDate = $evaluations->groupBy(fn($evaluation) => $evaluation->created_at->format('Y-m-d'));
        }

        return view('student.halaqas.current', compact('student', 'halaqas', 'evaluationsByDate'));
    }

    /**
     * --- ACTEUR : ÉTUDIANT ---
     * Recherche (sourate/date) au sein de sa Halaqa actuelle.
     */
    public function searchCurrentHalaqa(Halaqa $halaqa, Request $request)
    {
        $student = Student::where('user_id', Auth::id())->firstOrFail();
        
        if (!$student->halaqas()->where('halaqa_id', $halaqa->id)->exists()) {
            abort(403, 'Action non autorisée.');
        }

        $halaqas = $student->halaqas()
            ->wherePivot('statut', 'active')
            ->with('cheikh')
            ->orderBy('halaqas.id', 'desc')
            ->get();

        $data = $request->validate([
            'du_sourate' => 'nullable|string|max:255',
            'date' => 'nullable|date',
        ]);

        $du_sourate = $data['du_sourate'] ?? null;
        $date = $data['date'] ?? null;

        $evaluations = $student->evaluations()
            ->where('halaqa_id', $halaqa->id)
            ->with('cheikh')
            ->orderByDesc('created_at');

        if ($du_sourate) {
            $evaluations->where('du_sourate', 'like', '%' . $du_sourate . '%');
        }

        if ($date) {
            $evaluations->whereDate('created_at', $date);
        }

        $evaluations = $evaluations->get();
        $evaluationsByDate = $evaluations->groupBy(fn($e) => $e->created_at->format('Y-m-d'));

        $search_results = $evaluations->isEmpty();

        return view('student.halaqas.current', compact('student', 'halaqas', 'evaluationsByDate', 'date', 'du_sourate', 'search_results'));
    }

    /**
     * --- ACTEUR : PARENT ---
     * Affiche les Halaqas suivies par un enfant spécifique.
     */
    public function showChildHalaqas(Student $student)
    {
        if ($student->parent_id !== Auth::id()) {
            abort(403, 'Action non autorisée.');
        }

        $halaqas = $student->halaqas()->with('cheikh')->get();

        return view('parent.children.halaqas', compact('student', 'halaqas'));
    }

    /**
     * --- ACTEUR MULTIPLE (ADMIN / CHEIKH / ÉTUDIANT) ---
     * Affiche la liste des Halaqas selon le rôle de l'utilisateur connecté.
     */
    public function index()
    {
        $user = Auth::user();

        // Si Admin : voit toutes les halaqas du système
        if ($user?->role === 'admin') {
            $halaqas = Halaqa::with(['students'=> function($query){
                $query->wherePivot('statut', 'active');
            }])
            ->orderBy('id', 'asc')
            ->get();

            return view('admin.halaqas.index', compact('halaqas'));
        }

        // Si Cheikh : voit uniquement les halaqas qu'il dirige
        if ($user?->role === 'cheikh') {
            $halaqas = Halaqa::with(['students' => function ($query) {
                $query->wherePivot('statut', 'active');
            }])
                ->where('cheikh_id', $user->id)
                ->orderBy('id', 'asc')
                ->get();

            return view('cheikh.halaqas.index', compact('halaqas'));
        }

        // Si Étudiant : voit la liste de ses halaqas passées et présentes
        if ($user?->role === 'student') {
            $student = Student::where('user_id', $user->id)->first();
            $halaqas = $student->halaqas()
                ->with('cheikh')
                ->orderBy('halaqas.id', 'asc')
                ->get();

            return view('student.halaqas.all', compact('student', 'halaqas'));
        }
    }

    /**
     * --- ACTEUR : ADMIN ---
     * Formulaire de création d'une nouvelle Halaqa.
     */
    public function createHalaqaPage()
    {
        $cheikhs = User::where('role', 'cheikh')->orderBy('id', 'asc')->get();

        $students = Student::with('user')
            ->whereDoesntHave('halaqas', function ($query) {
                $query->where('memberships.statut', 'active');
            })
            ->orderBy('id', 'asc')
            ->get();

        return view('admin.halaqas.create', compact('cheikhs', 'students'));
    }

    /**
     * --- ACTEUR : ADMIN ---
     * Enregistre la Halaqa et affecte les premiers étudiants.
     */
    public function store(StoreHalaqaRequest $request)
    {
        $data = $request->validated();

        if (isset($data['students']) && count($data['students']) > $data['capacite']) {
            return redirect()->route('halaqas.create')
                ->with('error', 'La capacité de la Halaqa ' . $data['nom_halaqa'] . ' est dépassée !');
        }

        $halaqa = Halaqa::create($data);
        $halaqa->students()->attach($data['students'] ?? []);

        return redirect()->route('halaqas.index')
            ->with('success', 'Nouvelle Halaqa ' . $halaqa->nom_halaqa . ' créée !');
    }

    /**
     * --- ACTEUR : ADMIN ---
     * Affiche les détails d'une Halaqa (Cheikh, liste des élèves).
     */
    public function show(Halaqa $halaqa)
    {
        $halaqa->load(['cheikh', 'students.user']);
        return view('admin.halaqas.show', compact('halaqa'));
    }

    /**
     * --- ACTEUR : ADMIN ---
     * Formulaire d'édition.
     */
    public function edit(Halaqa $halaqa)
    {
        $cheikhs = User::where('role', 'cheikh')->orderBy('id', 'asc')->get();

        $students = Membership::with('student.user')
            ->where('halaqa_id', $halaqa->id)
            ->where('statut', 'active')
            ->orderBy('student_id', 'asc')
            ->get()
            ->pluck('student');

        $studentsNotInHalaqa = Student::with('user')
            ->whereDoesntHave('halaqas', function($query){
                $query->where('memberships.statut', 'active');
            })
            ->orderBy('id', 'asc')
            ->get();

        return view('admin.halaqas.edit', compact('halaqa', 'cheikhs', 'students', 'studentsNotInHalaqa'));
    }

    /**
     * --- ACTEUR : ADMIN ---
     * Met à jour la Halaqa et gère les inscriptions/désinscriptions.
     */
    public function update(UpdateHalaqaRequest $request, Halaqa $halaqa)
    {
        $data = $request->validated();
        $selectedStudents = $data['students'] ?? [];
        unset($data['students']);

        $halaqa->update($data);

        if (count($selectedStudents) > $halaqa->capacite) {
            return redirect()->route('halaqas.edit', $halaqa)
                ->with('error', 'La capacité de la Halaqa ' . $halaqa->nom_halaqa . ' est dépassée !');
        }

        // Désactivation des membres actuels
        $studentsInHalaqa = $halaqa->students()->pluck('students.id');
        foreach ($studentsInHalaqa as $studentId) {
            $halaqa->students()->updateExistingPivot($studentId, ['statut' => 'inactive']);
        }

        // Activation des nouveaux membres choisis
        foreach ($selectedStudents as $studentId) {
            $halaqa->students()->syncWithoutDetaching([$studentId => ['statut' => 'active']]);
        }

        return redirect()->route('halaqas.index')
            ->with('success', 'Halaqa ' . $halaqa->nom_halaqa . ' modifiée !');
    }

    /**
     * --- ACTEUR : ADMIN ---
     * Supprime une Halaqa.
     */
    public function destroy(Halaqa $halaqa)
    {
        try {
            $halaqa->delete();
        } catch (\Exception $e) {
            return redirect()->route('halaqas.index')
                ->with('error', 'Impossible de supprimer cette Halaqa car elle possède des dépendances.');
        }

        return redirect()->route('halaqas.index')
            ->with('success', 'Halaqa ' . $halaqa->nom_halaqa . ' supprimée !');
    }
}

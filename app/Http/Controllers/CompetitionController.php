<?php

namespace App\Http\Controllers;

use App\Models\Competition;
use App\Http\Requests\StoreCompetitionRequest;
use App\Http\Requests\UpdateCompetitionRequest;
use App\Models\Participation;
use App\Models\Student;
use Illuminate\Support\Facades\Auth;

class CompetitionController extends Controller
{
    /**
     * Affiche la liste des compétitions.
     */
    public function index()
    {
        $user = Auth::user();


        if ($user?->role === 'cheikh' || $user?->role === 'admin') {
            $competitions = Competition::orderBy('id', 'asc')->get();

            return view('competitions.index', compact('competitions'));
        }

        if ($user?->role === 'student') {
            $competitions = Competition::where('statut', 'active')
            ->orderBy('id', 'asc')
            ->get();
            return view('student.competitions', compact('competitions'));
        }
    }

    /**
     * Affiche le formulaire de création d'une compétition.
     */
    public function create()
    {
        $students = Student::with('user')
            ->whereDoesntHave('competitions', function ($query) {
                $query->where('participations.statut', 'valide');
            })
            ->orderBy('id', 'asc')
            ->get();
        return view('admin.competitions.create', compact('students'));
    }

    /**
     * Enregistre une nouvelle compétition en base de données.
     */
    public function store(StoreCompetitionRequest $request)
    {
        $data = $request->validated();

        $competition = Competition::create($data);

        if (!empty($data['students'])) {
            $competition->students()->attach($data['students'], ['statut' => 'valide']);
        }
        
        return redirect()->route('competitions.index')
            ->with('success', 'Nouvelle compétition ' . $competition->titre . ' créée !');
    }

    /**
     * Affiche les détails d'une compétition spécifique.
     */
    public function show(Competition $competition)
    {
        $competition->load(['students.user']);

        $participationsByStudent = Participation::where('competition_id', $competition->id)
            ->with('cheikh')
            ->get()
            ->keyBy('student_id');

        return view('competitions.show', compact('competition', 'participationsByStudent'));
    }

    /**
     * Affiche le formulaire pour modifier une compétition.
     */
    public function edit(Competition $competition)
    {
        $students = Student::with('user')
            ->whereHas('competitions', function ($query) use ($competition) {
                $query->where('participations.competition_id', $competition->id);
            })
            ->orderBy('id', 'asc')
            ->get();

        $studentsNotInCompetition = Student::with('user')
            ->whereDoesntHave('competitions', function ($query) use ($competition) {
                $query->where('participations.competition_id', $competition->id);
            })
            ->orderBy('id', 'asc')
            ->get();
        
        return view('admin.competitions.edit', compact('competition', 'students', 'studentsNotInCompetition'));
    }

    /**
     * Met à jour une compétition existante.
     */
    public function update(UpdateCompetitionRequest $request, Competition $competition)
    {
        $data = $request->validated();
        $competition->update($data);
        $competition->students()->syncWithPivotValues($data['students'] ?? [], ['statut' => 'valide']);

        return redirect()->route('competitions.index')
            ->with('success', 'Compétition ' . $competition->titre . ' modifiée !');
    }

    /**
     * Change le statut d'une compétition (active/inactive).
     */
    public function statusCompetition(Competition $competition)
    {
        if ($competition->statut === 'active') {
            $competition->update([
                'statut' => 'inactive'
            ]);
        }
        else {
            $competition->update([
                'statut' => 'active'
            ]);
        }

        return redirect()->route('competitions.index')
            ->with('success', 'Compétition ' . $competition->titre . ' a un statut '.$competition->statut);
    }
}

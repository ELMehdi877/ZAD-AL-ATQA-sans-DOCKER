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
     * Display a listing of the resource.
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
     * Show the form for creating a new resource.
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
     * Store a newly created resource in storage.
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
     * Display the specified resource.
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
     * Show the form for editing the specified resource.
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
     * Update the specified resource in storage.
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
     * Remove the specified resource from storage.
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

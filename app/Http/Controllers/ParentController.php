<?php

namespace App\Http\Controllers;

use App\Models\Parente;
use App\Http\Requests\StoreParenteRequest;
use App\Http\Requests\UpdateParenteRequest;
use App\Models\Competition;
use App\Models\Halaqa;
use App\Models\Participation;
use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ParentController extends Controller
{
    public function currentParent(): User
    {
        return User::findOrFail(Auth::id());
    }
    /**
     * Affiche le tableau de bord avec les enfants du parent.
     */
    public function index()
    {
        $parent = $this->currentParent();

        $students = Student::where('parent_id', $parent->id)->with('user')->get();



        return view('parent.dashboard', compact('students'));
    }

    public function showchildHalaqas(Student $student)
    {
        $parent = $this->currentParent();
        if ($student->parent_id !== $parent->id) {
            abort(403, 'Unauthorized action.');
        }

        $halaqas = $student->halaqas()->with('cheikh')->get();

        return view('parent.children.halaqas', compact('student', 'halaqas'));
    }

    public function showchildEvaluations(Student $student, Halaqa $halaqa)
    {
        $parent = $this->currentParent();
        if ($student->parent_id !== $parent->id) {
            abort(403, 'Unauthorized action.');
        }

        $evaluations = $student->evaluations()
            ->where('halaqa_id', $halaqa->id)
            ->with('cheikh')
            ->orderByDesc('created_at')
            ->get();

        $evaluationsByDay = $evaluations->groupBy(fn ($evaluation) => $evaluation->created_at->format('Y-m-d'));

        return view('parent.children.evaluations', compact('student', 'halaqa', 'evaluationsByDay'));
    }

    public function showchildEvaluationsHistorique(Student $student)
    {
        $parent = $this->currentParent();
        if ($student->parent_id !== $parent->id) {
            abort(403, 'Unauthorized action.');
        }

        $evaluations = $student->evaluations()
            ->with(['cheikh', 'halaqa'])
            ->orderByDesc('created_at')
            ->get();
        
        $evaluationsByDay = $evaluations->groupBy(fn ($evaluation) => $evaluation->created_at->format('Y-m-d'));

        return view('parent.children.evaluations_historique', compact('student', 'evaluationsByDay'));
    }


    public function showchildCompetitions(Student $student)
    {
        $parent = $this->currentParent();
        if ($student->parent_id !== $parent->id) {
            abort(403, 'Unauthorized action.');
        }

        $competitions = $student->competitions()->withPivot('statut')->get();

        return view('parent.children.competitions', compact('student', 'competitions'));
    }

    public function showchildParticipations(Student $student,Competition $competition)
    {
        $parent = $this->currentParent();
        if ($student->parent_id !== $parent->id) {
            abort(403, 'Unauthorized action.');
        }

        $participation = Participation::where('student_id', $student->id)
            ->where('competition_id', $competition->id)
            ->with('cheikh')
            ->first();

        return view('parent.children.participations', compact('student', 'participation', 'competition'));
    }

    public function searchChildEvaluationsBySourateOrDate(Request $request, Student $student, Halaqa $halaqa )
    {
        $parent = $this->currentParent();
        if ($student->parent_id !== $parent->id) {
            abort(403, 'Unauthorized action.');
        }

        $data = $request->validate([
            'du_sourate' => 'nullable|string|max:255',
            'date' => 'nullable|date',
        ]);

        $du_sourate = $data['du_sourate'] ?? null;
        $date = $data['date'] ?? null;

            
        $evaluations = $student->evaluations()
            ->where('halaqa_id', $halaqa?->id)
            ->with('cheikh')
            ->orderByDesc('created_at');
        

        if ($du_sourate) {
            $evaluations->where('du_sourate', 'like', '%' . $du_sourate . '%');
        }

        if ($date) {
            $evaluations->whereDate('created_at', $date);
        }

        $evaluations = $evaluations->get();

        $evaluationsByDay = $evaluations->groupBy(fn ($e) => $e->created_at->format('Y-m-d'));


        return view('parent.children.evaluations', compact('student', 'halaqa', 'evaluationsByDay', 'date', 'du_sourate'));

    }

    public function searchChildEvaluationsHistoriqueBySourateOrDate(Request $request, Student $student)
    {
        $parent = $this->currentParent();
        if ($student->parent_id !== $parent->id) {
            abort(403, 'Unauthorized action.');
        }

        $data = $request->validate([
            'du_sourate' => 'nullable|string|max:255',
            'date' => 'nullable|date',
        ]);

        $du_sourate = $data['du_sourate'] ?? null;
        $date = $data['date'] ?? null;

            
        $evaluations = $student->evaluations()
            ->with(['cheikh', 'halaqa'])
            ->orderByDesc('created_at');
        

        if ($du_sourate) {
            $evaluations->where('du_sourate', 'like', '%' . $du_sourate . '%');
        }

        if ($date) {
            $evaluations->whereDate('created_at', $date);
        }

        $evaluations = $evaluations->get();

        $evaluationsByDay = $evaluations->groupBy(fn ($e) => $e->created_at->format('Y-m-d'));

        return view('parent.children.evaluations_historique', compact('student', 'evaluationsByDay', 'date', 'du_sourate'));
    
    }

}

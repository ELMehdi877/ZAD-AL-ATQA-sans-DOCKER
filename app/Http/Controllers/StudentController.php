<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Competition;
use App\Models\Halaqa;
use App\Models\Participation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    /**
     * Get the current authenticated student's record.
     */
    private function currentStudent(): Student
    {
        return Student::where('user_id', Auth::id())->firstOrFail();
    }

    /**
     * Show all participations.
     */
    public function participations()
    {
        $student = $this->currentStudent();
        
        $participations = $student->competitions()->withPivot('statut')->get();

        return view('student.participations', compact('participations'));
    }

    /**
     * Show all available competitions.
     */
    public function competitions()
    {
        $competitions = Competition::where('statut', 'active')->get();

        return view('student.competitions', compact('competitions'));
    }


    /**
     * Cancel participation in a competition.
     */
    public function cancelParticipation (Competition $competition)
    {
        $student = $this->currentStudent();

        $student->competitions()->detach($competition->id);

        return back()->with('success', 'Votre participation pour la competition '.$competition->titre .' a ete annulee.');
    }
    

    /**
     * Participate in a competition.
     */
    public function participateCompetition (Competition $competition)
    {

        $student = $this->currentStudent();

        if ($student->competitions()->where('competition_id', $competition->id)->exists()) {
            return back()->with('error', 'Vous avez déjà participé à cette compétition.');
        }

        $student->competitions()->syncWithoutDetaching([
            $competition->id => ['statut' => 'en_attente'],
        ]);

        return back()->with('success', 'Votre participation pour la competition '.$competition->titre.' a ete enregistree et est en attente de validation.');

    }

    /**
     * Show the current active halaqa for the student.
     */
    public function currentHalaqa()
    {
        $student = $this->currentStudent();

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

            $evaluationsByDate = $evaluations->groupBy(fn ($evaluation) => $evaluation->created_at->format('Y-m-d'));
        }
        
        return view('student.halaqas.current', compact('student', 'halaqas', 'evaluationsByDate'));
    }

    public function searchBySourateOrdateCurrentHalaqa(Halaqa $halaqa, Request $request)
    {
        $student = $this->currentStudent();
        if (! $student->halaqas()->where('halaqa_id', $halaqa->id)->exists()) {
             abort(403, 'Unauthorized action.');
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

        $evaluationsByDate = $evaluations->groupBy(fn ($e) => $e->created_at->format('Y-m-d'));

        if ($evaluations->isEmpty()) {
            $search_results = true;
        }
        else {
            $search_results = false;
        }

        return view('student.halaqas.current', compact('student', 'halaqas', 'evaluationsByDate', 'date', 'du_sourate', 'search_results'));
            
    }

    /**
     * Show the history of evaluations for the student.
     */
    public function historiqueEvaluations()
    {
        $student = $this->currentStudent();

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

    public function searchBySourateOrdate(Request $request)
    {
        $student = $this->currentStudent();

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

        $evaluationsByDate = $evaluations->groupBy(fn ($e) => $e->created_at->format('Y-m-d'));

        return view('student.evaluations.historique', compact('student', 'evaluationsByDate', 'date', 'du_sourate'));
    }

    public function showCompetitionEvaluation(Competition $competition)
    {
        $student = $this->currentStudent();

        $participation = Participation::where('competition_id', $competition->id)
            ->where('student_id', $student->id)
            ->with('cheikh')
            ->first();

        if (!$participation) {
            abort(404);
        }

        return view('student.competitions.evaluation', compact('student', 'competition', 'participation'));
    }
}

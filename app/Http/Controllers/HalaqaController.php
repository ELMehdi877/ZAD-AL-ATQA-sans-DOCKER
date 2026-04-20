<?php

namespace App\Http\Controllers;

use App\Models\Halaqa;
use App\Http\Requests\StoreHalaqaRequest;
use App\Http\Requests\UpdateHalaqaRequest;
use App\Models\Membership;
use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class HalaqaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();

        if ($user?->role === 'admin') {

            $halaqas = Halaqa::with(['students'=> function($query){
                $query->wherePivot('statut', 'active');
            }])
            ->orderBy('id', 'asc')
            ->get();

            return view('admin.halaqas.index', compact('halaqas'));
        }

        if ($user?->role === 'cheikh') {

            $halaqas = Halaqa::with(['students' => function ($query) {
                $query->wherePivot('statut', 'active');
            }])
                ->where('cheikh_id', $user->id)
                ->orderBy('id', 'asc')
                ->get();

            return view('cheikh.halaqas.index', compact('halaqas'));
        }

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
     * Show the form for creating a new resource.
     */
    public function createHalaqaPage()
    {
        $cheikhs = User::where('role', 'cheikh')
            ->orderBy('id', 'asc')
            ->get();

        $students = Student::with('user')
            ->whereDoesntHave('halaqas', function ($query) {
                $query->where('memberships.statut', 'active');
            })
            ->orderBy('id', 'asc')
            ->get();

        return view('admin.halaqas.create', compact('cheikhs', 'students'));
    }

    /**
     * Store a newly created resource in storage.
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
            ->with('success', 'Nouvelle Halaqa ' . $halaqa->nom_halaqa . ' créé !');
    }

    /**
     * Display the specified resource.
     */
    public function show(Halaqa $halaqa)
    {
        $halaqa->load(['cheikh', 'students.user']);

        return view('admin.halaqas.show', compact('halaqa'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Halaqa $halaqa)
    {
        $cheikhs = User::where('role', 'cheikh')
            ->orderBy('id', 'asc')
            ->get();

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
     * Update the specified resource in storage.
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

        // 1) On met tous les membres actuels en inactif.
        $studentsInHalaqa = $halaqa->students()->pluck('students.id');
        foreach ($studentsInHalaqa as $studentId) {
            $halaqa->students()->updateExistingPivot($studentId, [
                'statut' => 'inactive',
            ]);
        }

        // 2) On active les étudiants choisis (attach si absent, update si déjà présent).
        foreach ($selectedStudents as $studentId) {
            $halaqa->students()->syncWithoutDetaching([
                $studentId => ['statut' => 'active'],
            ]);
        }

        return redirect()->route('halaqas.index')
            ->with('success', 'Halaqa ' . $halaqa->nom_halaqa . ' modifié !');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Halaqa $halaqa)
    {
        try {
            $halaqa->delete();
        } catch (\Exception $e) {
            return redirect()->route('halaqas.index')
                ->with('error', 'Impossible de supprimer la Halaqa ' . $halaqa->nom_halaqa . ' car elle a des étudiants associés.');
        }

        return redirect()->route('halaqas.index')
            ->with('success', 'Halaqa ' . $halaqa->nom_halaqa . ' supprimé !');
    }
}

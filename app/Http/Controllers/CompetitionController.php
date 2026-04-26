<?php

namespace App\Http\Controllers;

use App\Models\Competition;
use App\Http\Requests\StoreCompetitionRequest;
use App\Http\Requests\UpdateCompetitionRequest;
use App\Models\Student;
use App\Models\Participation;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class CompetitionController extends Controller
{
    /**
     * --- ACTEUR MULTIPLE (ADMIN / CHEIKH / ÉTUDIANT) ---
     * Affiche la liste des compétitions.
     * Les étudiants voient les compétitions actives, les autres voient tout.
     */
    public function index()
    {
        $user = Auth::user();

        if ($user?->role === 'student') {
            $competitions = Competition::where('statut', 'active')->orderBy('id', 'asc')->get();
            return view('student.competitions.index', compact('competitions'));
        }

        $competitions = Competition::orderBy('id', 'asc')->get();
        
        $view = match($user?->role) {
            'admin' => 'admin.competitions.index',
            'cheikh' => 'cheikh.competitions.index',
            default => abort(403)
        };

        return view($view, compact('competitions'));
    }

    /**
     * --- ACTEUR : ADMIN ---
     * Formulaire de création d'une nouvelle compétition.
     */
    public function create()
    {
        return view('admin.competitions.create');
    }

    /**
     * --- ACTEUR : ADMIN ---
     * Enregistre une nouvelle compétition dans le système.
     */
    public function store(StoreCompetitionRequest $request)
    {
        $data = $request->validated();
        $competition = Competition::create($data);

        return redirect()->route('competitions.index')
            ->with('success', 'Nouvelle compétition ' . $competition->titre . ' créée !');
    }

    /**
     * --- ACTEUR MULTIPLE (ADMIN / CHEIKH) ---
     * Affiche les détails d'une compétition et la liste des participants.
     */
    public function show(Competition $competition)
    {
        $user = Auth::user();

        $participations = Participation::where('competition_id', $competition->id)
            ->with('student.user')
            ->get();

        $view = match($user?->role) {
            'admin' => 'admin.competitions.show',
            'cheikh' => 'cheikh.competitions.show',
            default => abort(403)
        };

        return view($view, compact('competition', 'participations'));
    }

    /**
     * --- ACTEUR : ADMIN ---
     * Formulaire d'édition d'une compétition (titre, date, statut).
     */
    public function edit(Competition $competition)
    {
        return view('admin.competitions.edit', compact('competition'));
    }

    /**
     * --- ACTEUR : ADMIN ---
     * Met à jour les informations de la compétition.
     */
    public function update(UpdateCompetitionRequest $request, Competition $competition)
    {
        $data = $request->validated();
        $competition->update($data);

        return redirect()->route('competitions.index')
            ->with('success', 'Compétition ' . $competition->titre . ' mise à jour !');
    }

    /**
     * --- ACTEUR : ADMIN ---
     * Supprime une compétition du système.
     */
    public function destroy(Competition $competition)
    {
        try {
            $competition->delete();
        } catch (\Exception $e) {
            return redirect()->route('competitions.index')
                ->with('error', 'Impossible de supprimer cette compétition car elle contient des participations.');
        }

        return redirect()->route('competitions.index')
            ->with('success', 'Compétition ' . $competition->titre . ' supprimée !');
    }

    /**
     * --- ACTEUR : ADMIN ---
     * Change le statut d'une compétition (active/inactive).
     */
    public function statusCompetition(Competition $competition)
    {
        $competition->update([
            'statut' => ($competition->statut === 'active') ? 'inactive' : 'active'
        ]);

        return back()->with('success', 'Le statut de la compétition a été mis à jour.');
    }
}

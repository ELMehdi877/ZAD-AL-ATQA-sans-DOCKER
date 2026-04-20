<?php

namespace App\Http\Controllers;

use App\Models\Participation;
use App\Http\Requests\UpdateParticipationRequest;
use Illuminate\Http\Request;

class ParticipationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $participations = Participation::with(['student.user', 'cheikh', 'competition'])->orderBy('id', 'asc')->get();

        return view('participations.index', compact('participations'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Participation $participation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Participation $participation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateParticipationRequest $request, Participation $participation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Participation $participation)
    {
        //
    }

    /**
     * Accept a student's participation in a competition.
     */

    public function acceptParticipation(UpdateParticipationRequest $request, Participation $participation)
    {
        $data = $request->validated();
        $statut = $data['statut'];

        if ($statut === 'en_attente') {

            $participation->update(['statut' => 'en_attente']);
        }

        elseif ($statut === 'valide') {
            
            $participation->update(['statut' => 'valide']);
        }

        elseif ($statut === 'refuse') {
            
            $participation->update(['statut' => 'refuse']);
        }

        return back()->with('success', 'Le statut de la participation de ' . ($participation->student->user->nom ?? '-') . ' ' . ($participation->student->user->prenom ?? '') . ' pour la competition ' . $participation->competition->titre . ' a ete mis a jour.');
    }

    

}

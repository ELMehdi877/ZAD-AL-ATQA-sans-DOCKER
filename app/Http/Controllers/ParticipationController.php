<?php

namespace App\Http\Controllers;

use App\Models\Participation;
use App\Http\Requests\UpdateParticipationRequest;
use Illuminate\Http\Request;

class ParticipationController extends Controller
{
    /**
     * Affiche la liste de toutes les participations avec les relations associées.
     */
    public function index()
    {
        $participations = Participation::with(['student.user', 'cheikh', 'competition'])->orderBy('id', 'asc')->get();

        return view('participations.index', compact('participations'));

    }



    /**
     * Met à jour le statut d'une participation (en attente, validé, refusé).
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

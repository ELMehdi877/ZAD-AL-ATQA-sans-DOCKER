@extends('layouts.user-navbar')

@section('content')
    <header class="mb-6 rounded-2xl bg-gradient-to-r from-[#04371f] to-[#04371f]/80 p-6 text-white shadow-xl">
        <h2 class="text-2xl font-bold">Mes Participations</h2>
        <p class="mt-2 text-sm text-slate-200">Voici la liste de toutes vos participations aux compétitions.</p>
    </header>

        <!-- Tableau des participations -->
        @if($participations->count() > 0)
            <div class="bg-white rounded-lg shadow overflow-x-auto">
                <table class="w-full">
                    <!-- En-têtes du tableau -->
                    <thead class="bg-gray-100 border-b">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Compétition</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Statut</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Date Début</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Date Fin</th>
                            <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700">Actions</th>
                        </tr>
                    </thead>

                    <!-- Corps du tableau -->
                    <tbody>
                        @foreach($participations as $competition)
                            <tr class="border-b hover:bg-gray-50">
                                <!-- Titre de la compétition -->
                                <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                    {{ $competition->titre }}
                                </td>

                                <!-- Statut -->
                                <td class="px-6 py-4 text-sm">
                                    @if($competition->pivot->statut === 'en_attente')
                                        <span class="inline-block bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-xs font-semibold">
                                            ⏳ En attente
                                        </span>
                                    @elseif($competition->pivot->statut === 'valide' || $competition->pivot->statut === 'accepte')
                                        <span class="inline-block bg-green-100 text-green-800 px-3 py-1 rounded-full text-xs font-semibold">
                                            ✓ Acceptee
                                        </span>
                                    @elseif($competition->pivot->statut === 'refuse')
                                        <span class="inline-block bg-red-100 text-red-800 px-3 py-1 rounded-full text-xs font-semibold">
                                            ✗ Refusee
                                        </span>
                                    @else
                                        <span class="inline-block bg-gray-100 text-gray-800 px-3 py-1 rounded-full text-xs font-semibold">
                                            {{ $competition->pivot->statut }}
                                        </span>
                                    @endif
                                </td>

                                <!-- Date de début -->
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    {{ \Carbon\Carbon::parse($competition->date_debut)->format('d/m/Y') }}
                                </td>

                                <!-- Date de fin -->
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    {{ \Carbon\Carbon::parse($competition->date_fin)->format('d/m/Y') }}
                                </td>

                                <!-- Bouton d'annulation -->
                                <td class="px-6 py-4 text-center">
                                    @if($competition->pivot->statut === 'en_attente' )
                                        <form action="{{ route('student.cancel', $competition->id) }}" method="POST" class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-1 px-3 rounded text-sm" 
                                                    onclick="return confirm('Êtes-vous sûr de vouloir annuler cette participation ?')">
                                                Annuler
                                            </button>
                                        </form>
                                    @else
                                        <form action="{{ route('student.showParticipation', $competition->id) }}" method="GET" class="inline-block">
                                            @csrf
                                            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-1 px-3 rounded text-sm" >
                                                Voir
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <!-- Message si aucune participation -->
            <div class="bg-blue-50 border-l-4 border-blue-500 p-4">
                <p class="text-blue-700">Vous n'avez pas encore participé à une compétition.</p>
                <p class="text-blue-600 mt-2">
                    <a href="{{ route('student.competitions') }}" class="font-bold hover:underline">Parcourez les compétitions disponibles →</a>
                </p>
            </div>
        @endif
@endsection

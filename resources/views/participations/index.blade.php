@extends('layouts.admin')

@section('title', 'Participations')

@section('content')
    <header class="mb-6 rounded-2xl bg-gradient-to-r from-[#04371f] to-[#04371f]/80 p-6 text-white shadow-xl">
        <h2 class="text-2xl font-bold">Liste des participations</h2>
        <p class="mt-2 text-sm text-slate-200">Visible uniquement pour admin et cheikh.</p>
    </header>

    <section class="overflow-hidden rounded-xl bg-white shadow">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-slate-100 text-slate-700">
                    <tr>
                        <th class="px-4 py-3 text-left">#</th>
                        <th class="px-4 py-3 text-left">Etudiant</th>
                        <th class="px-4 py-3 text-left">Competition</th>
                        <th class="px-4 py-3 text-left">Statut</th>
                        <th class="px-4 py-3 text-left">Date</th>
                        <th class="px-4 py-3 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($participations as $participation)
                        <tr class="border-t border-slate-100">
                            <td class="px-4 py-3">{{ $participation->id }}</td>
                            <td class="px-4 py-3">
                                {{ $participation->student->user->nom ?? '-' }}
                                {{ $participation->student->user->prenom ?? '' }}
                            </td>
                            <td class="px-4 py-3">{{ $participation->competition->titre ?? '-' }}</td>
                            <td class="px-4 py-3">{{ $participation->statut }}</td>
                            <td class="px-4 py-3">{{ $participation->created_at ? $participation->created_at->format('d/m/Y H:i') : '-' }}</td>
                            <td class="px-4 py-3">
                                <form method="POST" action="{{ auth()->user()->role === 'cheikh' ? route('participations.statut', $participation->id) : route('participations.statut', $participation->id) }}" class="flex items-center gap-2">
                                    @csrf
                                    @method('PATCH')

                                    <select name="statut" class="rounded-md border border-slate-300 px-2 py-1 text-xs">
                                        <option value="en_attente" {{ $participation->statut === 'en_attente' ? 'selected' : '' }}>En attente</option>
                                        <option value="valide" {{ $participation->statut === 'valide' ? 'selected' : '' }}>Valide</option>
                                        <option value="refuse" {{ $participation->statut === 'refuse' ? 'selected' : '' }}>Refuse</option>
                                    </select>

                                    <button type="submit" class="rounded-md bg-slate-900 px-3 py-1.5 text-xs font-medium text-white hover:bg-slate-700">
                                        Enregistrer
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-6 text-center text-slate-500">Aucune participation trouvee.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endsection

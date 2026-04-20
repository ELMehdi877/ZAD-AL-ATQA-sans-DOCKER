@extends('layouts.user-navbar')

@section('title', 'Mes Halaqas')

@section('content')
    <header class="mb-6 rounded-2xl bg-gradient-to-r from-[#04371f] to-[#04371f]/80 p-6 text-white shadow-xl">
        <h1 class="text-2xl font-bold">Mes halaqas</h1>
        <p class="mt-2 text-sm text-slate-200">Liste des halaqas auxquelles vous etes inscrit.</p>
    </header>

    @if (!$student)
        <section class="rounded-xl bg-white p-6 shadow">
            <p class="text-slate-600">Votre profil etudiant n'est pas encore configure. Contactez l'administration.</p>
        </section>
    @else
        <section class="overflow-hidden rounded-xl bg-white shadow">
            <table class="min-w-full text-sm">
                <thead class="bg-slate-50 text-left">
                    <tr>
                        <th class="px-4 py-3">#</th>
                        <th class="px-4 py-3">Nom halaqa</th>
                        <th class="px-4 py-3">Capacite</th>
                        <th class="px-4 py-3">Cheikh</th>
                        <th class="px-4 py-3">Statut</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($halaqas as $halaqa)
                        <tr class="border-t border-slate-100">
                            <td class="px-4 py-3">{{ $halaqa->id }}</td>
                            <td class="px-4 py-3">{{ $halaqa->nom_halaqa }}</td>
                            <td class="px-4 py-3">{{ $halaqa->capacite }}</td>
                            <td class="px-4 py-3">{{ $halaqa->cheikh->nom ?? '-' }} {{ $halaqa->cheikh->prenom ?? '' }}</td>
                            <td class="px-4 py-3">{{ $halaqa->pivot->statut ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-6 text-center text-slate-500">Vous n'avez rejoint aucune halaqa pour le moment.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </section>
    @endif
@endsection

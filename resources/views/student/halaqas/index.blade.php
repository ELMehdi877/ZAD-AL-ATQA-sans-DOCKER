@extends('layouts.user-navbar')

@section('title', 'Mes Halaqas')

@section('content')
    @php($currentOnly = request()->routeIs('student.current-halaqa'))

    <header class="mb-6 rounded-2xl bg-gradient-to-r from-[#04371f] to-[#04371f]/80 p-6 text-white shadow-xl">
        <h1 class="text-2xl font-bold">{{ $currentOnly ? 'Ma halaqa actuelle' : 'Mes halaqas' }}</h1>
        <p class="mt-2 text-sm text-slate-200">
            {{ $currentOnly ? 'Halaqa active actuelle.' : 'Liste des halaqas auxquelles vous etes inscrit.' }}
        </p>
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
                    </tr>
                </thead>
                <tbody>
                    @forelse ($halaqas as $halaqa)
                        <tr class="border-t border-slate-100">
                            <td class="px-4 py-3">{{ $halaqa->id }}</td>
                            <td class="px-4 py-3">{{ $halaqa->nom_halaqa }}</td>
                            <td class="px-4 py-3">{{ $halaqa->capacite }}</td>
                            <td class="px-4 py-3">{{ $halaqa->cheikh->nom ?? '-' }} {{ $halaqa->cheikh->prenom ?? '' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-6 text-center text-slate-500">Vous n'avez rejoint aucune halaqa pour le moment.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </section>

        @if ($currentOnly)
            @foreach ($halaqas as $halaqa)
                <section class="mt-6 overflow-hidden rounded-xl bg-white shadow">
                    <div class="border-b border-slate-100 bg-slate-50 px-4 py-3">
                        <h3 class="text-sm font-semibold text-slate-800">
                            Evaluations - {{ $halaqa->nom_halaqa }}
                        </h3>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead class="bg-white text-left text-slate-700">
                                <tr>
                                    <th class="px-4 py-3">Date</th>
                                    <th class="px-4 py-3">Du Sourate</th>
                                    <th class="px-4 py-3">Au Sourate</th>
                                    <th class="px-4 py-3">Hizb</th>
                                    <th class="px-4 py-3">Du Aya</th>
                                    <th class="px-4 py-3">Au Aya</th>
                                    <th class="px-4 py-3">Presence</th>
                                    <th class="px-4 py-3">Note</th>
                                    <th class="px-4 py-3">Remarque</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($halaqa->evaluations as $evaluation)
                                    <tr class="border-t border-slate-100 align-top">
                                        <td class="px-4 py-3 whitespace-nowrap">{{ optional($evaluation->created_at)->format('d/m/Y H:i') ?? '-' }}</td>
                                        <td class="px-4 py-3">{{ $evaluation->du_sourate ?? '-' }}</td>
                                        <td class="px-4 py-3">{{ $evaluation->au_sourate ?? '-' }}</td>
                                        <td class="px-4 py-3">{{ $evaluation->hizb ?? '-' }}</td>
                                        <td class="px-4 py-3">{{ $evaluation->du_aya ?? '-' }}</td>
                                        <td class="px-4 py-3">{{ $evaluation->au_aya ?? '-' }}</td>
                                        <td class="px-4 py-3">{{ $evaluation->presence ?? '-' }}</td>
                                        <td class="px-4 py-3">{{ is_null($evaluation->note) ? '-' : $evaluation->note . '/20' }}</td>
                                        <td class="px-4 py-3">{{ $evaluation->remarque ?? '-' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="px-4 py-6 text-center text-slate-500">
                                            Aucune evaluation pour votre halaqa active.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </section>
            @endforeach
        @endif
    @endif
@endsection

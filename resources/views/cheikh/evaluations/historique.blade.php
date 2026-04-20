@extends('layouts.cheikh')

@section('title', 'Historique des evaluations')

@section('content')
    <header class="mb-6 rounded-2xl bg-gradient-to-r from-[#04371f] to-[#04371f]/80 p-6 text-white shadow-xl flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold">Historique des evaluations</h2>
            <p class="mt-2 text-sm text-slate-200">Etudiant: {{ $student->user->nom ?? '-' }} {{ $student->user->prenom ?? '' }}
            </p>
            <p class="text-sm text-slate-500">
                Halaqa: {{ $halaqa->nom_halaqa ?? '-' }}</p>
        </div>
        <a href="{{ route('cheikh.halaqas.show', $halaqa->id) }}" class="rounded-lg bg-white/20 px-4 py-2 text-sm font-medium text-white hover:bg-white/30 backdrop-blur-sm transition">Retour</a>
    </header>

    <section class="overflow-hidden rounded-xl bg-white shadow">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-slate-100 text-slate-700">
                    <tr>
                        <th class="px-4 py-3 text-left">Date</th>
                        <th class="px-4 py-3 text-left">Halaqa</th>
                        <th class="px-4 py-3 text-left">Du sourate</th>
                        <th class="px-4 py-3 text-left">Au sourate</th>
                        <th class="px-4 py-3 text-left">Hizb</th>
                        <th class="px-4 py-3 text-left">Huitieme</th>
                        <th class="px-4 py-3 text-left">Du aya</th>
                        <th class="px-4 py-3 text-left">Au aya</th>
                        <th class="px-4 py-3 text-left">Presence</th>
                        <th class="px-4 py-3 text-left">Note</th>
                        <th class="px-4 py-3 text-left">Remarque</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($evaluations as $evaluation)
                        <tr class="border-t border-slate-100">
                            <td class="px-4 py-3">{{ $evaluation->created_at?->format('d/m/Y H:i') ?? '-' }}</td>
                            <td class="px-4 py-3">{{ $evaluation->halaqa->nom_halaqa ?? '-' }}</td>
                            <td class="px-4 py-3">{{ $evaluation->du_sourate ?? '-' }}</td>
                            <td class="px-4 py-3">{{ $evaluation->au_sourate ?? '-' }}</td>
                            <td class="px-4 py-3">{{ $evaluation->hizb ?? '-' }}</td>
                            <td class="px-4 py-3">{{ $evaluation->huitieme ?? '-' }}</td>
                            <td class="px-4 py-3">{{ $evaluation->du_aya ?? '-' }}</td>
                            <td class="px-4 py-3">{{ $evaluation->au_aya ?? '-' }}</td>
                            <td class="px-4 py-3">{{ $evaluation->presence ?? '-' }}</td>
                            <td class="px-4 py-3">{{ $evaluation->note ?? '-' }}</td>
                            <td class="px-4 py-3">{{ $evaluation->remarque ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="px-4 py-6 text-center text-slate-500">
                                Aucune evaluation trouvee pour cet etudiant.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endsection

@extends('layouts.admin')

@section('title', 'Detail Halaqa')

@section('content')
    <header class="mb-6 rounded-2xl bg-gradient-to-r from-[#04371f] to-[#04371f]/80 p-6 text-white shadow-xl flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-2xl font-bold">Halaqa #{{ $halaqa->id }}</h2>
            <p class="mt-2 text-sm text-slate-200">Details de la halaqa et liste des etudiants.</p>
        </div>
        <div class="flex flex-wrap items-center gap-2 mt-4 sm:mt-0">
            <a href="{{ route('halaqas.edit', ['halaqa' => $halaqa->id]) }}" class="rounded-lg bg-white/20 px-4 py-2 text-sm font-medium text-white hover:bg-white/30 backdrop-blur-sm transition">Modifier</a>
            <a href="{{ route('halaqas.index') }}" class="rounded-lg bg-white/20 px-4 py-2 text-sm font-medium text-white hover:bg-white/30 backdrop-blur-sm transition">Retour liste</a>
        </div>
    </header>

    <section class="mb-6 rounded-xl bg-white p-5 shadow">
        <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div>
                <dt class="text-xs uppercase tracking-wide text-slate-500">Nom halaqa</dt>
                <dd class="mt-1 text-sm font-medium text-slate-900">{{ $halaqa->nom_halaqa }}</dd>
            </div>
            <div>
                <dt class="text-xs uppercase tracking-wide text-slate-500">Capacite</dt>
                <dd class="mt-1 text-sm font-medium text-slate-900">{{ $halaqa->capacite }}</dd>
            </div>
            <div>
                <dt class="text-xs uppercase tracking-wide text-slate-500">Cheikh</dt>
                <dd class="mt-1 text-sm font-medium text-slate-900">
                    {{ $halaqa->cheikh->nom ?? '-' }} {{ $halaqa->cheikh->prenom ?? '' }}
                </dd>
            </div>
            <div>
                <dt class="text-xs uppercase tracking-wide text-slate-500">Etudiants inscrits</dt>
                <dd class="mt-1 text-sm font-medium text-slate-900">{{ $halaqa->students->count() }}</dd>
            </div>
        </dl>
    </section>

    <section class="rounded-xl bg-white p-5 shadow">
        <h3 class="mb-3 text-lg font-semibold">Liste des etudiants</h3>

        <div class="overflow-hidden rounded-lg border border-slate-200">
            <table class="min-w-full text-sm">
                <thead class="bg-slate-100 text-slate-700">
                    <tr>
                        <th class="px-4 py-3 text-left">#</th>
                        <th class="px-4 py-3 text-left">Nom</th>
                        <th class="px-4 py-3 text-left">Prenom</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($halaqa->students as $student)
                        <tr class="border-t border-slate-100">
                            <td class="px-4 py-3">{{ $student->id }}</td>
                            <td class="px-4 py-3">{{ $student->user->nom ?? '-' }}</td>
                            <td class="px-4 py-3">{{ $student->user->prenom ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-4 py-6 text-center text-slate-500">Aucun etudiant inscrit dans cette halaqa.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endsection

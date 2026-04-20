@extends('layouts.admin')

@section('title', 'Nouvelle Competition')

@section('content')
    <header class="mb-6 rounded-2xl bg-gradient-to-r from-[#04371f] to-[#04371f]/80 p-6 text-white shadow-xl flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold">Nouvelle competition</h2>
            <p class="mt-2 text-sm text-slate-200">Creation d'une competition avec selection des participants.</p>
        </div>
        <a href="{{ route('competitions.index') }}" class="rounded-lg bg-white/20 px-4 py-2 text-sm font-medium text-white hover:bg-white/30 backdrop-blur-sm transition">Retour liste</a>
    </header>

    <section class="rounded-xl bg-white p-5 shadow xl:max-w-6xl">
        <form method="POST" action="{{ route('competitions.store') }}" class="grid grid-cols-1 md:grid-cols-12 gap-6">
            @csrf

            <!-- Colonne de gauche : Informations -->
            <div class="md:col-span-7 grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div class="sm:col-span-2">
                    <label class="mb-1 block text-sm font-medium" for="titre">Titre</label>
                    <input id="titre" name="titre" type="text" required class="w-full rounded-lg border border-slate-300 px-3 py-2" value="{{ old('titre') }}">
                </div>

                <div class="sm:col-span-2">
                    <label class="mb-1 block text-sm font-medium" for="description">Description</label>
                    <textarea id="description" name="description" rows="4" class="w-full rounded-lg border border-slate-300 px-3 py-2">{{ old('description') }}</textarea>
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium" for="date_debut">Date debut</label>
                    <input id="date_debut" name="date_debut" type="date" required class="w-full rounded-lg border border-slate-300 px-3 py-2" value="{{ old('date_debut') }}">
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium" for="date_fin">Date fin</label>
                    <input id="date_fin" name="date_fin" type="date" required class="w-full rounded-lg border border-slate-300 px-3 py-2" value="{{ old('date_fin') }}">
                </div>

                <div class="sm:col-span-2">
                    <label class="mb-1 block text-sm font-medium" for="statut">Statut</label>
                    <select id="statut" name="statut" required class="w-full rounded-lg border border-slate-300 px-3 py-2">
                        <option value="">-- choisir --</option>
                        @foreach (['active', 'inactive'] as $statut)
                            <option value="{{ $statut }}" @selected(old('statut') === $statut)>{{ $statut }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Colonne de droite : Liste des etudiants -->
            <div class="md:col-span-5 flex flex-col lg:pt-0">
                <label class="mb-1 block text-sm font-medium text-slate-700">Etudiants participants</label>
                @php
                    $selectedStudentIds = collect(old('students', []))
                        ->map(fn ($id) => (int) $id)
                        ->all();
                @endphp

                <div class="flex-1 min-h-[250px] max-h-[380px] overflow-y-auto rounded-lg border border-slate-300 bg-slate-50 p-3">
                    @forelse ($students as $student)
                        <label class="flex cursor-pointer items-center rounded p-2 hover:bg-emerald-50 mb-1">
                            <input type="checkbox" name="students[]" value="{{ $student->id }}" @checked(in_array($student->id, $selectedStudentIds)) class="h-4 w-4 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                            <span class="ml-3 text-sm font-medium text-slate-700">{{ $student->user->nom }} {{ $student->user->prenom }}</span>
                        </label>
                    @empty
                        <p class="p-2 text-sm text-slate-500">Aucun etudiant disponible.</p>
                    @endforelse
                </div>
            </div>

            <div class="md:col-span-12 mt-2 pt-4 border-t border-slate-100 flex justify-end">
                <button type="submit" class="rounded-lg bg-emerald-600 px-8 py-2.5 font-semibold text-white shadow-sm hover:bg-emerald-500 transition-colors">Creer la competition</button>
            </div>
        </form>
    </section>
@endsection

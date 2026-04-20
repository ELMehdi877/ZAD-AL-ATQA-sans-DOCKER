@extends('layouts.admin')

@section('title', 'Créer Halaqa')

@section('content')

    <header class="mb-6 rounded-2xl bg-gradient-to-r from-[#04371f] to-[#04371f]/80 p-6 text-white shadow-xl flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold">Créer une halaqa</h2>
            <p class="mt-2 text-sm text-slate-200">Créer un groupe, assigner un cheikh et ajouter des étudiants.</p>
        </div>
        <a href="{{ route('halaqas.index') }}" class="rounded-lg bg-white/20 px-4 py-2 text-sm font-medium text-white hover:bg-white/30 backdrop-blur-sm transition">Retour liste</a>
    </header>

    <section class="rounded-xl bg-white p-5 shadow xl:max-w-6xl">
        <form method="POST" action="{{ route('halaqa.store') }}" class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            @csrf
            
            <div class="lg:col-span-7 grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div class="sm:col-span-2">
                    <label class="mb-1 block text-sm font-medium" for="nom_halaqa">Nom de la halaqa</label>
                    <input id="nom_halaqa" name="nom_halaqa" type="text" required class="w-full rounded-lg border border-slate-300 px-3 py-2" value="{{ old('nom_halaqa') }}">
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium" for="capacite">Capacité max</label>
                    <input id="capacite" name="capacite" type="number" min="1" required class="w-full rounded-lg border border-slate-300 px-3 py-2" value="{{ old('capacite', 20) }}">
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium" for="cheikh_id">Enseignant (Cheikh)</label>
                    <select id="cheikh_id" name="cheikh_id" required class="w-full rounded-lg border border-slate-300 px-3 py-2">
                        <option value="">-- Choisir un Cheikh --</option>
                        @forelse ($cheikhs as $cheikh)
                            <option value="{{ $cheikh->id }}" @selected(old('cheikh_id') == $cheikh->id)>
                                {{ $cheikh->nom }} {{ $cheikh->prenom }}
                            </option>
                        @empty
                            <option value="" disabled>Aucun Cheikh disponible</option>
                        @endforelse
                    </select>
                </div>
            </div>

            <div class="lg:col-span-5 flex flex-col lg:pt-0">
                <label class="mb-1 block text-sm font-medium text-slate-700">Ajouter des Étudiants</label>
                
                <div class="flex-1 min-h-[250px] max-h-[380px] overflow-y-auto border border-slate-300 rounded-lg p-3 bg-slate-50">
                    @forelse ($students as $student)
                        <label class="flex items-center p-2 hover:bg-emerald-50 rounded cursor-pointer mb-1">
                            <input type="checkbox" name="students[]" value="{{ $student->id }}" class="w-4 h-4 text-emerald-600 border-slate-300 rounded focus:ring-emerald-500">
                            <span class="ml-3 text-sm font-medium text-slate-700">{{ $student->user->nom }} {{ $student->user->prenom }}</span>
                        </label>
                    @empty
                        <p class="text-sm text-slate-500 p-2">Aucun étudiant disponible.</p>
                    @endforelse
                </div>
            </div>

            <div class="lg:col-span-12 mt-2 pt-4 border-t border-slate-100 flex justify-end">
                <button type="submit" class="rounded-lg bg-emerald-600 px-8 py-2.5 font-semibold text-white shadow-sm hover:bg-emerald-500 transition-colors">
                    Créer la halaqa
                </button>
            </div>
        </form>
    </section>

@endsection
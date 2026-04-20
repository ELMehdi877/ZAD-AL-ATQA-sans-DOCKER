@extends('layouts.admin')

@section('title', 'Modifier Halaqa')

@section('content')
    <header class="mb-6 rounded-2xl bg-gradient-to-r from-[#04371f] to-[#04371f]/80 p-6 text-white shadow-xl flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold">Modifier halaqa #{{ $halaqa->id }}</h2>
            <p class="mt-2 text-sm text-slate-200">Mise a jour des informations de la halaqa.</p>
        </div>
        <a href="{{ route('halaqas.index') }}" class="rounded-lg bg-white/20 px-4 py-2 text-sm font-medium text-white hover:bg-white/30 backdrop-blur-sm transition">Retour liste</a>
    </header>

    <section class="max-w-3xl rounded-xl bg-white p-5 shadow">
        <form method="POST" action="{{ route('halaqas.update', ['halaqa' => $halaqa->id]) }}" class="grid grid-cols-1 gap-3 sm:grid-cols-2">
            @csrf
            @method('PUT')

            <div class="sm:col-span-2">
                <label class="mb-1 block text-sm font-medium" for="nom_halaqa">Nom halaqa</label>
                <input id="nom_halaqa" name="nom_halaqa" type="text" required class="w-full rounded-lg border border-slate-300 px-3 py-2" value="{{ old('nom_halaqa', $halaqa->nom_halaqa) }}">
            </div>

            <div>
                <label class="mb-1 block text-sm font-medium" for="capacite">Capacite</label>
                <input id="capacite" name="capacite" type="number" min="1" required class="w-full rounded-lg border border-slate-300 px-3 py-2" value="{{ old('capacite', $halaqa->capacite) }}">
            </div>

            <div>
                <label class="mb-1 block text-sm font-medium" for="cheikh_id">Cheikh</label>
                <select id="cheikh_id" name="cheikh_id" required class="w-full rounded-lg border border-slate-300 px-3 py-2">
                    <option value="">-- choisir --</option>
                    @forelse ($cheikhs as $u)
                        <option value="{{ $u->id }}" @selected(old('cheikh_id', $halaqa->cheikh_id) == $u->id)>
                            #{{ $u->id }} - {{ $u->nom }} {{ $u->prenom }}
                        </option>
                    @empty
                        <option value="" disabled>Aucun cheikh disponible</option>
                    @endforelse
                </select>
            </div>

            <div class="sm:col-span-2 mt-2">
                <label class="mb-1 block text-sm font-medium text-slate-700">Ajouter des Étudiants</label>
                @php
                    $selectedStudentIds = collect(old('students', $students->pluck('id')->toArray()))
                        ->map(fn ($id) => (int) $id)
                        ->all();

                    $allStudents = $students
                        ->merge($studentsNotInHalaqa)
                        ->unique('id')
                        ->sortBy('id');
                @endphp
                
                <div class="h-48 overflow-y-auto border border-slate-300 rounded-lg p-3 bg-slate-50">
                    @forelse ($allStudents as $student)
                        <label class="flex items-center p-2 hover:bg-emerald-50 rounded cursor-pointer">
                            <input type="checkbox" name="students[]" value="{{ $student->id }}" @checked(in_array($student->id, $selectedStudentIds)) class="w-4 h-4 text-emerald-600 border-slate-300 rounded focus:ring-emerald-500">
                            <span class="ml-3 text-sm font-medium text-slate-700">{{ $student->user->nom }} {{ $student->user->prenom }}</span>
                        </label>
                    @empty
                        <p class="text-sm text-slate-500 p-2">Aucun étudiant disponible.</p>
                    @endforelse
                </div>
            </div>

            <div class="sm:col-span-2">
                <button type="submit" class="rounded-lg bg-blue-600 px-4 py-2 font-medium text-white hover:bg-blue-500">Mettre a jour</button>
            </div>
        </form>
    </section>
@endsection

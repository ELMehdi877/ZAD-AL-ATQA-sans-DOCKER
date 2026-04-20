@extends('layouts.admin')

@section('title', 'Detail Competition')

@section('content')

@php
    $isAdmin = auth()->user()?->role === 'admin';
@endphp
    <header class="mb-6 rounded-2xl bg-gradient-to-r from-[#04371f] to-[#04371f]/80 p-6 text-white shadow-xl flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-2xl font-bold">Competition #{{ $competition->titre }}</h2>
            <p class="mt-2 text-sm text-slate-200">Details et participants de la competition.</p>
        </div>
        <div class="flex flex-wrap items-center gap-2 mt-4 sm:mt-0">
            @if ($isAdmin)
                <a href="{{ route('competitions.edit', ['competition' => $competition->id]) }}" class="rounded-lg bg-white/20 px-4 py-2 text-sm font-medium text-white hover:bg-white/30 backdrop-blur-sm transition">Modifier</a>
            @endif
            <a href="{{ route('competitions.index') }}" class="rounded-lg bg-white/20 px-4 py-2 text-sm font-medium text-white hover:bg-white/30 backdrop-blur-sm transition">Retour liste</a>
        </div>
    </header>

    <section class="mb-6 rounded-xl bg-white p-5 shadow">
        <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div>
                <dt class="text-xs uppercase tracking-wide text-slate-500">Titre</dt>
                <dd class="mt-1 text-sm font-medium text-slate-900">{{ $competition->titre }}</dd>
            </div>
            <div>
                <dt class="text-xs uppercase tracking-wide text-slate-500">Periode</dt>
                <dd class="mt-1 text-sm font-medium text-slate-900">{{ $competition->date_debut }} - {{ $competition->date_fin }}</dd>
            </div>
            <div class="sm:col-span-2">
                <dt class="text-xs uppercase tracking-wide text-slate-500">Description</dt>
                <dd class="mt-1 text-sm text-slate-700">{{ $competition->description ?: 'Aucune description.' }}</dd>
            </div>
        </dl>
    </section>

    @if (session('success'))
        <div class="mb-4 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-4 rounded-lg border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <section class="rounded-xl bg-white p-5 shadow">
        <h3 class="mb-3 text-lg font-semibold">Etudiants participants ({{ $competition->students->count() }})</h3>

        <div class="overflow-x-auto rounded-lg border border-slate-200">
            <table class="min-w-full text-sm">
                <thead class="bg-slate-100 text-slate-700">
                    <tr>
                        <th class="px-4 py-3 text-left">#</th>
                        <th class="px-4 py-3 text-left">Nom</th>
                        <th class="px-4 py-3 text-left">Prenom</th>
                        <th class="px-4 py-3 text-left">Statut</th>
                        <th class="px-4 py-3 text-left">Note Tajwid</th>
                        <th class="px-4 py-3 text-left">Note Hifz</th>
                        <th class="px-4 py-3 text-left">Remarque</th>
                        <th class="px-4 py-3 text-left">Cheikh</th>
                        <th class="px-4 py-3 text-left">Action</th>
                        
                    </tr>
                </thead>
                <tbody>
                    @forelse ($competition->students as $student)
                        @php
                            $participation = $participationsByStudent[$student->id] ?? null;
                            $cheikhEvaluateur = $participation?->cheikh;
                        @endphp
                        <tr class="border-t border-slate-100">
                            <td class="px-4 py-3">{{ $student->id }}</td>
                            <td class="px-4 py-3">{{ $student->user->nom ?? '-' }}</td>
                            <td class="px-4 py-3">{{ $student->user->prenom ?? '-' }}</td>
                            <td class="px-4 py-3">{{ $student->pivot->statut ?? '-' }}</td>
                            <td class="px-4 py-3">{{ $student->pivot->note_tajwid ?? '-' }}</td>
                            <td class="px-4 py-3">{{ $student->pivot->note_hifz ?? '-' }}</td>
                            <td class="px-4 py-3">{{ $student->pivot->remarque ?? '-' }}</td>
                            <td class="px-4 py-3">{{ $cheikhEvaluateur->nom ?? '-' }} {{ $cheikhEvaluateur->prenom ?? '' }}</td>
                            @if (auth()->check() && auth()->user()->role === 'cheikh')
                                @if ($student->pivot->statut === 'valide' && $student->pivot->note_hifz === null)
                                    <td class="px-4 py-3">
                                        <button
                                            type="button"
                                            data-student-id="{{ $student->id }}"
                                            data-action="open-eval-modal"
                                            class="rounded-lg bg-indigo-600 px-3 py-2 text-xs font-semibold text-white hover:bg-indigo-700"
                                        >
                                            Evaluer
                                        </button>
                                    </td>
                                @elseif ($student->pivot->statut === 'valide' && $student->pivot->note_hifz !== null && $student->pivot->cheikh_id === auth()->id())
                                    <td class="">
                                        <button
                                            type="button"
                                            data-student-id="{{ $student->id }}"
                                            data-action="open-eval-modal"
                                            class="rounded-lg bg-green-600 px-3 py-2 text-xs font-semibold text-white hover:bg-green-700"
                                        >
                                            Modifier
                                        </button>
                                    </td>
                                    <td>
                                        <form method="POST" action="{{ route('cheikh.competitions.students.evaluation.delete', ['competition' => $competition->id, 'student' => $student->id]) }}" class="flex items-center gap-2">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="rounded-lg bg-red-600 px-3 py-2 text-xs font-semibold text-white hover:bg-red-700">
                                                Supprimer
                                            </button>
                                        </form>

                                    </td>
                                @else 
                                    <td class="px-4 py-3"> - </td> 
                                @endif

                            @else 
                                <td class="px-4 py-3"> - </td>  

                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-6 text-center text-slate-500">Aucun participant pour cette competition.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    @if (auth()->check() && auth()->user()->role === 'cheikh')
        @foreach ($competition->students as $student)
            <div
                id="eval-modal-{{ $student->id }}"
                class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/60 px-4"
            >
                <div class="w-full max-w-lg rounded-xl bg-white p-5 shadow-xl">
                    <div class="mb-4 flex items-start justify-between">
                        <h4 class="text-lg font-semibold text-slate-900">
                            Evaluer {{ $student->user->nom ?? '' }} {{ $student->user->prenom ?? '' }}
                        </h4>
                        <button
                            type="button"
                            data-student-id="{{ $student->id }}"
                            data-action="close-eval-modal"
                            class="rounded-md border border-slate-300 px-2 py-1 text-xs text-slate-600 hover:bg-slate-50"
                        >
                            Fermer
                        </button>
                    </div>

                    <form method="POST" action="{{ route('cheikh.competitions.students.evaluation', ['competition' => $competition->id, 'student' => $student->id]) }}" class="space-y-4">
                        @csrf
                        @method('PATCH')

                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div>
                                <label class="mb-1 block text-xs font-medium uppercase tracking-wide text-slate-600">Note Tajwid</label>
                                <input
                                    type="number"
                                    name="note_tajwid"
                                    min="0"
                                    max="20"
                                    step="0.25"
                                    value="{{ old('note_tajwid', $student->pivot->note_tajwid) }}"
                                    class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none"
                                >
                            </div>

                            <div>
                                <label class="mb-1 block text-xs font-medium uppercase tracking-wide text-slate-600">Note Hifz</label>
                                <input
                                    type="number"
                                    name="note_hifz"
                                    min="0"
                                    max="20"
                                    step="0.25"
                                    value="{{ old('note_hifz', $student->pivot->note_hifz) }}"
                                    class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none"
                                >
                            </div>
                        </div>

                        <div>
                            <label class="mb-1 block text-xs font-medium uppercase tracking-wide text-slate-600">Statut</label>
                            @php
                                $oldStatut = old('statut', $student->pivot->statut ?? 'en_attente');
                            @endphp
                            <input type="hidden" name="statut" value="{{ $oldStatut }}">
                            <select class="w-full cursor-not-allowed rounded-lg border border-slate-300 bg-slate-100 px-3 py-2 text-sm text-slate-600" disabled>
                                <option value="en_attente" @selected($oldStatut === 'en_attente')>En attente</option>
                                <option value="accepte" @selected($oldStatut === 'accepte')>Accepte</option>
                                <option value="refuse" @selected($oldStatut === 'refuse')>Refuse</option>
                                <option value="valide" @selected($oldStatut === 'valide')>Valide</option>
                            </select>
                        </div>

                        <div>
                            <label class="mb-1 block text-xs font-medium uppercase tracking-wide text-slate-600">Remarque</label>
                            <textarea
                                name="remarque"
                                rows="3"
                                maxlength="255"
                                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none"
                            >{{ old('remarque', $student->pivot->remarque) }}</textarea>
                        </div>

                        <div class="flex justify-end gap-2">
                            <button
                                type="button"
                                data-student-id="{{ $student->id }}"
                                data-action="close-eval-modal"
                                class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50"
                            >
                                Annuler
                            </button>
                            <button
                                type="submit"
                                class="rounded-lg bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700"
                            >
                                Enregistrer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endforeach

        <script>
            function openEvalModal(studentId) {
                const modal = document.getElementById(`eval-modal-${studentId}`);
                if (modal) {
                    modal.classList.remove('hidden');
                    modal.classList.add('flex');
                }
            }

            function closeEvalModal(studentId) {
                const modal = document.getElementById(`eval-modal-${studentId}`);
                if (modal) {
                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
                }
            }

            document.querySelectorAll('[data-action="open-eval-modal"]').forEach((button) => {
                button.addEventListener('click', () => {
                    openEvalModal(button.dataset.studentId);
                });
            });

            document.querySelectorAll('[data-action="close-eval-modal"]').forEach((button) => {
                button.addEventListener('click', () => {
                    closeEvalModal(button.dataset.studentId);
                });
            });
        </script>
    @endif
@endsection

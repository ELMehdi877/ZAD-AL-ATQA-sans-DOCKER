@extends('layouts.user-navbar')

@section('content')
<div class="px-2 sm:px-4 py-4">
    <!-- Header Premium -->
    <header class="mb-6 rounded-2xl bg-gradient-to-r from-[#04371f] to-[#04371f]/80 p-6 text-white shadow-xl flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-[#a48834] mb-1">DÉTAILS DE L'ÉVALUATION</p>
            <h2 class="text-2xl font-bold">{{ $competition->titre }}</h2>
            <p class="mt-1 text-sm text-slate-200 opacity-90">{{ \Carbon\Carbon::parse($competition->date_debut)->format('d M Y') }} - {{ \Carbon\Carbon::parse($competition->date_fin)->format('d M Y') }}</p>
        </div>
        <a href="{{ route('student.participations') }}" class="inline-flex items-center justify-center rounded-lg bg-white/20 px-4 py-2 text-sm font-medium text-white hover:bg-white/30 backdrop-blur-sm transition">
            Retour
        </a>
    </header>

    <div class="mx-auto max-w-6xl space-y-6">
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
            <div class="rounded-2xl bg-white p-5 shadow-sm border border-slate-100 flex flex-col justify-between">
                <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Statut</p>
                <div class="mt-2 flex items-center justify-between">
                    <span class="text-lg font-bold text-slate-900 capitalize">{{ $participation->statut ?? '-' }}</span>
                    @if(($participation->statut ?? '') === 'valide' || ($participation->statut ?? '') === 'accepte')
                        <div class="h-8 w-8 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-600">
                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l5-5z" clip-rule="evenodd"></path></svg>
                        </div>
                    @endif
                </div>
            </div>

            <div class="rounded-2xl bg-white p-5 shadow-sm border border-slate-100">
                <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Note Tajwid</p>
                <div class="mt-2 flex items-baseline gap-1">
                    <span class="text-3xl font-bold text-slate-900">{{ $participation->note_tajwid ?? '-' }}</span>
                    <span class="text-sm text-slate-400 font-medium">/ 10</span>
                </div>
                <div class="mt-3 w-full bg-slate-100 h-1.5 rounded-full overflow-hidden">
                    <div class="bg-amber-500 h-full rounded-full" style="width: {{ (($participation->note_tajwid ?? 0) * 10) }}%"></div>
                </div>
            </div>

            <div class="rounded-2xl bg-white p-5 shadow-sm border border-slate-100">
                <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Note Hifz</p>
                <div class="mt-2 flex items-baseline gap-1">
                    <span class="text-3xl font-bold text-slate-900">{{ $participation->note_hifz ?? '-' }}</span>
                    <span class="text-sm text-slate-400 font-medium">/ 10</span>
                </div>
                <div class="mt-3 w-full bg-slate-100 h-1.5 rounded-full overflow-hidden">
                    <div class="bg-emerald-500 h-full rounded-full" style="width: {{ (($participation->note_hifz ?? 0) * 10) }}%"></div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-12">
            <!-- Details Competition -->
            <div class="lg:col-span-12">
                 <div class="rounded-2xl bg-white shadow-sm border border-slate-100 overflow-hidden">
                    <div class="border-b border-slate-100 bg-slate-50/50 px-6 py-4">
                        <h2 class="text-lg font-bold text-slate-900">Résumé de l'Évaluation</h2>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                             <!-- Info Section -->
                             <div class="space-y-6">
                                <div>
                                    <h3 class="text-xs font-bold text-[#a48834] uppercase tracking-widest mb-3">Informations Étudiant</h3>
                                    <div class="bg-slate-50 rounded-xl p-4 border border-slate-100">
                                        <div class="flex items-center gap-4">
                                            <div class="h-12 w-12 rounded-full bg-[#04371f] text-white flex items-center justify-center font-bold text-lg">
                                                {{ strtoupper(substr($student->user->nom ?? 'S', 0, 1)) }}
                                            </div>
                                            <div>
                                                <p class="text-sm font-bold text-slate-900">{{ $student->user->nom ?? '-' }} {{ $student->user->prenom ?? '' }}</p>
                                                <p class="text-xs text-slate-500">Étudiant participant</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <h3 class="text-xs font-bold text-[#a48834] uppercase tracking-widest mb-3">Évaluateur</h3>
                                    <div class="bg-slate-50 rounded-xl p-4 border border-slate-100">
                                        <div class="flex items-center gap-3 text-sm">
                                            <div class="h-8 w-8 rounded-lg bg-slate-200 flex items-center justify-center text-slate-600">
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                            </div>
                                            <div>
                                                <p class="font-bold text-slate-800">{{ $participation->cheikh->nom ?? 'En attente' }} {{ $participation->cheikh->prenom ?? '' }}</p>
                                                <p class="text-[10px] text-slate-500 uppercase tracking-tight">Ckeikh Responsable</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                             </div>

                             <!-- Remarque Section -->
                             <div>
                                <h3 class="text-xs font-bold text-[#a48834] uppercase tracking-widest mb-3">Remarques & Commentaires</h3>
                                <div class="bg-slate-50 rounded-xl p-5 border border-slate-100 h-full">
                                    <div class="prose prose-sm text-slate-600 italic">
                                        @if($participation->remarque)
                                            "{{ $participation->remarque }}"
                                        @else
                                            <span class="text-slate-400">Aucune remarque particulière pour cette évaluation.</span>
                                        @endif
                                    </div>
                                </div>
                             </div>
                        </div>
                    </div>
                 </div>
            </div>

            <!-- Competition Details Info -->
            <div class="lg:col-span-12">
                <div class="rounded-2xl bg-white shadow-sm border border-slate-100 overflow-hidden">
                    <div class="border-b border-slate-100 bg-slate-50/50 px-6 py-4 flex items-center justify-between">
                        <h2 class="text-lg font-bold text-slate-900">Détails de la Compétition</h2>
                        <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-bold text-emerald-700 capitalize">{{ $competition->statut }}</span>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="md:col-span-2">
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Description</p>
                                <p class="text-sm text-slate-600 leading-relaxed">{{ $competition->description ?: 'Aucune description disponible.' }}</p>
                            </div>
                            <div class="space-y-4">
                                <div>
                                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Période</p>
                                    <div class="flex items-center gap-2 text-sm text-slate-700">
                                        <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        <span>{{ \Carbon\Carbon::parse($competition->date_debut)->format('d/m/Y') }} au {{ \Carbon\Carbon::parse($competition->date_fin)->format('d/m/Y') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

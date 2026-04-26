@extends('layouts.user-navbar')

@section('title', 'Détails de l\'évaluation')

@section('content')
    <!-- Header Premium -->
    <header class="mb-4 sm:mb-6 rounded-xl sm:rounded-2xl bg-gradient-to-r from-[#04371f] to-[#04371f]/80 p-4 sm:p-6 text-white shadow-xl flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-[9px] sm:text-[10px] font-bold uppercase tracking-[0.2em] text-[#a48834] mb-1">MON ÉVALUATION</p>
            <h2 class="text-xl sm:text-2xl font-bold leading-tight">{{ $competition->titre }}</h2>
            <p class="mt-1 text-xs sm:text-sm text-slate-200 opacity-90 truncate max-w-xs sm:max-w-none">
                {{ \Carbon\Carbon::parse($competition->date_debut)->translatedFormat('d M Y') }} - {{ \Carbon\Carbon::parse($competition->date_fin)->translatedFormat('d M Y') }}
            </p>
        </div>
        <a href="{{ route('student.participations') }}" class="inline-flex w-full sm:w-auto items-center justify-center rounded-lg bg-white/20 px-4 py-2.5 sm:py-2 text-sm font-medium text-white hover:bg-white/30 backdrop-blur-sm transition shadow-sm border border-white/10">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Retour
        </a>
    </header>

    <div class="mx-auto max-w-6xl space-y-6 pb-4">
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="rounded-2xl bg-white p-4 sm:p-5 shadow-sm border border-slate-100 flex flex-col justify-between">
                <p class="text-[10px] sm:text-xs font-bold uppercase tracking-wider text-slate-500">Mon Statut</p>
                <div class="mt-2 flex items-center justify-between">
                    <span class="text-base sm:text-lg font-bold text-slate-900 capitalize">{{ $participation->statut ?? '-' }}</span>
                    @if(($participation->statut ?? '') === 'valide' || ($participation->statut ?? '') === 'accepte')
                        <div class="h-8 w-8 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-600 shadow-inner">
                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l5-5z" clip-rule="evenodd"></path></svg>
                        </div>
                    @else
                         <div class="h-8 w-8 rounded-full bg-amber-100 flex items-center justify-center text-amber-600">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                    @endif
                </div>
            </div>

            <div class="rounded-2xl bg-white p-4 sm:p-5 shadow-sm border border-slate-100">
                <p class="text-[10px] sm:text-xs font-bold uppercase tracking-wider text-slate-500">Ma Note Tajwid</p>
                <div class="mt-2 flex items-baseline gap-1">
                    <span class="text-2xl sm:text-3xl font-bold text-slate-900">{{ $participation->note_tajwid ?? '-' }}</span>
                    <span class="text-xs sm:text-sm text-slate-400 font-medium">/ 10</span>
                </div>
                <div class="mt-3 w-full bg-slate-100 h-1.5 rounded-full overflow-hidden">
                    <div class="bg-amber-500 h-full rounded-full transition-all duration-1000 ease-out" style="width: {{ (($participation->note_tajwid ?? 0) * 10) }}%"></div>
                </div>
            </div>

            <div class="rounded-2xl bg-white p-4 sm:p-5 shadow-sm border border-slate-100">
                <p class="text-[10px] sm:text-xs font-bold uppercase tracking-wider text-slate-500">Ma Note Hifz</p>
                <div class="mt-2 flex items-baseline gap-1">
                    <span class="text-2xl sm:text-3xl font-bold text-slate-900">{{ $participation->note_hifz ?? '-' }}</span>
                    <span class="text-xs sm:text-sm text-slate-400 font-medium">/ 10</span>
                </div>
                <div class="mt-3 w-full bg-slate-100 h-1.5 rounded-full overflow-hidden">
                    <div class="bg-emerald-500 h-full rounded-full transition-all duration-1000 ease-out" style="width: {{ (($participation->note_hifz ?? 0) * 10) }}%"></div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-12">
            <!-- Details Evaluation -->
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
                                    <h3 class="text-xs font-bold text-[#a48834] uppercase tracking-widest mb-3">Mes Informations</h3>
                                    <div class="bg-slate-50 rounded-xl p-4 border border-slate-100">
                                        <div class="flex items-center gap-4">
                                            <div class="h-12 w-12 rounded-full bg-[#04371f] text-white flex items-center justify-center font-bold text-lg">
                                                {{ strtoupper(substr($student->user->nom ?? 'M', 0, 1)) }}
                                            </div>
                                            <div>
                                                <p class="text-sm font-bold text-slate-900">{{ $student->user->nom ?? '-' }} {{ $student->user->prenom ?? '' }}</p>
                                                <p class="text-xs text-slate-500">Étudiant participant</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <h3 class="text-xs font-bold text-[#a48834] uppercase tracking-widest mb-3">Mon Évaluateur</h3>
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
                             <div class="flex flex-col h-full mt-4 md:mt-0">
                                <h3 class="text-[10px] sm:text-xs font-bold text-[#a48834] uppercase tracking-widest mb-3">Remarques du Cheikh</h3>
                                <div class="bg-[#f8fafc] rounded-2xl p-5 sm:p-6 border border-slate-100 flex-1 relative overflow-hidden group hover:border-[#a48834]/30 transition-colors duration-300 min-h-[160px]">
                                    <!-- Decorative Quote Icon -->
                                    <div class="absolute -right-2 -bottom-2 text-slate-100 group-hover:text-[#a48834]/5 transition-colors duration-500 pointer-events-none">
                                        <svg class="w-16 h-16 sm:w-24 sm:h-24" fill="currentColor" viewBox="0 0 24 24"><path d="M14.017 21L14.017 18C14.017 16.8954 14.9124 16 16.017 16H19.017C19.5693 16 20.017 15.5523 20.017 15V9C20.017 8.44772 19.5693 8 19.017 8H15.017C14.4647 8 14.017 8.44772 14.017 9V12C14.017 12.5523 13.5693 13 13.017 13H11.017C10.4647 13 10.017 12.5523 10.017 12V9C10.017 7.34315 11.3601 6 13.017 6H19.017C20.6739 6 22.017 7.34315 22.017 9V15C22.017 17.7614 19.7784 20 17.017 20H14.017V21ZM5.017 21L5.017 18C5.017 16.8954 5.91243 16 7.017 16H10.017C10.5693 16 11.017 15.5523 11.017 15V9C11.017 8.44772 10.5693 8 10.017 8H6.017C5.46472 8 5.017 8.44772 5.017 9V12C5.017 12.5523 4.56928 13 4.017 13H2.017C1.46472 13 1.017 12.5523 1.017 12V9C1.017 7.34315 2.36015 6 4.017 6H10.017C11.6739 6 13.017 7.34315 13.017 9V15C13.017 17.7614 10.7786 20 8.017 20H5.017V21Z"></path></svg>
                                    </div>
                                    
                                    <div class="relative z-10">
                                        @if($participation->remarque)
                                            <p class="text-sm sm:text-base text-slate-600 italic leading-relaxed whitespace-pre-line">
                                                <span class="text-[#a48834] font-serif text-xl sm:text-2xl mr-1">"</span>{{ $participation->remarque }}<span class="text-[#a48834] font-serif text-xl sm:text-2xl ml-1">"</span>
                                            </p>
                                        @else
                                            <div class="flex flex-col items-center justify-center py-8 text-center">
                                                <div class="w-12 h-12 bg-slate-100 rounded-full flex items-center justify-center text-slate-400 mb-2">
                                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                                                </div>
                                                <p class="text-sm text-slate-400">Aucune remarque particulière pour cette évaluation.</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                             </div>
                        </div>
                    </div>
                 </div>
            </div>

            <!-- Competition Details -->
            <div class="lg:col-span-12">
                <div class="rounded-2xl bg-white shadow-sm border border-slate-100 overflow-hidden">
                    <div class="border-b border-slate-100 bg-slate-50/50 px-4 sm:px-6 py-4 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                        <h2 class="text-base sm:text-lg font-bold text-slate-900">Détails de la Compétition</h2>
                        <span class="inline-flex w-fit rounded-full bg-emerald-100 px-3 py-1 text-[10px] sm:text-xs font-bold text-emerald-700 capitalize border border-emerald-200/50">{{ $competition->statut }}</span>
                    </div>
                    <div class="p-4 sm:p-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 sm:gap-8">
                            <div class="md:col-span-2">
                                <p class="text-[10px] sm:text-xs font-bold text-slate-400 uppercase tracking-widest mb-3 sm:mb-2">Description</p>
                                <p class="text-sm text-slate-600 leading-relaxed">{{ $competition->description ?: 'Aucune description disponible.' }}</p>
                            </div>
                            <div class="grid grid-cols-2 md:grid-cols-1 gap-4 text-sm">
                                <div>
                                    <p class="text-[10px] sm:text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Date de début</p>
                                    <p class="font-bold text-slate-700">{{ \Carbon\Carbon::parse($competition->date_debut)->format('d/m/Y') }}</p>
                                </div>
                                <div>
                                    <p class="text-[10px] sm:text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Date de fin</p>
                                    <p class="font-bold text-slate-700">{{ \Carbon\Carbon::parse($competition->date_fin)->format('d/m/Y') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

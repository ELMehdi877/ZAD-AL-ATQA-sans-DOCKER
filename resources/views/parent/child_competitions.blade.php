@extends('layouts.user-navbar')

@section('title', 'Compétitions de l\'enfant')

@section('content')
<div class="px-2 sm:px-4 py-4">
    <!-- Header Premium -->
    <header class="mb-8 rounded-2xl bg-gradient-to-r from-[#04371f] to-[#04371f]/80 p-6 text-white shadow-xl flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-[#a48834] mb-1">SUIVI DES COMPÉTITIONS</p>
            <h2 class="text-2xl font-bold">Compétitions de {{ $student->user->nom ?? '-' }} {{ $student->user->prenom ?? '' }}</h2>
            <p class="mt-1 text-sm text-slate-200 opacity-90">Consultez l'état et les résultats des compétitions de votre enfant.</p>
        </div>
        <a href="{{ route('parent.dashboard') }}" class="inline-flex items-center justify-center rounded-lg bg-white/20 px-4 py-2 text-sm font-medium text-white hover:bg-white/30 backdrop-blur-sm transition">
            Retour
        </a>
    </header>

    @if($competitions->count() > 0)
        <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3 mx-auto max-w-7xl">
            @foreach($competitions as $competition)
                <div class="group flex flex-col rounded-2xl bg-white shadow-sm border border-slate-100 transition-all duration-300 hover:shadow-xl overflow-hidden">
                    <!-- Status Header -->
                    <div class="p-4 border-b border-slate-50 bg-slate-50/50 flex items-center justify-between">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">ID: #{{ $competition->id }}</span>
                        <span class="rounded-full bg-emerald-100 px-2.5 py-0.5 text-[10px] font-bold text-emerald-700 capitalize border border-emerald-200">
                            {{ $competition->pivot->statut ?? 'En attente' }}
                        </span>
                    </div>

                    <!-- Card Body -->
                    <div class="flex-1 p-6">
                        <h3 class="text-lg font-bold text-slate-900 line-clamp-1">
                            {{ $competition->titre }}
                        </h3>
                        
                        <div class="mt-4 space-y-3">
                            <div class="flex items-center gap-3 text-xs">
                                <div class="h-8 w-8 rounded-lg bg-slate-100 flex items-center justify-center text-[#04371f]">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                </div>
                                <div class="flex flex-col">
                                    <span class="font-bold text-slate-400 uppercase text-[9px] tracking-tighter">Date de début</span>
                                    <span class="text-slate-700 font-medium">{{ \Carbon\Carbon::parse($competition->date_debut)->format('d/m/Y') }}</span>
                                </div>
                            </div>

                            <div class="flex items-center gap-3 text-xs">
                                <div class="h-8 w-8 rounded-lg bg-slate-100 flex items-center justify-center text-[#a48834]">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </div>
                                <div class="flex flex-col">
                                    <span class="font-bold text-slate-400 uppercase text-[9px] tracking-tighter">Votre rôle</span>
                                    <span class="text-slate-700 font-medium">Suivi parental</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Card Footer Actions -->
                    <div class="p-6 pt-0">
                        <a href="{{ route('parent.children.participations', [$student->id, $competition->id]) }}" class="flex items-center justify-center gap-2 w-full rounded-xl bg-slate-900 py-3 text-sm font-bold text-white transition-all hover:bg-slate-800 shadow-lg shadow-slate-900/10">
                            <span>Voir les résultats</span>
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="flex flex-col items-center justify-center rounded-3xl bg-white p-12 text-center shadow-sm border border-slate-100">
            <div class="h-20 w-20 rounded-full bg-slate-50 flex items-center justify-center text-slate-300 mb-4">
                 <svg class="h-10 w-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
            </div>
            <h3 class="text-xl font-bold text-slate-800">Aucune compétition</h3>
            <p class="mt-2 text-slate-500">Votre enfant n'est inscrit à aucune compétition pour le moment.</p>
        </div>
    @endif
</div>
@endsection

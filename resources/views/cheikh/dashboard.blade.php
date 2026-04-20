@extends('layouts.cheikh')

@section('title', 'Dashboard Cheikh')

@section('content')
    <header class="mb-6 rounded-2xl bg-gradient-to-br from-[#04371f] to-[#04371f]/80 p-6 text-white shadow-2xl relative overflow-hidden flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div class="relative z-10">
            <h2 class="text-2xl font-extrabold tracking-tight">Dashboard Cheikh</h2>
            <p class="mt-2 text-sm text-slate-200 font-medium tracking-wide">Bienvenue, Fadilat ach-Cheikh <span class="text-[#d4af37]">{{ $cheikh->nom }} {{ $cheikh->prenom }}</span></p>
        </div>

        
        <!-- Decorative subtle pattern -->
        <div class="absolute top-0 right-0 w-64 h-64 bg-[#d4af37] opacity-10 rounded-full blur-[80px] -mr-32 -mt-32"></div>
    </header>

    <div class="grid gap-6 md:grid-cols-3 mb-8">
        <!-- Card 1: Activités & Évaluations -->
        <article class="rounded-2xl bg-white p-6 shadow-lg border border-slate-100 flex flex-col justify-between">
            <div>
                <div class="flex items-center gap-3 mb-6 font-bold text-slate-800">
                    <div class="p-2 bg-teal-50 rounded-lg">
                        <svg class="w-6 h-6 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                    </div>
                    <span>Performance & Suivi</span>
                </div>
                <div class="space-y-4">
                    <div class="flex items-center justify-between p-3 bg-slate-50 rounded-xl border border-slate-100">
                        <span class="text-sm font-bold text-slate-600">Total Halaqas</span>
                        <span class="text-xl font-bold text-[#04371f]">{{ $halaqasCount }}</span>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div class="p-3 bg-emerald-50 rounded-xl border border-emerald-100">
                            <span class="block text-[10px] uppercase tracking-wider font-bold text-emerald-700 mb-1 leading-tight">Éval. Jour</span>
                            <span class="text-xl font-bold text-emerald-600">{{ $evaluationsToday }}</span>
                        </div>
                        <div class="p-3 bg-blue-50 rounded-xl border border-blue-100">
                            <span class="block text-[10px] uppercase tracking-wider font-bold text-blue-700 mb-1 leading-tight">Éval. Mois</span>
                            <span class="text-xl font-bold text-blue-600">{{ $evaluationsThisMonth }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </article>

        <!-- Card 2: Mes Étudiants -->
        <article class="relative overflow-hidden rounded-2xl bg-white p-6 shadow-lg border border-slate-100 flex flex-col items-center justify-center text-center">
            <div class="absolute top-4 left-4">
                <div class="p-2 bg-amber-50 rounded-lg">
                    <svg class="w-6 h-6 text-[#d4af37]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                </div>
            </div>
            <div class="mt-4 w-full">
                <div class="flex flex-col items-center justify-center p-4 bg-amber-50 rounded-2xl border border-amber-100 mb-2">
                    <span class="block text-[10px] uppercase tracking-wider font-bold text-amber-700 mb-1">Effectif Total</span>
                    <span class="text-3xl font-black text-[#d4af37]">{{ $totalActiveStudents }}</span>
                </div>
                <h3 class="text-lg font-bold text-slate-800 leading-tight">Étudiants Actifs</h3>
                <p class="mt-1 text-sm font-medium text-slate-500 tracking-wide">Membres engagés</p>
            </div>
        </article>

        <!-- Card 3: Compétitions -->
        <article class="rounded-2xl bg-white p-6 shadow-lg border border-slate-100 flex flex-col justify-between">
            <div class="flex items-center gap-3 mb-6 font-bold text-slate-800">
                <div class="p-2 bg-amber-50 rounded-lg text-[#d4af37]">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 6h2a2 2 0 012 2v2a6 6 0 01-6 6H10a6 6 0 01-6-6V8a2 2 0 012-2h2m0-2v2m8-2v2M8 20h8m-4-4v4"></path></svg>
                </div>
                <span>Compétitions</span>
            </div>
            <div class="space-y-4">
                <div class="p-4 bg-emerald-50 rounded-xl border border-emerald-100 flex flex-col items-center text-center">
                    <span class="block text-[10px] uppercase tracking-wider font-bold text-emerald-700 mb-1">Total Évaluées</span>
                    <span class="text-3xl font-black text-emerald-600 leading-none">{{ $competitionsEvaluated }}</span>
                </div>
                <div class="p-3 bg-slate-50 rounded-xl flex justify-between items-center px-4">
                    <span class="text-xs font-bold text-slate-600">Participations totales</span>
                    <span class="text-sm font-bold text-slate-900 bg-white px-2 py-1 rounded shadow-sm">{{ $participationsEvaluated }}</span>
                </div>
            </div>
        </article>
    </div>

    <section class="rounded-2xl bg-white p-8 shadow-xl border border-slate-100">
        <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between px-2">
            <div>
                <h2 class="text-xl font-bold text-slate-900">Mes Halaqas Actuelles</h2>
                <p class="text-sm text-slate-500 font-medium">Gestion et suivi de vos cercles d'apprentissage</p>
            </div>
            <a href="{{ route('cheikh.halaqas') }}" class="rounded-xl border border-[#04371f] px-5 py-2 text-sm font-bold text-[#04371f] hover:bg-[#04371f] hover:text-white transition shadow-sm inline-flex items-center gap-2 justify-center">
                Voir toutes
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
            </a>
        </div>

        <div class="overflow-x-auto rounded-2xl border border-slate-100">
           <table class="min-w-full text-sm">
            <thead class="bg-slate-50 text-left">
                <tr>
                    <th class="px-4 py-3">#</th>
                    <th class="px-4 py-3">Nom halaqa</th>
                    <th class="px-4 py-3">Capacite</th>
                    <th class="px-4 py-3">Etudiants</th>
                    <th class="px-4 py-3">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($halaqas as $halaqa)
                    <tr class="border-t border-slate-100">
                        <td class="px-4 py-3">{{ $halaqa->id }}</td>
                        <td class="px-4 py-3">{{ $halaqa->nom_halaqa }}</td>
                        <td class="px-4 py-3">{{ $halaqa->capacite }}</td>
                        <td class="px-4 py-3">{{ $halaqa->students->count() }}</td>
                        <td class="px-4 py-3">
                            <a href="{{ route('cheikh.halaqas.show', $halaqa->id) }}" class="rounded-md border border-emerald-300 px-3 py-1.5 text-emerald-700 hover:bg-emerald-50">
                                Voir
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-6 text-center text-slate-500">Aucune halaqa disponible.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        </div>
    </section>
@endsection

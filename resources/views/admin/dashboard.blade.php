@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@section('content')

    <header class="mb-6 rounded-2xl bg-gradient-to-br from-[#04371f] to-[#04371f]/80 p-6 text-white shadow-2xl relative overflow-hidden">
        <div class="relative z-10">
            <h2 class="text-2xl font-extrabold tracking-tight">Dashboard Admin</h2>
            <p class="mt-2 text-sm text-slate-200 font-medium">Vue d'ensemble de la plateforme Zad Al Atqa</p>
        </div>
        <!-- Decorative subtle pattern -->
        <div class="absolute top-0 right-0 w-64 h-64 bg-[#d4af37] opacity-10 rounded-full blur-[80px] -mr-32 -mt-32"></div>
    </header>

    <div class="grid gap-6 md:grid-cols-3 mb-8">
        <!-- Card 1: État des Comptes -->
        <article class="rounded-2xl bg-white p-6 shadow-lg border border-slate-100">
            <div class="flex items-center gap-3 mb-6">
                <div class="p-2 bg-emerald-50 rounded-lg">
                    <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                </div>
                <h3 class="font-bold text-slate-800">État des Comptes</h3>
            </div>
            <div class="space-y-4">
                <div class="flex items-center justify-between p-3 bg-slate-50 rounded-xl">
                    <span class="text-sm font-semibold text-slate-600">Total Utilisateurs</span>
                    <span class="text-xl font-bold text-[#04371f]">{{ $usersCount }}</span>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div class="p-3 bg-emerald-50 rounded-xl border border-emerald-100">
                        <span class="block text-[10px] uppercase tracking-wider font-bold text-emerald-700 mb-1">Actifs</span>
                        <span class="text-lg font-bold text-emerald-600">{{ $activeUsersCount }}</span>
                    </div>
                    <div class="p-3 bg-slate-100 rounded-xl border border-slate-200">
                        <span class="block text-[10px] uppercase tracking-wider font-bold text-slate-700 mb-1">Bloqués</span>
                        <span class="text-lg font-bold text-slate-600">{{ $inactiveUsersCount }}</span>
                    </div>
                </div>
            </div>
        </article>

        <!-- Card 2: Répartition des Rôles -->
        <article class="rounded-2xl bg-white p-6 shadow-lg border border-slate-100">
            <div class="flex items-center gap-3 mb-6">
                <div class="p-2 bg-teal-50 rounded-lg">
                    <svg class="w-6 h-6 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </div>
                <h3 class="font-bold text-slate-800">Communauté</h3>
            </div>
            <div class="grid grid-cols-3 gap-2">
                <div class="p-3 bg-indigo-50 rounded-xl text-center border border-indigo-100">
                    <span class="block text-[9px] uppercase tracking-wider font-bold text-indigo-700 mb-1">Cheikhs</span>
                    <span class="text-lg font-bold text-indigo-600">{{ $cheilhsCount }}</span>
                </div>
                <div class="p-3 bg-amber-50 rounded-xl text-center border border-amber-100">
                    <span class="block text-[9px] uppercase tracking-wider font-bold text-amber-700 mb-1">Étudiants</span>
                    <span class="text-lg font-bold text-amber-600">{{ $studentsCount }}</span>
                </div>
                <div class="p-3 bg-sky-50 rounded-xl text-center border border-sky-100">
                    <span class="block text-[9px] uppercase tracking-wider font-bold text-sky-700 mb-1">Parents</span>
                    <span class="text-lg font-bold text-sky-600">{{ $parentsCount }}</span>
                </div>
            </div>
            <div class="mt-4 p-3 bg-slate-50 rounded-xl text-center">
                <span class="text-xs font-semibold text-slate-500">Membres enregistrés sur la plateforme</span>
            </div>
        </article>

        <!-- Card 3: Activités -->
        <article class="rounded-2xl bg-white p-6 shadow-lg border border-slate-100">
            <div class="flex items-center gap-3 mb-6">
                <div class="p-2 bg-amber-50 rounded-lg text-[#d4af37]">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                </div>
                <h3 class="font-bold text-slate-800">Activités & Événements</h3>
            </div>
            <div class="space-y-3">
                <div class="flex items-center justify-between p-4 bg-emerald-50 rounded-2xl border border-emerald-100">
                    <div class="flex items-center gap-3">
                        <div class="w-2 h-2 rounded-full bg-emerald-600"></div>
                        <span class="text-sm font-bold text-emerald-900 tracking-tight">Halaqas</span>
                    </div>
                    <span class="text-2xl font-black text-emerald-600">{{ $halaqasCount }}</span>
                </div>
                <div class="flex items-center justify-between p-4 bg-[#d4af37]/10 rounded-2xl border border-[#d4af37]/20">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-white rounded-lg shadow-sm">
                            <svg class="w-6 h-6 text-[#d4af37]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 6h2a2 2 0 012 2v2a6 6 0 01-6 6H10a6 6 0 01-6-6V8a2 2 0 012-2h2m0-2v2m8-2v2M8 20h8m-4-4v4"></path></svg>
                        </div>
                        <span class="text-sm font-bold text-[#8c6d17] tracking-tight">Compétitions</span>
                    </div>
                    <span class="text-2xl font-black text-[#d4af37]">{{ $competitionsCount }}</span>
                </div>
            </div>
        </article>
    </div>

    <!-- Actions Rapides -->
    <h3 class="text-lg font-bold text-gray-800 mb-4 px-4 py-1 bg-[#d4af37]/80 w-fit rounded-lg ">
        Actions Rapides
    </h3>
    <section class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <a href="{{ route('users.index') }}" class="group relative overflow-hidden rounded-2xl bg-white p-6 shadow-sm border border-slate-100 transition-all hover:shadow-xl hover:border-[#04371f]/20">
            <h3 class="font-bold text-slate-900 group-hover:text-[#04371f] transition">Gérer Utilisateurs</h3>
            <p class="mt-1 text-xs text-slate-500">Modifier, bloquer ou supprimer</p>
            <div class="mt-4 flex justify-end">
                <span class="text-[#04371f] font-bold text-sm flex items-center gap-1 opacity-0 group-hover:opacity-100 transition">
                    Accéder <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </span>
            </div>
        </a>

        <a href="{{ route('users.create') }}" class="group relative overflow-hidden rounded-2xl bg-white p-6 shadow-sm border border-slate-100 transition-all hover:shadow-xl hover:border-[#04371f]/20">
            <h3 class="font-bold text-slate-900 group-hover:text-[#04371f] transition">Ajouter Nouveau</h3>
            <p class="mt-1 text-xs text-slate-500">Créer un compte utilisateur</p>
            <div class="mt-4 flex justify-end">
                <span class="text-[#04371f] font-bold text-sm flex items-center gap-1 opacity-0 group-hover:opacity-100 transition">
                    Créer <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                </span>
            </div>
        </a>

        <a href="{{ route('halaqas.index') }}" class="group relative overflow-hidden rounded-2xl bg-white p-6 shadow-sm border border-slate-100 transition-all hover:shadow-xl hover:border-[#04371f]/20">
            <h3 class="font-bold text-slate-900 group-hover:text-[#04371f] transition">Liste des Halaqas</h3>
            <p class="mt-1 text-xs text-slate-500">Suivi des cercles d'apprentissage</p>
            <div class="mt-4 flex justify-end">
                <span class="text-[#04371f] font-bold text-sm flex items-center gap-1 opacity-0 group-hover:opacity-100 transition">
                    Consulter <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </span>
            </div>
        </a>

        <a href="{{ route('halaqas.create') }}" class="group relative overflow-hidden rounded-2xl bg-white p-6 shadow-sm border border-slate-100 transition-all hover:shadow-xl hover:border-[#04371f]/20">
            <h3 class="font-bold text-slate-900 group-hover:text-[#04371f] transition">Créer Halaqa</h3>
            <p class="mt-1 text-xs text-slate-500">Associer à un enseignant</p>
            <div class="mt-4 flex justify-end">
                <span class="text-[#04371f] font-bold text-sm flex items-center gap-1 opacity-0 group-hover:opacity-100 transition">
                    Créer <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                </span>
            </div>
        </a>
    </section>

@endsection

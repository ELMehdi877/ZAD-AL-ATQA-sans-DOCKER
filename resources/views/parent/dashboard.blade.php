@extends('layouts.user-navbar')

@section('title', 'Dashboard Parent')

@section('content')
    <header class="mb-6 rounded-2xl bg-gradient-to-r from-[#04371f] to-[#04371f]/80 p-6 text-white shadow-xl flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-2xl font-bold">Dashboard Parent</h2>
            <p class="mt-2 text-sm text-slate-200">Liste de vos enfants et de leurs acces rapides.</p>
        </div>

    </header>

    <section class="rounded-xl bg-white p-6 shadow">
        <div class="mb-4 flex items-center justify-between">
            <h2 class="text-lg font-semibold text-slate-900">Mes enfants</h2>
            <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-600">{{ $students->count() }} enfant(s)</span>
        </div>

        <div class="overflow-hidden rounded-lg border border-slate-200">
            <table class="min-w-full text-sm">
                <thead class="bg-slate-100 text-slate-700">
                    <tr>
                        <th class="px-4 py-3 text-left">#</th>
                        <th class="px-4 py-3 text-left">Nom</th>
                        <th class="px-4 py-3 text-left">Prenom</th>
                        <th class="px-4 py-3 text-left">Nombre hifz</th>
                        <th class="px-4 py-3 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($students as $student)
                        <tr class="border-t border-slate-100">
                            <td class="px-4 py-3">{{ $student->id }}</td>
                            <td class="px-4 py-3">{{ $student->user->nom ?? '-' }}</td>
                            <td class="px-4 py-3">{{ $student->user->prenom ?? '-' }}</td>
                            <td class="px-4 py-3">{{ $student->nombre_hifz ?? '0' }}</td>
                            <td class="px-4 py-3">
                                <div class="flex flex-wrap gap-2">
                                    <a href="{{ route('parent.children.halaqas', $student->id) }}" class="rounded-md bg-slate-900 px-3 py-2 text-xs font-medium text-white hover:bg-slate-700">Halaqas</a>
                                    <a href="{{ route('parent.children.evaluations.historique', $student->id) }}" class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-medium text-white hover:bg-slate-700">Historique complet</a>
                                    <a href="{{ route('parent.children.competitions', $student->id) }}" class="rounded-md border border-slate-300 px-3 py-2 text-xs font-medium text-slate-700 hover:bg-slate-50">Competitions</a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-6 text-center text-slate-500">Aucun enfant lie a ce compte.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endsection

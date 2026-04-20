@extends('layouts.cheikh')

@section('title', 'Halaqas Cheikh')

@section('content')
    <header class="mb-6 rounded-2xl bg-gradient-to-r from-[#04371f] to-[#04371f]/80 p-6 text-white shadow-xl">
        <h2 class="text-2xl font-bold">Mes halaqas</h2>
        <p class="mt-2 text-sm text-slate-200">Affichage via une seule methode index de HalaqaController.</p>
    </header>

    <section class="overflow-hidden rounded-xl bg-white shadow">
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
    </section>
@endsection

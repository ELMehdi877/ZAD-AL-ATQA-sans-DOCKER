@extends('layouts.user-navbar')

@section('title', 'Halaqas de l\'enfant')

@section('content')
    <header class="mb-6 rounded-2xl bg-gradient-to-r from-[#04371f] to-[#04371f]/80 p-6 text-white shadow-xl flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold">Halaqas de {{ $student->user->nom ?? '-' }} {{ $student->user->prenom ?? '' }}</h2>
            <p class="mt-2 text-sm text-slate-200">Liste des halaqas liées à cet enfant.</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('parent.dashboard') }}" class="rounded-lg bg-white/20 px-4 py-2 text-sm font-medium text-white hover:bg-white/30 backdrop-blur-sm transition">Retour</a>
        </div>
    </header>

        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-100 border-b">
                    <tr>
                        <th class="px-6 py-3 text-left">#</th>
                        <th class="px-6 py-3 text-left">Halaqa</th>
                        <th class="px-6 py-3 text-left">Cheikh</th>
                        <th class="px-6 py-3 text-left">statut</th>
                        <th class="px-6 py-3 text-left">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($halaqas as $halaqa)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-6 py-4">{{ $halaqa->id }}</td>
                            <td class="px-6 py-4 font-medium text-gray-900">{{ $halaqa->nom_halaqa }}</td>
                            <td class="px-6 py-4">{{ $halaqa->cheikh->nom ?? '-' }} {{ $halaqa->cheikh->prenom ?? '' }}</td>
                            <td class="px-6 py-4">{{ $halaqa->pivot->statut ?? '-' }}</td>
                            <td class="px-6 py-4">
                                <a href="{{ route('parent.children.evaluations', [$student->id, $halaqa->id]) }}" class="rounded-md bg-slate-900 px-3 py-2 text-xs font-medium text-white hover:bg-slate-700">Voir evaluations</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-6 text-center text-gray-500">Aucune halaqa pour cet enfant.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
@endsection

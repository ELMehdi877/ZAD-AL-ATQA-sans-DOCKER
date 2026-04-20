@extends('layouts.admin')

@section('title', 'Liste Halaqas')

@section('content')

    <header class="mb-6 rounded-2xl bg-gradient-to-r from-[#04371f] to-[#04371f]/80 p-6 text-white shadow-xl flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold">Halaqas</h2>
            <p class="mt-2 text-sm text-slate-200">Liste complete des halaqas.</p>
        </div>
        <a href="{{ route('halaqas.create') }}" class="rounded-lg bg-white/20 px-4 py-2 text-sm font-medium text-white hover:bg-white/30 backdrop-blur-sm transition">Nouvelle halaqa</a>
    </header>

    <section class="overflow-hidden rounded-xl bg-white shadow">
        <table class="min-w-full border-collapse text-sm">
            <thead>
                <tr class="border-b bg-slate-50 text-left">
                    <th class="px-3 py-2">ID</th>
                    <th class="px-3 py-2">Nom halaqa</th>
                    <th class="px-3 py-2">Capacite</th>
                    <th class="px-3 py-2">Nombre d'etudiants</th>
                    <th class="px-3 py-2">Cheikh ID</th>
                    <th class="px-3 py-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($halaqas as $halaqa)
                    <tr class="border-b hover:bg-slate-50">
                        <td class="px-3 py-2">{{ $halaqa->id }}</td>
                        <td class="px-3 py-2">{{ $halaqa->nom_halaqa }}</td>
                        <td class="px-3 py-2">{{ $halaqa->capacite }}</td>
                        <td class="px-3 py-2">{{ $halaqa->students->count() }}</td>
                        <td class="px-3 py-2">{{ $halaqa->cheikh_id }}</td>
                        <td class="px-3 py-2">
                            <div class="flex flex-wrap gap-2">
                                <a href="{{ route('halaqas.show', ['halaqa' => $halaqa->id]) }}" class="rounded bg-emerald-100 px-2 py-1 font-medium text-emerald-700 hover:bg-emerald-200">Entrer</a>
                                <a href="{{ route('halaqas.edit', ['halaqa' => $halaqa->id]) }}" class="rounded bg-blue-100 px-2 py-1 font-medium text-blue-700 hover:bg-blue-200">Modifier</a>

                                <form method="POST" action="{{ route('halaqas.destroy', ['halaqa' => $halaqa->id]) }}" class="inline delete-form" data-halaqa="{{ $halaqa->nom_halaqa }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="rounded bg-rose-100 px-2 py-1 font-medium text-rose-700 hover:bg-rose-200">Supprimer</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-3 py-6 text-center text-slate-500">Aucune halaqa trouvee.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </section>
@endsection

@section('scripts')
    <script>
        document.querySelectorAll('.delete-form').forEach((form) => {
            form.addEventListener('submit', (event) => {
                const halaqaName = form.dataset.halaqa || 'cette halaqa';
                const ok = window.confirm(`Supprimer ${halaqaName} ?`);
                if (!ok) {
                    event.preventDefault();
                }
            });
        });
    </script>
@endsection

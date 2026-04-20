@extends('layouts.admin')

@section('title', 'Utilisateurs')

@section('content')

    <header class="mb-6 rounded-2xl bg-gradient-to-r from-[#04371f] to-[#04371f]/80 p-6 text-white shadow-xl flex flex-wrap items-center justify-between gap-3">
        <div>
            <h2 class="text-2xl font-bold">Utilisateurs</h2>
            <p class="mt-2 text-sm text-slate-200">Gestion complete des comptes.</p>
            <p class="mt-1 text-sm font-medium text-slate-300">
                Nombre d'utilisateurs: <span class="font-bold text-white">{{ $users->count() }}</span>
            </p>
        </div>
        <a href="{{ route('users.create') }}" class="rounded-lg bg-white/20 px-4 py-2 text-sm font-medium text-white hover:bg-white/30 backdrop-blur-sm transition">Nouveau utilisateur</a>
    </header>

    <section class="mb-6 rounded-xl bg-white p-4 shadow">
        <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">
            <form method="GET" action="{{ route('user.search') }}" class="flex flex-col gap-3 sm:flex-row sm:items-end">
                <div class="w-full sm:max-w-md">
                    <label for="nom" class="mb-1 block text-sm font-medium">Recherche par nom</label>
                    <input id="nom" name="nom" type="text" required class="w-full rounded-lg border border-slate-300 px-3 py-2" value="{{ request('nom') }}" placeholder="Ex: Ahmed">
                </div>
                <button type="submit" class="rounded-lg bg-slate-900 px-4 py-2 font-medium text-white hover:bg-slate-700">Rechercher</button>
                <a href="{{ route('users.index') }}" class="rounded-lg border border-slate-300 px-4 py-2 text-center font-medium hover:bg-slate-50">Reset</a>
            </form>

            <form method="GET" action="{{ route('user.filter') }}" class="flex flex-col gap-3 sm:flex-row sm:items-end lg:justify-end">
                <div class="w-full sm:max-w-xs">
                    <label for="role" class="mb-1 block text-sm font-medium">Filtrer par role</label>
                    <select id="role" name="role" required class="w-full rounded-lg border border-slate-300 px-3 py-2">
                        <option value="">-- choisir --</option>
                        @foreach (['admin', 'cheikh', 'student', 'parent'] as $role)
                            <option value="{{ $role }}" @selected(request('role') === $role)>{{ $role }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="rounded-lg bg-blue-600 px-4 py-2 font-medium text-white hover:bg-blue-500">Filtrer</button>
            </form>
        </div>
    </section>

    <section class="overflow-x-auto rounded-xl bg-white shadow">
        <table class="min-w-full border-collapse text-sm">
            <thead>
                <tr class="border-b bg-slate-50 text-left">
                    <th class="px-3 py-2">ID</th>
                    <th class="px-3 py-2">Nom</th>
                    <th class="px-3 py-2">Prenom</th>
                    <th class="px-3 py-2">Email</th>
                    <th class="px-3 py-2">Telephone</th>
                    <th class="px-3 py-2">Role</th>
                    <th class="px-3 py-2">Statut</th>
                    <th class="px-3 py-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $user)
                    <tr class="border-b hover:bg-slate-50">
                        <td class="px-3 py-2">{{ $user->id }}</td>
                        <td class="px-3 py-2">{{ $user->nom }}</td>
                        <td class="px-3 py-2">{{ $user->prenom }}</td>
                        <td class="px-3 py-2">{{ $user->email }}</td>
                        <td class="px-3 py-2">{{ $user->telephone ?? '-' }}</td>
                        <td class="px-3 py-2">{{ $user->role }}</td>
                        <td class="px-3 py-2">
                            <span class="rounded-full px-2 py-1 text-xs {{ $user->statut === 'active' ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-200 text-slate-700' }}">
                                {{ $user->statut }}
                            </span>
                        </td>
                        <td class="px-3 py-2">
                            <div class="flex flex-wrap gap-2">
                                <a href="{{ route('users.edit', ['user' => $user->id]) }}" class="rounded bg-blue-100 px-2 py-1 font-medium text-blue-700 hover:bg-blue-200">Modifier</a>

                                <form method="POST" action="{{ route('user.statut', ['id' => $user->id]) }}" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="rounded bg-amber-100 px-2 py-1 font-medium text-amber-800 hover:bg-amber-200">Statut</button>
                                </form>

                                <form method="POST" action="{{ route('user.delete', ['id' => $user->id]) }}" class="inline delete-form" data-user="{{ $user->nom }} {{ $user->prenom }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="rounded bg-rose-100 px-2 py-1 font-medium text-rose-700 hover:bg-rose-200">Supprimer</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-3 py-5 text-center text-slate-500">Aucun utilisateur trouve.</td>
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
                const userName = form.dataset.user || 'cet utilisateur';
                const ok = window.confirm(`Supprimer ${userName} ?`);
                if (!ok) {
                    event.preventDefault();
                }
            });
        });
    </script>
@endsection

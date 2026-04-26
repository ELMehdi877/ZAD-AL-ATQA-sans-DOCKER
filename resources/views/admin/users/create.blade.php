@extends('layouts.admin')

@section('title', 'Creer Utilisateur')

@section('content')

    <header class="mb-6 rounded-2xl bg-gradient-to-r from-[#04371f] to-[#04371f]/80 p-6 text-white shadow-xl flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold">Creer un utilisateur</h2>
            <p class="mt-2 text-sm text-slate-200">Ajout d'un nouveau compte.</p>
        </div>
        <a href="{{ route('users.index') }}" class="rounded-lg bg-white/20 px-4 py-2 text-sm font-medium text-white hover:bg-white/30 backdrop-blur-sm transition">Retour liste</a>
    </header>

    <section class="max-w-3xl rounded-xl bg-white p-5 shadow">
        <form method="POST" action="{{ route('users.store') }}" class="grid grid-cols-1 gap-3 sm:grid-cols-2">
            @csrf
            <div>
                <label class="mb-1 block text-sm font-medium" for="nom">Nom</label>
                <input id="nom" name="nom" type="text" required class="w-full rounded-lg border border-slate-300 px-3 py-2" value="{{ old('nom') }}">
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium" for="prenom">Prenom</label>
                <input id="prenom" name="prenom" type="text" required class="w-full rounded-lg border border-slate-300 px-3 py-2" value="{{ old('prenom') }}">
            </div>
            <div class="sm:col-span-2">
                <label class="mb-1 block text-sm font-medium" for="email">Email</label>
                <input id="email" name="email" type="email" required class="w-full rounded-lg border border-slate-300 px-3 py-2" value="{{ old('email') }}">
            </div>
            <div class="sm:col-span-2">
                <label class="mb-1 block text-sm font-medium" for="telephone">Telephone</label>
                <input id="telephone" name="telephone" type="text" class="w-full rounded-lg border border-slate-300 px-3 py-2" value="{{ old('telephone') }}" placeholder="Ex: 0612345678">
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium" for="password">Mot de passe</label>
                <input id="password" name="password" type="password" required class="w-full rounded-lg border border-slate-300 px-3 py-2">
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium" for="password_confirmation">Confirmation</label>
                <input id="password_confirmation" name="password_confirmation" type="password" required class="w-full rounded-lg border border-slate-300 px-3 py-2">
            </div>
            <div class="sm:col-span-2">
                <label class="mb-1 block text-sm font-medium" for="role">Role</label>
                <select id="role" name="role" required class="w-full rounded-lg border border-slate-300 px-3 py-2">
                    <option value="">-- choisir --</option>
                    @foreach (['admin', 'student', 'parent', 'cheikh'] as $role)
                        <option value="{{ $role }}" @selected(old('role') === $role)>{{ $role }}</option>
                    @endforeach
                </select>
            </div>
            <div id="parent-field" class="sm:col-span-2 hidden">
                <label class="mb-1 block text-sm font-medium" for="parent_id">Parent du student (optionnel)</label>
                <select id="parent_id" name="parent_id" class="w-full rounded-lg border border-slate-300 px-3 py-2">
                    <option value="">-- choisir un parent --</option>
                    @foreach ($parents as $parent)
                        <option value="{{ $parent->id }}" @selected(old('parent_id') == $parent->id)>
                            #{{ $parent->id }} - {{ $parent->nom }} {{ $parent->prenom }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div id="hifz-field" class="sm:col-span-2 hidden">
                <label class="mb-1 block text-sm font-medium" for="nombre_hifz">Nombre de hifz</label>
                <input id="nombre_hifz" name="nombre_hifz" type="number" min="0" max="60" class="w-full rounded-lg border border-slate-300 px-3 py-2" value="{{ old('nombre_hifz') }}">
            </div>
            <div class="sm:col-span-2">
                <button type="submit" class="rounded-lg bg-emerald-600 px-4 py-2 font-medium text-white hover:bg-emerald-500">Creer l'utilisateur</button>
            </div>
        </form>
    </section>
@endsection

@section('scripts')
    <script>
        const roleSelect = document.getElementById('role');
        const parentField = document.getElementById('parent-field');
        const hifzField = document.getElementById('hifz-field');

        function toggleParentField() {
            if (roleSelect.value === 'student') {
                parentField.classList.remove('hidden');
                hifzField.classList.remove('hidden');
            } else {
                parentField.classList.add('hidden');
                hifzField.classList.add('hidden');
            }
        }

        roleSelect.addEventListener('change', toggleParentField);
        toggleParentField();
    </script>
@endsection

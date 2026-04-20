@extends('layouts.cheikh')

@section('content')
<div class="text-white">

    <header class="mb-6 rounded-2xl bg-gradient-to-r from-[#04371f] to-[#04371f]/80 p-6 text-white shadow-xl">
        <h2 class="text-2xl font-bold">Créer une réunion</h2>
        <p class="mt-2 text-sm text-slate-200">Générer un lien Daily pour une de vos halaqas.</p>
    </header>

    @if ($errors->any())
        <div class="bg-red-500 text-white p-4 rounded mb-4">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('meetings.store') }}" class="space-y-4">
        @csrf

        <div>
            <label for="halaqa_id" class="block mb-2 font-medium">Choisir une halaqa</label>
            <select name="halaqa_id" id="halaqa_id" class="w-full rounded text-black">
                <option value="">-- Sélectionner une halaqa --</option>
                @foreach ($halaqas as $halaqa)
                    <option value="{{ $halaqa->id }}">{{ $halaqa->nom_halaqa }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit"
            class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700">
            Créer une réunion
        </button>
    </form>

</div>
@endsection
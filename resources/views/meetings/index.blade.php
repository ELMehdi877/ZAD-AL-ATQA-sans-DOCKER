@php
    $role = auth()->user()?->role;
    $layout = match($role) {
        'admin', 'cheikh' => 'layouts.admin',
        'student', 'parent' => 'layouts.user-navbar',
        default => 'layouts.app',
    };
@endphp

@extends($layout)

@section('content')
<div class="min-h-full bg-slate-50 ">
    <div class="w-full">
        <header class="mb-6 rounded-2xl bg-gradient-to-r from-[#04371f] to-[#04371f]/80 p-6 text-white shadow-xl flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-2xl font-bold">Réunions</h2>
                <p class="mt-2 text-sm text-slate-200">Liens Daily disponibles pour votre halaqa.</p>
            </div>

            @if (auth()->user()->role === 'cheikh')
                <a href="{{ route('meetings.create') }}" class="inline-flex items-center justify-center rounded-lg bg-white/20 px-4 py-2 text-sm font-semibold text-white transition hover:bg-white/30 backdrop-blur-sm">
                    Nouvelle réunion
                </a>
            @endif
        </header>

        @forelse ($meetings as $meeting)
            @php
                $meetingName = $meeting->meeting_name ?? $meeting->name ?? 'Séance en direct';
                $halaqaName = $meeting->halaqa->nom_halaqa ?? 'Halaqa';
            @endphp

            <div class="mb-4 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
                    <div class="space-y-2">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wide text-sky-600">{{ $halaqaName }}</p>
                            <h2 class="mt-1 text-xl font-bold text-slate-900">{{ $meetingName }}</h2>
                        </div>

                        <p class="text-sm text-slate-500">Acces securise par token Daily.</p>
                    </div>

                    <div class="flex flex-col gap-3 sm:flex-row">
                        <a href="{{ route('meetings.join', $meeting) }}" class="inline-flex items-center justify-center rounded-lg bg-sky-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-sky-700">
                            Rejoindre la reunion
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="rounded-2xl border border-dashed border-slate-300 bg-white px-6 py-12 text-center shadow-sm">
                <h2 class="text-lg font-semibold text-slate-900">Aucune séance active</h2>
                <p class="mt-2 text-sm text-slate-500">Il n’y a pas de réunion disponible pour votre halaqa pour le moment.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection
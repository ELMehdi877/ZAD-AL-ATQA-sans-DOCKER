@extends('layouts.user-navbar')

@section('content')
    <header class="mb-6 rounded-2xl bg-gradient-to-r from-[#04371f] to-[#04371f]/80 p-6 text-white shadow-xl">
        <h1 class="text-2xl font-bold">Compétitions Disponibles</h1>
        <p class="mt-2 text-sm text-slate-200">Relevez le défi, perfectionnez votre récitation et participez aux compétitions organisées par Zad Al Atqa.</p>
    </header>

    @if($competitions->count() > 0)
        @php $currentStudentId = auth()->user()?->student?->id; @endphp
        
        <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
            @foreach($competitions as $competition)
                <div class="group flex flex-col rounded-2xl bg-white shadow-sm border border-slate-100 transition-all duration-300 hover:shadow-xl hover:-translate-y-1 overflow-hidden">
                    <!-- Card Status/Header -->
                    <div class="relative h-24 bg-slate-50 flex items-center justify-center overflow-hidden">
                        <div class="absolute inset-0 bg-gradient-to-br from-[#04371f]/50 to-transparent"></div>
                        <div class="z-10 rounded-full bg-white/80 backdrop-blur-md px-4 py-1.5 shadow-sm border border-slate-100">
                             <span class="text-[10px] font-bold uppercase tracking-wider text-[#04371f]">Ouvert à tous</span>
                        </div>
                    </div>

                    <!-- Card Body -->
                    <div class="flex-1 p-6">
                        <h3 class="text-xl font-bold text-slate-900 group-hover:text-[#04371f] transition-colors line-clamp-1">
                            {{ $competition->titre }}
                        </h3>
                        
                        <p class="mt-3 text-sm text-slate-600 line-clamp-2 min-h-[40px] tranckute">
                            {{ $competition->description ?? 'Aucune description disponible pour cette compétition.' }}
                        </p>

                        <div class="mt-6 space-y-3">
                            <!-- Dates Info -->
                            <div class="flex items-center gap-3 text-xs text-slate-500">
                                <div class="h-8 w-8 rounded-lg bg-slate-100 flex items-center justify-center text-[#04371f]">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                </div>
                                <div class="flex flex-col">
                                    <span class="font-bold text-slate-400 uppercase text-[9px] tracking-tighter">Période</span>
                                    <span class="text-slate-700 font-medium">{{ \Carbon\Carbon::parse($competition->date_debut)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($competition->date_fin)->format('d/m/Y') }}</span>
                                </div>
                            </div>

                            <!-- Participants Info -->
                            <div class="flex items-center gap-3 text-xs text-slate-500">
                                <div class="h-8 w-8 rounded-lg bg-slate-100 flex items-center justify-center text-[#a48834]">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                </div>
                                <div class="flex flex-col">
                                    <span class="font-bold text-slate-400 uppercase text-[9px] tracking-tighter">Participants</span>
                                    <span class="text-slate-700 font-medium">{{ $competition->students()->count() }} inscrit(s)</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Card Footer -->
                    <div class="p-6 pt-0">
                        @if($competition->students->contains('id', $currentStudentId))
                            <div class="flex items-center justify-center gap-2 rounded-xl bg-emerald-50 py-3 text-sm font-bold text-emerald-700 border border-emerald-100">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <span>Déjà inscrit</span>
                            </div>
                        @else
                            <form action="{{ route('student.participate', $competition->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full rounded-xl bg-[#04371f] py-3 text-sm font-bold text-white shadow-lg shadow-[#04371f]/20 transition-all hover:bg-[#054527] hover:shadow-xl active:scale-[0.98]">
                                    S'inscrire maintenant
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="flex flex-col items-center justify-center rounded-3xl bg-white p-12 text-center shadow-sm border border-slate-100">
            <div class="h-20 w-20 rounded-full bg-slate-50 flex items-center justify-center text-slate-300 mb-4">
                 <svg class="h-10 w-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
            </div>
            <h3 class="text-xl font-bold text-slate-800">Aucune compétition disponible</h3>
            <p class="mt-2 text-slate-500">Les prochaines compétitions seront annoncées prochainement. Restez connectés !</p>
        </div>
    @endif

@endsection

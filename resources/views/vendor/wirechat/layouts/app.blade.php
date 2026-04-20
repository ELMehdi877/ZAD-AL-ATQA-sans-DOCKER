<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@php
    $currentPanel = \Wirechat\Wirechat\Facades\Wirechat::currentPanel();
    $title = $currentPanel->getHeading() ?? config('app.name', 'Laravel');
    $user = auth()->user();
    $role = $user?->role;
    $chatUrl = route('wirechat.chats.chats');
@endphp
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title }}</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; -webkit-font-smoothing: antialiased; -moz-osx-font-smoothing: grayscale; }
        
        @media (min-width: 1024px) {
            .wirechat-admin-shell {
                grid-template-columns: 260px 1fr;
                transition: grid-template-columns 0.3s ease-in-out;
            }

            .wirechat-admin-shell.sidebar-collapsed {
                grid-template-columns: 88px 1fr;
            }

            .wirechat-mobile-sidebar {
                transition: width 0.3s ease-in-out;
            }

            .wirechat-admin-shell.sidebar-collapsed .sidebar-brand-full,
            .wirechat-admin-shell.sidebar-collapsed .sidebar-label,
            .wirechat-admin-shell.sidebar-collapsed .sidebar-user-info,
            .wirechat-admin-shell.sidebar-collapsed .sidebar-section-title {
                display: none;
            }

            .wirechat-admin-shell.sidebar-collapsed .sidebar-brand-collapsed {
                display: block;
            }

            .wirechat-admin-shell.sidebar-collapsed .sidebar-nav a,
            .wirechat-admin-shell.sidebar-collapsed .sidebar-footer a,
            .wirechat-admin-shell.sidebar-collapsed .sidebar-footer button {
                text-align: center;
                padding-left: 0.25rem;
                padding-right: 0.25rem;
            }

            .wirechat-admin-shell.sidebar-collapsed .sidebar-nav a::before,
            .wirechat-admin-shell.sidebar-collapsed .sidebar-footer a::before,
            .wirechat-admin-shell.sidebar-collapsed .sidebar-footer button::before {
                content: attr(data-short);
                display: block;
                font-size: 11px;
                font-weight: 700;
            }
        }

        .sidebar-brand-collapsed {
            display: none;
        }

        @media (max-width: 1023px) {
            .wirechat-mobile-sidebar {
                position: fixed;
                top: 0;
                left: 0;
                bottom: 0;
                width: 260px;
                max-width: 85vw;
                z-index: 100;
                transform: translateX(-100%);
                transition: transform 0.3s ease-in-out;
            }

            .wirechat-admin-shell.mobile-sidebar-open .wirechat-mobile-sidebar {
                transform: translateX(0);
            }

            .wirechat-mobile-overlay {
                display: none;
                position: fixed;
                inset: 0;
                background: rgba(4, 55, 31, 0.45);
                backdrop-filter: blur(4px);
                z-index: 90;
            }

            .wirechat-admin-shell.mobile-sidebar-open .wirechat-mobile-overlay {
                display: block;
            }
        }
        
        .mobile-drawer {
            transform: translateX(100%);
            transition: transform 0.3s ease-in-out;
        }
        
        .mobile-drawer.open {
            transform: translateX(0);
        }
        
        .drawer-overlay {
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s ease-in-out;
        }
        
        .drawer-overlay.open {
            opacity: 1;
            pointer-events: auto;
        }

        #wirechat-user-nav-toggle, #wirechat-user-nav-toggle * {
            transition: none !important;
        }

        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>

    @if($currentPanel->hasFavicon())
        <link rel="icon" href="{{ $currentPanel->getFavicon() }}" />
    @endif

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    @wirechatStyles(panel: $panel)
</head>
<body class="antialiased min-h-screen bg-slate-50 text-slate-900">
    @if (in_array($role, ['admin', 'cheikh'], true))
        @php
            $isAdmin = $role === 'admin';
            $isCheikh = $role === 'cheikh';
        @endphp
        <div id="layout-shell" class="group layout-shell min-h-screen lg:grid lg:grid-cols-[260px_1fr] lg:transition-[grid-template-columns] lg:duration-300 lg:ease-in-out [&.sidebar-collapsed]:lg:grid-cols-[88px_1fr]">
            <aside id="app-sidebar" class="app-sidebar flex h-screen flex-col bg-[#04371f] text-white overflow-y-auto fixed inset-y-0 left-0 z-50 w-[260px] max-w-[85vw] -translate-x-full lg:sticky lg:top-0 lg:translate-x-0 lg:w-full lg:max-w-none transition-transform duration-300 ease-in-out group-[.mobile-sidebar-open]:translate-x-0">
                <div class="relative pt-8 pb-6 border-b border-[#1e583d] flex flex-col items-center justify-center group-[.sidebar-collapsed]:lg:hidden">
                    <!-- Close Button Inside Drawer (Mobile Only) -->
                    <button id="mobile-sidebar-close" type="button" class="absolute top-4 right-4 p-2 rounded-lg bg-[#094d2c] border border-[#1e583d] text-white hover:bg-[#0a5c34] lg:hidden">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>

                    <!-- Centered Branding -->
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" width="100" height="100" class=" object-contain mb-1 ">
                    <div class="rounded-full border-[1px] border-[#6C4A0C] bg-[#FFFFFF]/15 px-4 flex items-center py-1">
                        <span class="text-[#7F6224] text-[9px] font-bold tracking-[0.15em] uppercase">{{ $isCheikh ? 'CHEIKH' : 'ADMINISTRATEUR' }}</span>
                    </div>
                </div>

                <nav class="sidebar-nav flex-1 px-8 py-6 space-y-5">
                    <!-- GÉNÉRAL -->
                    <div>
                        <h3 class="mb-2 px-3 text-[8px] font-bold uppercase tracking-widest text-[#8da496] group-[.sidebar-collapsed]:lg:hidden">Général</h3>
                        <div class="space-y-2">
                            <a href="{{ $isCheikh ? route('cheikh.dashboard') : route('admin.dashboard') }}" data-short="DB" class="block rounded-[9px] px-3 py-2 text-[11px] font-semibold group-[.sidebar-collapsed]:lg:before:content-[attr(data-short)] group-[.sidebar-collapsed]:lg:text-center group-[.sidebar-collapsed]:lg:px-1 {{ request()->routeIs('*.dashboard') || request()->is('admin') || request()->is('cheikh') ? 'border border-[#d4af37] text-[#d4af37] bg-transparent' : 'border border-transparent bg-[#094d2c] text-white hover:bg-[#0a5c34]' }}">
                                <span class="sidebar-label group-[.sidebar-collapsed]:lg:hidden">Dashboard</span>
                            </a>
                        </div>
                    </div>

                    <!-- GESTION -->
                    <div>
                        <h3 class="mb-2 px-3 text-[8px] font-bold uppercase tracking-widest text-[#8da496] group-[.sidebar-collapsed]:lg:hidden">Gestion</h3>
                        <div class="space-y-2">
                            @if ($isAdmin)
                                <a href="{{ route('users.index') }}" data-short="US" class="block rounded-[9px] px-3 py-2 text-[11px] font-semibold group-[.sidebar-collapsed]:lg:before:content-[attr(data-short)] group-[.sidebar-collapsed]:lg:text-center group-[.sidebar-collapsed]:lg:px-1 {{ request()->routeIs('users.*') ? 'border border-[#d4af37] text-[#d4af37] bg-transparent' : 'border border-transparent bg-[#094d2c] text-white hover:bg-[#0a5c34]' }}">
                                    <span class="sidebar-label group-[.sidebar-collapsed]:lg:hidden">Utilisateurs</span>
                                </a>
                                <a href="{{ route('halaqas.index') }}" data-short="HA" class="block rounded-[9px] px-3 py-2 text-[11px] font-semibold group-[.sidebar-collapsed]:lg:before:content-[attr(data-short)] group-[.sidebar-collapsed]:lg:text-center group-[.sidebar-collapsed]:lg:px-1 {{ request()->routeIs('halaqas.*') ? 'border border-[#d4af37] text-[#d4af37] bg-transparent' : 'border border-transparent bg-[#094d2c] text-white hover:bg-[#0a5c34]' }}">
                                    <span class="sidebar-label group-[.sidebar-collapsed]:lg:hidden">Halaqat</span>
                                </a>
                            @endif

                            @if ($isCheikh)
                                <a href="{{ route('cheikh.halaqas') }}" data-short="HA" class="block rounded-[9px] px-3 py-2 text-[11px] font-semibold group-[.sidebar-collapsed]:lg:before:content-[attr(data-short)] group-[.sidebar-collapsed]:lg:text-center group-[.sidebar-collapsed]:lg:px-1 {{ request()->routeIs('cheikh.halaqas*') ? 'border border-[#d4af37] text-[#d4af37] bg-transparent' : 'border border-transparent bg-[#094d2c] text-white hover:bg-[#0a5c34]' }}">
                                    <span class="sidebar-label group-[.sidebar-collapsed]:lg:hidden">Halaqat</span>
                                </a>
                            @endif
                        </div>
                    </div>

                    <!-- ACTIVITÉS -->
                    <div>
                        <h3 class="mb-2 px-3 text-[8px] font-bold uppercase tracking-widest text-[#8da496] group-[.sidebar-collapsed]:lg:hidden">Activités</h3>
                        <div class="space-y-2">
                            @if ($isCheikh)
                                <a href="{{ route('cheikh.competitions') }}" data-short="CP" class="block rounded-[9px] px-3 py-2 text-[11px] font-semibold group-[.sidebar-collapsed]:lg:before:content-[attr(data-short)] group-[.sidebar-collapsed]:lg:text-center group-[.sidebar-collapsed]:lg:px-1 {{ request()->routeIs('cheikh.competitions*') ? 'border border-[#d4af37] text-[#d4af37] bg-transparent' : 'border border-transparent bg-[#094d2c] text-white hover:bg-[#0a5c34]' }}">
                                    <span class="sidebar-label group-[.sidebar-collapsed]:lg:hidden">Competitions</span>
                                </a>
                                <a href="{{ route('cheikh.participations') }}" data-short="PA" class="block rounded-[9px] px-3 py-2 text-[11px] font-semibold group-[.sidebar-collapsed]:lg:before:content-[attr(data-short)] group-[.sidebar-collapsed]:lg:text-center group-[.sidebar-collapsed]:lg:px-1 {{ request()->routeIs('cheikh.participations*') ? 'border border-[#d4af37] text-[#d4af37] bg-transparent' : 'border border-transparent bg-[#094d2c] text-white hover:bg-[#0a5c34]' }}">
                                    <span class="sidebar-label group-[.sidebar-collapsed]:lg:hidden">Participations</span>
                                </a>
                            @else
                                <a href="{{ route('competitions.index') }}" data-short="CP" class="block rounded-[9px] px-3 py-2 text-[11px] font-semibold group-[.sidebar-collapsed]:lg:before:content-[attr(data-short)] group-[.sidebar-collapsed]:lg:text-center group-[.sidebar-collapsed]:lg:px-1 {{ request()->routeIs('competitions.*') ? 'border border-[#d4af37] text-[#d4af37] bg-transparent' : 'border border-transparent bg-[#094d2c] text-white hover:bg-[#0a5c34]' }}">
                                    <span class="sidebar-label group-[.sidebar-collapsed]:lg:hidden">Competitions</span>
                                </a>
                                <a href="{{ route('participations.index') }}" data-short="PA" class="block rounded-[9px] px-3 py-2 text-[11px] font-semibold group-[.sidebar-collapsed]:lg:before:content-[attr(data-short)] group-[.sidebar-collapsed]:lg:text-center group-[.sidebar-collapsed]:lg:px-1 {{ request()->routeIs('participations.*') ? 'border border-[#d4af37] text-[#d4af37] bg-transparent' : 'border border-transparent bg-[#094d2c] text-white hover:bg-[#0a5c34]' }}">
                                    <span class="sidebar-label group-[.sidebar-collapsed]:lg:hidden">Participations</span>
                                </a>
                            @endif
                        </div>
                    </div>

                    <!-- COMMUNICATION -->
                    <div>
                        <h3 class="mb-2 px-3 text-[8px] font-bold uppercase tracking-widest text-[#8da496] group-[.sidebar-collapsed]:lg:hidden">Communication</h3>
                        <div class="space-y-2">
                            @if ($isCheikh)
                                <a href="{{ route('meetings.index') }}" data-short="RV" class="block rounded-[9px] px-3 py-2 text-[11px] font-semibold group-[.sidebar-collapsed]:lg:before:content-[attr(data-short)] group-[.sidebar-collapsed]:lg:text-center group-[.sidebar-collapsed]:lg:px-1 {{ request()->routeIs('meetings.*') ? 'border border-[#d4af37] text-[#d4af37] bg-transparent' : 'border border-transparent bg-[#094d2c] text-white hover:bg-[#0a5c34]' }}">
                                    <span class="sidebar-label group-[.sidebar-collapsed]:lg:hidden">Réunions</span>
                                </a>
                            @endif
                            <a href="{{ $chatUrl }}" data-short="CH" class="block rounded-[9px] px-3 py-2 text-[11px] font-semibold group-[.sidebar-collapsed]:lg:before:content-[attr(data-short)] group-[.sidebar-collapsed]:lg:text-center group-[.sidebar-collapsed]:lg:px-1 border border-[#d4af37] text-[#d4af37] bg-transparent">
                                <span class="sidebar-label group-[.sidebar-collapsed]:lg:hidden">Chats</span>
                            </a>
                        </div>
                    </div>
                </nav>

                <div class="mt-auto px-8 pb-8">
                    <a href="{{ route('profile.edit') }}" data-short="PR" class="mb-3 block rounded-[9px] px-3 py-2 text-[11px] font-semibold group-[.sidebar-collapsed]:lg:before:content-[attr(data-short)] group-[.sidebar-collapsed]:lg:text-center group-[.sidebar-collapsed]:lg:px-1 {{ request()->routeIs('profile.edit') ? 'border border-[#d4af37] text-[#d4af37] bg-transparent' : 'border border-transparent bg-[#094d2c] text-white hover:bg-[#0a5c34]' }}">
                        <span class="sidebar-label group-[.sidebar-collapsed]:lg:hidden">Mon Profil</span>
                    </a>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" data-short="LO" class="block w-full rounded-[9px] border border-transparent bg-[#991b1b] px-3 py-2 text-left text-[11px] font-semibold text-white hover:bg-[#7f1818] transition-colors group-[.sidebar-collapsed]:lg:before:content-[attr(data-short)] group-[.sidebar-collapsed]:lg:text-center group-[.sidebar-collapsed]:lg:px-1">
                            <span class="sidebar-label group-[.sidebar-collapsed]:lg:hidden">Logout</span>
                        </button>
                    </form>
                </div>
            </aside>

            <div id="mobile-sidebar-overlay" class="wirechat-mobile-overlay fixed inset-0 z-40 bg-slate-900/45 hidden max-lg:group-[.mobile-sidebar-open]:block"></div>

            <main class="h-screen overflow-hidden flex flex-col">
                <div class="lg:hidden shrink-0 border-b border-[#1e583d] bg-[#04371f] px-4 py-2 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-8 w-auto">
                        <span class="text-white text-xs font-bold">{{ $role === 'cheikh' ? 'Cheikh Panel' : 'Admin Panel' }}</span>
                    </div>
                    <button
                        id="wirechat-mobile-sidebar-toggle"
                        type="button"
                        class="rounded-xl bg-[#094d2c] p-2 text-white hover:bg-[#0a5c34] border border-[#1e583d]"
                        aria-controls="app-sidebar"
                        aria-expanded="false"
                        aria-label="Ouvrir ou fermer le menu"
                    >
                         <svg class="h-6 w-6 block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path></svg>
                    </button>
                </div>
                <div class="flex-1 overflow-hidden p-0">
                    @yield('content', $slot ?? null)
                </div>
            </main>
        </div>
    @elseif (in_array($role, ['student', 'parent'], true))
        <div class="h-screen flex flex-col overflow-hidden bg-slate-50">
             <header class="fixed top-0 left-0 w-full z-[60] bg-[#04371f] text-white shadow-2xl border-b border-[#1e583d] shrink-0">
                <div class="mx-auto max-w-[1400px] px-4 py-2 sm:px-6 lg:px-8">
                    <div class="flex items-center justify-between">
                        <!-- Branding -->
                        <div class="flex items-center gap-4">
                            <img src="{{ asset('images/logo.png') }}" alt="Logo" width="60" height="60" class=" object-contain">
                            <div class="hidden sm:flex flex-col items-start gap-1">
                                <div class="rounded-full border border-[#6b5624] bg-[#FFFFFF]/15 px-4 py-1 flex items-center">
                                    <span class="text-[#a48834] text-[9px] font-bold tracking-[0.15em] uppercase">{{ $role === 'student' ? 'ÉTUDIANT' : 'PARENT' }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Navigation (Desktop) -->
                        <nav class="hidden lg:flex items-center gap-2">
                             @if ($role === 'student')
                                <a href="{{ route('student.halaqas') }}" class="rounded-[9px] px-4 py-2 text-[11px] font-semibold transition-all {{ request()->routeIs('student.halaqas') ? 'border border-[#d4af37] text-[#d4af37] bg-transparent' : 'border border-transparent bg-[#094d2c] text-white hover:bg-[#0a5c34]' }}">Mes halaqas</a>
                                <a href="{{ route('student.current-halaqa') }}" class="rounded-[9px] px-4 py-2 text-[11px] font-semibold transition-all {{ request()->routeIs('student.current-halaqa') ? 'border border-[#d4af37] text-[#d4af37] bg-transparent' : 'border border-transparent bg-[#094d2c] text-white hover:bg-[#0a5c34]' }}">Halaqa actuelle</a>
                                <a href="{{ route('student.evaluations.historique') }}" class="rounded-[9px] px-4 py-2 text-[11px] font-semibold transition-all {{ request()->routeIs('student.evaluations.historique') ? 'border border-[#d4af37] text-[#d4af37] bg-transparent' : 'border border-transparent bg-[#094d2c] text-white hover:bg-[#0a5c34]' }}">Évaluations</a>
                                <a href="{{ route('student.competitions') }}" class="rounded-[9px] px-4 py-2 text-[11px] font-semibold transition-all {{ request()->routeIs('student.competitions') ? 'border border-[#d4af37] text-[#d4af37] bg-transparent' : 'border border-transparent bg-[#094d2c] text-white hover:bg-[#0a5c34]' }}">Compétitions</a>
                                <a href="{{ route('student.participations') }}" class="rounded-[9px] px-4 py-2 text-[11px] font-semibold transition-all {{ request()->routeIs('student.participations') ? 'border border-[#d4af37] text-[#d4af37] bg-transparent' : 'border border-transparent bg-[#094d2c] text-white hover:bg-[#0a5c34]' }}">Participations</a>
                            @endif

                            @if ($role === 'parent')
                                <a href="{{ route('parent.children') }}" class="rounded-[9px] px-4 py-2 text-[11px] font-semibold transition-all {{ request()->routeIs('parent.children') ? 'border border-[#d4af37] text-[#d4af37] bg-transparent' : 'border border-transparent bg-[#094d2c] text-white hover:bg-[#0a5c34]' }}">Mes enfants</a>
                            @endif

                             <a href="{{ $chatUrl }}" class="rounded-[9px] px-4 py-2 text-[11px] font-semibold transition-all {{ request()->routeIs('wirechat.*') ? 'border border-[#d4af37] text-[#d4af37] bg-transparent' : 'border border-transparent bg-[#094d2c] text-white hover:bg-[#0a5c34]' }}">Chats</a>
                        </nav>

                        <!-- Actions (Desktop) -->
                        <div class="hidden lg:flex items-center gap-3 ml-4 pl-4 border-l border-[#1e583d]">
                             <a href="{{ route('profile.edit') }}" class="rounded-[9px] px-4 py-2 text-[11px] font-semibold transition-all {{ request()->routeIs('profile.edit') ? 'border border-[#d4af37] text-[#d4af37] bg-transparent' : 'border border-transparent bg-[#094d2c] text-white hover:bg-[#0a5c34]' }}">Profil</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="rounded-[9px] bg-[#991b1b] px-5 py-2 text-[11px] font-bold text-white hover:bg-[#7f1818] transition-all shadow-lg shadow-black/10">Logout</button>
                            </form>
                        </div>

                        <!-- Mobile menu button (Hamburger) -->
                        <button id="wirechat-user-nav-toggle" type="button" class="rounded-xl bg-[#094d2c] p-2 text-white lg:hidden hover:bg-[#0a5c34] border border-[#1e583d] relative z-[60]">
                             <svg id="hamburger-icon" class="h-6 w-6 block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path></svg>
                        </button>
                    </div>
                </div>
            </header>

             <!-- Mobile Drawer Overlay -->
            <div id="drawer-overlay" class="drawer-overlay fixed inset-0 bg-[#04371f]/60 backdrop-blur-sm z-[70] lg:hidden"></div>

            <!-- Mobile Drawer -->
            <nav id="wirechat-user-nav-menu" class="mobile-drawer fixed top-0 right-0 h-full w-[280px] bg-[#04371f] text-white z-[80] lg:hidden shadow-2xl p-6 flex flex-col gap-6 overflow-y-auto">
                
                <div class="relative pb-6 border-b border-[#1e583d] flex flex-col items-center justify-center">
                    <!-- Close Button Inside Drawer -->
                    <button id="wirechat-drawer-close" type="button" class="absolute top-0 right-0 p-2 rounded-lg bg-[#094d2c] border border-[#1e583d] text-white hover:bg-[#0a5c34] transition-colors">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>

                    <!-- Centered Branding -->
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-14 w-auto object-contain mb-3 drop-shadow-md">
                    <div class="rounded-full border border-[#6b5624] bg-[#FFFFFF]/15 px-3 py-1">
                        <span class="text-[#a48834] text-[9px] font-bold tracking-[0.15em] uppercase">{{ $role === 'student' ? 'ÉTUDIANT' : 'PARENT' }}</span>
                    </div>
                </div>

                <div class="flex flex-col gap-3 flex-1">
                    @if ($role === 'student')
                        <a href="{{ route('student.halaqas') }}" class="block rounded-[9px] px-4 py-3 text-[13px] font-semibold {{ request()->routeIs('student.halaqas') ? 'border border-[#d4af37] text-[#d4af37]' : 'bg-[#094d2c] text-white' }}">Mes halaqas</a>
                        <a href="{{ route('student.current-halaqa') }}" class="block rounded-[9px] px-4 py-3 text-[13px] font-semibold {{ request()->routeIs('student.current-halaqa') ? 'border border-[#d4af37] text-[#d4af37]' : 'bg-[#094d2c] text-white' }}">Halaqa actuelle</a>
                        <a href="{{ route('student.evaluations.historique') }}" class="block rounded-[9px] px-4 py-3 text-[13px] font-semibold {{ request()->routeIs('student.evaluations.historique') ? 'border border-[#d4af37] text-[#d4af37]' : 'bg-[#094d2c] text-white' }}">Mes évaluations</a>
                        <a href="{{ route('student.competitions') }}" class="block rounded-[9px] px-4 py-3 text-[13px] font-semibold {{ request()->routeIs('student.competitions') ? 'border border-[#d4af37] text-[#d4af37]' : 'bg-[#094d2c] text-white' }}">Compétitions</a>
                        <a href="{{ route('student.participations') }}" class="block rounded-[9px] px-4 py-3 text-[13px] font-semibold {{ request()->routeIs('student.participations') ? 'border border-[#d4af37] text-[#d4af37]' : 'bg-[#094d2c] text-white' }}">Mes participations</a>
                    @endif

                    @if ($role === 'parent')
                        <a href="{{ route('parent.children') }}" class="block rounded-[9px] px-4 py-3 text-[13px] font-semibold {{ request()->routeIs('parent.children') ? 'border border-[#d4af37] text-[#d4af37]' : 'bg-[#094d2c] text-white' }}">Mes enfants</a>
                    @endif

                    <a href="{{ $chatUrl }}" class="block rounded-[9px] px-4 py-3 text-[13px] font-semibold {{ request()->routeIs('wirechat.*') ? 'border border-[#d4af37] text-[#d4af37]' : 'bg-[#094d2c] text-white' }}">Chats</a>
                </div>
                
                <div class="pt-6 border-t border-[#1e583d] flex flex-col gap-3">
                    <a href="{{ route('profile.edit') }}" class="block rounded-[9px] px-4 py-3 text-[13px] font-semibold {{ request()->routeIs('profile.edit') ? 'border border-[#d4af37] text-[#d4af37]' : 'bg-[#094d2c] text-white' }}">Profil</a>
                    <form method="POST" action="{{ route('logout') }}" class="block">
                        @csrf
                        <button type="submit" class="w-full text-left rounded-[9px] bg-[#991b1b] px-4 py-3 text-[13px] font-bold text-white shadow-lg shadow-black/10">Logout</button>
                    </form>
                </div>
            </nav>

            <main class="flex-1 overflow-hidden pt-20">
                <div class="h-full w-full">
                     @yield('content', $slot ?? null)
                </div>
            </main>
        </div>
    @else
        <div class="min-h-screen bg-[var(--wc-light-primary)] dark:bg-[var(--wc-dark-primary)]">
            <main class="h-[calc(100vh_-_0.0rem)]">
                @yield('content', $slot ?? null)
            </main>
        </div>
    @endif

    @livewireScripts
    @wirechatAssets(panel: $panel)
    <script>
        (function () {
            // General elements for Admin/Cheikh
            const layoutShell = document.getElementById('layout-shell');
            const adminSidebar = document.getElementById('app-sidebar');
            const sidebarToggle = document.getElementById('sidebar-toggle');
            const mobileSidebarToggle = document.getElementById('wirechat-mobile-sidebar-toggle');
            const mobileSidebarClose = document.getElementById('mobile-sidebar-close');
            const mobileSidebarOverlay = document.getElementById('mobile-sidebar-overlay');
            const storageKey = 'zad_sidebar_collapsed';

            function applyDesktopSidebarState(collapsed) {
                if (!layoutShell) return;
                layoutShell.classList.toggle('sidebar-collapsed', collapsed);
            }

            if (layoutShell) {
                const savedState = localStorage.getItem(storageKey) === '1';
                applyDesktopSidebarState(savedState);
            }

            function setSidebarOpen(open) {
                if (!layoutShell) return;
                layoutShell.classList.toggle('mobile-sidebar-open', open);
                if (mobileSidebarToggle) {
                    mobileSidebarToggle.setAttribute('aria-expanded', open ? 'true' : 'false');
                }
            }

            if (mobileSidebarToggle) {
                mobileSidebarToggle.addEventListener('click', function () {
                    const isOpen = layoutShell && layoutShell.classList.contains('mobile-sidebar-open');
                    setSidebarOpen(!isOpen);
                });
            }

            if (mobileSidebarClose) {
                mobileSidebarClose.addEventListener('click', function () {
                    setSidebarOpen(false);
                });
            }

            if (mobileSidebarOverlay) {
                mobileSidebarOverlay.addEventListener('click', function () {
                    setSidebarOpen(false);
                });
            }

            if (adminSidebar) {
                adminSidebar.querySelectorAll('a').forEach(function (link) {
                    link.addEventListener('click', function () {
                        setSidebarOpen(false);
                    });
                });
            }

            // User Nav Menu (for Students/Parents)
            const userNavToggle = document.getElementById('wirechat-user-nav-toggle');
            const wirechatDrawerClose = document.getElementById('wirechat-drawer-close');
            const userNavMenu = document.getElementById('wirechat-user-nav-menu');
            const drawerOverlay = document.getElementById('drawer-overlay');
            const hamburgerIcon = document.getElementById('hamburger-icon');
            const closeIcon = document.getElementById('close-icon');

            function toggleUserDrawer() {
                if (!userNavMenu) return;
                const isOpen = userNavMenu.classList.contains('open');
                if (isOpen) {
                    userNavMenu.classList.remove('open');
                    if (drawerOverlay) drawerOverlay.classList.remove('open');
                    if (hamburgerIcon) hamburgerIcon.classList.remove('hidden');
                    if (closeIcon) closeIcon.classList.add('hidden');
                    document.body.style.overflow = '';
                } else {
                    userNavMenu.classList.add('open');
                    if (drawerOverlay) drawerOverlay.classList.add('open');
                    if (hamburgerIcon) hamburgerIcon.classList.add('hidden');
                    if (closeIcon) closeIcon.classList.remove('hidden');
                    document.body.style.overflow = 'hidden';
                }
            }

            if (userNavToggle) userNavToggle.addEventListener('click', toggleUserDrawer);
            if (wirechatDrawerClose) wirechatDrawerClose.addEventListener('click', toggleUserDrawer);
            if (drawerOverlay) drawerOverlay.addEventListener('click', toggleUserDrawer);

            if (userNavMenu) {
                userNavMenu.querySelectorAll('a').forEach(function (link) {
                    link.addEventListener('click', function () {
                        if (userNavMenu.classList.contains('open')) {
                            toggleUserDrawer();
                        }
                    });
                });
            }
        })();
    </script>
</body>
</html>

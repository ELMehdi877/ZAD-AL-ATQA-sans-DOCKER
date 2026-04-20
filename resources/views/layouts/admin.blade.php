<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
    </style>
</head>
<body class="antialiased min-h-screen bg-slate-100 text-slate-900">
    @php
        $currentUser = auth()->user();
        $isAdmin = $currentUser?->role === 'admin';
        $isCheikh = $currentUser?->role === 'cheikh';
        $chatUrl = route('wirechat.chats.chats');
    @endphp

    <div id="layout-shell" class="group layout-shell min-h-screen lg:grid lg:grid-cols-[260px_1fr] lg:transition-[grid-template-columns] lg:duration-300 lg:ease-in-out [&.sidebar-collapsed]:lg:grid-cols-[88px_1fr]">
        <aside id="app-sidebar" class="app-sidebar flex h-screen flex-col bg-[#04371f] text-white overflow-y-auto fixed inset-y-0 left-0 z-50 w-[260px] max-w-[85vw] -translate-x-full lg:sticky lg:top-0 lg:translate-x-0 lg:w-full lg:max-w-none transition-transform duration-300 ease-in-out group-[.mobile-sidebar-open]:translate-x-0">
            <div class="relative pt-8 pb-6 border-b border-[#1e583d] flex flex-col items-center justify-center group-[.sidebar-collapsed]:lg:hidden">
                <!-- Close Button Inside Drawer (Mobile Only) -->
                <button id="mobile-sidebar-close" type="button" class="absolute top-4 right-4 p-2 rounded-lg bg-[#094d2c] border border-[#1e583d] text-white hover:bg-[#0a5c34] lg:hidden">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>

                <!-- Centered Branding -->
                <img src="{{ asset('images/logo.png') }}" alt="Logo" width="100" height="100" class=" object-contain mb-1 drop-shadow-md">
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
                        @endif

                        @if ($isCheikh)
                            
                            <a href="{{ route('cheikh.halaqas') }}" data-short="HA" class="block rounded-[9px] px-3 py-2 text-[11px] font-semibold group-[.sidebar-collapsed]:lg:before:content-[attr(data-short)] group-[.sidebar-collapsed]:lg:text-center group-[.sidebar-collapsed]:lg:px-1 {{ request()->routeIs('cheikh.halaqas*') ? 'border border-[#d4af37] text-[#d4af37] bg-transparent' : 'border border-transparent bg-[#094d2c] text-white hover:bg-[#0a5c34]' }}">
                                <span class="sidebar-label group-[.sidebar-collapsed]:lg:hidden">Halaqas</span>
                            </a>
                        @else
                            <a href="{{ route('halaqas.index') }}" data-short="HA" class="block rounded-[9px] px-3 py-2 text-[11px] font-semibold group-[.sidebar-collapsed]:lg:before:content-[attr(data-short)] group-[.sidebar-collapsed]:lg:text-center group-[.sidebar-collapsed]:lg:px-1 {{ request()->routeIs('halaqas.*') ? 'border border-[#d4af37] text-[#d4af37] bg-transparent' : 'border border-transparent bg-[#094d2c] text-white hover:bg-[#0a5c34]' }}">
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
                        <a href="{{ $chatUrl }}" data-short="CH" class="block rounded-[9px] px-3 py-2 text-[11px] font-semibold group-[.sidebar-collapsed]:lg:before:content-[attr(data-short)] group-[.sidebar-collapsed]:lg:text-center group-[.sidebar-collapsed]:lg:px-1 {{ request()->routeIs('wirechat.*') ? 'border border-[#d4af37] text-[#d4af37] bg-transparent' : 'border border-transparent bg-[#094d2c] text-white hover:bg-[#0a5c34]' }}">
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

        <div id="mobile-sidebar-overlay" class="mobile-sidebar-overlay fixed inset-0 z-40 bg-slate-900/45 hidden max-lg:group-[.mobile-sidebar-open]:block"></div>

        <main class="p-1">
            <div class="lg:hidden shrink-0 border-b border-[#1e583d] bg-[#04371f] px-4 py-3 flex items-center justify-between mb-4">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-8 w-auto">
                    <span class="text-white text-xs font-bold">{{ $isCheikh ? 'Cheikh Panel' : 'Admin Panel' }}</span>
                </div>
                <button
                    id="mobile-sidebar-toggle"
                    type="button"
                    class="rounded-xl bg-[#094d2c] p-2 text-white hover:bg-[#0a5c34] border border-[#1e583d]"
                    aria-controls="app-sidebar"
                    aria-expanded="false"
                    aria-label="Ouvrir ou fermer le menu"
                >
                    <svg class="h-6 w-6 block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path></svg>
                </button>
            </div>

            @if (session('success'))
                <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-800">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-emerald-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-emerald-800">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-6 rounded-lg border border-rose-200 bg-rose-50 px-4 py-3 text-rose-800">
                    {{ session('error') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-6 rounded-lg border border-rose-200 bg-rose-50 px-4 py-3 text-rose-800">
                    <p class="mb-1 font-semibold">Erreurs de validation :</p>
                    <ul class="list-disc pl-5 text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    @yield('scripts')
    <script>
        (function () {
            const layoutShell = document.getElementById('layout-shell');
            const toggleButton = document.getElementById('sidebar-toggle');
            const mobileToggleButton = document.getElementById('mobile-sidebar-toggle');
            const mobileOverlay = document.getElementById('mobile-sidebar-overlay');
            const sidebar = document.getElementById('app-sidebar');
            const storageKey = 'zad_sidebar_collapsed';

            if (!layoutShell) {
                return;
            }

            function applyState(collapsed) {
                layoutShell.classList.toggle('sidebar-collapsed', collapsed);
                if (toggleButton) {
                    toggleButton.textContent = collapsed ? '>>' : '<<';
                }
            }

            function setMobileSidebar(open) {
                layoutShell.classList.toggle('mobile-sidebar-open', open);
                if (mobileToggleButton) {
                    mobileToggleButton.setAttribute('aria-expanded', open ? 'true' : 'false');
                }
            }

            const savedState = localStorage.getItem(storageKey) === '1';
            applyState(savedState);

            if (toggleButton) {
                toggleButton.addEventListener('click', function () {
                    const collapsed = !layoutShell.classList.contains('sidebar-collapsed');
                    applyState(collapsed);
                    localStorage.setItem(storageKey, collapsed ? '1' : '0');
                });
            }

            if (mobileToggleButton) {
                mobileToggleButton.addEventListener('click', function () {
                    const open = !layoutShell.classList.contains('mobile-sidebar-open');
                    setMobileSidebar(open);
                });
            }

            const mobileSidebarClose = document.getElementById('mobile-sidebar-close');
            if (mobileSidebarClose) {
                mobileSidebarClose.addEventListener('click', function() {
                    setMobileSidebar(false);
                });
            }

            if (mobileOverlay) {
                mobileOverlay.addEventListener('click', function () {
                    setMobileSidebar(false);
                });
            }

            if (sidebar) {
                sidebar.querySelectorAll('a').forEach(function (link) {
                    link.addEventListener('click', function () {
                        setMobileSidebar(false);
                    });
                });
            }
        })();
    </script>
</body>
</html>

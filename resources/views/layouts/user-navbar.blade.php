<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Zad Al Atqa')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            scrollbar-gutter: stable;
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

        #user-nav-toggle, #user-nav-toggle * {
            transition: none !important;
        }
    </style>
</head>

<body class="min-h-screen bg-slate-50 text-slate-900">
    @php
        $chatUrl = route('wirechat.chats.chats');
        $role = auth()->user()?->role;
    @endphp

    <header
        class="fixed top-0 left-0 w-full z-[60] bg-[#04371f] text-white shadow-2xl border-b border-[#1e583d] shrink-0">
        <div class="mx-auto max-w-[1400px] px-4 py-2 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between">
                <!-- Branding: Sidebar Style -->
                <div class="flex items-center gap-4">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" width="60" height="60" class=" object-contain">
                    
                    <div class="rounded-full border border-[#6b5624] bg-[#FFFFFF]/15 px-4 flex items-center py-1">
                        <span
                            class="text-[#a48834] text-[9px] font-bold tracking-[0.15em] uppercase">{{ $role === 'student' ? 'ÉTUDIANT' : 'PARENT' }}</span>
                    </div>
                    
                </div>

                <!-- Desktop Navigation: Sidebar Button Style -->
                <nav class="hidden lg:flex items-center gap-2">
                    @if ($role === 'student')
                        <a href="{{ route('student.halaqas') }}"
                            class="rounded-[9px] px-4 py-2 text-[11px] font-semibold transition-all {{ request()->routeIs('student.halaqas') ? 'border border-[#d4af37] text-[#d4af37] bg-transparent' : 'border border-transparent bg-[#094d2c] text-white hover:bg-[#0a5c34]' }}">Mes
                            halaqas</a>
                        <a href="{{ route('student.current-halaqa') }}"
                            class="rounded-[9px] px-4 py-2 text-[11px] font-semibold transition-all {{ request()->routeIs('student.current-halaqa') ? 'border border-[#d4af37] text-[#d4af37] bg-transparent' : 'border border-transparent bg-[#094d2c] text-white hover:bg-[#0a5c34]' }}">Halaqa
                            actuelle</a>
                        <a href="{{ route('student.evaluations.historique') }}"
                            class="rounded-[9px] px-4 py-2 text-[11px] font-semibold transition-all {{ request()->routeIs('student.evaluations.historique') ? 'border border-[#d4af37] text-[#d4af37] bg-transparent' : 'border border-transparent bg-[#094d2c] text-white hover:bg-[#0a5c34]' }}">Évaluations</a>
                        <a href="{{ route('student.competitions') }}"
                            class="rounded-[9px] px-4 py-2 text-[11px] font-semibold transition-all {{ request()->routeIs('student.competitions') ? 'border border-[#d4af37] text-[#d4af37] bg-transparent' : 'border border-transparent bg-[#094d2c] text-white hover:bg-[#0a5c34]' }}">Compétitions</a>
                        <a href="{{ route('student.participations') }}"
                            class="rounded-[9px] px-4 py-2 text-[11px] font-semibold transition-all {{ request()->routeIs('student.participations') ? 'border border-[#d4af37] text-[#d4af37] bg-transparent' : 'border border-transparent bg-[#094d2c] text-white hover:bg-[#0a5c34]' }}">Participations</a>
                    @endif

                    @if ($role === 'parent')
                        <a href="{{ route('parent.children') }}"
                            class="rounded-[9px] px-4 py-2 text-[11px] font-semibold transition-all {{ request()->routeIs('parent.children') ? 'border border-[#d4af37] text-[#d4af37] bg-transparent' : 'border border-transparent bg-[#094d2c] text-white hover:bg-[#0a5c34]' }}">Mes
                            enfants</a>
                    @endif

                    <a href="{{ $chatUrl }}"
                        class="rounded-[9px] px-4 py-2 text-[11px] font-semibold transition-all {{ request()->routeIs('wirechat.*') ? 'border border-[#d4af37] text-[#d4af37] bg-transparent' : 'border border-transparent bg-[#094d2c] text-white hover:bg-[#0a5c34]' }}">Chats</a>
                </nav>

                <!-- Action Buttons: Sidebar Profile/Logout Style -->
                <div class="hidden lg:flex items-center gap-3 ml-4 pl-4 border-l border-[#1e583d]">
                    <a href="{{ route('profile.edit') }}"
                        class="rounded-[9px] px-4 py-2 text-[11px] font-semibold transition-all {{ request()->routeIs('profile.edit') ? 'border border-[#d4af37] text-[#d4af37] bg-transparent' : 'border border-transparent bg-[#094d2c] text-white hover:bg-[#0a5c34]' }}">Profil</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="rounded-[9px] bg-[#991b1b] px-5 py-2 text-[11px] font-bold text-white hover:bg-[#7f1818] transition-all shadow-lg shadow-black/10">Logout</button>
                    </form>
                </div>

                <!-- Mobile menu button (Hamburger) -->
                <button id="user-nav-toggle" type="button"
                    class="rounded-xl bg-[#094d2c] p-2 text-white lg:hidden hover:bg-[#0a5c34] border border-[#1e583d] relative z-[60]">
                    <svg id="hamburger-icon" class="h-6 w-6 block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>
                    </svg>
                    
                </button>
            </div>
        </div>
    </header>

    <!-- Mobile Drawer Overlay -->
    <div id="drawer-overlay" class="drawer-overlay fixed inset-0 bg-[#04371f]/60 backdrop-blur-sm z-[70] lg:hidden">
    </div>

    <!-- Mobile Drawer -->
    <nav id="user-nav-menu"
        class="mobile-drawer fixed top-0 right-0 h-full w-[280px] bg-[#04371f] text-white z-[80] lg:hidden shadow-2xl p-6 flex flex-col gap-6 overflow-y-auto">
        
        <div class="relative pb-6 border-b border-[#1e583d] flex flex-col items-center justify-center">
            <!-- Close Button Inside Drawer -->
            <button id="drawer-close" type="button" class="absolute top-0 right-1 p-2 rounded-lg bg-[#094d2c] border border-[#1e583d] text-white hover:bg-[#0a5c34] transition-colors">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>

            <!-- Centered Branding -->
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-14 w-auto object-contain  drop-shadow-md">
            <div class="rounded-full border border-[#6b5624] bg-[#FFFFFF]/15 px-3 py-1 flex items-center ">
                <span class="text-[#a48834] text-[9px] font-bold tracking-[0.15em] uppercase">{{ $role === 'student' ? 'ÉTUDIANT' : 'PARENT' }}</span>
            </div>
        </div>

        <div class="flex flex-col gap-3 flex-1">
            @if ($role === 'student')
                <a href="{{ route('student.halaqas') }}"
                    class="block rounded-[9px] px-4 py-3 text-[13px] font-semibold {{ request()->routeIs('student.halaqas') ? 'border border-[#d4af37] text-[#d4af37]' : 'bg-[#094d2c] text-white' }}">Mes
                    halaqas</a>
                <a href="{{ route('student.current-halaqa') }}"
                    class="block rounded-[9px] px-4 py-3 text-[13px] font-semibold {{ request()->routeIs('student.current-halaqa') ? 'border border-[#d4af37] text-[#d4af37]' : 'bg-[#094d2c] text-white' }}">Halaqa
                    actuelle</a>
                <a href="{{ route('student.evaluations.historique') }}"
                    class="block rounded-[9px] px-4 py-3 text-[13px] font-semibold {{ request()->routeIs('student.evaluations.historique') ? 'border border-[#d4af37] text-[#d4af37]' : 'bg-[#094d2c] text-white' }}">Mes
                    évaluations</a>
                <a href="{{ route('student.competitions') }}"
                    class="block rounded-[9px] px-4 py-3 text-[13px] font-semibold {{ request()->routeIs('student.competitions') ? 'border border-[#d4af37] text-[#d4af37]' : 'bg-[#094d2c] text-white' }}">Compétitions</a>
                <a href="{{ route('student.participations') }}"
                    class="block rounded-[9px] px-4 py-3 text-[13px] font-semibold {{ request()->routeIs('student.participations') ? 'border border-[#d4af37] text-[#d4af37]' : 'bg-[#094d2c] text-white' }}">Mes
                    participations</a>
            @endif

            @if ($role === 'parent')
                <a href="{{ route('parent.children') }}"
                    class="block rounded-[9px] px-4 py-3 text-[13px] font-semibold {{ request()->routeIs('parent.children') ? 'border border-[#d4af37] text-[#d4af37]' : 'bg-[#094d2c] text-white' }}">Mes
                    enfants</a>
            @endif

            <a href="{{ $chatUrl }}"
                class="block rounded-[9px] px-4 py-3 text-[13px] font-semibold {{ request()->routeIs('wirechat.*') ? 'border border-[#d4af37] text-[#d4af37]' : 'bg-[#094d2c] text-white' }}">Chats</a>
        </div>

        <div class="pt-6 border-t border-[#1e583d] flex flex-col gap-3">
            <a href="{{ route('profile.edit') }}"
                class="block rounded-[9px] px-4 py-3 text-[13px] font-semibold {{ request()->routeIs('profile.edit') ? 'border border-[#d4af37] text-[#d4af37]' : 'bg-[#094d2c] text-white' }}">Profil</a>
            <form method="POST" action="{{ route('logout') }}" class="block">
                @csrf
                <button type="submit"
                    class="w-full text-left rounded-[9px] bg-[#991b1b] px-4 py-3 text-[13px] font-bold text-white shadow-lg shadow-black/10">Logout</button>
            </form>
        </div>
    </nav>

    <main class="pt-20 px-1">
        @if (session('success'))
            <div
                class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-emerald-800 shadow-sm flex items-center gap-3">
                <svg class="h-5 w-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                <p class="text-sm font-medium">{{ session('success') }}</p>
            </div>
        @endif

        @if (session('error'))
            <div
                class="mb-6 rounded-2xl border border-rose-200 bg-rose-50 p-4 text-rose-800 shadow-sm flex items-center gap-3">
                <svg class="h-5 w-5 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
                <p class="text-sm font-medium">{{ session('error') }}</p>
            </div>
        @endif

        @yield('content')
    </main>

    @yield('scripts')
    <script>
        (function () {
            const toggleButton = document.getElementById('user-nav-toggle');
            const drawerClose = document.getElementById('drawer-close');
            const menu = document.getElementById('user-nav-menu');
            const overlay = document.getElementById('drawer-overlay');
            const hamburgerIcon = document.getElementById('hamburger-icon');
            const closeIcon = document.getElementById('close-icon');

            function toggleDrawer() {
                if (!menu) return;
                const isOpen = menu.classList.contains('open');
                if (isOpen) {
                    menu.classList.remove('open');
                    overlay.classList.remove('open');
                    if (hamburgerIcon) hamburgerIcon.classList.remove('hidden');
                    if (closeIcon) closeIcon.classList.add('hidden');
                    document.body.style.overflow = '';
                } else {
                    menu.classList.add('open');
                    overlay.classList.add('open');
                    if (hamburgerIcon) hamburgerIcon.classList.add('hidden');
                    if (closeIcon) closeIcon.classList.remove('hidden');
                    document.body.style.overflow = 'hidden';
                }
            }

            if (toggleButton) toggleButton.addEventListener('click', toggleDrawer);
            if (drawerClose) drawerClose.addEventListener('click', toggleDrawer);
            if (overlay) overlay.addEventListener('click', toggleDrawer);

            menu.querySelectorAll('a').forEach(function (link) {
                link.addEventListener('click', function () {
                    if (menu.classList.contains('open')) {
                        toggleDrawer();
                    }
                });
            });
        })();
    </script>
</body>

</html>
<nav x-data="{ open: false }" class="bg-[#04371f] border-b border-white/10 shadow-lg">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="rounded-xl bg-white/10 p-1 backdrop-blur-sm">
                        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="block h-9 w-auto object-contain">
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-4 sm:-my-px sm:ms-10 sm:flex items-center">
                    @php
                        $role = auth()->user()?->role;
                        $dashboardRoute = 'dashboard';
                        if ($role === 'admin') $dashboardRoute = 'admin.dashboard';
                        elseif ($role === 'cheikh') $dashboardRoute = 'cheikh.dashboard';
                    @endphp
                    
                    <a href="{{ route($dashboardRoute) }}" class="rounded-lg px-3 py-2 text-sm font-semibold transition-all {{ request()->routeIs('dashboard') || request()->routeIs('admin.dashboard') || request()->routeIs('cheikh.dashboard') ? 'bg-white/20 text-white shadow-inner' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
                        {{ __('Tableau de bord') }}
                    </a>

                    @if ($role === 'student')
                         <a href="{{ route('student.halaqas') }}" class="rounded-lg px-3 py-2 text-sm font-semibold transition-all {{ request()->routeIs('student.halaqas') ? 'bg-white/20 text-white shadow-inner' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">Mes halaqas</a>
                    @endif
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <div class="flex items-center gap-3">
                    <a href="{{ route('profile.edit') }}" class="rounded-lg px-3 py-2 text-xs font-semibold {{ request()->routeIs('profile.edit') ? 'bg-white/20 text-white shadow-inner' : 'text-slate-300 hover:bg-white/10 hover:text-white' }} transition-colors">Profil</a>
                    
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="rounded-lg bg-rose-600/20 px-4 py-2 text-xs font-semibold text-rose-300 hover:bg-rose-600 hover:text-white transition-all shadow-sm">Logout</button>
                    </form>
                </div>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-lg text-slate-300 hover:text-white hover:bg-white/10 focus:outline-none transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-[#04371f] border-t border-white/10">
        <div class="pt-2 pb-3 space-y-1">
            <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-base font-medium text-slate-300 hover:text-white hover:bg-white/10">
                {{ __('Tableau de bord') }}
            </a>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-white/10">
            <div class="px-4">
                <div class="font-medium text-base text-white">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-slate-400">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-base font-medium text-slate-300 hover:text-white hover:bg-white/10">
                    {{ __('Profil') }}
                </a>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="block w-full text-left px-4 py-2 text-base font-medium text-rose-300 hover:bg-rose-600 hover:text-white">
                        {{ __('Log Out') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>

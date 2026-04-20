<!DOCTYPE html>
<html lang="fr" dir="ltr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zad Al Atqa | Plateforme de Mémorisation du Saint Coran</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            scroll-behavior: smooth;
        }
        .bg-zad-dark {
            background-color: #04371f;
        }
        .text-zad-gold {
            color: #d4af37;
        }
        .bg-zad-gold {
            background-color: #d4af37;
        }
        .border-zad-gold {
            border-color: #d4af37;
        }
    </style>
</head>

<body class="bg-slate-50 text-slate-900 overflow-x-hidden">

    <!-- Header / Navbar -->
    <header class="fixed top-0 w-full z-50 bg-[#04371f]/95 backdrop-blur-md border-b border-[#1e583d] transition-all">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <!-- Logo -->
                <div class="flex items-center gap-3">
                    <img src="{{ asset('images/logo.png') }}" alt="Zad Al Atqa" class="h-12 w-auto object-contain">
                    <div class="flex flex-col leading-tight">
                        <span class="text-white font-extrabold text-sm md:text-xl tracking-tight">Zad Al Atqa</span>
                        <span class="text-[#d4af37] md:text-[10px] text-[8px] font-bold tracking-[0.2em] uppercase">Mémorisation Sacrée</span>
                    </div>
                </div>

                <!-- Desktop Nav -->
                <nav class="items-center gap-8">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="text-white hover:text-[#d4af37] font-semibold transition">Tableau de bord</a>
                    @else
                        <a href="{{ route('login') }}" class="text-white hover:text-[#d4af37] font-semibold transition text-[8px] md:text-sm">Connexion</a>
                        <a href="{{ route('register') }}" class="bg-[#d4af37] hover:bg-[#b8952d] text-[#04371f] md:px-6 md:py-2.5 px-3 py-1.5 rounded-[9px] font-bold transition shadow-lg shadow-black/20 text-[8px] md:text-sm">S'inscrire</a>
                    @endauth
                </nav>

                <!-- Mobile menu button hidden for simplicity as requested -->
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="relative pt-32 pb-20 lg:pt-20 lg:pb-20 overflow-hidden">
        <!-- Background decorative elements -->
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-full -z-10 opacity-10">
            <div class="absolute top-0 right-0 w-96 h-96 bg-[#d4af37] rounded-full blur-[120px]"></div>
            <div class="absolute bottom-0 left-0 w-96 h-96 bg-[#04371f] rounded-full blur-[120px]"></div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 ">
            <div class="flex flex-col lg:flex-row items-center gap-16">
                <!-- Hero Content -->
                <div class="flex-1 text-center lg:text-left">
                    <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-[#04371f]/5 border border-[#04371f]/10 mb-6 font-semibold text-[#04371f] text-sm">
                        <span class="flex h-2 w-2 rounded-full bg-[#d4af37]"></span>
                        Nouvelle session d'inscription ouverte
                    </div>
                    <h1 class="text-5xl lg:text-7xl font-extrabold text-[#04371f] leading-[1.1] mb-8 tracking-tight">
                        Élevez votre âme avec le <span class="bg-clip-text text-transparent bg-gradient-to-r from-[#d4af37] to-[#8c6d17]">Saint Coran</span>.
                    </h1>
                    <p class="text-lg text-slate-600 mb-10 max-w-2xl mx-auto lg:mx-0 leading-relaxed">
                        Rejoignez Zad Al Atqa, la plateforme de mémorisation du Coran qui allie tradition prophétique et outils modernes pour faciliter votre chemin vers Allah.
                    </p>
                    <div class="flex flex-col sm:flex-row justify-center lg:justify-start gap-4">
                        <a href="{{ route('register') }}" class="bg-[#04371f] hover:bg-[#064d2c] text-white px-10 py-4 rounded-[12px] font-bold transition shadow-xl text-lg flex items-center justify-center gap-2">
                            Commencer maintenant
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </a>
                        <a href="#features" class="border-2 border-[#04371f]/10 hover:border-[#04371f]/20 bg-white px-10 py-4 rounded-[12px] font-bold text-[#04371f] transition text-lg flex items-center justify-center">
                            En savoir plus
                        </a>
                    </div>
                    
                    <!-- Stats summary -->
                    <div class="mt-12 flex items-center justify-center lg:justify-start gap-8 pt-8 border-t border-slate-200">
                        <div>
                            <span class="block text-2xl font-bold text-[#04371f]">1000+</span>
                            <span class="text-sm text-slate-500 font-medium tracking-wide">Étudiants</span>
                        </div>
                        <div class="w-px h-8 bg-slate-200"></div>
                        <div>
                            <span class="block text-2xl font-bold text-[#04371f]">50+</span>
                            <span class="text-sm text-slate-500 font-medium tracking-wide">Cheikhs Experts</span>
                        </div>
                        <div class="w-px h-8 bg-slate-200"></div>
                        <div>
                            <span class="block text-2xl font-bold text-[#04371f]">24/7</span>
                            <span class="text-sm text-slate-500 font-medium tracking-wide">Accès illimité</span>
                        </div>
                    </div>
                </div>

                <!-- Hero Image -->
                <div class="flex-1 relative">
                    <div class="relative z-10 rounded-[32px] overflow-hidden shadow-2xl border-4 border-white">
                        <img src="{{ asset('images/quran_memorization_hero_1776629097028.png') }}" alt="Quran Memorization" class="w-full h-auto object-cover transform scale-105">
                    </div>
                    <!-- Decorative back elements -->
                    <div class="absolute -top-6 -right-6 w-full h-full bg-[#d4af37]/10 rounded-[32px] -z-10 rotate-3"></div>
                    <div class="absolute -bottom-6 -left-6 w-full h-full bg-[#04371f]/10 rounded-[32px] -z-10 -rotate-2"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center mb-8">
            <h2 class="text-zad-gold font-bold text-sm tracking-[0.3em] uppercase mb-4">Pourquoi Zad Al Atqa ?</h2>
            <p class="text-3xl lg:text-4xl font-extrabold text-[#04371f] tracking-tight">Une expérience spirituelle unique</p>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid md:grid-cols-3 gap-10">
            <!-- Feature 1 -->
            <div class="group p-10 bg-slate-50 rounded-[24px] border border-transparent hover:border-[#d4af37] transition-all hover:bg-white hover:shadow-xl">
                <div class="w-16 h-16 bg-[#04371f] text-[#d4af37] rounded-2xl flex items-center justify-center text-3xl mb-8 shadow-lg group-hover:scale-110 transition duration-300">📖</div>
                <h3 class="text-xl font-bold text-[#04371f] mb-4">Halaqas Interactives</h3>
                <p class="text-slate-600 leading-relaxed font-medium">Rejoignez des cercles de mémorisation en direct avec des enseignants qualifiés, quel que soit votre niveau.</p>
            </div>

            <!-- Feature 2 -->
            <div class="group p-10 bg-slate-50 rounded-[24px] border border-transparent hover:border-[#d4af37] transition-all hover:bg-white hover:shadow-xl">
                <div class="w-16 h-16 bg-[#04371f] text-[#d4af37] rounded-2xl flex items-center justify-center text-3xl mb-8 shadow-lg group-hover:scale-110 transition duration-300">🏅</div>
                <h3 class="text-xl font-bold text-[#04371f] mb-4">Compétitions Motivantes</h3>
                <p class="text-slate-600 leading-relaxed font-medium">Participez à des concours périodiques, gagnez des prix et stimulez votre progression entouré d'une communauté soudée.</p>
            </div>

            <!-- Feature 3 -->
            <div class="group p-10 bg-slate-50 rounded-[24px] border border-transparent hover:border-[#d4af37] transition-all hover:bg-white hover:shadow-xl">
                <div class="w-16 h-16 bg-[#04371f] text-[#d4af37] rounded-2xl flex items-center justify-center text-3xl mb-8 shadow-lg group-hover:scale-110 transition duration-300">📱</div>
                <h3 class="text-xl font-bold text-[#04371f] mb-4">Suivi de Progression</h3>
                <p class="text-slate-600 leading-relaxed font-medium">Visualisez vos progrès quotidiennement grâce à notre tableau de bord intelligent et recevez des commentaires personnalisés.</p>
            </div>
        </div>
    </section>


    <!-- Footer -->
    <footer class="bg-white py-12 border-t border-slate-100 mt-5">
        <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8 flex flex-col md:flex-row justify-center items-center gap-20">
            <div class="flex items-center gap-3 grayscale opacity-60">
                <img src="{{ asset('images/logo.png') }}" alt="Zad Al Atqa" class="h-10 w-auto">
                <span class="text-[#04371f] font-bold text-lg">Zad Al Atqa</span>
            </div>
            <p class="text-slate-400 font-medium text-sm">
                &copy; {{ date('Y') }} Zad Al Atqa • Pour une communauté éclairée par le Coran.
            </p>
           
        </div>
    </footer>

</body>

</html>
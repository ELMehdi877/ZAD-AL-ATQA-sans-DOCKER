<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Zad Al-Atqa') }} - تسجيل الدخول</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Amiri:wght@400;700&family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            font-family: 'Cairo', sans-serif;
        }
        .glass-panel {
            background: rgba(153, 153, 153, 0.14);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
        }
        .input-gradient {
            background: rgba(255, 255, 255, 0.85);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
    </style>
</head>
<body class="antialiased h-screen bg-gray-900 overflow-hidden">
    <div class="relative h-screen flex items-stretch overflow-hidden">
        <!-- Background Image Container -->
        <div class="absolute inset-0 z-0">
            <img src="{{ asset('images/background_login.jpg') }}" alt="Background" class="w-full h-full object-cover">
        </div>
        

        <div class="hidden lg:block absolute inset-0 z-0">
            <img 
                src="{{ asset('images/background_login.jpg') }}" 
                class="absolute left-0 top-0 h-full w-[50%] object-cover"
            >
        </div>

        <!-- Right Side: Login Form with Glassmorphism -->
        <div class="w-full lg:w-1/2 relative z-10 glass-panel flex flex-col items-center justify-center px-6 lg:px-16 py-6 lg:py-8 order-1 h-screen">
            <div class="w-full max-w-md flex flex-col h-full justify-center py-4">
                <!-- Logo -->
                <div class="flex justify-center mb-4 lg:mb-6 shrink-0">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-20 lg:h-28 w-auto object-contain">
                </div>

                <!-- Title -->
                <h1 class="text-xl lg:text-2xl font-bold text-white text-center mb-6 lg:mb-8 tracking-wide shrink-0">ابدأ رحلتك مع القرآن</h1>

                <!-- Session Status / Errors -->
                <div class="shrink-0">
                    <x-auth-session-status class="mb-2 lg:mb-3" :status="session('status')" />
                    @if(session('error'))
                        <div class="mb-2 lg:mb-3 rounded-lg border border-red-200 bg-red-50/10 px-4 py-2 text-red-100 backdrop-blur-sm text-sm">
                            {{ session('error') }}
                        </div>
                    @endif
                    @if($errors->any())
                        <div class="mb-2 lg:mb-3 rounded-lg border border-red-200/50 bg-red-50/10 px-4 py-2 text-red-200 backdrop-blur-sm">
                            <ul class="list-disc list-inside text-xs">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>

                <form method="POST" action="{{ route('login') }}" class="space-y-4 lg:space-y-5">
                    @csrf

                    <!-- Email Address -->
                    <div>
                        <label for="email" class="block text-xs lg:text-sm font-semibold text-gray-200 mb-1 lg:mb-2 mr-1">
                            البريد الالكتروني:
                        </label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                                <svg class="h-4 w-4 lg:h-5 lg:w-5 text-gray-400 transition-colors group-focus-within:text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <input id="email" 
                                   type="email" 
                                   name="email" 
                                   value="{{ old('email') }}" 
                                   required 
                                   autofocus 
                                   placeholder="البريد الالكتروني"
                                   class="w-full h-11 lg:h-12 pl-4 lg:pl-5 pr-10 lg:pr-12 rounded-xl lg:rounded-2xl bg-white/90 text-gray-800 border-none shadow-inner focus:ring-2 focus:ring-emerald-500/50 transition-all text-right text-sm lg:text-base font-medium"
                                   style="direction: rtl;">
                        </div>
                    </div>

                    <!-- Password -->
                    <div class="mt-2 lg:mt-3">
                        <label for="password" class="block text-xs lg:text-sm font-semibold text-gray-200 mb-1 lg:mb-2 mr-1">
                            كلمة السر:
                        </label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                                <svg class="h-4 w-4 lg:h-5 lg:w-5 text-gray-400 transition-colors group-focus-within:text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                                </svg>
                            </div>
                            <input id="password" 
                                   type="password" 
                                   name="password" 
                                   required 
                                   placeholder="كلمة السر"
                                   class="w-full h-11 lg:h-12 pl-4 lg:pl-5 pr-10 lg:pr-12 rounded-xl lg:rounded-2xl bg-white/90 text-gray-800 border-none shadow-inner focus:ring-2 focus:ring-emerald-500/50 transition-all text-right text-sm lg:text-base font-medium"
                                   style="direction: rtl;">
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="pt-2 lg:pt-3">
                        <button type="submit" 
                                class="w-full h-11 lg:h-12 bg-[#064e3b] hover:bg-[#065f46] text-white font-bold rounded-xl lg:rounded-2xl transition-all duration-300 shadow-xl flex items-center justify-center text-base lg:text-lg">
                            تسجيل الدخول
                        </button>
                    </div>

                    <div class="flex items-center justify-center mt-4">
                        @if (Route::has('password.request'))
                            <a class="text-xs lg:text-sm text-gray-300 hover:text-white transition-colors underline decoration-gray-500 underline-offset-4 lg:underline-offset-4" href="{{ route('password.request') }}">
                                نسيت كلمة السر؟
                            </a>
                        @endif
                    </div>
                </form>

               
            </div>
        </div>

        <!-- Left Side: Empty on desktop (just shows background) -->
        <div class="hidden lg:flex lg:w-1/2 relative z-10 order-2 h-screen">
            <!-- This side stays clear to show the background image (Quran) -->
        </div>
    </div>
</body>
</html>


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
    <header class="mb-6 rounded-2xl bg-gradient-to-r from-[#04371f] to-[#04371f]/80 p-6 text-white shadow-xl">
        <h2 class="text-2xl font-bold">{{ __('Profil') }}</h2>
        <p class="mt-2 text-sm text-slate-200">{{ __('Gérez vos informations personnelles et la sécurité de votre compte.') }}</p>
    </header>

    <div class="py-6">
        <div class="max-w-7xl mx-auto space-y-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Personal Information -->
                <div class="p-6 sm:p-8 bg-white shadow-sm border border-slate-200 sm:rounded-2xl transition-all hover:shadow-md flex flex-col h-full">
                    <div class="flex-1">
                        @include('profile.partials.update-profile-information-form')
                    </div>
                </div>

                <!-- Password Update -->
                <div class="p-6 sm:p-8 bg-white shadow-sm border border-slate-200 sm:rounded-2xl transition-all hover:shadow-md flex flex-col h-full">
                    <div class="flex-1">
                        @include('profile.partials.update-password-form')
                    </div>
                </div>
            </div>

            <!-- Danger Zone -->
            <div class="p-6 sm:p-8 bg-white shadow-sm border border-red-100 sm:rounded-2xl transition-all hover:shadow-md bg-gradient-to-br from-white to-red-50/30">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
@endsection

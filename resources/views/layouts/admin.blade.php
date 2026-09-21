<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $heading ?? 'Admin' }} — {{ config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=cormorant-garamond:500,600|outfit:300,400,500,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://unpkg.com/trix@2.1.0/dist/trix.css">
    @livewireStyles
    @stack('head')
</head>
<body class="min-h-screen bg-cream font-sans text-charcoal antialiased">
    <div class="min-h-screen lg:flex">
        <aside class="w-full lg:w-64 bg-forest text-sand shrink-0 flex flex-col">
            <div class="px-6 py-6 border-b border-sand/10">
                <a href="{{ route('admin.dashboard') }}" class="font-display text-2xl" wire:navigate>Pearl Pulse</a>
                <p class="text-[10px] tracking-[0.25em] uppercase text-sand/50 mt-1">Admin</p>
            </div>
            <nav class="px-4 py-6 space-y-1 text-sm flex-1">
                <a href="{{ route('admin.dashboard') }}" wire:navigate class="block px-3 py-2 rounded-sm {{ request()->routeIs('admin.dashboard') ? 'bg-sand/10 text-sand' : 'text-sand/70 hover:text-sand' }}">Dashboard</a>
                <a href="{{ route('admin.journeys.index') }}" wire:navigate class="block px-3 py-2 rounded-sm {{ request()->routeIs('admin.journeys.*') ? 'bg-sand/10 text-sand' : 'text-sand/70 hover:text-sand' }}">Journeys</a>
                <a href="{{ route('admin.countries.index') }}" wire:navigate class="block px-3 py-2 rounded-sm {{ request()->routeIs('admin.countries.*') ? 'bg-sand/10 text-sand' : 'text-sand/70 hover:text-sand' }}">Countries</a>
                <a href="{{ route('admin.destinations.index') }}" wire:navigate class="block px-3 py-2 rounded-sm {{ request()->routeIs('admin.destinations.*') ? 'bg-sand/10 text-sand' : 'text-sand/70 hover:text-sand' }}">Destinations</a>
                <a href="{{ route('admin.experiences.index') }}" wire:navigate class="block px-3 py-2 rounded-sm {{ request()->routeIs('admin.experiences.*') ? 'bg-sand/10 text-sand' : 'text-sand/70 hover:text-sand' }}">Experiences</a>
                <a href="{{ route('admin.stays.index') }}" wire:navigate class="block px-3 py-2 rounded-sm {{ request()->routeIs('admin.stays.*') ? 'bg-sand/10 text-sand' : 'text-sand/70 hover:text-sand' }}">Selected stays</a>
                <a href="{{ route('admin.team.index') }}" wire:navigate class="block px-3 py-2 rounded-sm {{ request()->routeIs('admin.team.*') ? 'bg-sand/10 text-sand' : 'text-sand/70 hover:text-sand' }}">Team</a>
                <a href="{{ route('admin.reviews.index') }}" wire:navigate class="block px-3 py-2 rounded-sm {{ request()->routeIs('admin.reviews.*') ? 'bg-sand/10 text-sand' : 'text-sand/70 hover:text-sand' }}">Reviews</a>
                <a href="{{ route('admin.articles.index') }}" wire:navigate class="block px-3 py-2 rounded-sm {{ request()->routeIs('admin.articles.*') ? 'bg-sand/10 text-sand' : 'text-sand/70 hover:text-sand' }}">Insiders</a>
                <a href="{{ route('admin.pulse.index') }}" wire:navigate class="block px-3 py-2 rounded-sm {{ request()->routeIs('admin.pulse.*') ? 'bg-sand/10 text-sand' : 'text-sand/70 hover:text-sand' }}">True Pulse</a>
                <a href="{{ route('admin.faqs.index') }}" wire:navigate class="block px-3 py-2 rounded-sm {{ request()->routeIs('admin.faqs.*') ? 'bg-sand/10 text-sand' : 'text-sand/70 hover:text-sand' }}">FAQs</a>
                <a href="{{ route('admin.pages.index') }}" wire:navigate class="block px-3 py-2 rounded-sm {{ request()->routeIs('admin.pages.*') ? 'bg-sand/10 text-sand' : 'text-sand/70 hover:text-sand' }}">Pages</a>
                <a href="{{ route('admin.enquiries.index') }}" wire:navigate class="block px-3 py-2 rounded-sm {{ request()->routeIs('admin.enquiries.*') ? 'bg-sand/10 text-sand' : 'text-sand/70 hover:text-sand' }}">Enquiries</a>
                <a href="{{ route('admin.bookings.index') }}" wire:navigate class="block px-3 py-2 rounded-sm {{ request()->routeIs('admin.bookings.*') ? 'bg-sand/10 text-sand' : 'text-sand/70 hover:text-sand' }}">Bookings</a>
                <a href="{{ route('admin.settings.edit') }}" wire:navigate class="block px-3 py-2 rounded-sm {{ request()->routeIs('admin.settings.*') ? 'bg-sand/10 text-sand' : 'text-sand/70 hover:text-sand' }}">Settings</a>
                <a href="{{ route('home') }}" class="block px-3 py-2 rounded-sm text-sand/50 hover:text-sand mt-6" target="_blank">View site →</a>
            </nav>
            <div class="px-6 py-6 border-t border-sand/10">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-xs tracking-wider uppercase text-sand/50 hover:text-sand">Log out</button>
                </form>
            </div>
        </aside>

        <div class="flex-1 min-w-0">
            <header class="border-b border-sand-deep/30 bg-white/60 backdrop-blur px-6 py-4 flex items-center justify-between">
                <h1 class="font-display text-2xl text-forest">{{ $heading ?? 'Admin' }}</h1>
                <span class="text-sm text-muted">{{ auth()->user()?->name }}</span>
            </header>
            <div class="p-6 lg:p-8">
                @if (session('status'))
                    <div class="mb-6 rounded-sm border border-forest/20 bg-forest/5 px-4 py-3 text-sm text-forest">
                        {{ session('status') }}
                    </div>
                @endif
                {{ $slot }}
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/trix@2.1.0/dist/trix.umd.min.js"></script>
    @livewireScripts
    @stack('scripts')
</body>
</html>

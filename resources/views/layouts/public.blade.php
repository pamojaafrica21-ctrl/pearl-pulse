<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name'))</title>
    <meta name="description" content="@yield('meta_description', 'Discover East Africa with Pearl Pulse Safaris — curated destinations across Uganda, Kenya, Tanzania, and Rwanda.')">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=cormorant-garamond:400,500,600,700|outfit:300,400,500,600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    @stack('head')
</head>
<body class="min-h-screen flex flex-col">
    @php
        $navDark = request()->routeIs('about', 'contact', 'destinations.index');
        $linkClass = $navDark
            ? 'nav-link'
            : 'text-sm tracking-[0.14em] uppercase text-sand/90 hover:text-sand transition-colors';
        $activeClass = $navDark ? 'nav-link-active font-medium' : 'text-sand font-medium';
        $headerBg = $navDark ? 'bg-cream/90 backdrop-blur border-b border-sand-deep/20' : '';
    @endphp
    <header class="absolute inset-x-0 top-0 z-40 {{ $headerBg }}" x-data="{ open: false }">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-5 py-5 lg:px-8">
            <a href="{{ route('home') }}" class="font-display text-2xl md:text-3xl tracking-wide {{ $navDark ? 'text-forest' : 'text-sand' }} transition-opacity hover:opacity-90">
                Pearl Pulse <span class="font-sans text-[0.55em] tracking-[0.2em] uppercase opacity-80">Safaris</span>
            </a>

            <nav class="hidden items-center gap-8 md:flex">
                <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? $activeClass : $linkClass }}">Home</a>
                <a href="{{ route('destinations.index') }}" class="{{ request()->routeIs('destinations.*') ? $activeClass : $linkClass }}">Destinations</a>
                <a href="{{ route('about') }}" class="{{ request()->routeIs('about') ? $activeClass : $linkClass }}">About</a>
                <a href="{{ route('contact') }}" class="{{ request()->routeIs('contact') ? $activeClass : $linkClass }}">Contact</a>
            </nav>

            <button type="button" class="md:hidden {{ $navDark ? 'text-forest' : 'text-sand' }}" @click="open = !open" aria-label="Menu">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 7h16M4 12h16M4 17h16"/></svg>
            </button>
        </div>

        <div x-show="open" x-cloak x-transition class="md:hidden border-t {{ $navDark ? 'border-sand-deep/20 bg-cream' : 'border-white/10 bg-forest/95' }} backdrop-blur px-5 py-6 space-y-4">
            <a href="{{ route('home') }}" class="block tracking-[0.14em] uppercase text-sm {{ $navDark ? 'text-forest' : 'text-sand' }}">Home</a>
            <a href="{{ route('destinations.index') }}" class="block tracking-[0.14em] uppercase text-sm {{ $navDark ? 'text-forest' : 'text-sand' }}">Destinations</a>
            <a href="{{ route('about') }}" class="block tracking-[0.14em] uppercase text-sm {{ $navDark ? 'text-forest' : 'text-sand' }}">About</a>
            <a href="{{ route('contact') }}" class="block tracking-[0.14em] uppercase text-sm {{ $navDark ? 'text-forest' : 'text-sand' }}">Contact</a>
        </div>
    </header>

    <main class="flex-1">
        @yield('content')
    </main>

    <footer class="bg-forest text-sand mt-auto">
        <div class="mx-auto max-w-7xl px-5 py-14 lg:px-8 grid gap-10 md:grid-cols-3">
            <div>
                <p class="font-display text-3xl">Pearl Pulse Safaris</p>
                <p class="mt-3 text-sand/70 text-sm leading-relaxed max-w-xs">{{ $footerBlurb ?? 'Curated journeys through East Africa’s wildest landscapes.' }}</p>
            </div>
            <div>
                <p class="text-xs tracking-[0.2em] uppercase text-sand/50 mb-4">Contact</p>
                @if($siteContact['address'] ?? null)
                    <p class="text-sm text-sand/80">{{ $siteContact['address'] }}</p>
                @endif
                @if($siteContact['phone'] ?? null)
                    <p class="mt-2 text-sm"><a class="hover:text-white" href="tel:{{ preg_replace('/\s+/', '', $siteContact['phone']) }}">{{ $siteContact['phone'] }}</a></p>
                @endif
                @if($siteContact['email'] ?? null)
                    <p class="mt-2 text-sm"><a class="hover:text-white" href="mailto:{{ $siteContact['email'] }}">{{ $siteContact['email'] }}</a></p>
                @endif
            </div>
            <div>
                <p class="text-xs tracking-[0.2em] uppercase text-sand/50 mb-4">Follow</p>
                <div class="flex flex-wrap gap-4 text-sm">
                    @if(!empty($siteSocial['instagram']))
                        <a href="{{ $siteSocial['instagram'] }}" class="hover:text-white" target="_blank" rel="noopener">Instagram</a>
                    @endif
                    @if(!empty($siteSocial['facebook']))
                        <a href="{{ $siteSocial['facebook'] }}" class="hover:text-white" target="_blank" rel="noopener">Facebook</a>
                    @endif
                    @if(!empty($siteSocial['twitter']))
                        <a href="{{ $siteSocial['twitter'] }}" class="hover:text-white" target="_blank" rel="noopener">X</a>
                    @endif
                </div>
            </div>
        </div>
        <div class="border-t border-sand/10 px-5 py-5 text-center text-xs text-sand/40 tracking-wide">
            &copy; {{ date('Y') }} Pearl Pulse Safaris. All rights reserved.
        </div>
    </footer>

    @livewireScripts
    @stack('scripts')
</body>
</html>

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name'))</title>
    <meta name="description" content="@yield('meta_description', 'Private, tailor-made journeys across Uganda, Rwanda, Kenya, and Tanzania with Pearl Pulse Safaris.')">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=cormorant-garamond:400,500,600,700|outfit:300,400,500,600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    @stack('head')
</head>
<body class="min-h-screen flex flex-col">
    <header class="sticky top-0 z-50 border-b border-sand-deep/30 bg-cream/95 backdrop-blur" x-data="{ open: false, mega: null }">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-5 py-4 lg:px-8">
            <a href="{{ route('home') }}" class="font-display text-2xl md:text-3xl tracking-wide text-forest transition-opacity hover:opacity-80">
                Pearl Pulse <span class="font-sans text-[0.55em] tracking-[0.2em] uppercase text-forest/60">Safaris</span>
            </a>

            <nav class="hidden items-center gap-5 xl:gap-7 lg:flex" aria-label="Primary">
                <div class="relative" @mouseenter="mega = 'journeys'" @mouseleave="mega = null">
                    <a href="{{ route('journeys.index') }}" class="{{ request()->routeIs('journeys.*') ? 'nav-link-active font-medium' : 'nav-link' }}">Journeys</a>
                    <div x-show="mega === 'journeys'" x-cloak class="absolute left-1/2 top-full z-50 mt-3 w-[30rem] -translate-x-1/2 border border-sand-deep/30 bg-cream p-6 shadow-xl">
                        <p class="text-[11px] tracking-[0.18em] uppercase text-gold mb-3">Explore journeys</p>
                        <div class="grid grid-cols-2 gap-x-6 gap-y-3 text-sm">
                            <a href="{{ route('journeys.index') }}" class="text-forest hover:text-gold">All journeys</a>
                            <a href="{{ route('journeys.finder') }}" class="text-forest hover:text-gold">Journey Finder</a>
                            @foreach($navCountries as $country)
                                <a href="{{ route('journeys.country', $country) }}" class="text-forest/80 hover:text-gold">{{ $country->name }}</a>
                            @endforeach
                            <a href="{{ route('journeys.index') }}?type=multi" class="text-forest/80 hover:text-gold">Multi-country</a>
                            <a href="{{ route('journeys.index') }}?type=signature" class="text-forest/80 hover:text-gold">Signature</a>
                        </div>
                    </div>
                </div>
                <div class="relative" @mouseenter="mega = 'destinations'" @mouseleave="mega = null">
                    <a href="{{ route('destinations.index') }}" class="{{ request()->routeIs('destinations.*') ? 'nav-link-active font-medium' : 'nav-link' }}">Destinations</a>
                    <div x-show="mega === 'destinations'" x-cloak class="absolute left-1/2 top-full z-50 mt-3 w-72 -translate-x-1/2 border border-sand-deep/30 bg-cream p-6 shadow-xl">
                        <p class="text-[11px] tracking-[0.18em] uppercase text-gold mb-3">Countries</p>
                        <div class="space-y-3 text-sm">
                            @foreach($navCountries as $country)
                                <a href="{{ route('destinations.country', $country) }}" class="block text-forest hover:text-gold">
                                    {{ $country->name }}
                                    @if($country->subtitle)
                                        <span class="block text-xs text-muted mt-0.5 normal-case tracking-normal">{{ $country->subtitle }}</span>
                                    @endif
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="relative" @mouseenter="mega = 'experiences'" @mouseleave="mega = null">
                    <a href="{{ route('experiences.index') }}" class="{{ request()->routeIs('experiences.*') ? 'nav-link-active font-medium' : 'nav-link' }}">Experiences</a>
                    <div x-show="mega === 'experiences'" x-cloak class="absolute left-1/2 top-full z-50 mt-3 w-80 -translate-x-1/2 border border-sand-deep/30 bg-cream p-6 shadow-xl">
                        <p class="text-[11px] tracking-[0.18em] uppercase text-gold mb-3">How you travel</p>
                        <div class="space-y-2.5 text-sm">
                            @foreach($navExperiences as $experience)
                                <a href="{{ route('experiences.show', $experience) }}" class="block text-forest hover:text-gold">{{ $experience->name }}</a>
                            @endforeach
                        </div>
                    </div>
                </div>
                <a href="{{ route('true-pulse') }}" class="{{ request()->routeIs('true-pulse') ? 'nav-link-active font-medium' : 'nav-link' }}">True Pulse</a>
                <a href="{{ route('insiders.index') }}" class="{{ request()->routeIs('insiders.*') ? 'nav-link-active font-medium' : 'nav-link' }}">Insiders</a>
                <a href="{{ route('about') }}" class="{{ request()->routeIs('about') || request()->routeIs('our-people') || request()->routeIs('travel-with-a-reason') ? 'nav-link-active font-medium' : 'nav-link' }}">About</a>
                <a href="{{ route('plan') }}" class="btn-primary !px-5 !py-2 text-xs">Plan your journey</a>
            </nav>

            <button type="button" class="lg:hidden text-forest" @click="open = !open" aria-label="Menu" :aria-expanded="open.toString()">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 7h16M4 12h16M4 17h16"/></svg>
            </button>
        </div>

        <div x-show="open" x-cloak x-transition class="lg:hidden border-t border-sand-deep/20 bg-cream px-5 py-6 space-y-4 max-h-[80vh] overflow-y-auto">
            <a href="{{ route('journeys.index') }}" class="block tracking-[0.14em] uppercase text-sm text-forest">Journeys</a>
            <a href="{{ route('journeys.finder') }}" class="block pl-3 text-sm text-forest/70">Journey Finder</a>
            <a href="{{ route('destinations.index') }}" class="block tracking-[0.14em] uppercase text-sm text-forest">Destinations</a>
            @foreach($navCountries as $country)
                <a href="{{ route('destinations.country', $country) }}" class="block pl-3 text-sm text-forest/70">{{ $country->name }}</a>
            @endforeach
            <a href="{{ route('experiences.index') }}" class="block tracking-[0.14em] uppercase text-sm text-forest">Experiences</a>
            <a href="{{ route('true-pulse') }}" class="block tracking-[0.14em] uppercase text-sm text-forest">True Pulse</a>
            <a href="{{ route('insiders.index') }}" class="block tracking-[0.14em] uppercase text-sm text-forest">Insiders</a>
            <a href="{{ route('stays.index') }}" class="block tracking-[0.14em] uppercase text-sm text-forest">Selected stays</a>
            <a href="{{ route('about') }}" class="block tracking-[0.14em] uppercase text-sm text-forest">About</a>
            <a href="{{ route('plan') }}" class="block tracking-[0.14em] uppercase text-sm text-forest">Plan your journey</a>
        </div>
    </header>

    <main class="flex-1">
        @yield('content')
    </main>

    <footer class="bg-forest text-sand mt-auto">
        <div class="mx-auto max-w-7xl px-5 py-14 lg:px-8 grid gap-10 md:grid-cols-4">
            <div>
                <p class="font-display text-3xl">Pearl Pulse Safaris</p>
                <p class="mt-3 text-sand/70 text-sm leading-relaxed max-w-xs">{{ $footerBlurb ?? 'Private journeys through East Africa.' }}</p>
            </div>
            <div>
                <p class="text-xs tracking-[0.2em] uppercase text-sand/50 mb-4">Explore</p>
                <div class="space-y-2 text-sm">
                    <a href="{{ route('journeys.index') }}" class="block hover:text-white">Journeys</a>
                    <a href="{{ route('destinations.index') }}" class="block hover:text-white">Destinations</a>
                    <a href="{{ route('experiences.index') }}" class="block hover:text-white">Experiences</a>
                    <a href="{{ route('stays.index') }}" class="block hover:text-white">Selected stays</a>
                    <a href="{{ route('about') }}" class="block hover:text-white">About</a>
                    <a href="{{ route('our-people') }}" class="block hover:text-white">Our people</a>
                </div>
            </div>
            <div>
                <p class="text-xs tracking-[0.2em] uppercase text-sand/50 mb-4">Contact</p>
                @if($siteContact['address'] ?? null)
                    <p class="text-sm text-sand/80 whitespace-pre-line">{{ $siteContact['address'] }}</p>
                @endif
                @if($siteContact['phone'] ?? null)
                    <p class="mt-2 text-sm"><a class="hover:text-white" href="tel:{{ preg_replace('/\s+/', '', $siteContact['phone']) }}">{{ $siteContact['phone'] }}</a></p>
                @endif
                @if($siteContact['email'] ?? null)
                    <p class="mt-2 text-sm"><a class="hover:text-white" href="mailto:{{ $siteContact['email'] }}">{{ $siteContact['email'] }}</a></p>
                @endif
                @if(!empty($whatsappUrl))
                    <p class="mt-2 text-sm"><a class="hover:text-white" href="{{ $whatsappUrl }}" target="_blank" rel="noopener">WhatsApp</a></p>
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
                <div class="mt-6 space-y-2 text-xs text-sand/50">
                    <a href="{{ route('legal', 'privacy') }}" class="block hover:text-sand">Privacy</a>
                    <a href="{{ route('legal', 'terms') }}" class="block hover:text-sand">Terms</a>
                    <a href="{{ route('legal', 'cancellation') }}" class="block hover:text-sand">Cancellation</a>
                    <a href="{{ route('legal', 'cookies') }}" class="block hover:text-sand">Cookies</a>
                </div>
            </div>
        </div>
        <div class="border-t border-sand/10 px-5 py-5 text-center text-xs text-sand/40 tracking-wide">
            &copy; {{ date('Y') }} Pearl Pulse Safaris. All rights reserved.
        </div>
    </footer>

    <x-whatsapp-button :url="$whatsappUrl ?? null" />

    @livewireScripts
    @stack('scripts')
</body>
</html>

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="referrer" content="strict-origin-when-cross-origin">

    <title>@yield('title', config('app.name'))</title>
    <meta name="description" content="@yield('meta_description', 'Private, tailor-made journeys across Uganda, Rwanda, Kenya, and Tanzania with Pearl Pulse Safaris.')">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=cormorant-garamond:400,500,600,700|outfit:300,400,500,600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    @stack('head')
</head>
<body class="min-h-screen flex flex-col bg-white" x-data="{ mobileOpen: false, footerOpen: null }">
    {{-- Utility bar (AST trust cues) --}}
    <div class="hidden md:block bg-forest text-white">
        <div class="mx-auto flex max-w-7xl items-center justify-between gap-4 px-5 py-2 lg:px-8">
            <div class="flex flex-wrap items-center gap-x-5 gap-y-1">
                @if($siteContact['phone'] ?? null)
                    <a class="utility-link" href="tel:{{ preg_replace('/\s+/', '', $siteContact['phone']) }}">{{ $siteContact['phone'] }}</a>
                @endif
                @if($siteContact['email'] ?? null)
                    <a class="utility-link" href="mailto:{{ $siteContact['email'] }}">{{ $siteContact['email'] }}</a>
                @endif
            </div>
            <div class="flex flex-wrap items-center gap-x-5 gap-y-1">
                @if(!empty($reviewLinks['google'] ?? null))
                    <a class="utility-link" href="{{ $reviewLinks['google'] }}" target="_blank" rel="noopener">Google reviews</a>
                @endif
                @if(!empty($reviewLinks['tripadvisor'] ?? null))
                    <a class="utility-link" href="{{ $reviewLinks['tripadvisor'] }}" target="_blank" rel="noopener">Tripadvisor</a>
                @endif
                @auth
                    @if(auth()->user()->isAdmin())
                        <a class="utility-link" href="{{ route('admin.dashboard') }}">Admin</a>
                    @else
                        <a class="utility-link" href="{{ route('account.favorites') }}">My journeys</a>
                        <a class="utility-link" href="{{ route('account.profile') }}">Profile</a>
                    @endif
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="utility-link">Sign out</button>
                    </form>
                @else
                    <a class="utility-link" href="{{ route('login') }}">Sign in</a>
                    <a class="utility-link" href="{{ route('register') }}">Register</a>
                @endauth
            </div>
        </div>
    </div>

    <header class="sticky top-0 z-50 border-b border-charcoal/8 bg-white/95 backdrop-blur" data-site-header>
        <div class="site-header-grid mx-auto max-w-7xl px-5 py-3.5 lg:px-8 lg:py-4">
            <nav class="hidden lg:flex items-center gap-6 xl:gap-8" aria-label="Primary left">
                <div class="relative group/nav">
                    <a href="{{ route('journeys.index') }}" class="{{ request()->routeIs('journeys.*') ? 'nav-link-active font-medium' : 'nav-link' }}">Journeys</a>
                    <div class="nav-mega nav-mega-visual nav-mega-start">
                        <p class="nav-mega-label">Explore journeys</p>
                        <div class="nav-mega-countries">
                            <a href="{{ route('journeys.index') }}" class="nav-mega-country">
                                <span class="nav-mega-country-thumb nav-mega-country-thumb--icon">
                                    <span class="text-[10px] tracking-[0.14em] uppercase text-forest">All</span>
                                </span>
                                <span>
                                    <span class="block text-[0.95rem] text-charcoal">All journeys</span>
                                    <span class="nav-mega-sub">Browse every private itinerary</span>
                                </span>
                            </a>
                            <a href="{{ route('journeys.finder') }}" class="nav-mega-country">
                                <span class="nav-mega-country-thumb nav-mega-country-thumb--icon">
                                    <span class="text-[10px] tracking-[0.14em] uppercase text-forest">Find</span>
                                </span>
                                <span>
                                    <span class="block text-[0.95rem] text-charcoal">Journey Finder</span>
                                    <span class="nav-mega-sub">Filter by country, duration, and pace</span>
                                </span>
                            </a>
                            @foreach($navCountries as $country)
                                <a href="{{ route('journeys.country', $country) }}" class="nav-mega-country">
                                    <span class="nav-mega-country-thumb">
                                        @if($country->coverThumbUrl() || $country->coverUrl())
                                            <img src="{{ $country->coverThumbUrl() ?: $country->coverUrl() }}" alt="" loading="lazy">
                                        @endif
                                    </span>
                                    <span>
                                        <span class="block text-[0.95rem] text-charcoal">{{ $country->name }}</span>
                                        <span class="nav-mega-sub">{{ $country->subtitle ?: ($country->teaser ? \Illuminate\Support\Str::limit($country->teaser, 56) : 'Journeys in '.$country->name) }}</span>
                                    </span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="relative group/nav">
                    <a href="{{ route('destinations.index') }}" class="{{ request()->routeIs('destinations.*') ? 'nav-link-active font-medium' : 'nav-link' }}">Destinations</a>
                    <div class="nav-mega nav-mega-visual">
                        <p class="nav-mega-label">Where we travel</p>
                        <div class="nav-mega-countries">
                            @foreach($navCountries as $country)
                                <a href="{{ route('destinations.country', $country) }}" class="nav-mega-country">
                                    <span class="nav-mega-country-thumb">
                                        @if($country->coverThumbUrl() || $country->coverUrl())
                                            <img src="{{ $country->coverThumbUrl() ?: $country->coverUrl() }}" alt="" loading="lazy">
                                        @endif
                                    </span>
                                    <span>
                                        <span class="block text-[0.95rem] text-charcoal">{{ $country->name }}</span>
                                        @if($country->subtitle)
                                            <span class="nav-mega-sub">{{ $country->subtitle }}</span>
                                        @elseif($country->teaser)
                                            <span class="nav-mega-sub">{{ \Illuminate\Support\Str::limit($country->teaser, 56) }}</span>
                                        @endif
                                    </span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="relative group/nav">
                    <a href="{{ route('experiences.index') }}" class="{{ request()->routeIs('experiences.*') ? 'nav-link-active font-medium' : 'nav-link' }}">Experiences</a>
                    <div class="nav-mega nav-mega-visual">
                        <p class="nav-mega-label">How you travel</p>
                        <div class="nav-mega-countries">
                            @foreach($navExperiences as $experience)
                                <a href="{{ route('experiences.show', $experience) }}" class="nav-mega-country">
                                    <span class="nav-mega-country-thumb">
                                        @if($experience->coverThumbUrl() || $experience->coverUrl())
                                            <img src="{{ $experience->coverThumbUrl() ?: $experience->coverUrl() }}" alt="" loading="lazy">
                                        @endif
                                    </span>
                                    <span>
                                        <span class="block text-[0.95rem] text-charcoal">{{ $experience->name }}</span>
                                        @if($experience->teaser)
                                            <span class="nav-mega-sub">{{ \Illuminate\Support\Str::limit($experience->teaser, 56) }}</span>
                                        @elseif($experience->subtitle)
                                            <span class="nav-mega-sub">{{ $experience->subtitle }}</span>
                                        @endif
                                    </span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </nav>

            <a href="{{ route('home') }}" class="font-display text-2xl md:text-[1.85rem] tracking-wide text-charcoal transition-opacity hover:opacity-80 shrink-0 text-center justify-self-center">
                Pearl Pulse <span class="font-sans text-[0.42em] tracking-[0.22em] uppercase text-charcoal/50 align-middle">Safaris</span>
            </a>

            <div class="hidden lg:flex items-center justify-end gap-5 xl:gap-6">
                <div
                    class="relative group/nav"
                    x-data="{ aboutPreview: 0 }"
                >
                    <a href="{{ route('about') }}" class="{{ request()->routeIs('about') || request()->routeIs('our-people') || request()->routeIs('travel-with-a-reason') || request()->routeIs('true-pulse') || request()->routeIs('insiders.*') || request()->routeIs('stays.*') ? 'nav-link-active font-medium' : 'nav-link' }}">About</a>
                    <div class="nav-mega nav-mega-about nav-mega-end">
                        <p class="nav-mega-label">Pearl Pulse</p>
                        <div class="nav-mega-about-grid">
                            <div class="nav-mega-list">
                                @foreach($navAboutItems as $i => $item)
                                    <a
                                        href="{{ $item['href'] }}"
                                        class="nav-mega-link {{ !empty($item['muted']) ? 'nav-mega-link-muted' : '' }}"
                                        @mouseenter="aboutPreview = {{ $i }}"
                                        @focus="aboutPreview = {{ $i }}"
                                    >{{ $item['label'] }}</a>
                                @endforeach
                            </div>
                            <div class="nav-mega-about-preview">
                                @foreach($navAboutItems as $i => $item)
                                    <div
                                        class="nav-mega-about-preview__panel"
                                        x-show="aboutPreview === {{ $i }}"
                                        @if($i !== 0) x-cloak @endif
                                    >
                                        @if(!empty($item['image']))
                                            <img src="{{ $item['image'] }}" alt="" loading="lazy">
                                        @else
                                            <div class="nav-mega-about-preview__fallback"></div>
                                        @endif
                                        <p class="nav-mega-about-preview__copy">{{ $item['teaser'] }}</p>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                <button
                    type="button"
                    class="nav-link inline-flex items-center gap-2"
                    @click="$dispatch('open-journey-search')"
                    aria-label="Find your journey"
                >
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="m21 21-4.3-4.3M11 18a7 7 0 1 1 0-14 7 7 0 0 1 0 14Z"/>
                    </svg>
                    <span class="hidden xl:inline">Find your journey</span>
                </button>
                <a href="{{ route('plan') }}" class="btn-primary !px-5 !py-2.5 text-[11px]">Plan your journey</a>
            </div>

            <div class="flex lg:hidden items-center gap-3 justify-self-end">
                <button type="button" class="text-charcoal" @click="$dispatch('open-journey-search')" aria-label="Find your journey">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="m21 21-4.3-4.3M11 18a7 7 0 1 1 0-14 7 7 0 0 1 0 14Z"/></svg>
                </button>
                <button type="button" class="text-charcoal" @click="mobileOpen = !mobileOpen" aria-label="Menu" :aria-expanded="mobileOpen.toString()">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 7h16M4 12h16M4 17h16"/></svg>
                </button>
            </div>
        </div>

        {{-- Mobile drawer --}}
        <div
            x-show="mobileOpen"
            x-cloak
            x-transition
            class="lg:hidden border-t border-charcoal/8 bg-white px-5 py-6 max-h-[85vh] overflow-y-auto"
            x-data="{ section: null }"
        >
            <div class="space-y-1">
                <button type="button" class="flex w-full items-center justify-between py-3 text-left tracking-[0.14em] uppercase text-sm text-charcoal" @click="section = section === 'journeys' ? null : 'journeys'">
                    Journeys
                    <span class="text-muted" x-text="section === 'journeys' ? '−' : '+'"></span>
                </button>
                <div x-show="section === 'journeys'" x-cloak class="pb-3 space-y-2 pl-3">
                    <a href="{{ route('journeys.index') }}" class="block text-sm text-muted">All journeys</a>
                    <a href="{{ route('journeys.finder') }}" class="block text-sm text-muted">Journey Finder</a>
                    @foreach($navCountries as $country)
                        <a href="{{ route('journeys.country', $country) }}" class="block text-sm text-muted">{{ $country->name }}</a>
                    @endforeach
                </div>

                <button type="button" class="flex w-full items-center justify-between py-3 text-left tracking-[0.14em] uppercase text-sm text-charcoal" @click="section = section === 'destinations' ? null : 'destinations'">
                    Destinations
                    <span class="text-muted" x-text="section === 'destinations' ? '−' : '+'"></span>
                </button>
                <div x-show="section === 'destinations'" x-cloak class="pb-3 space-y-2 pl-3">
                    @foreach($navCountries as $country)
                        <a href="{{ route('destinations.country', $country) }}" class="block text-sm text-muted">{{ $country->name }}</a>
                    @endforeach
                </div>

                <button type="button" class="flex w-full items-center justify-between py-3 text-left tracking-[0.14em] uppercase text-sm text-charcoal" @click="section = section === 'experiences' ? null : 'experiences'">
                    Experiences
                    <span class="text-muted" x-text="section === 'experiences' ? '−' : '+'"></span>
                </button>
                <div x-show="section === 'experiences'" x-cloak class="pb-3 space-y-2 pl-3">
                    @foreach($navExperiences as $experience)
                        <a href="{{ route('experiences.show', $experience) }}" class="block text-sm text-muted">{{ $experience->name }}</a>
                    @endforeach
                </div>

                <button type="button" class="flex w-full items-center justify-between py-3 text-left tracking-[0.14em] uppercase text-sm text-charcoal" @click="section = section === 'about' ? null : 'about'">
                    About
                    <span class="text-muted" x-text="section === 'about' ? '−' : '+'"></span>
                </button>
                <div x-show="section === 'about'" x-cloak class="pb-3 space-y-2 pl-3">
                    <a href="{{ route('about') }}" class="block text-sm text-muted">Our story</a>
                    <a href="{{ route('our-people') }}" class="block text-sm text-muted">Our people</a>
                    <a href="{{ route('travel-with-a-reason') }}" class="block text-sm text-muted">Travel with a reason</a>
                    <a href="{{ route('stays.index') }}" class="block text-sm text-muted">Selected stays</a>
                    <a href="{{ route('true-pulse') }}" class="block text-sm text-muted">True Pulse</a>
                    <a href="{{ route('insiders.index') }}" class="block text-sm text-muted">Insiders</a>
                </div>
            </div>

            <div class="mt-6 space-y-3 border-t border-charcoal/8 pt-6">
                @auth
                    @unless(auth()->user()->isAdmin())
                        <a href="{{ route('account.favorites') }}" class="block text-sm tracking-[0.14em] uppercase text-charcoal">My journeys</a>
                        <a href="{{ route('account.profile') }}" class="block text-sm tracking-[0.14em] uppercase text-charcoal">Profile</a>
                    @endunless
                @else
                    <a href="{{ route('login') }}" class="block text-sm tracking-[0.14em] uppercase text-charcoal">Sign in</a>
                    <a href="{{ route('register') }}" class="block text-sm tracking-[0.14em] uppercase text-charcoal">Register</a>
                @endauth
                <a href="{{ route('plan') }}" class="btn-primary w-full text-center text-[11px]">Plan your journey</a>
                @if($siteContact['phone'] ?? null)
                    <a href="tel:{{ preg_replace('/\s+/', '', $siteContact['phone']) }}" class="block text-center text-sm text-muted">{{ $siteContact['phone'] }}</a>
                @endif
            </div>
        </div>
    </header>

    <main class="flex-1">
        @yield('content')
    </main>

    <footer class="bg-forest text-sand mt-auto">
        <div class="mx-auto max-w-7xl px-5 py-10 lg:py-14 lg:px-8">
            <div class="mb-10 max-w-md">
                <p class="font-display text-3xl text-white">Pearl Pulse Safaris</p>
                <p class="mt-3 text-sand/70 text-sm leading-relaxed">{{ $footerBlurb ?? 'Private journeys through East Africa.' }}</p>
            </div>

            {{-- Desktop columns --}}
            <div class="hidden md:grid gap-10 md:grid-cols-3">
                <div>
                    <p class="text-[11px] tracking-[0.2em] uppercase text-sand/45 mb-4">Explore</p>
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
                    <p class="text-[11px] tracking-[0.2em] uppercase text-sand/45 mb-4">Contact</p>
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
                    <p class="text-[11px] tracking-[0.2em] uppercase text-sand/45 mb-4">Follow</p>
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
                    <div class="mt-6 space-y-2 text-xs text-sand/45">
                        <a href="{{ route('legal', 'privacy') }}" class="block hover:text-sand">Privacy</a>
                        <a href="{{ route('legal', 'terms') }}" class="block hover:text-sand">Terms</a>
                        <a href="{{ route('legal', 'cancellation') }}" class="block hover:text-sand">Cancellation</a>
                        <a href="{{ route('legal', 'cookies') }}" class="block hover:text-sand">Cookies</a>
                    </div>
                </div>
            </div>

            {{-- Mobile accordion footer --}}
            <div class="md:hidden divide-y divide-sand/15 border-y border-sand/15">
                <div>
                    <button type="button" class="flex w-full items-center justify-between py-4 text-[11px] tracking-[0.2em] uppercase text-sand/70" @click="footerOpen = footerOpen === 'explore' ? null : 'explore'">
                        Explore
                        <span x-text="footerOpen === 'explore' ? '−' : '+'"></span>
                    </button>
                    <div x-show="footerOpen === 'explore'" x-cloak class="pb-4 space-y-2 text-sm">
                        <a href="{{ route('journeys.index') }}" class="block hover:text-white">Journeys</a>
                        <a href="{{ route('destinations.index') }}" class="block hover:text-white">Destinations</a>
                        <a href="{{ route('experiences.index') }}" class="block hover:text-white">Experiences</a>
                        <a href="{{ route('stays.index') }}" class="block hover:text-white">Selected stays</a>
                        <a href="{{ route('about') }}" class="block hover:text-white">About</a>
                    </div>
                </div>
                <div>
                    <button type="button" class="flex w-full items-center justify-between py-4 text-[11px] tracking-[0.2em] uppercase text-sand/70" @click="footerOpen = footerOpen === 'contact' ? null : 'contact'">
                        Contact
                        <span x-text="footerOpen === 'contact' ? '−' : '+'"></span>
                    </button>
                    <div x-show="footerOpen === 'contact'" x-cloak class="pb-4 space-y-2 text-sm">
                        @if($siteContact['phone'] ?? null)
                            <a class="block hover:text-white" href="tel:{{ preg_replace('/\s+/', '', $siteContact['phone']) }}">{{ $siteContact['phone'] }}</a>
                        @endif
                        @if($siteContact['email'] ?? null)
                            <a class="block hover:text-white" href="mailto:{{ $siteContact['email'] }}">{{ $siteContact['email'] }}</a>
                        @endif
                        @if(!empty($whatsappUrl))
                            <a class="block hover:text-white" href="{{ $whatsappUrl }}" target="_blank" rel="noopener">WhatsApp</a>
                        @endif
                    </div>
                </div>
                <div>
                    <button type="button" class="flex w-full items-center justify-between py-4 text-[11px] tracking-[0.2em] uppercase text-sand/70" @click="footerOpen = footerOpen === 'legal' ? null : 'legal'">
                        Legal &amp; social
                        <span x-text="footerOpen === 'legal' ? '−' : '+'"></span>
                    </button>
                    <div x-show="footerOpen === 'legal'" x-cloak class="pb-4 space-y-2 text-sm">
                        @if(!empty($siteSocial['instagram']))
                            <a href="{{ $siteSocial['instagram'] }}" class="block hover:text-white" target="_blank" rel="noopener">Instagram</a>
                        @endif
                        <a href="{{ route('legal', 'privacy') }}" class="block hover:text-white">Privacy</a>
                        <a href="{{ route('legal', 'terms') }}" class="block hover:text-white">Terms</a>
                        <a href="{{ route('legal', 'cookies') }}" class="block hover:text-white">Cookies</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="border-t border-sand/10 px-5 py-5 text-center text-xs text-sand/40 tracking-wide">
            &copy; {{ date('Y') }} Pearl Pulse Safaris. All rights reserved.
        </div>
    </footer>

    <livewire:journey-search />
    <x-whatsapp-button :url="$whatsappUrl ?? null" />

    @livewireScripts
    @stack('scripts')
</body>
</html>

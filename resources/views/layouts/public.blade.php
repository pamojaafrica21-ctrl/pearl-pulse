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
<body
    class="min-h-screen flex flex-col bg-white"
    x-data="{
        mobileOpen: false,
        footerOpen: null,
        navOpen: false,
        navSection: 'destinations',
        navFocus: {{ $navCountries->first()['id'] ?? 'null' }},
        countryFocus: {{ $navCountries->first()['id'] ?? 'null' }},
        experienceFocus: {{ $navExperiences->first()['id'] ?? 'null' }},
        aboutFocus: 0,
        openNav(section) {
            if (this.navOpen && this.navSection === section) {
                this.closeNav();
                return;
            }
            this.navSection = section;
            this.syncFocus();
            this.navOpen = true;
            this.mobileOpen = false;
        },
        setSection(section) {
            this.navSection = section;
            this.syncFocus();
        },
        syncFocus() {
            if (this.navSection === 'experiences') {
                this.navFocus = this.experienceFocus;
            } else if (this.navSection === 'about') {
                this.navFocus = this.aboutFocus;
            } else {
                this.navFocus = this.countryFocus;
            }
        },
        closeNav() {
            this.navOpen = false;
        }
    }"
    @keydown.escape.window="closeNav()"
>
    <div class="sticky top-0 z-50" data-site-chrome x-init="$nextTick(() => { const set = () => document.documentElement.style.setProperty('--site-chrome-height', $el.offsetHeight + 'px'); set(); new ResizeObserver(set).observe($el); })">
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
                        <a class="utility-link" href="{{ route('account.requests') }}">My requests</a>
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

    <header
        class="border-b border-charcoal/8 bg-white/95 backdrop-blur"
        data-site-header
    >
        <div class="site-header-grid mx-auto max-w-7xl px-5 py-1 lg:px-8 lg:py-1.5">
            <nav class="hidden lg:flex items-center gap-6 xl:gap-8" aria-label="Primary left">
                <button
                    type="button"
                    class="nav-link"
                    :class="navOpen && navSection === 'destinations' ? 'nav-link-active font-medium' : ''"
                    @click="openNav('destinations')"
                    :aria-expanded="(navOpen && navSection === 'destinations').toString()"
                >Destinations</button>
                <button
                    type="button"
                    class="nav-link"
                    :class="navOpen && navSection === 'journeys' ? 'nav-link-active font-medium' : ''"
                    @click="openNav('journeys')"
                    :aria-expanded="(navOpen && navSection === 'journeys').toString()"
                >Journeys</button>
                <button
                    type="button"
                    class="nav-link"
                    :class="navOpen && navSection === 'experiences' ? 'nav-link-active font-medium' : ''"
                    @click="openNav('experiences')"
                    :aria-expanded="(navOpen && navSection === 'experiences').toString()"
                >Experiences</button>
            </nav>

            <a href="{{ route('home') }}" class="font-display text-2xl md:text-[1.85rem] tracking-wide text-charcoal transition-opacity hover:opacity-80 shrink-0 text-center justify-self-center" @click="closeNav()">
                Pearl Pulse <span class="font-sans text-[0.42em] tracking-[0.22em] uppercase text-charcoal/50 align-middle">Safaris</span>
            </a>

            <div class="hidden lg:flex items-center justify-end gap-5 xl:gap-6">
                <button
                    type="button"
                    class="nav-link"
                    :class="navOpen && navSection === 'about' ? 'nav-link-active font-medium' : ''"
                    @click="openNav('about')"
                    :aria-expanded="(navOpen && navSection === 'about').toString()"
                >About</button>
                <button
                    type="button"
                    class="nav-link inline-flex items-center gap-2"
                    @click="closeNav(); $dispatch('open-journey-search')"
                    aria-label="Find your journey"
                >
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="m21 21-4.3-4.3M11 18a7 7 0 1 1 0-14 7 7 0 0 1 0 14Z"/>
                    </svg>
                    <span class="hidden xl:inline">Find your journey</span>
                </button>
                <a href="{{ route('plan') }}" class="btn-primary !px-5 !py-1.5 text-[11px]" @click="closeNav()">Plan your journey</a>
            </div>

            <div class="flex lg:hidden items-center gap-3 justify-self-end">
                <button type="button" class="text-charcoal" @click="closeNav(); $dispatch('open-journey-search')" aria-label="Find your journey">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="m21 21-4.3-4.3M11 18a7 7 0 1 1 0-14 7 7 0 0 1 0 14Z"/></svg>
                </button>
                <button type="button" class="text-charcoal" @click="closeNav(); mobileOpen = !mobileOpen" aria-label="Menu" :aria-expanded="mobileOpen.toString()">
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
                <button type="button" class="flex w-full items-center justify-between py-3 text-left tracking-[0.14em] uppercase text-sm text-charcoal" @click="section = section === 'destinations' ? null : 'destinations'">
                    Destinations
                    <span class="text-muted" x-text="section === 'destinations' ? '−' : '+'"></span>
                </button>
                <div x-show="section === 'destinations'" x-cloak class="pb-3 space-y-2 pl-3">
                    <a href="{{ route('destinations.index') }}" class="block text-sm text-muted">All destinations</a>
                    @foreach($navCountries as $country)
                        <a href="{{ $country['destinations_url'] }}" class="block text-sm text-muted">{{ $country['name'] }}</a>
                        @foreach(array_slice($country['destinations'], 0, 4) as $destination)
                            <a href="{{ $destination['url'] }}" class="block text-sm text-muted/80 pl-3">{{ $destination['name'] }}</a>
                        @endforeach
                    @endforeach
                </div>

                <button type="button" class="flex w-full items-center justify-between py-3 text-left tracking-[0.14em] uppercase text-sm text-charcoal" @click="section = section === 'journeys' ? null : 'journeys'">
                    Journeys
                    <span class="text-muted" x-text="section === 'journeys' ? '−' : '+'"></span>
                </button>
                <div x-show="section === 'journeys'" x-cloak class="pb-3 space-y-2 pl-3">
                    <a href="{{ route('journeys.index') }}" class="block text-sm text-muted">All journeys</a>
                    <a href="{{ route('journeys.finder') }}" class="block text-sm text-muted">Journey Finder</a>
                    @foreach($navCountries as $country)
                        <a href="{{ $country['journeys_url'] }}" class="block text-sm text-muted">{{ $country['name'] }}</a>
                        @foreach(array_slice($country['journeys'], 0, 3) as $journey)
                            <a href="{{ $journey['url'] }}" class="block text-sm text-muted/80 pl-3">{{ $journey['name'] }}</a>
                        @endforeach
                    @endforeach
                </div>

                <button type="button" class="flex w-full items-center justify-between py-3 text-left tracking-[0.14em] uppercase text-sm text-charcoal" @click="section = section === 'experiences' ? null : 'experiences'">
                    Experiences
                    <span class="text-muted" x-text="section === 'experiences' ? '−' : '+'"></span>
                </button>
                <div x-show="section === 'experiences'" x-cloak class="pb-3 space-y-2 pl-3">
                    <a href="{{ route('experiences.index') }}" class="block text-sm text-muted">All experiences</a>
                    @foreach($navExperiences as $experience)
                        <a href="{{ $experience['url'] }}" class="block text-sm text-muted">{{ $experience['name'] }}</a>
                    @endforeach
                </div>

                <button type="button" class="flex w-full items-center justify-between py-3 text-left tracking-[0.14em] uppercase text-sm text-charcoal" @click="section = section === 'about' ? null : 'about'">
                    About
                    <span class="text-muted" x-text="section === 'about' ? '−' : '+'"></span>
                </button>
                <div x-show="section === 'about'" x-cloak class="pb-3 space-y-2 pl-3">
                    @foreach($navAboutItems as $item)
                        <a href="{{ $item['href'] }}" class="block text-sm text-muted">{{ $item['label'] }}</a>
                    @endforeach
                </div>
            </div>

            <div class="mt-6 space-y-3 border-t border-charcoal/8 pt-6">
                @auth
                    @unless(auth()->user()->isAdmin())
                        <a href="{{ route('account.favorites') }}" class="block text-sm tracking-[0.14em] uppercase text-charcoal">My journeys</a>
                        <a href="{{ route('account.requests') }}" class="block text-sm tracking-[0.14em] uppercase text-charcoal">My requests</a>
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
    </div>

    <div
        class="fixed inset-0 z-40 bg-charcoal/40"
        style="top: var(--site-chrome-height, 4.5rem)"
        x-show="navOpen"
        x-cloak
        x-transition.opacity
        @click="closeNav()"
        aria-hidden="true"
    ></div>

    <div class="hidden lg:block">
        <x-site-nav-panel
            :countries="$navCountries"
            :experiences="$navExperiences"
            :about-items="$navAboutItems"
        />
    </div>

    <main class="flex-1">
        @yield('content')
    </main>

    <div class="site-closer relative isolate overflow-hidden text-sand mt-auto">
        <div class="site-closer__media" aria-hidden="true">
            <img
                src="{{ asset('images/footer-gorilla.jpg') }}"
                alt=""
                class="site-closer__image"
                loading="lazy"
            >
            <div class="site-closer__veil"></div>
        </div>

        <div class="relative z-10">
            @stack('before-footer')

            <footer>
                <div class="mx-auto max-w-7xl px-5 py-12 lg:py-16 lg:px-8">
                    <div class="mb-12 max-w-md">
                        <p class="font-display text-3xl text-white [text-shadow:0_1px_18px_rgb(0_0_0_/_0.45)]">Pearl Pulse Safaris</p>
                        <p class="mt-3 text-white/85 text-sm leading-relaxed [text-shadow:0_1px_12px_rgb(0_0_0_/_0.4)]">{{ $footerBlurb ?? 'Private journeys through East Africa.' }}</p>
                    </div>

                    {{-- Desktop columns --}}
                    <div class="hidden md:grid gap-10 md:grid-cols-3">
                        <div>
                            <p class="text-[11px] tracking-[0.2em] uppercase text-white/60 mb-4">Explore</p>
                            <div class="space-y-2 text-sm text-white/90">
                                <a href="{{ route('journeys.index') }}" class="block hover:text-white transition">Journeys</a>
                                <a href="{{ route('destinations.index') }}" class="block hover:text-white transition">Destinations</a>
                                <a href="{{ route('experiences.index') }}" class="block hover:text-white transition">Experiences</a>
                                <a href="{{ route('stays.index') }}" class="block hover:text-white transition">Selected stays</a>
                                <a href="{{ route('about') }}" class="block hover:text-white transition">About</a>
                                <a href="{{ route('our-people') }}" class="block hover:text-white transition">Our people</a>
                            </div>
                        </div>
                        <div>
                            <p class="text-[11px] tracking-[0.2em] uppercase text-white/60 mb-4">Contact</p>
                            @if($siteContact['address'] ?? null)
                                <p class="text-sm text-white/90 whitespace-pre-line">{{ $siteContact['address'] }}</p>
                            @endif
                            @if($siteContact['phone'] ?? null)
                                <p class="mt-2 text-sm"><a class="text-white/90 hover:text-white transition" href="tel:{{ preg_replace('/\s+/', '', $siteContact['phone']) }}">{{ $siteContact['phone'] }}</a></p>
                            @endif
                            @if($siteContact['email'] ?? null)
                                <p class="mt-2 text-sm"><a class="text-white/90 hover:text-white transition" href="mailto:{{ $siteContact['email'] }}">{{ $siteContact['email'] }}</a></p>
                            @endif
                            @if(!empty($whatsappUrl))
                                <p class="mt-2 text-sm"><a class="text-white/90 hover:text-white transition" href="{{ $whatsappUrl }}" target="_blank" rel="noopener">WhatsApp</a></p>
                            @endif
                        </div>
                        <div>
                            <p class="text-[11px] tracking-[0.2em] uppercase text-white/60 mb-4">Follow</p>
                            <div class="flex flex-wrap gap-4 text-sm text-white/90">
                                @if(!empty($siteSocial['instagram']))
                                    <a href="{{ $siteSocial['instagram'] }}" class="hover:text-white transition" target="_blank" rel="noopener">Instagram</a>
                                @endif
                                @if(!empty($siteSocial['facebook']))
                                    <a href="{{ $siteSocial['facebook'] }}" class="hover:text-white transition" target="_blank" rel="noopener">Facebook</a>
                                @endif
                                @if(!empty($siteSocial['twitter']))
                                    <a href="{{ $siteSocial['twitter'] }}" class="hover:text-white transition" target="_blank" rel="noopener">X</a>
                                @endif
                            </div>
                            <div class="mt-6 space-y-2 text-xs text-white/55">
                                <a href="{{ route('legal', 'privacy') }}" class="block hover:text-white transition">Privacy</a>
                                <a href="{{ route('legal', 'terms') }}" class="block hover:text-white transition">Terms</a>
                                <a href="{{ route('legal', 'cancellation') }}" class="block hover:text-white transition">Cancellation</a>
                                <a href="{{ route('legal', 'cookies') }}" class="block hover:text-white transition">Cookies</a>
                            </div>
                        </div>
                    </div>

                    {{-- Mobile accordion footer --}}
                    <div class="md:hidden divide-y divide-white/15 border-y border-white/15">
                        <div>
                            <button type="button" class="flex w-full items-center justify-between py-4 text-[11px] tracking-[0.2em] uppercase text-white/80" @click="footerOpen = footerOpen === 'explore' ? null : 'explore'">
                                Explore
                                <span x-text="footerOpen === 'explore' ? '−' : '+'"></span>
                            </button>
                            <div x-show="footerOpen === 'explore'" x-cloak class="pb-4 space-y-2 text-sm text-white/90">
                                <a href="{{ route('journeys.index') }}" class="block hover:text-white">Journeys</a>
                                <a href="{{ route('destinations.index') }}" class="block hover:text-white">Destinations</a>
                                <a href="{{ route('experiences.index') }}" class="block hover:text-white">Experiences</a>
                                <a href="{{ route('stays.index') }}" class="block hover:text-white">Selected stays</a>
                                <a href="{{ route('about') }}" class="block hover:text-white">About</a>
                            </div>
                        </div>
                        <div>
                            <button type="button" class="flex w-full items-center justify-between py-4 text-[11px] tracking-[0.2em] uppercase text-white/80" @click="footerOpen = footerOpen === 'contact' ? null : 'contact'">
                                Contact
                                <span x-text="footerOpen === 'contact' ? '−' : '+'"></span>
                            </button>
                            <div x-show="footerOpen === 'contact'" x-cloak class="pb-4 space-y-2 text-sm text-white/90">
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
                            <button type="button" class="flex w-full items-center justify-between py-4 text-[11px] tracking-[0.2em] uppercase text-white/80" @click="footerOpen = footerOpen === 'legal' ? null : 'legal'">
                                Legal &amp; social
                                <span x-text="footerOpen === 'legal' ? '−' : '+'"></span>
                            </button>
                            <div x-show="footerOpen === 'legal'" x-cloak class="pb-4 space-y-2 text-sm text-white/90">
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
                <div class="border-t border-white/15 px-5 py-5 text-center text-xs text-white/55 tracking-wide">
                    &copy; {{ date('Y') }} Pearl Pulse Safaris. All rights reserved.
                </div>
            </footer>
        </div>
    </div>

    <livewire:journey-search />
    <x-whatsapp-button :url="$whatsappUrl ?? null" />

    @livewireScripts
    @stack('scripts')
</body>
</html>

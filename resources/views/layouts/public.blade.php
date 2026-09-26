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
        mobilePanel: null,
        footerOpen: null,
        navOpen: false,
        navSection: null,
        navFocus: null,
        itemFocus: null,
        countryFocus: null,
        experienceFocus: null,
        aboutFocus: null,
        mobileCountry: null,
        openNav(section) {
            if (this.navOpen && this.navSection === section) {
                if (this.navFocus !== null) {
                    this.navFocus = null;
                    this.itemFocus = null;
                    return;
                }
                this.closeNav();
                return;
            }
            this.navSection = section;
            this.navFocus = null;
            this.itemFocus = null;
            this.navOpen = true;
            this.closeMobile();
        },
        setSection(section) {
            this.navSection = section;
            this.navFocus = null;
            this.itemFocus = null;
        },
        selectNavItem(id) {
            this.navFocus = id;
            this.itemFocus = null;
            if (this.navSection === 'experiences') {
                this.experienceFocus = id;
            } else if (this.navSection === 'about') {
                this.aboutFocus = id;
            } else {
                this.countryFocus = id;
            }
        },
        hoverLeaf(id) {
            this.itemFocus = id;
        },
        closeNav() {
            this.navOpen = false;
            this.navFocus = null;
            this.itemFocus = null;
        },
        openMobile() {
            this.closeNav();
            this.mobileOpen = true;
            this.mobilePanel = null;
            this.mobileCountry = null;
            document.documentElement.classList.add('overflow-hidden');
        },
        closeMobile() {
            this.mobileOpen = false;
            this.mobilePanel = null;
            this.mobileCountry = null;
            document.documentElement.classList.remove('overflow-hidden');
        },
        toggleMobile() {
            if (this.mobileOpen) {
                this.closeMobile();
            } else {
                this.openMobile();
            }
        },
        openMobilePanel(panel) {
            this.mobilePanel = panel;
            this.mobileCountry = null;
        },
        openMobileCountry(id) {
            this.mobileCountry = id;
        },
        backMobilePanel() {
            if (this.mobileCountry !== null) {
                this.mobileCountry = null;
                return;
            }
            this.mobilePanel = null;
        },
        toggleMenu() {
            if (window.matchMedia('(min-width: 1024px)').matches) {
                if (this.navOpen) {
                    this.closeNav();
                } else {
                    this.openNav('destinations');
                }
                return;
            }
            this.toggleMobile();
        },
        menuIsOpen() {
            return this.navOpen || this.mobileOpen;
        }
    }"
    @keydown.escape.window="closeNav(); closeMobile()"
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
        <div class="site-header-grid mx-auto max-w-7xl px-5 py-2.5 sm:py-3 lg:px-8 lg:py-3.5 xl:py-4">
            <div class="flex items-center gap-3 sm:gap-4 justify-self-start">
                <button
                    type="button"
                    class="site-menu-toggle text-charcoal"
                    @click="toggleMenu()"
                    aria-label="Menu"
                    :aria-expanded="menuIsOpen().toString()"
                >
                    <svg x-show="!menuIsOpen()" class="h-6 w-6 sm:h-7 sm:w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 7h16M4 12h16M4 17h16"/></svg>
                    <svg x-show="menuIsOpen()" x-cloak class="h-6 w-6 sm:h-7 sm:w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 6l12 12M18 6 6 18"/></svg>
                </button>
                <button
                    type="button"
                    class="nav-link inline-flex items-center gap-2"
                    @click="closeNav(); closeMobile(); $dispatch('open-journey-search')"
                    aria-label="Find your journey"
                >
                    <svg class="h-5 w-5 lg:h-4 lg:w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="m21 21-4.3-4.3M11 18a7 7 0 1 1 0-14 7 7 0 0 1 0 14Z"/>
                    </svg>
                    <span class="hidden sm:inline text-[11px] tracking-[0.16em] uppercase">Find your journey</span>
                </button>
            </div>

            <a href="{{ route('home') }}" class="site-logo font-display tracking-wide text-charcoal transition-opacity hover:opacity-80 shrink-0 text-center justify-self-center" @click="closeNav(); closeMobile()">
                Pearl Pulse <span class="font-sans text-[0.42em] tracking-[0.22em] uppercase text-charcoal/50 align-middle">Safaris</span>
            </a>

            <div class="flex items-center justify-end gap-4 justify-self-end">
                <a href="{{ route('plan') }}" class="btn-primary !px-3.5 !py-2 text-[10px] sm:!px-5 sm:!py-2.5 sm:text-[11px] lg:!px-6 lg:!py-3 whitespace-nowrap" @click="closeNav(); closeMobile()">
                    <span class="sm:hidden">Plan</span>
                    <span class="hidden sm:inline">Plan your journey</span>
                </a>
            </div>
        </div>
    </header>
    </div>

    {{-- Mobile full-screen drawer (A&K-style) --}}
    <div
        class="mobile-drawer lg:hidden"
        x-show="mobileOpen"
        x-cloak
        role="dialog"
        aria-modal="true"
        aria-label="Site menu"
    >
        <div
            class="mobile-drawer__backdrop"
            x-show="mobileOpen"
            x-transition.opacity
            @click="closeMobile()"
        ></div>

        <div
            class="mobile-drawer__sheet"
            x-show="mobileOpen"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="-translate-x-full"
            x-transition:enter-end="translate-x-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="translate-x-0"
            x-transition:leave-end="-translate-x-full"
            @click.stop
        >
            {{-- Root --}}
            <div class="mobile-drawer__level" x-show="!mobilePanel" x-transition.opacity>
                <nav class="mobile-drawer__primary" aria-label="Mobile primary">
                    <button type="button" class="mobile-drawer__primary-item" @click="openMobilePanel('destinations')">
                        <span>Destinations</span>
                        <span class="mobile-drawer__chevron" aria-hidden="true"></span>
                    </button>
                    <button type="button" class="mobile-drawer__primary-item" @click="openMobilePanel('journeys')">
                        <span>Journeys</span>
                        <span class="mobile-drawer__chevron" aria-hidden="true"></span>
                    </button>
                    <button type="button" class="mobile-drawer__primary-item" @click="openMobilePanel('experiences')">
                        <span>Experiences</span>
                        <span class="mobile-drawer__chevron" aria-hidden="true"></span>
                    </button>
                </nav>

                <div class="mobile-drawer__section">
                    <p class="mobile-drawer__eyebrow">Popular</p>
                    <a href="{{ route('journeys.finder') }}" class="mobile-drawer__text-link" @click="closeMobile()">Journey Finder</a>
                    <a href="{{ route('plan') }}" class="mobile-drawer__text-link" @click="closeMobile()">Plan your journey</a>
                    <a href="{{ route('stays.index') }}" class="mobile-drawer__text-link" @click="closeMobile()">Selected stays</a>
                </div>

                <div class="mobile-drawer__section">
                    <button type="button" class="mobile-drawer__row" @click="openMobilePanel('about')">
                        <span>About</span>
                        <span class="mobile-drawer__chevron" aria-hidden="true"></span>
                    </button>
                    @auth
                        @unless(auth()->user()->isAdmin())
                            <a href="{{ route('account.favorites') }}" class="mobile-drawer__text-link" @click="closeMobile()">My journeys</a>
                            <a href="{{ route('account.requests') }}" class="mobile-drawer__text-link" @click="closeMobile()">My requests</a>
                            <a href="{{ route('account.profile') }}" class="mobile-drawer__text-link" @click="closeMobile()">Profile</a>
                        @endunless
                    @else
                        <a href="{{ route('login') }}" class="mobile-drawer__text-link" @click="closeMobile()">Sign in</a>
                        <a href="{{ route('register') }}" class="mobile-drawer__text-link" @click="closeMobile()">Register</a>
                    @endauth
                    @if($siteContact['phone'] ?? null)
                        <a href="tel:{{ preg_replace('/\s+/', '', $siteContact['phone']) }}" class="mobile-drawer__text-link">{{ $siteContact['phone'] }}</a>
                    @endif
                </div>
            </div>

            {{-- Destinations --}}
            <div class="mobile-drawer__level" x-show="mobilePanel === 'destinations' && mobileCountry === null" x-cloak x-transition.opacity>
                <button type="button" class="mobile-drawer__subhead" @click="backMobilePanel()">
                    <span>Destinations</span>
                    <span class="mobile-drawer__chevron mobile-drawer__chevron--down" aria-hidden="true"></span>
                </button>
                @if($navCountries->isNotEmpty())
                    <div class="mobile-nav__grid">
                        @foreach($navCountries as $country)
                            <button type="button" class="mobile-nav__card" @click="openMobileCountry({{ $country['id'] }})">
                                @if(!empty($country['image']) || !empty($country['image_full']))
                                    <img src="{{ $country['image'] ?: $country['image_full'] }}" alt="" loading="lazy" decoding="async">
                                @else
                                    <span class="mobile-nav__card-fallback" aria-hidden="true"></span>
                                @endif
                                <span class="mobile-nav__card-label">{{ $country['name'] }}</span>
                            </button>
                        @endforeach
                    </div>
                @endif
                <a href="{{ route('destinations.index') }}" class="mobile-nav__all" @click="closeMobile()">All destinations</a>
            </div>

            @foreach($navCountries as $country)
                <div class="mobile-drawer__level" x-show="mobilePanel === 'destinations' && mobileCountry === {{ $country['id'] }}" x-cloak x-transition.opacity>
                    <button type="button" class="mobile-drawer__subhead" @click="backMobilePanel()">
                        <span>{{ $country['name'] }}</span>
                        <span class="mobile-drawer__chevron mobile-drawer__chevron--down" aria-hidden="true"></span>
                    </button>
                    <div class="mobile-nav__leaf-list">
                        @forelse($country['destinations'] as $destination)
                            <a href="{{ $destination['url'] }}" class="mobile-nav__leaf" @click="closeMobile()">
                                @if(!empty($destination['image']) || !empty($destination['image_full']) || !empty($country['image']))
                                    <img src="{{ $destination['image'] ?? ($destination['image_full'] ?? $country['image']) }}" alt="" loading="lazy" decoding="async">
                                @else
                                    <span class="mobile-nav__leaf-fallback" aria-hidden="true"></span>
                                @endif
                                <span>{{ $destination['name'] }}</span>
                            </a>
                        @empty
                            <p class="mobile-drawer__text-link">Destinations coming soon.</p>
                        @endforelse
                    </div>
                    <a href="{{ $country['destinations_url'] }}" class="mobile-nav__all" @click="closeMobile()">All {{ $country['name'] }} destinations</a>
                </div>
            @endforeach

            {{-- Journeys --}}
            <div class="mobile-drawer__level" x-show="mobilePanel === 'journeys' && mobileCountry === null" x-cloak x-transition.opacity>
                <button type="button" class="mobile-drawer__subhead" @click="backMobilePanel()">
                    <span>Journeys</span>
                    <span class="mobile-drawer__chevron mobile-drawer__chevron--down" aria-hidden="true"></span>
                </button>
                @if($navCountries->isNotEmpty())
                    <div class="mobile-nav__grid">
                        @foreach($navCountries as $country)
                            <button type="button" class="mobile-nav__card" @click="openMobileCountry({{ $country['id'] }})">
                                @if(!empty($country['image']) || !empty($country['image_full']))
                                    <img src="{{ $country['image'] ?: $country['image_full'] }}" alt="" loading="lazy" decoding="async">
                                @else
                                    <span class="mobile-nav__card-fallback" aria-hidden="true"></span>
                                @endif
                                <span class="mobile-nav__card-label">{{ $country['name'] }}</span>
                            </button>
                        @endforeach
                    </div>
                @endif
                <a href="{{ route('journeys.index') }}" class="mobile-nav__all" @click="closeMobile()">All journeys</a>
                <a href="{{ route('journeys.finder') }}" class="mobile-drawer__text-link mt-3" @click="closeMobile()">Journey Finder</a>
            </div>

            @foreach($navCountries as $country)
                <div class="mobile-drawer__level" x-show="mobilePanel === 'journeys' && mobileCountry === {{ $country['id'] }}" x-cloak x-transition.opacity>
                    <button type="button" class="mobile-drawer__subhead" @click="backMobilePanel()">
                        <span>{{ $country['name'] }} journeys</span>
                        <span class="mobile-drawer__chevron mobile-drawer__chevron--down" aria-hidden="true"></span>
                    </button>
                    <div class="mobile-nav__leaf-list">
                        @forelse($country['journeys'] as $journey)
                            <a href="{{ $journey['url'] }}" class="mobile-nav__leaf" @click="closeMobile()">
                                @if(!empty($journey['image']) || !empty($country['image']))
                                    <img src="{{ $journey['image'] ?? $country['image'] }}" alt="" loading="lazy" decoding="async">
                                @else
                                    <span class="mobile-nav__leaf-fallback" aria-hidden="true"></span>
                                @endif
                                <span>{{ $journey['name'] }}</span>
                            </a>
                        @empty
                            <p class="mobile-drawer__text-link">Journeys coming soon.</p>
                        @endforelse
                    </div>
                    <a href="{{ $country['journeys_url'] }}" class="mobile-nav__all" @click="closeMobile()">All {{ $country['name'] }} journeys</a>
                </div>
            @endforeach

            {{-- Experiences --}}
            <div class="mobile-drawer__level" x-show="mobilePanel === 'experiences'" x-cloak x-transition.opacity>
                <button type="button" class="mobile-drawer__subhead" @click="backMobilePanel()">
                    <span>Experiences</span>
                    <span class="mobile-drawer__chevron mobile-drawer__chevron--down" aria-hidden="true"></span>
                </button>
                @if($navExperiences->isNotEmpty())
                    <div class="mobile-nav__grid">
                        @foreach($navExperiences as $experience)
                            <a href="{{ $experience['url'] }}" class="mobile-nav__card" @click="closeMobile()">
                                @if(!empty($experience['image']) || !empty($experience['image_full']))
                                    <img src="{{ $experience['image'] ?: $experience['image_full'] }}" alt="" loading="lazy" decoding="async">
                                @else
                                    <span class="mobile-nav__card-fallback" aria-hidden="true"></span>
                                @endif
                                <span class="mobile-nav__card-label">{{ $experience['name'] }}</span>
                            </a>
                        @endforeach
                    </div>
                @endif
                <a href="{{ route('experiences.index') }}" class="mobile-nav__all" @click="closeMobile()">All experiences</a>
            </div>

            {{-- About --}}
            <div class="mobile-drawer__level" x-show="mobilePanel === 'about'" x-cloak x-transition.opacity>
                <button type="button" class="mobile-drawer__subhead" @click="backMobilePanel()">
                    <span>About</span>
                    <span class="mobile-drawer__chevron mobile-drawer__chevron--down" aria-hidden="true"></span>
                </button>
                <div class="mobile-drawer__section !border-0 !pt-0">
                    @foreach($navAboutItems as $item)
                        <a href="{{ $item['href'] }}" class="mobile-drawer__text-link" @click="closeMobile()">{{ $item['label'] }}</a>
                    @endforeach
                </div>
            </div>
        </div>
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

    <div class="hidden lg:block relative z-[46]">
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

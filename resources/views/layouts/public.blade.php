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
    <link href="https://fonts.bunny.net/css?family=cormorant-garamond:400,500,600,700|great-vibes:400|outfit:300,400,500,600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    @stack('head')
</head>
<body
    class="min-h-screen flex flex-col bg-cream"
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
    <div
        class="sticky top-0 z-50"
        data-site-chrome
        x-init="$nextTick(() => {
            let frame = null;
            let last = 0;
            const set = () => {
                cancelAnimationFrame(frame);
                frame = requestAnimationFrame(() => {
                    const height = Math.round($el.offsetHeight);
                    if (height === last) return;
                    last = height;
                    document.documentElement.style.setProperty('--site-chrome-height', height + 'px');
                });
            };
            set();
            new ResizeObserver(set).observe($el);
        })"
    >
    {{-- Utility bar — quieter, collapses on scroll --}}
    <div class="site-utility bg-forest text-white" data-site-utility>
        <div class="site-utility__inner mx-auto flex max-w-7xl items-center justify-between gap-2 px-4 sm:gap-4 sm:px-5 lg:px-8">
            <div class="flex min-w-0 items-center gap-x-3 sm:gap-x-5">
                @if($siteContact['phone'] ?? null)
                    <a class="utility-link shrink-0" href="tel:{{ preg_replace('/\s+/', '', $siteContact['phone']) }}">{{ $siteContact['phone'] }}</a>
                @endif
                @if($siteContact['email'] ?? null)
                    <a class="utility-link site-utility__email truncate" href="mailto:{{ $siteContact['email'] }}">{{ $siteContact['email'] }}</a>
                @endif
            </div>
            <div class="flex shrink-0 items-center gap-x-3 sm:gap-x-5">
                @auth
                    @if(auth()->user()->isAdmin())
                        <a class="utility-link" href="{{ route('admin.dashboard') }}">Admin</a>
                    @else
                        <a class="utility-link site-utility__account" href="{{ route('account.favorites') }}">My journeys</a>
                        <a class="utility-link site-utility__account" href="{{ route('account.requests') }}">My requests</a>
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
        class="border-b border-charcoal/8 bg-cream/95 backdrop-blur"
        data-site-header
    >
        <div class="site-header-grid mx-auto max-w-7xl px-5 py-2 sm:py-2.5 lg:px-8 lg:py-3 xl:py-3.5">
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
                    class="nav-link inline-flex items-center gap-2 shrink-0"
                    @click="closeNav(); closeMobile(); $dispatch('open-journey-search')"
                    aria-label="Find your journey"
                >
                    <svg class="h-5 w-5 lg:h-4 lg:w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="m21 21-4.3-4.3M11 18a7 7 0 1 1 0-14 7 7 0 0 1 0 14Z"/>
                    </svg>
                    <span class="site-header-search-label hidden lg:inline text-[0.85rem] tracking-[0.16em] uppercase">Find your journey</span>
                </button>
            </div>

            <a href="{{ route('home') }}" class="site-logo font-display tracking-wide text-charcoal transition-opacity hover:opacity-80 shrink-0 text-center justify-self-center" @click="closeNav(); closeMobile()">
                Pearl Pulse <span class="font-sans text-[0.42em] tracking-[0.22em] uppercase text-charcoal/50 align-middle">Safaris</span>
            </a>

            <div class="flex items-center justify-end gap-4 justify-self-end">
                <a href="{{ route('plan') }}" class="btn-primary site-header__plan whitespace-nowrap" @click="closeNav(); closeMobile()">
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
                    <a href="{{ route('how-it-works') }}" class="mobile-drawer__text-link" @click="closeMobile()">How it works</a>
                    <a href="{{ route('specialist.index') }}" class="mobile-drawer__text-link" @click="closeMobile()">Specialist journeys</a>
                    <a href="{{ route('true-pulse') }}" class="mobile-drawer__text-link" @click="closeMobile()">True Pulse</a>
                    <a href="{{ route('insiders.index') }}" class="mobile-drawer__text-link" @click="closeMobile()">Insiders</a>
                    <a href="{{ route('faqs.index') }}" class="mobile-drawer__text-link" @click="closeMobile()">FAQs</a>
                    <a href="{{ route('stays.index') }}" class="mobile-drawer__text-link" @click="closeMobile()">Preferred lodges</a>
                    <a href="{{ route('reviews.index') }}" class="mobile-drawer__text-link" @click="closeMobile()">Guest stories</a>
                    <a href="{{ route('contact') }}" class="mobile-drawer__text-link" @click="closeMobile()">Speak to a specialist</a>
                    <a href="{{ route('legal', 'privacy') }}" class="mobile-drawer__text-link" @click="closeMobile()">Privacy &amp; terms</a>
                    <a href="{{ route('plan') }}" class="mobile-drawer__text-link" @click="closeMobile()">Plan your journey</a>
                </div>

                @if(!empty($reviewLinks['google'] ?? null) || !empty($reviewLinks['tripadvisor'] ?? null))
                    <div class="mobile-drawer__section">
                        <p class="mobile-drawer__eyebrow">Leave a review</p>
                        <x-review-platform-links
                            :links="$reviewLinks ?? []"
                            :show-lead="false"
                            class="mobile-drawer__reviews"
                        />
                    </div>
                @endif

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

    @stack('before-footer')

    <div class="footer-separator" aria-hidden="true">
        <span class="footer-separator__script">Feel The Nature</span>
    </div>

    <div class="site-closer relative isolate overflow-hidden text-sand">
        <div class="relative z-10">

            <footer class="site-footer">
                <div class="mx-auto max-w-7xl px-5 pb-14 pt-16 lg:px-8 lg:pb-16 lg:pt-20">
                    {{-- Desktop columns --}}
                    <div class="hidden gap-12 md:grid md:grid-cols-2 lg:grid-cols-12 lg:gap-10">
                        <div class="lg:col-span-4">
                            <p class="font-display text-4xl leading-none text-white">Pearl Pulse <span class="align-middle font-sans text-[0.38em] uppercase tracking-[0.24em] text-white/60">Safaris</span></p>
                            <p class="mt-4 max-w-xs text-sm leading-relaxed text-white/75">{{ $footerBlurb ?? 'Private journeys through East Africa.' }}</p>
                            <p class="mb-3 mt-8 text-[0.68rem] uppercase tracking-[0.26em] text-white/50">Follow us</p>
                            <div class="flex flex-wrap gap-2.5">
                                @foreach(($siteSocial ?? []) as $platform => $url)
                                    @if(!empty($url))
                                        <x-social-link :platform="$platform" :url="$url" />
                                    @endif
                                @endforeach
                            </div>
                            <div class="mt-8 max-w-sm">
                                <livewire:newsletter-signup wire:key="newsletter-desktop" />
                            </div>
                        </div>
                        <div class="lg:col-span-2">
                            <p class="footer-heading">Explore</p>
                            <div class="space-y-2.5 text-sm">
                                <a href="{{ route('journeys.index') }}" class="footer-link">Journeys</a>
                                <a href="{{ route('destinations.index') }}" class="footer-link">Destinations</a>
                                <a href="{{ route('experiences.index') }}" class="footer-link">Experiences</a>
                                <a href="{{ route('how-it-works') }}" class="footer-link">How it works</a>
                                <a href="{{ route('stays.index') }}" class="footer-link">Preferred lodges</a>
                            </div>
                        </div>
                        <div class="lg:col-span-2">
                            <p class="footer-heading">Company</p>
                            <div class="space-y-2.5 text-sm">
                                <a href="{{ route('about') }}" class="footer-link">About</a>
                                <a href="{{ route('our-people') }}" class="footer-link">Our people</a>
                                <a href="{{ route('reviews.index') }}" class="footer-link">Guest stories</a>
                                <a href="{{ route('faqs.index') }}" class="footer-link">FAQs</a>
                                <a href="{{ route('contact') }}" class="footer-link">Contact</a>
                            </div>
                        </div>
                        <div class="lg:col-span-4">
                            <p class="footer-heading">Contact</p>
                            <a href="{{ route('contact') }}" class="inline-flex items-center gap-2 border border-white/35 px-5 py-2.5 text-[0.72rem] uppercase tracking-[0.18em] text-white transition hover:border-[var(--color-brown)] hover:bg-[var(--color-brown)] hover:text-[#211a13]">
                                Talk to a specialist
                                <span aria-hidden="true">&rarr;</span>
                            </a>
                            <div class="mt-6 space-y-2.5 text-sm text-white/80">
                                @if($siteContact['address'] ?? null)
                                    <p class="whitespace-pre-line leading-relaxed text-white/75">{{ $siteContact['address'] }}</p>
                                @endif
                                @if($siteContact['phone'] ?? null)
                                    <p><a class="footer-link" href="tel:{{ preg_replace('/\s+/', '', $siteContact['phone']) }}">{{ $siteContact['phone'] }}</a></p>
                                @endif
                                @if($siteContact['email'] ?? null)
                                    <p><a class="footer-link" href="mailto:{{ $siteContact['email'] }}">{{ $siteContact['email'] }}</a></p>
                                @endif
                                @if(!empty($whatsappUrl))
                                    <p><a class="footer-link" href="{{ $whatsappUrl }}" target="_blank" rel="noopener">WhatsApp</a></p>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Mobile brand block --}}
                    <div class="mb-10 md:hidden">
                        <p class="font-display text-3xl leading-none text-white">Pearl Pulse <span class="align-middle font-sans text-[0.38em] uppercase tracking-[0.24em] text-white/60">Safaris</span></p>
                        <p class="mt-3 text-sm leading-relaxed text-white/75">{{ $footerBlurb ?? 'Private journeys through East Africa.' }}</p>
                        <p class="mb-3 mt-6 text-[0.68rem] uppercase tracking-[0.26em] text-white/50">Follow us</p>
                        <div class="flex flex-wrap gap-2.5">
                            @foreach(($siteSocial ?? []) as $platform => $url)
                                @if(!empty($url))
                                    <x-social-link :platform="$platform" :url="$url" />
                                @endif
                            @endforeach
                        </div>
                        <div class="mt-8">
                            <livewire:newsletter-signup wire:key="newsletter-mobile" />
                        </div>
                    </div>

                    {{-- Mobile accordion footer --}}
                    <div class="md:hidden divide-y divide-white/15 border-y border-white/15">
                        <div>
                            <button type="button" class="flex w-full items-center justify-between py-4 text-[13px] tracking-[0.2em] uppercase text-white/80" @click="footerOpen = footerOpen === 'explore' ? null : 'explore'">
                                Explore
                                <span x-text="footerOpen === 'explore' ? '−' : '+'"></span>
                            </button>
                            <div x-show="footerOpen === 'explore'" x-cloak class="pb-4 space-y-2 text-sm text-white/90">
                                <a href="{{ route('journeys.index') }}" class="block hover:text-white">Journeys</a>
                                <a href="{{ route('destinations.index') }}" class="block hover:text-white">Destinations</a>
                                <a href="{{ route('experiences.index') }}" class="block hover:text-white">Experiences</a>
                                <a href="{{ route('how-it-works') }}" class="block hover:text-white">How it works</a>
                                <a href="{{ route('stays.index') }}" class="block hover:text-white">Preferred lodges</a>
                            </div>
                        </div>
                        <div>
                            <button type="button" class="flex w-full items-center justify-between py-4 text-[13px] tracking-[0.2em] uppercase text-white/80" @click="footerOpen = footerOpen === 'company' ? null : 'company'">
                                Company
                                <span x-text="footerOpen === 'company' ? '−' : '+'"></span>
                            </button>
                            <div x-show="footerOpen === 'company'" x-cloak class="pb-4 space-y-2 text-sm text-white/90">
                                <a href="{{ route('about') }}" class="block hover:text-white">About</a>
                                <a href="{{ route('our-people') }}" class="block hover:text-white">Our people</a>
                                <a href="{{ route('reviews.index') }}" class="block hover:text-white">Guest stories</a>
                                <a href="{{ route('faqs.index') }}" class="block hover:text-white">FAQs</a>
                                <a href="{{ route('contact') }}" class="block hover:text-white">Contact</a>
                            </div>
                        </div>
                        <div>
                            <button type="button" class="flex w-full items-center justify-between py-4 text-[13px] tracking-[0.2em] uppercase text-white/80" @click="footerOpen = footerOpen === 'contact' ? null : 'contact'">
                                Contact
                                <span x-text="footerOpen === 'contact' ? '−' : '+'"></span>
                            </button>
                            <div x-show="footerOpen === 'contact'" x-cloak class="pb-4 space-y-2 text-sm text-white/90">
                                <a href="{{ route('contact') }}" class="block hover:text-white">Speak to a specialist</a>
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
                            <button type="button" class="flex w-full items-center justify-between py-4 text-[13px] tracking-[0.2em] uppercase text-white/80" @click="footerOpen = footerOpen === 'legal' ? null : 'legal'">
                                Legal
                                <span x-text="footerOpen === 'legal' ? '−' : '+'"></span>
                            </button>
                            <div x-show="footerOpen === 'legal'" x-cloak class="pb-4 space-y-2 text-sm text-white/90">
                                <a href="{{ route('legal', 'privacy') }}" class="block hover:text-white">Privacy</a>
                                <a href="{{ route('legal', 'terms') }}" class="block hover:text-white">Terms</a>
                                <a href="{{ route('legal', 'cancellation') }}" class="block hover:text-white">Cancellation</a>
                                <a href="{{ route('legal', 'cookies') }}" class="block hover:text-white">Cookies</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="border-t border-white/12">
                    <div class="mx-auto flex max-w-7xl flex-col items-center gap-3 px-5 py-6 text-center text-xs tracking-wide text-white/55 md:flex-row md:justify-between md:text-left lg:px-8">
                        <p>&copy; {{ date('Y') }} Pearl Pulse Safaris. All rights reserved.</p>
                        <nav class="flex flex-wrap items-center justify-center gap-x-6 gap-y-2" aria-label="Legal">
                            <a href="{{ route('legal', 'privacy') }}" class="footer-legal-link">Privacy</a>
                            <a href="{{ route('legal', 'terms') }}" class="footer-legal-link">Terms</a>
                            <a href="{{ route('legal', 'cancellation') }}" class="footer-legal-link">Cancellation</a>
                            <a href="{{ route('legal', 'cookies') }}" class="footer-legal-link">Cookies</a>
                        </nav>
                    </div>
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

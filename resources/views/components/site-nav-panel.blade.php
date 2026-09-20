@props([
    'countries',
    'experiences',
    'aboutItems',
])

<div
    class="site-nav-panel"
    x-show="navOpen"
    x-cloak
    :class="navFocus !== null && 'is-drilled'"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 -translate-x-4"
    x-transition:enter-end="opacity-100 translate-x-0"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 translate-x-0"
    x-transition:leave-end="opacity-0 -translate-x-4"
    role="dialog"
    aria-label="Site navigation"
    :aria-hidden="(!navOpen).toString()"
>
    <div class="site-nav-panel__inner">
        {{-- Column 1: sections --}}
        <div class="site-nav-panel__col site-nav-panel__col--sections">
            <button type="button" class="site-nav-panel__close" @click="closeNav()" aria-label="Close menu">
                <span aria-hidden="true">×</span>
                <span class="sr-only">Close</span>
            </button>
            <div class="site-nav-panel__sections">
                <button type="button" class="site-nav-panel__section" :class="navSection === 'destinations' && 'is-active'" @click="setSection('destinations')">
                    <span>Destinations</span>
                    <span class="site-nav-panel__chevron" aria-hidden="true">›</span>
                </button>
                <button type="button" class="site-nav-panel__section" :class="navSection === 'journeys' && 'is-active'" @click="setSection('journeys')">
                    <span>Journeys</span>
                    <span class="site-nav-panel__chevron" aria-hidden="true">›</span>
                </button>
                <button type="button" class="site-nav-panel__section" :class="navSection === 'experiences' && 'is-active'" @click="setSection('experiences')">
                    <span>Experiences</span>
                    <span class="site-nav-panel__chevron" aria-hidden="true">›</span>
                </button>
                <button type="button" class="site-nav-panel__section" :class="navSection === 'about' && 'is-active'" @click="setSection('about')">
                    <span>About</span>
                    <span class="site-nav-panel__chevron" aria-hidden="true">›</span>
                </button>
            </div>
            <div class="site-nav-panel__aside-links">
                <p class="site-nav-panel__eyebrow">Popular</p>
                <a href="{{ route('journeys.finder') }}" class="site-nav-panel__aside-link">Journey Finder</a>
                <a href="{{ route('plan') }}" class="site-nav-panel__aside-link">Plan your journey</a>
                <a href="{{ route('stays.index') }}" class="site-nav-panel__aside-link">Selected stays</a>
            </div>
        </div>

        {{-- Destinations --}}
        <template x-if="navSection === 'destinations'">
            <div class="site-nav-panel__rest">
                <div class="site-nav-panel__col site-nav-panel__col--mid">
                    @if($countries->isNotEmpty())
                        <div class="site-nav-panel__tiles">
                            @foreach($countries->take(2) as $country)
                                <button
                                    type="button"
                                    class="site-nav-panel__tile"
                                    @mouseenter="selectNavItem({{ $country['id'] }})"
                                    @focus="selectNavItem({{ $country['id'] }})"
                                    @click="selectNavItem({{ $country['id'] }})"
                                >
                                    @if(!empty($country['image']))
                                        <img src="{{ $country['image'] }}" alt="" loading="lazy">
                                    @endif
                                    <span>{{ $country['name'] }}</span>
                                </button>
                            @endforeach
                        </div>
                        <a href="{{ route('destinations.index') }}" class="site-nav-panel__all">All destinations</a>
                        <div class="site-nav-panel__list">
                            @foreach($countries as $country)
                                <button
                                    type="button"
                                    class="site-nav-panel__list-item"
                                    :class="navFocus === {{ $country['id'] }} && 'is-active'"
                                    @mouseenter="selectNavItem({{ $country['id'] }})"
                                    @focus="selectNavItem({{ $country['id'] }})"
                                    @click="selectNavItem({{ $country['id'] }})"
                                >
                                    <span>{{ $country['name'] }}</span>
                                    <span class="site-nav-panel__chevron" aria-hidden="true">›</span>
                                </button>
                            @endforeach
                        </div>
                    @endif
                </div>
                <div
                    class="site-nav-panel__drill"
                    x-show="navFocus !== null"
                    x-cloak
                    x-transition:enter="transition ease-out duration-250"
                    x-transition:enter-start="opacity-0 translate-x-3"
                    x-transition:enter-end="opacity-100 translate-x-0"
                >
                    <div class="site-nav-panel__col site-nav-panel__col--detail">
                        @foreach($countries as $country)
                            <div x-show="navFocus === {{ $country['id'] }}" x-cloak>
                                <a href="{{ $country['destinations_url'] }}" class="site-nav-panel__all">All {{ $country['name'] }} destinations</a>
                                <div class="site-nav-panel__list">
                                    @forelse(array_slice($country['destinations'], 0, 10) as $destination)
                                        <a
                                            href="{{ $destination['url'] }}"
                                            class="site-nav-panel__list-link"
                                            :class="itemFocus === {{ $destination['id'] }} && 'is-active'"
                                            @mouseenter="hoverLeaf({{ $destination['id'] }})"
                                            @focus="hoverLeaf({{ $destination['id'] }})"
                                        >{{ $destination['name'] }}</a>
                                    @empty
                                        <p class="site-nav-panel__empty">Destinations coming soon.</p>
                                    @endforelse
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="site-nav-panel__col site-nav-panel__col--preview">
                        @foreach($countries as $country)
                            <div class="site-nav-panel__preview" x-show="navFocus === {{ $country['id'] }}" x-cloak>
                                <div class="site-nav-panel__preview-frame" x-show="itemFocus === null">
                                    @if(!empty($country['image_full']) || !empty($country['image']))
                                        <img src="{{ $country['image_full'] ?: $country['image'] }}" alt="" loading="lazy">
                                    @else
                                        <div class="site-nav-panel__preview-fallback"></div>
                                    @endif
                                    <div class="site-nav-panel__preview-copy">
                                        <p class="site-nav-panel__preview-title">{{ $country['name'] }}</p>
                                        @if(!empty($country['teaser']) || !empty($country['subtitle']))
                                            <p>{{ $country['subtitle'] ?: \Illuminate\Support\Str::limit($country['teaser'], 110) }}</p>
                                        @endif
                                        <a href="{{ $country['destinations_url'] }}">Explore {{ $country['name'] }}</a>
                                    </div>
                                </div>
                                @foreach($country['destinations'] as $destination)
                                    @php
                                        $destinationImage = $destination['image_full'] ?? $destination['image'] ?? ($country['image_full'] ?: $country['image']);
                                    @endphp
                                    <div class="site-nav-panel__preview-frame" x-show="itemFocus === {{ $destination['id'] }}" x-cloak>
                                        @if(!empty($destinationImage))
                                            <img src="{{ $destinationImage }}" alt="" loading="lazy">
                                        @else
                                            <div class="site-nav-panel__preview-fallback"></div>
                                        @endif
                                        <div class="site-nav-panel__preview-copy">
                                            <p class="site-nav-panel__preview-title">{{ $destination['name'] }}</p>
                                            @if(!empty($destination['teaser']) || !empty($destination['subtitle']))
                                                <p>{{ $destination['subtitle'] ?: \Illuminate\Support\Str::limit($destination['teaser'], 110) }}</p>
                                            @endif
                                            <a href="{{ $destination['url'] }}">Explore destination</a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </template>

        {{-- Journeys --}}
        <template x-if="navSection === 'journeys'">
            <div class="site-nav-panel__rest">
                <div class="site-nav-panel__col site-nav-panel__col--mid">
                    <div class="site-nav-panel__tiles">
                        <a href="{{ route('journeys.index') }}" class="site-nav-panel__tile site-nav-panel__tile--plain">
                            <span>All journeys</span>
                        </a>
                        <a href="{{ route('journeys.finder') }}" class="site-nav-panel__tile site-nav-panel__tile--plain">
                            <span>Journey Finder</span>
                        </a>
                    </div>
                    <a href="{{ route('journeys.index') }}" class="site-nav-panel__all">Browse every itinerary</a>
                    <div class="site-nav-panel__list">
                        @foreach($countries as $country)
                            <button
                                type="button"
                                class="site-nav-panel__list-item"
                                :class="navFocus === {{ $country['id'] }} && 'is-active'"
                                @mouseenter="selectNavItem({{ $country['id'] }})"
                                @focus="selectNavItem({{ $country['id'] }})"
                                @click="selectNavItem({{ $country['id'] }})"
                            >
                                <span>{{ $country['name'] }}</span>
                                <span class="site-nav-panel__chevron" aria-hidden="true">›</span>
                            </button>
                        @endforeach
                    </div>
                </div>
                <div
                    class="site-nav-panel__drill"
                    x-show="navFocus !== null"
                    x-cloak
                    x-transition:enter="transition ease-out duration-250"
                    x-transition:enter-start="opacity-0 translate-x-3"
                    x-transition:enter-end="opacity-100 translate-x-0"
                >
                    <div class="site-nav-panel__col site-nav-panel__col--detail">
                        @foreach($countries as $country)
                            <div x-show="navFocus === {{ $country['id'] }}" x-cloak>
                                <a href="{{ $country['journeys_url'] }}" class="site-nav-panel__all">All {{ $country['name'] }} journeys</a>
                                <div class="site-nav-panel__list">
                                    @forelse(array_slice($country['journeys'], 0, 8) as $journey)
                                        <a
                                            href="{{ $journey['url'] }}"
                                            class="site-nav-panel__list-link"
                                            :class="itemFocus === {{ $journey['id'] }} && 'is-active'"
                                            @mouseenter="hoverLeaf({{ $journey['id'] }})"
                                            @focus="hoverLeaf({{ $journey['id'] }})"
                                        >
                                            <span>{{ $journey['name'] }}</span>
                                            @if(!empty($journey['duration_label']))
                                                <span class="site-nav-panel__meta">{{ $journey['duration_label'] }}</span>
                                            @endif
                                        </a>
                                    @empty
                                        <p class="site-nav-panel__empty">Journeys coming soon.</p>
                                    @endforelse
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="site-nav-panel__col site-nav-panel__col--preview">
                        @foreach($countries as $country)
                            @php $previewJourney = $country['journeys'][0] ?? null; @endphp
                            <div class="site-nav-panel__preview" x-show="navFocus === {{ $country['id'] }}" x-cloak>
                                <div class="site-nav-panel__preview-frame" x-show="itemFocus === null">
                                    @if(!empty($previewJourney['image']) || !empty($country['image_full']) || !empty($country['image']))
                                        <img src="{{ $previewJourney['image'] ?? ($country['image_full'] ?: $country['image']) }}" alt="" loading="lazy">
                                    @else
                                        <div class="site-nav-panel__preview-fallback"></div>
                                    @endif
                                    <div class="site-nav-panel__preview-copy">
                                        <p class="site-nav-panel__preview-title">{{ $country['name'] }} journeys</p>
                                        <p>Private itineraries shaped around {{ $country['name'] }}.</p>
                                        <a href="{{ $country['journeys_url'] }}">Explore journeys</a>
                                    </div>
                                </div>
                                @foreach($country['journeys'] as $journey)
                                    <div class="site-nav-panel__preview-frame" x-show="itemFocus === {{ $journey['id'] }}" x-cloak>
                                        @if(!empty($journey['image']) || !empty($country['image_full']) || !empty($country['image']))
                                            <img src="{{ $journey['image'] ?? ($country['image_full'] ?: $country['image']) }}" alt="" loading="lazy">
                                        @else
                                            <div class="site-nav-panel__preview-fallback"></div>
                                        @endif
                                        <div class="site-nav-panel__preview-copy">
                                            <p class="site-nav-panel__preview-title">{{ $journey['name'] }}</p>
                                            <p>{{ !empty($journey['teaser']) ? \Illuminate\Support\Str::limit($journey['teaser'], 110) : 'A private itinerary through '.$country['name'].'.' }}</p>
                                            <a href="{{ $journey['url'] }}">View journey</a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </template>

        {{-- Experiences --}}
        <template x-if="navSection === 'experiences'">
            <div class="site-nav-panel__rest">
                <div class="site-nav-panel__col site-nav-panel__col--mid">
                    @if($experiences->isNotEmpty())
                        <div class="site-nav-panel__tiles">
                            @foreach($experiences->take(2) as $experience)
                                <button
                                    type="button"
                                    class="site-nav-panel__tile"
                                    @mouseenter="selectNavItem({{ $experience['id'] }})"
                                    @focus="selectNavItem({{ $experience['id'] }})"
                                    @click="selectNavItem({{ $experience['id'] }})"
                                >
                                    @if(!empty($experience['image']))
                                        <img src="{{ $experience['image'] }}" alt="" loading="lazy">
                                    @endif
                                    <span>{{ $experience['name'] }}</span>
                                </button>
                            @endforeach
                        </div>
                        <a href="{{ route('experiences.index') }}" class="site-nav-panel__all">All experiences</a>
                        <div class="site-nav-panel__list">
                            @foreach($experiences as $experience)
                                <button
                                    type="button"
                                    class="site-nav-panel__list-item"
                                    :class="navFocus === {{ $experience['id'] }} && 'is-active'"
                                    @mouseenter="selectNavItem({{ $experience['id'] }})"
                                    @focus="selectNavItem({{ $experience['id'] }})"
                                    @click="selectNavItem({{ $experience['id'] }})"
                                >
                                    <span>{{ $experience['name'] }}</span>
                                    <span class="site-nav-panel__chevron" aria-hidden="true">›</span>
                                </button>
                            @endforeach
                        </div>
                    @endif
                </div>
                <div
                    class="site-nav-panel__drill"
                    x-show="navFocus !== null"
                    x-cloak
                    x-transition:enter="transition ease-out duration-250"
                    x-transition:enter-start="opacity-0 translate-x-3"
                    x-transition:enter-end="opacity-100 translate-x-0"
                >
                    <div class="site-nav-panel__col site-nav-panel__col--preview">
                        @foreach($experiences as $experience)
                            <div class="site-nav-panel__preview" x-show="navFocus === {{ $experience['id'] }}" x-cloak>
                                <div class="site-nav-panel__preview-frame">
                                    @if(!empty($experience['image_full']) || !empty($experience['image']))
                                        <img src="{{ $experience['image_full'] ?: $experience['image'] }}" alt="" loading="lazy">
                                    @else
                                        <div class="site-nav-panel__preview-fallback"></div>
                                    @endif
                                    <div class="site-nav-panel__preview-copy">
                                        <p class="site-nav-panel__preview-title">{{ $experience['name'] }}</p>
                                        @if(!empty($experience['teaser']) || !empty($experience['subtitle']))
                                            <p>{{ $experience['subtitle'] ?: \Illuminate\Support\Str::limit($experience['teaser'], 110) }}</p>
                                        @endif
                                        <a href="{{ $experience['url'] }}">Explore experience</a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </template>

        {{-- About --}}
        <template x-if="navSection === 'about'">
            <div class="site-nav-panel__rest">
                <div class="site-nav-panel__col site-nav-panel__col--mid">
                    <p class="site-nav-panel__eyebrow">Pearl Pulse</p>
                    <div class="site-nav-panel__list">
                        @foreach($aboutItems as $i => $item)
                            <a
                                href="{{ $item['href'] }}"
                                class="site-nav-panel__list-item {{ !empty($item['muted']) ? 'is-muted' : '' }}"
                                :class="navFocus === {{ $i }} && 'is-active'"
                                @mouseenter="selectNavItem({{ $i }})"
                                @focus="selectNavItem({{ $i }})"
                            >
                                <span>{{ $item['label'] }}</span>
                                <span class="site-nav-panel__chevron" aria-hidden="true">›</span>
                            </a>
                        @endforeach
                    </div>
                </div>
                <div
                    class="site-nav-panel__drill"
                    x-show="navFocus !== null"
                    x-cloak
                    x-transition:enter="transition ease-out duration-250"
                    x-transition:enter-start="opacity-0 translate-x-3"
                    x-transition:enter-end="opacity-100 translate-x-0"
                >
                    <div class="site-nav-panel__col site-nav-panel__col--preview">
                        @foreach($aboutItems as $i => $item)
                            <div class="site-nav-panel__preview" x-show="navFocus === {{ $i }}" x-cloak>
                                <div class="site-nav-panel__preview-frame">
                                    @if(!empty($item['image']))
                                        <img src="{{ $item['image'] }}" alt="" loading="lazy">
                                    @else
                                        <div class="site-nav-panel__preview-fallback"></div>
                                    @endif
                                    <div class="site-nav-panel__preview-copy">
                                        <p class="site-nav-panel__preview-title">{{ $item['label'] }}</p>
                                        <p>{{ $item['teaser'] }}</p>
                                        <a href="{{ $item['href'] }}">Read more</a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </template>
    </div>
</div>

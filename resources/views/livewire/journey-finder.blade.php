<div class="journey-quiz">
    <section class="journey-quiz__hero">
        <div class="mx-auto max-w-7xl px-5 lg:px-8">
            <p class="text-xs tracking-[0.22em] uppercase text-gold mb-3">Journey Finder</p>
            <h1 class="font-display text-4xl md:text-6xl text-forest">Find your journey</h1>
            @if($step === 1)
                <p class="mt-4 max-w-2xl text-muted leading-relaxed">
                    A short, personal quiz — destination, experiences, pace, and timing — then we match journeys or craft one around you.
                </p>
            @else
                <div class="journey-quiz__progress mt-6" aria-label="Quiz progress">
                    <p class="text-xs tracking-[0.16em] uppercase text-muted mb-3">
                        @if($step <= 7)
                            Step {{ min($step, 7) }} of 7
                        @elseif($step === 8)
                            Your custom request
                        @else
                            Request sent
                        @endif
                    </p>
                    <div class="journey-quiz__dots">
                        @for($i = 1; $i <= 7; $i++)
                            <span @class(['journey-quiz__dot', 'is-active' => $step === $i, 'is-done' => $step > $i])></span>
                        @endfor
                    </div>
                </div>
            @endif
        </div>
    </section>

    <section class="journey-quiz__body">
        <div class="journey-quiz__layout mx-auto max-w-7xl px-5 lg:px-8">
            <aside class="journey-quiz__path-rail" aria-hidden="true">
                <svg class="journey-quiz__path-svg" viewBox="0 0 80 720" fill="none" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none">
                    <path
                        d="M40 12 C40 80 18 120 40 180 C62 240 40 280 40 340 C40 400 62 440 40 500 C18 560 40 600 40 700"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-dasharray="3 8"
                        stroke-linecap="round"
                        class="journey-quiz__path-line"
                    />
                </svg>
                <div class="journey-quiz__milestones">
                    <span class="journey-quiz__milestone" style="--m: 8%">
                        <svg viewBox="0 0 48 32" fill="currentColor"><path d="M6 22h4l2-6h10l3 6h5l-2-8h4l2 3h4v-3h-3l-1-4H28l-2-5H16l-2 5H8l-1 4H4v3h2l2 5zm12-8h6l1 3h-8l1-3zM10 24h20v2H10z"/></svg>
                    </span>
                    <span class="journey-quiz__milestone" style="--m: 36%">
                        <svg viewBox="0 0 40 40" fill="currentColor"><ellipse cx="20" cy="28" rx="12" ry="6"/><path d="M10 26c2-10 8-16 18-18 1 4-1 10-4 14"/><circle cx="28" cy="12" r="3"/></svg>
                    </span>
                    <span class="journey-quiz__milestone" style="--m: 64%">
                        <svg viewBox="0 0 40 40" fill="currentColor"><path d="M20 6c-2 6-8 10-8 18h16c0-8-6-12-8-18z"/><path d="M12 28c2 4 6 6 8 6s6-2 8-6"/></svg>
                    </span>
                    <span class="journey-quiz__milestone" style="--m: 88%">
                        <svg viewBox="0 0 40 40" fill="currentColor"><path d="M8 30c4-10 10-16 12-22 2 6 8 12 12 22H8z"/><path d="M14 18c2-1 4-1 6 0"/></svg>
                    </span>
                </div>
            </aside>

            <div class="journey-quiz__panel">
                @if($step === 1)
                    <div class="journey-quiz__step" wire:key="step-1">
                        <x-path-accent class="text-forest/40 mb-6" />
                        <h2 class="font-display text-3xl md:text-4xl text-forest">Where will your path lead?</h2>
                        <p class="mt-3 max-w-xl text-muted leading-relaxed">
                            Answer a few essentials. If what you want is not listed, you can tell us in your own words — we will shape the journey around you.
                        </p>
                        <button type="button" wire:click="start" class="btn-primary mt-8">Begin the quiz</button>
                    </div>
                @endif

                @if($step === 2)
                    <div class="journey-quiz__step" wire:key="step-2">
                        <h2 class="font-display text-3xl text-forest">Where do you want to go?</h2>
                        <p class="mt-2 text-muted">Select one or more countries — your next choices will narrow to what we offer there.</p>
                        <div class="journey-quiz__tiles mt-8">
                            @foreach($countryOptions as $countryOption)
                                <button
                                    type="button"
                                    wire:click="toggleCountry('{{ $countryOption->slug }}')"
                                    aria-pressed="{{ in_array($countryOption->slug, $selectedCountries, true) ? 'true' : 'false' }}"
                                    @class(['journey-quiz__tile', 'is-selected' => in_array($countryOption->slug, $selectedCountries, true)])
                                >
                                    @if($countryOption->coverThumbUrl() || $countryOption->coverUrl())
                                        <img src="{{ $countryOption->coverThumbUrl() ?: $countryOption->coverUrl() }}" alt="" loading="lazy">
                                    @else
                                        <span class="journey-quiz__tile-fallback"></span>
                                    @endif
                                    <span class="journey-quiz__tile-check" aria-hidden="true">✓</span>
                                    <span class="journey-quiz__tile-copy">
                                        <span class="journey-quiz__tile-label">{{ $countryOption->name }}</span>
                                        @if($countryOption->subtitle || $countryOption->teaser)
                                            <span class="journey-quiz__tile-desc">{{ $countryOption->subtitle ?: \Illuminate\Support\Str::limit(strip_tags($countryOption->teaser), 70) }}</span>
                                        @endif
                                    </span>
                                </button>
                            @endforeach
                            <button
                                type="button"
                                wire:click="toggleCountryOther"
                                aria-pressed="{{ $countryOtherMode ? 'true' : 'false' }}"
                                @class(['journey-quiz__tile', 'journey-quiz__tile--other', 'is-selected' => $countryOtherMode])
                            >
                                <span class="journey-quiz__tile-check" aria-hidden="true">✓</span>
                                <span class="journey-quiz__tile-copy">
                                    <span class="journey-quiz__tile-label">Somewhere else</span>
                                    <span class="journey-quiz__tile-desc">A place not listed — we’ll craft a custom route around it.</span>
                                </span>
                            </button>
                        </div>
                        @if($countryOtherMode)
                            <div class="mt-6 max-w-md">
                                <label class="block text-xs tracking-[0.14em] uppercase text-muted mb-2">Where exactly?</label>
                                <input type="text" wire:model="countryOther" class="w-full border-sand-deep/40 focus:border-forest focus:ring-forest" placeholder="e.g. Cross-border Uganda & Rwanda">
                                @error('countryOther') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
                            </div>
                        @endif
                        <div class="journey-quiz__nav">
                            <button type="button" wire:click="back" class="journey-quiz__back">Back</button>
                            <button type="button" wire:click="next" class="btn-primary">Continue</button>
                        </div>
                    </div>
                @endif

                @if($step === 3)
                    <div class="journey-quiz__step" wire:key="step-3">
                        <h2 class="font-display text-3xl text-forest">What do you want to experience?</h2>
                        <p class="mt-2 text-muted">
                            @if($selectedCountries !== [])
                                Experiences offered on journeys in your selected {{ \Illuminate\Support\Str::plural('country', count($selectedCountries)) }}.
                            @else
                                Select all that call to you — or describe something else.
                            @endif
                        </p>
                        @if($experienceOptions->isEmpty() && $selectedCountries !== [])
                            <p class="mt-6 text-sm text-muted">No listed experiences match those countries yet — describe what you want below, or go back and adjust.</p>
                        @endif
                        <div class="journey-quiz__tiles mt-8">
                            @foreach($experienceOptions as $experienceOption)
                                <button
                                    type="button"
                                    wire:click="toggleExperience('{{ $experienceOption->slug }}')"
                                    aria-pressed="{{ in_array($experienceOption->slug, $experiences, true) ? 'true' : 'false' }}"
                                    @class(['journey-quiz__tile', 'is-selected' => in_array($experienceOption->slug, $experiences, true)])
                                >
                                    @if($experienceOption->coverThumbUrl() || $experienceOption->coverUrl())
                                        <img src="{{ $experienceOption->coverThumbUrl() ?: $experienceOption->coverUrl() }}" alt="" loading="lazy">
                                    @else
                                        <span class="journey-quiz__tile-fallback"></span>
                                    @endif
                                    <span class="journey-quiz__tile-check" aria-hidden="true">✓</span>
                                    <span class="journey-quiz__tile-copy">
                                        <span class="journey-quiz__tile-label">{{ $experienceOption->name }}</span>
                                        @if($experienceOption->subtitle || $experienceOption->teaser)
                                            <span class="journey-quiz__tile-desc">{{ $experienceOption->subtitle ?: \Illuminate\Support\Str::limit(strip_tags($experienceOption->teaser), 70) }}</span>
                                        @endif
                                    </span>
                                </button>
                            @endforeach
                            <button
                                type="button"
                                wire:click="toggleExperienceOther"
                                aria-pressed="{{ $experienceOtherMode ? 'true' : 'false' }}"
                                @class(['journey-quiz__tile', 'journey-quiz__tile--other', 'is-selected' => $experienceOtherMode])
                            >
                                <span class="journey-quiz__tile-check" aria-hidden="true">✓</span>
                                <span class="journey-quiz__tile-copy">
                                    <span class="journey-quiz__tile-label">Something else</span>
                                    <span class="journey-quiz__tile-desc">Not on the list — tell us in your own words.</span>
                                </span>
                            </button>
                        </div>
                        @if($experienceOtherMode)
                            <div class="mt-6 max-w-md">
                                <label class="block text-xs tracking-[0.14em] uppercase text-muted mb-2">Describe it</label>
                                <input type="text" wire:model="experienceOther" class="w-full border-sand-deep/40 focus:border-forest focus:ring-forest" placeholder="e.g. Chimpanzee tracking at dawn">
                                @error('experienceOther') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
                            </div>
                        @endif
                        <div class="journey-quiz__nav">
                            <button type="button" wire:click="back" class="journey-quiz__back">Back</button>
                            <button type="button" wire:click="next" class="btn-primary">Continue</button>
                        </div>
                    </div>
                @endif

                @if($step === 4)
                    <div class="journey-quiz__step" wire:key="step-4">
                        <h2 class="font-display text-3xl text-forest">How long will you travel?</h2>
                        <p class="mt-2 text-muted">Lengths available for your choices so far — pick a pace, or enter a custom length.</p>
                        <div class="journey-quiz__option-cards mt-8">
                            @foreach($durationOptions as $value => $option)
                                <button
                                    type="button"
                                    wire:click="selectDuration('{{ $value }}')"
                                    @disabled(! $option['available'])
                                    aria-pressed="{{ $duration === $value ? 'true' : 'false' }}"
                                    @class([
                                        'journey-quiz__option-card',
                                        'is-selected' => $duration === $value,
                                        'is-disabled' => ! $option['available'],
                                    ])
                                >
                                    <span class="journey-quiz__option-card-check" aria-hidden="true">✓</span>
                                    <span class="journey-quiz__option-card-title">{{ $option['label'] }}</span>
                                    <span class="journey-quiz__option-card-copy">{{ $option['description'] }}</span>
                                    @if($value !== 'custom')
                                        <span class="journey-quiz__option-card-meta">
                                            @if($option['available'])
                                                {{ $option['count'] }} {{ \Illuminate\Support\Str::plural('journey', $option['count']) }} match
                                            @else
                                                No journeys at this length for your picks
                                            @endif
                                        </span>
                                    @endif
                                </button>
                            @endforeach
                        </div>
                        @if($duration === 'custom')
                            <div class="mt-6 max-w-xs">
                                <label class="block text-xs tracking-[0.14em] uppercase text-muted mb-2">How many days?</label>
                                <input type="text" wire:model="durationCustom" class="w-full border-sand-deep/40 focus:border-forest focus:ring-forest" placeholder="e.g. 14 days">
                                @error('durationCustom') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
                            </div>
                        @endif
                        <div class="journey-quiz__nav">
                            <button type="button" wire:click="back" class="journey-quiz__back">Back</button>
                            <button type="button" wire:click="next" class="btn-primary">Continue</button>
                        </div>
                    </div>
                @endif

                @if($step === 5)
                    <div class="journey-quiz__step" wire:key="step-5">
                        <h2 class="font-display text-3xl text-forest">When would you like to travel?</h2>
                        <p class="mt-2 text-muted">Select one or more periods — or leave dates open and we will advise.</p>
                        <div class="journey-quiz__option-cards mt-8">
                            @foreach([
                                'Dry season' => 'Clearer skies, classic game viewing, cooler nights; peak wildlife concentrations.',
                                'Green season' => 'Lush landscapes, fewer crowds, dramatic skies; great for birding and photography.',
                                'Flexible' => 'We’ll recommend the best window for your destinations and experiences.',
                            ] as $value => $description)
                                <button
                                    type="button"
                                    wire:click="toggleSeason('{{ $value }}')"
                                    aria-pressed="{{ in_array($value, $travelSeasons, true) ? 'true' : 'false' }}"
                                    @class(['journey-quiz__option-card', 'is-selected' => in_array($value, $travelSeasons, true)])
                                >
                                    <span class="journey-quiz__option-card-check" aria-hidden="true">✓</span>
                                    <span class="journey-quiz__option-card-title">{{ $value }}</span>
                                    <span class="journey-quiz__option-card-copy">{{ $description }}</span>
                                </button>
                            @endforeach
                        </div>
                        <div class="mt-8 grid gap-4 sm:grid-cols-2 max-w-lg">
                            <div>
                                <label class="block text-xs tracking-[0.14em] uppercase text-muted mb-2">From</label>
                                <input type="date" wire:model="travelDateFrom" class="w-full border-sand-deep/40 focus:border-forest focus:ring-forest">
                                @error('travelDateFrom') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-xs tracking-[0.14em] uppercase text-muted mb-2">To</label>
                                <input type="date" wire:model="travelDateTo" class="w-full border-sand-deep/40 focus:border-forest focus:ring-forest">
                                @error('travelDateTo') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
                            </div>
                        </div>
                        <div class="journey-quiz__nav">
                            <button type="button" wire:click="back" class="journey-quiz__back">Back</button>
                            <button type="button" wire:click="next" class="btn-primary">Continue</button>
                        </div>
                    </div>
                @endif

                @if($step === 6)
                    <div class="journey-quiz__step" wire:key="step-6">
                        <h2 class="font-display text-3xl text-forest">Stay style & travellers</h2>
                        <p class="mt-2 text-muted">Stay styles available for your route so far — and who is coming with you.</p>
                        <div class="journey-quiz__option-cards mt-8">
                            @foreach($stayOptions as $value => $option)
                                <button
                                    type="button"
                                    wire:click="selectStay('{{ $value }}')"
                                    @disabled(! $option['available'])
                                    aria-pressed="{{ $stayStyle === $value ? 'true' : 'false' }}"
                                    @class([
                                        'journey-quiz__option-card',
                                        'is-selected' => $stayStyle === $value,
                                        'is-disabled' => ! $option['available'],
                                    ])
                                >
                                    <span class="journey-quiz__option-card-check" aria-hidden="true">✓</span>
                                    <span class="journey-quiz__option-card-title">{{ $option['label'] }}</span>
                                    <span class="journey-quiz__option-card-copy">{{ $option['description'] }}</span>
                                    @unless($option['available'])
                                        <span class="journey-quiz__option-card-meta">Not offered on matching journeys</span>
                                    @endunless
                                </button>
                            @endforeach
                        </div>
                        <div class="mt-8 max-w-xs">
                            <label class="block text-xs tracking-[0.14em] uppercase text-muted mb-2">Travellers</label>
                            <input type="text" wire:model="travellers" class="w-full border-sand-deep/40 focus:border-forest focus:ring-forest" placeholder="e.g. 2">
                            @error('travellers') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
                        </div>
                        <div class="journey-quiz__nav">
                            <button type="button" wire:click="back" class="journey-quiz__back">Back</button>
                            <button type="button" wire:click="next" class="btn-primary">See matches</button>
                        </div>
                    </div>
                @endif

                @if($step === 7)
                    <div class="journey-quiz__step" wire:key="step-7">
                        <h2 class="font-display text-3xl text-forest">
                            @if($journeys->isNotEmpty())
                                Journeys shaped around you
                            @else
                                Let us craft this for you
                            @endif
                        </h2>
                        <p class="mt-2 text-muted">
                            @if($journeys->isNotEmpty())
                                {{ $journeys->count() }} {{ Str::plural('matched journey', $journeys->count()) }}. Browse below, or request a custom private plan.
                            @else
                                Nothing on the shelf matches exactly — tell us a little more and we will design a private itinerary.
                            @endif
                        </p>

                        @if($journeys->isNotEmpty())
                            <div class="mt-8 grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
                                @foreach($journeys as $journey)
                                    <x-journey-card :journey="$journey" />
                                @endforeach
                            </div>
                        @endif

                        <div class="journey-quiz__request-band mt-12">
                            <div>
                                <p class="font-display text-2xl text-forest">Request a custom journey</p>
                                <p class="mt-2 text-sm text-muted max-w-lg">
                                    Leave your details and we will follow up with a personal proposal
                                    @if($needsCustom)
                                        based on what you described
                                    @endif.
                                </p>
                            </div>
                            <button type="button" wire:click="goToRequest" class="btn-primary shrink-0">
                                {{ $needsCustom ? 'Send your request' : 'Request a custom plan' }}
                            </button>
                        </div>

                        <div class="journey-quiz__nav">
                            <button type="button" wire:click="back" class="journey-quiz__back">Back</button>
                        </div>
                    </div>
                @endif

                @if($step === 8)
                    <div class="journey-quiz__step" wire:key="step-8">
                        <h2 class="font-display text-3xl text-forest">How can we reach you?</h2>
                        <p class="mt-2 text-muted">Your quiz answers will be included so we can respond with something personal.</p>

                        @auth
                            <p class="mt-4 text-sm text-forest bg-forest/5 border border-forest/15 px-4 py-3">
                                We’ll save this request to
                                <a href="{{ route('account.requests') }}" class="underline underline-offset-2">My requests</a>
                                in your account.
                            </p>
                        @else
                            <p class="mt-4 text-sm text-muted bg-white border border-charcoal/10 px-4 py-3">
                                <a href="{{ route('login') }}" class="text-forest underline underline-offset-2">Sign in</a>
                                or
                                <a href="{{ route('register') }}" class="text-forest underline underline-offset-2">register</a>
                                to save this request to your account.
                            </p>
                        @endauth

                        <form wire:submit="submitRequest" class="mt-8 max-w-xl space-y-5">
                            <div>
                                <label class="block text-xs tracking-[0.14em] uppercase text-muted mb-2">Name</label>
                                <input type="text" wire:model="name" class="w-full border-sand-deep/40 focus:border-forest focus:ring-forest" required>
                                @error('name') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-xs tracking-[0.14em] uppercase text-muted mb-2">Email</label>
                                <input type="email" wire:model="email" class="w-full border-sand-deep/40 focus:border-forest focus:ring-forest" required>
                                @error('email') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
                            </div>
                            <div class="grid gap-4 sm:grid-cols-2">
                                <div>
                                    <label class="block text-xs tracking-[0.14em] uppercase text-muted mb-2">Phone</label>
                                    <input type="text" wire:model="phone" class="w-full border-sand-deep/40 focus:border-forest focus:ring-forest">
                                </div>
                                <div>
                                    <label class="block text-xs tracking-[0.14em] uppercase text-muted mb-2">WhatsApp</label>
                                    <input type="text" wire:model="whatsapp" class="w-full border-sand-deep/40 focus:border-forest focus:ring-forest">
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs tracking-[0.14em] uppercase text-muted mb-2">Message</label>
                                <textarea wire:model="message" rows="5" class="w-full border-sand-deep/40 focus:border-forest focus:ring-forest" required></textarea>
                                @error('message') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
                            </div>
                            <div class="journey-quiz__nav !mt-6">
                                <button type="button" wire:click="$set('step', 7)" class="journey-quiz__back">Back</button>
                                <button type="submit" class="btn-primary" wire:loading.attr="disabled">Send request</button>
                            </div>
                        </form>
                    </div>
                @endif

                @if($step === 9)
                    <div class="journey-quiz__step journey-quiz__step--done" wire:key="step-9">
                        <x-path-accent class="text-forest/40 mb-6" />
                        <h2 class="font-display text-3xl md:text-4xl text-forest">We have your path</h2>
                        <p class="mt-3 max-w-xl text-muted leading-relaxed">
                            Thank you. Your request is with our planners now — we will be in touch shortly to shape the journey with you.
                        </p>
                        <div class="mt-8 flex flex-wrap gap-4">
                            <button type="button" wire:click="restart" class="btn-primary">Start another quiz</button>
                            @auth
                                <a href="{{ route('account.requests') }}" class="btn-outline">View My requests</a>
                            @else
                                <a href="{{ route('journeys.index') }}" class="btn-outline">Browse all journeys</a>
                            @endauth
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>

    @if($step < 8)
        <x-page-cta heading="Prefer to talk it through?" text="Skip the quiz and tell us directly — we will shape something around you." button="Plan your journey" :href="route('plan')" />
    @endif
</div>

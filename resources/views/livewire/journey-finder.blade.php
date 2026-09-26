@php
    $stepperLabels = [
        1 => 'Places',
        2 => 'Experiences',
        3 => 'Length',
        4 => 'When',
        5 => 'Stays',
        6 => 'Matches',
        7 => 'Request',
    ];
    // Livewire: 1 intro, 2–7 quiz, 8 request form, 9 done
    $stepProgress = $step <= 1 ? 0 : min($step - 1, 7);
    $showStepper = $step >= 2 && $step <= 8;
@endphp

<div class="journey-quiz">
    <section class="journey-quiz__hero">
        <div class="mx-auto max-w-7xl px-5 lg:px-8">
            <p class="text-xs tracking-[0.22em] uppercase text-gold mb-2 md:mb-3">Journey Finder</p>
            <h1 class="font-display text-3xl sm:text-4xl md:text-5xl lg:text-6xl text-forest leading-tight">Find your journey</h1>
            @if($step === 1)
                <p class="mt-3 md:mt-4 max-w-2xl text-muted leading-relaxed text-sm sm:text-base">
                    A considered sequence — destination, experiences, pace, and timing — then we match journeys or craft one around you.
                </p>
            @elseif($showStepper)
                <div class="journey-quiz__progress mt-5 md:mt-6" aria-label="Finder progress">
                    <p class="text-xs tracking-[0.16em] uppercase text-muted mb-3">
                        @if($step <= 7)
                            Step {{ $step - 1 }} of 7
                        @else
                            Your custom request
                        @endif
                    </p>
                    <ol class="journey-quiz__stepper">
                        @foreach($stepperLabels as $n => $label)
                            <li
                                class="journey-quiz__stepper-item{{ $stepProgress === $n ? ' is-active' : '' }}{{ $stepProgress > $n ? ' is-done' : '' }}"
                                @if($stepProgress === $n) aria-current="step" @endif
                            >
                                <span class="journey-quiz__stepper-mark">
                                    <span class="journey-quiz__stepper-num">{{ $n }}</span>
                                </span>
                                <span class="journey-quiz__stepper-label">{{ $label }}</span>
                            </li>
                        @endforeach
                    </ol>
                </div>
            @endif
        </div>
    </section>

    <section class="journey-quiz__body">
        <div class="journey-quiz__layout mx-auto max-w-7xl px-5 lg:px-8">
            <div class="journey-quiz__panel">
                @if($step === 1)
                    <div class="journey-quiz__step journey-quiz__step--intro" wire:key="step-1">
                        <x-path-accent class="text-forest/40 mb-5 md:mb-6" />
                        <h2 class="font-display text-3xl md:text-4xl text-forest">Where will your path lead?</h2>
                        <p class="mt-3 max-w-xl text-muted leading-relaxed text-sm sm:text-base">
                            Answer a few essentials. If what you want is not listed, you can tell us in your own words — we will shape the journey around you.
                        </p>
                        <button type="button" wire:click="start" class="btn-primary mt-8">Begin</button>
                    </div>
                @endif

                @if($step === 2)
                    <div class="journey-quiz__step" wire:key="step-2">
                        <h2 class="font-display text-2xl sm:text-3xl text-forest">Where do you want to go?</h2>
                        <p class="mt-2 text-muted text-sm sm:text-base max-w-2xl">Select one or more countries — your next choices will narrow to what we offer there.</p>
                        <div class="journey-quiz__tiles mt-6 md:mt-8">
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
                        <h2 class="font-display text-2xl sm:text-3xl text-forest">What do you want to experience?</h2>
                        <p class="mt-2 text-muted text-sm sm:text-base max-w-2xl">
                            @if($selectedCountries !== [])
                                Experiences offered on journeys in your selected {{ \Illuminate\Support\Str::plural('country', count($selectedCountries)) }}.
                            @else
                                Select all that call to you — or describe something else.
                            @endif
                        </p>
                        @if($experienceOptions->isEmpty() && $selectedCountries !== [])
                            <p class="mt-6 text-sm text-muted">No listed experiences match those countries yet — describe what you want below, or go back and adjust.</p>
                        @endif
                        <div class="journey-quiz__tiles mt-6 md:mt-8">
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
                        <h2 class="font-display text-2xl sm:text-3xl text-forest">How long will you travel?</h2>
                        <p class="mt-2 text-muted text-sm sm:text-base max-w-2xl">Lengths available for your choices so far — pick a pace, or enter a custom length.</p>
                        <div class="journey-quiz__option-cards mt-6 md:mt-8">
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
                        <h2 class="font-display text-2xl sm:text-3xl text-forest">When would you like to travel?</h2>
                        <p class="mt-2 text-muted text-sm sm:text-base max-w-2xl">Select one or more periods — or leave dates open and we will advise.</p>
                        <div class="journey-quiz__option-cards mt-6 md:mt-8">
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
                        <h2 class="font-display text-2xl sm:text-3xl text-forest">Stay style & travellers</h2>
                        <p class="mt-2 text-muted text-sm sm:text-base max-w-2xl">Stay styles available for your route so far — and who is coming with you.</p>
                        <div class="journey-quiz__stay-tiles mt-6 md:mt-8">
                            @foreach($stayOptions as $value => $option)
                                <button
                                    type="button"
                                    wire:click="selectStay('{{ $value }}')"
                                    @disabled(! $option['available'])
                                    aria-pressed="{{ $stayStyle === $value ? 'true' : 'false' }}"
                                    @class([
                                        'journey-quiz__stay-tile',
                                        'is-selected' => $stayStyle === $value,
                                        'is-disabled' => ! $option['available'],
                                    ])
                                >
                                    @if(! empty($option['image']))
                                        <img src="{{ $option['image'] }}" alt="" loading="lazy" decoding="async">
                                    @else
                                        <span class="journey-quiz__stay-tile-fallback" aria-hidden="true"></span>
                                    @endif
                                    <span class="journey-quiz__stay-tile-shade" aria-hidden="true"></span>
                                    <span class="journey-quiz__stay-tile-check" aria-hidden="true">✓</span>
                                    <span class="journey-quiz__stay-tile-title">{{ $option['label'] }}</span>
                                    <span class="journey-quiz__stay-tile-copy">
                                        {{ $option['description'] }}
                                        @unless($option['available'])
                                            <span class="journey-quiz__stay-tile-meta">Not offered on matching journeys</span>
                                        @endunless
                                    </span>
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
                        <h2 class="font-display text-2xl sm:text-3xl text-forest">
                            @if($journeys->isNotEmpty())
                                Journeys shaped around you
                            @else
                                Let us craft this for you
                            @endif
                        </h2>
                        <p class="mt-2 text-muted text-sm sm:text-base max-w-2xl">
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

                        <div class="journey-quiz__request-band mt-10 md:mt-12">
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
                        <h2 class="font-display text-2xl sm:text-3xl text-forest">How can we reach you?</h2>
                        <p class="mt-2 text-muted text-sm sm:text-base max-w-2xl">Your answers will be included so we can respond with something personal.</p>

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
                            <div class="journey-quiz__nav">
                                <button type="button" wire:click="$set('step', 7)" class="journey-quiz__back">Back</button>
                                <button type="submit" class="btn-primary" wire:loading.attr="disabled">Send request</button>
                            </div>
                        </form>
                    </div>
                @endif

                @if($step === 9)
                    <div class="journey-quiz__step journey-quiz__step--done" wire:key="step-9">
                        <x-path-accent class="text-forest/40 mb-5 md:mb-6" />
                        <h2 class="font-display text-3xl md:text-4xl text-forest">We have your path</h2>
                        <p class="mt-3 max-w-xl text-muted leading-relaxed text-sm sm:text-base">
                            Thank you. Your request is with our planners now — we will be in touch shortly to shape the journey with you.
                        </p>
                        <div class="mt-8 flex flex-wrap gap-4">
                            <button type="button" wire:click="restart" class="btn-primary">Start again</button>
                            @auth
                                <a href="{{ route('account.requests') }}" class="btn-outline-dark">View My requests</a>
                            @else
                                <a href="{{ route('journeys.index') }}" class="btn-outline-dark">Browse all journeys</a>
                            @endauth
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>

    @if($step < 8)
        <x-page-cta
            class="journey-quiz__talk-cta"
            heading="Prefer to talk it through?"
            text="Prefer to tell us directly — we will shape something around you."
            button="Plan your journey"
            :href="route('plan')"
        />
    @endif
</div>

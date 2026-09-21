<?php

namespace App\Livewire;

use App\Mail\EnquiryReceived;
use App\Models\Country;
use App\Models\Enquiry;
use App\Models\Experience;
use App\Models\Journey;
use App\Services\SettingService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class JourneyFinder extends Component
{
    public int $step = 1;

    /** @var array<int, string> */
    public array $selectedCountries = [];

    public bool $countryOtherMode = false;

    public string $countryOther = '';

    /** @var array<int, string> */
    public array $experiences = [];

    public bool $experienceOtherMode = false;

    public string $experienceOther = '';

    public string $duration = '';

    public string $durationCustom = '';

    public string $travelDateFrom = '';

    public string $travelDateTo = '';

    /** @var array<int, string> */
    public array $travelSeasons = [];

    public string $stayStyle = '';

    public string $travellers = '2';

    public string $name = '';

    public string $email = '';

    public string $phone = '';

    public string $whatsapp = '';

    public string $message = '';

    public bool $submitted = false;

    public const TOTAL_STEPS = 7;

    public function mount(): void
    {
        $this->prefillFromAuth();
    }

    public function start(): void
    {
        $this->step = 2;
    }

    public function next(): void
    {
        $this->validateStep();

        if ($this->step < self::TOTAL_STEPS) {
            $this->step++;
        }

        if ($this->step === 7 && $this->message === '') {
            $this->message = $this->defaultMessage();
        }

        if ($this->step === 8) {
            $this->prefillFromAuth();
        }
    }

    public function back(): void
    {
        if ($this->step > 1) {
            $this->step--;
        }
    }

    public function goToRequest(): void
    {
        if ($this->message === '') {
            $this->message = $this->defaultMessage();
        }
        $this->prefillFromAuth();
        $this->step = 8;
    }

    public function toggleCountry(string $slug): void
    {
        if (in_array($slug, $this->selectedCountries, true)) {
            $this->selectedCountries = array_values(array_filter(
                $this->selectedCountries,
                fn ($item) => $item !== $slug
            ));
        } else {
            $this->selectedCountries[] = $slug;
        }

        $this->pruneDownstreamSelections();
    }

    public function toggleCountryOther(): void
    {
        $this->countryOtherMode = ! $this->countryOtherMode;
        if (! $this->countryOtherMode) {
            $this->countryOther = '';
        }

        $this->pruneDownstreamSelections();
    }

    public function toggleExperience(string $slug): void
    {
        if (in_array($slug, $this->experiences, true)) {
            $this->experiences = array_values(array_filter(
                $this->experiences,
                fn ($item) => $item !== $slug
            ));
        } else {
            $this->experiences[] = $slug;
        }

        $this->pruneDownstreamSelections(fromExperiences: true);
    }

    public function toggleExperienceOther(): void
    {
        $this->experienceOtherMode = ! $this->experienceOtherMode;
        if (! $this->experienceOtherMode) {
            $this->experienceOther = '';
        }

        $this->pruneDownstreamSelections(fromExperiences: true);
    }

    public function selectDuration(string $value): void
    {
        $this->duration = $this->duration === $value ? '' : $value;
        if ($this->duration !== 'custom') {
            $this->durationCustom = '';
        }

        $this->pruneDownstreamSelections(fromDuration: true);
    }

    public function selectStay(string $value): void
    {
        $this->stayStyle = $this->stayStyle === $value ? '' : $value;
    }

    public function toggleSeason(string $value): void
    {
        if (in_array($value, $this->travelSeasons, true)) {
            $this->travelSeasons = array_values(array_filter(
                $this->travelSeasons,
                fn ($item) => $item !== $value
            ));
        } else {
            $this->travelSeasons[] = $value;
        }
    }

    public function submitRequest(SettingService $settings): void
    {
        $this->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:40'],
            'whatsapp' => ['nullable', 'string', 'max:40'],
            'message' => ['required', 'string', 'max:5000'],
        ]);

        $enquiry = Enquiry::query()->create([
            'user_id' => Enquiry::matchingUserId($this->email),
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone ?: null,
            'whatsapp' => $this->whatsapp ?: null,
            'message' => nl2br(e($this->message)),
            'preferred_destinations' => $this->resolvedDestinations() ?: null,
            'days' => $this->resolvedDays(),
            'travellers' => $this->travellers ?: null,
            'preferred_experiences' => $this->resolvedExperiences() ?: null,
            'accommodation' => $this->resolvedAccommodation(),
            'travel_dates' => $this->resolvedTravelDates(),
            'preferences' => $this->quizSummaryText(),
            'status' => 'new',
            'source' => 'journey_finder',
            'channel' => Enquiry::CHANNEL_FINDER,
        ]);

        $to = $settings->get('admin_email') ?: config('mail.from.address');

        if ($to) {
            Mail::to($to)->send(new EnquiryReceived($enquiry));
        }

        $this->submitted = true;
        $this->step = 9;
    }

    public function restart(): void
    {
        $this->reset([
            'step', 'selectedCountries', 'countryOtherMode', 'countryOther',
            'experiences', 'experienceOtherMode', 'experienceOther',
            'duration', 'durationCustom', 'travelDateFrom', 'travelDateTo', 'travelSeasons',
            'stayStyle', 'travellers', 'name', 'email', 'phone', 'whatsapp', 'message', 'submitted',
        ]);
        $this->step = 1;
        $this->travellers = '2';
        $this->prefillFromAuth();
    }

    protected function prefillFromAuth(): void
    {
        $user = Auth::user();
        if (! $user) {
            return;
        }

        if ($this->name === '') {
            $this->name = $user->name;
        }
        if ($this->email === '') {
            $this->email = $user->email;
        }
    }

    protected function validateStep(): void
    {
        match ($this->step) {
            2 => $this->validate([
                'countryOther' => [$this->countryOtherMode ? 'required' : 'nullable', 'string', 'max:120'],
            ], [
                'countryOther.required' => 'Please tell us where you want to go.',
            ]),
            3 => $this->validate([
                'experienceOther' => [$this->experienceOtherMode ? 'required' : 'nullable', 'string', 'max:120'],
            ], [
                'experienceOther.required' => 'Please describe what you want to experience.',
            ]),
            4 => $this->validate([
                'durationCustom' => [$this->duration === 'custom' ? 'required' : 'nullable', 'string', 'max:40'],
            ], [
                'durationCustom.required' => 'Please enter how long you want to travel.',
            ]),
            5 => $this->validate([
                'travelDateFrom' => ['nullable', 'date'],
                'travelDateTo' => ['nullable', 'date', 'after_or_equal:travelDateFrom'],
            ]),
            6 => $this->validate([
                'travellers' => ['required', 'string', 'max:40'],
            ]),
            default => null,
        };
    }

    protected function matchedJourneys(): Collection
    {
        if ($this->countryOtherMode && filled($this->countryOther) && $this->selectedCountries === []) {
            return collect();
        }

        return $this->journeyQuery(
            withCountries: true,
            withExperiences: true,
            withDuration: true,
            withStay: true,
        )->with(['countries', 'experiences', 'stays'])->get();
    }

    /**
     * Published journeys narrowed by answers so far.
     * Used both for final matches and to build the next step's option list.
     */
    protected function journeyQuery(
        bool $withCountries = true,
        bool $withExperiences = true,
        bool $withDuration = true,
        bool $withStay = false,
    ): \Illuminate\Database\Eloquent\Builder {
        return Journey::query()
            ->published()
            ->when(
                $withCountries && $this->selectedCountries !== [],
                fn ($q) => $q->whereHas('countries', fn ($c) => $c->whereIn('slug', $this->selectedCountries))
            )
            ->when(
                $withExperiences && $this->experiences !== [],
                fn ($q) => $q->whereHas('experiences', fn ($e) => $e->whereIn('slug', $this->experiences))
            )
            ->when($withDuration && $this->duration === 'short', fn ($q) => $q->where('days', '<=', 5))
            ->when($withDuration && $this->duration === 'medium', fn ($q) => $q->whereBetween('days', [6, 9]))
            ->when($withDuration && $this->duration === 'long', fn ($q) => $q->where('days', '>=', 10))
            ->when($withDuration && $this->duration === 'custom', fn ($q) => $q->whereRaw('0 = 1'))
            ->when(
                $withStay && $this->stayStyle !== '',
                fn ($q) => $q->whereHas('stays', fn ($s) => $s->where('style', $this->stayStyle))
            )
            ->orderBy('sort_order');
    }

    protected function availableExperienceOptions(): Collection
    {
        $query = Experience::query()->published()->orderBy('sort_order');

        if ($this->selectedCountries !== []) {
            // Scope by destination geography — not journeys — so multi-country
            // itineraries do not surface gorilla trekking under Kenya, etc.
            $query->whereHas('destinations', function ($q) {
                $q->whereHas('country', fn ($c) => $c->whereIn('slug', $this->selectedCountries));
            });
        }

        return $query->get();
    }

    /**
     * @return array<string, array{label: string, description: string, count: int, available: bool}>
     */
    protected function durationOptions(): array
    {
        $base = $this->journeyQuery(withCountries: true, withExperiences: true, withDuration: false, withStay: false);
        $days = (clone $base)->pluck('days')->filter();

        $short = $days->filter(fn ($d) => (int) $d <= 5)->count();
        $medium = $days->filter(fn ($d) => (int) $d >= 6 && (int) $d <= 9)->count();
        $long = $days->filter(fn ($d) => (int) $d >= 10)->count();

        return [
            'short' => [
                'label' => '1–5 days',
                'description' => 'A focused escape — one or two parks, a clear rhythm, little transit.',
                'count' => $short,
                'available' => $short > 0,
            ],
            'medium' => [
                'label' => '6–9 days',
                'description' => 'Room to combine destinations without rushing — our most popular pace.',
                'count' => $medium,
                'available' => $medium > 0,
            ],
            'long' => [
                'label' => '10+ days',
                'description' => 'A fuller circuit — multiple countries or a deeper, unhurried safari.',
                'count' => $long,
                'available' => $long > 0,
            ],
            'custom' => [
                'label' => 'Custom length',
                'description' => 'Tell us the exact number of days and we will shape the route around it.',
                'count' => 0,
                'available' => true,
            ],
        ];
    }

    /**
     * @return array<string, array{label: string, description: string, available: bool}>
     */
    protected function stayOptions(): array
    {
        $all = [
            'Comfortable' => [
                'label' => 'Essential',
                'description' => 'Comfortable, well-located camps and lodges; solid guiding, honest comfort.',
            ],
            'Luxury' => [
                'label' => 'Premium',
                'description' => 'Elevated lodges and camps; stronger design, dining, and guiding polish.',
            ],
            'Ultra-luxury' => [
                'label' => 'Signature',
                'description' => 'Ultra-private, high-touch stays; the most exclusive settings we place guests in.',
            ],
        ];

        $styles = $this->journeyQuery(withCountries: true, withExperiences: true, withDuration: true, withStay: false)
            ->with('stays')
            ->get()
            ->flatMap(fn ($journey) => $journey->stays->pluck('style'))
            ->unique()
            ->filter()
            ->values()
            ->all();

        $restrict = $this->selectedCountries !== [] || $this->experiences !== [] || in_array($this->duration, ['short', 'medium', 'long'], true);

        foreach ($all as $key => &$meta) {
            $meta['available'] = ! $restrict || in_array($key, $styles, true);
        }
        unset($meta);

        return $all;
    }

    protected function pruneDownstreamSelections(bool $fromExperiences = false, bool $fromDuration = false): void
    {
        if (! $fromExperiences && ! $fromDuration) {
            $allowed = $this->availableExperienceOptions()->pluck('slug')->all();
            $this->experiences = array_values(array_filter(
                $this->experiences,
                fn ($slug) => in_array($slug, $allowed, true)
            ));
        }

        if (! $fromDuration) {
            $durationMeta = $this->durationOptions();
            if ($this->duration !== '' && $this->duration !== 'custom' && empty($durationMeta[$this->duration]['available'])) {
                $this->duration = '';
                $this->durationCustom = '';
            }
        }

        $stayMeta = $this->stayOptions();
        if ($this->stayStyle !== '' && empty($stayMeta[$this->stayStyle]['available'])) {
            $this->stayStyle = '';
        }
    }

    public function needsCustomRequest(): bool
    {
        return ($this->countryOtherMode && filled($this->countryOther))
            || $this->experienceOtherMode
            || $this->duration === 'custom'
            || $this->matchedJourneys()->isEmpty();
    }

    protected function resolvedDestinations(): array
    {
        $items = Country::query()
            ->whereIn('slug', $this->selectedCountries)
            ->orderBy('sort_order')
            ->pluck('name')
            ->all();

        if ($this->countryOtherMode && filled($this->countryOther)) {
            $items[] = 'Other: '.trim($this->countryOther);
        }

        return $items;
    }

    protected function resolvedExperiences(): array
    {
        $items = Experience::query()
            ->whereIn('slug', $this->experiences)
            ->orderBy('sort_order')
            ->pluck('name')
            ->all();

        if ($this->experienceOtherMode && filled($this->experienceOther)) {
            $items[] = 'Other: '.trim($this->experienceOther);
        }

        return $items;
    }

    protected function resolvedDays(): ?string
    {
        return match ($this->duration) {
            'short' => '1–5 days',
            'medium' => '6–9 days',
            'long' => '10+ days',
            'custom' => filled($this->durationCustom) ? trim($this->durationCustom) : 'Custom',
            default => null,
        };
    }

    protected function resolvedAccommodation(): ?string
    {
        return match ($this->stayStyle) {
            'Comfortable' => 'Essential',
            'Luxury' => 'Premium',
            'Ultra-luxury' => 'Signature',
            default => $this->stayStyle ?: null,
        };
    }

    protected function resolvedTravelDates(): ?string
    {
        $parts = [];

        if ($this->travelSeasons !== []) {
            $parts[] = implode(', ', $this->travelSeasons);
        }

        if ($this->travelDateFrom && $this->travelDateTo) {
            $parts[] = $this->travelDateFrom.' to '.$this->travelDateTo;
        } elseif ($this->travelDateFrom) {
            $parts[] = 'From '.$this->travelDateFrom;
        } elseif ($this->travelDateTo) {
            $parts[] = 'Until '.$this->travelDateTo;
        }

        return $parts !== [] ? implode(' · ', $parts) : null;
    }

    protected function quizSummaryText(): string
    {
        $lines = [
            'Journey Finder quiz summary',
            'Countries: '.implode(', ', $this->resolvedDestinations() ?: ['Any']),
            'Experiences: '.implode(', ', $this->resolvedExperiences() ?: ['Any']),
            'Duration: '.($this->resolvedDays() ?: 'Any'),
            'Dates: '.($this->resolvedTravelDates() ?: 'Flexible'),
            'Stay: '.($this->resolvedAccommodation() ?: 'Any'),
            'Travellers: '.($this->travellers ?: '—'),
        ];

        return implode("\n", $lines);
    }

    protected function defaultMessage(): string
    {
        $where = implode(', ', $this->resolvedDestinations() ?: ['East Africa']);
        $what = implode(', ', $this->resolvedExperiences() ?: ['a private safari']);
        $days = $this->resolvedDays() ?: 'a flexible length';

        return "I would like help planning a journey to {$where}, focused on {$what}, for {$days}.";
    }

    public function render()
    {
        $journeys = $this->step >= 7 ? $this->matchedJourneys() : collect();
        $experienceOptions = $this->availableExperienceOptions();
        $durationOptions = $this->durationOptions();
        $stayOptions = $this->stayOptions();

        return view('livewire.journey-finder', [
            'journeys' => $journeys,
            'countryOptions' => Country::query()->published()->orderBy('sort_order')->get(),
            'experienceOptions' => $experienceOptions,
            'durationOptions' => $durationOptions,
            'stayOptions' => $stayOptions,
            'needsCustom' => $this->step >= 7 ? $this->needsCustomRequest() : false,
        ]);
    }
}

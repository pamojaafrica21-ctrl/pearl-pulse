<?php

namespace App\Livewire;

use App\Mail\EnquiryReceived;
use App\Models\Country;
use App\Models\Enquiry;
use App\Models\Experience;
use App\Models\Journey;
use App\Services\SettingService;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class EnquiryForm extends Component
{
    public ?int $destinationId = null;

    public ?string $journeySlug = null;

    public string $name = '';

    public string $email = '';

    public string $phone = '';

    public string $whatsapp = '';

    public string $message = '';

    public array $preferredDestinations = [];

    public string $destinationOther = '';

    public string $days = '';

    public string $travellers = '';

    public array $preferredExperiences = [];

    public string $experienceOther = '';

    public string $accommodation = '';

    public string $accommodationOther = '';

    public string $investment = '';

    public string $investmentOther = '';

    public string $travelDateFrom = '';

    public string $travelDateTo = '';

    public string $preferences = '';

    public bool $submitted = false;

    public function mount(?int $destinationId = null, ?string $journeySlug = null): void
    {
        $this->destinationId = $destinationId;
        $this->journeySlug = $journeySlug;
    }

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:40'],
            'whatsapp' => ['nullable', 'string', 'max:40'],
            'message' => ['required', 'string', 'max:20000'],
            'preferredDestinations' => ['array'],
            'destinationOther' => ['nullable', 'string', 'max:120'],
            'days' => ['nullable', 'string', 'max:40'],
            'travellers' => ['nullable', 'string', 'max:40'],
            'preferredExperiences' => ['array'],
            'experienceOther' => ['nullable', 'string', 'max:120'],
            'accommodation' => ['nullable', 'string', 'max:80'],
            'accommodationOther' => ['nullable', 'string', 'max:120'],
            'investment' => ['nullable', 'string', 'max:80'],
            'investmentOther' => ['nullable', 'string', 'max:120'],
            'travelDateFrom' => ['nullable', 'date'],
            'travelDateTo' => ['nullable', 'date', 'after_or_equal:travelDateFrom'],
            'preferences' => ['nullable', 'string', 'max:20000'],
        ];
    }

    public function submit(SettingService $settings): void
    {
        $this->validate();

        $journey = $this->journeySlug
            ? Journey::query()->where('slug', $this->journeySlug)->first()
            : null;

        $enquiry = Enquiry::query()->create([
            'destination_id' => $this->destinationId,
            'journey_id' => $journey?->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone ?: null,
            'whatsapp' => $this->whatsapp ?: null,
            'message' => $this->message,
            'preferred_destinations' => $this->resolvedDestinations() ?: null,
            'days' => $this->days ?: null,
            'travellers' => $this->travellers ?: null,
            'preferred_experiences' => $this->resolvedExperiences() ?: null,
            'accommodation' => $this->resolvedChoice($this->accommodation, $this->accommodationOther),
            'investment' => $this->resolvedChoice($this->investment, $this->investmentOther),
            'travel_dates' => $this->resolvedTravelDates(),
            'preferences' => $this->preferences ?: null,
            'status' => 'new',
        ]);

        $enquiry->load(['destination', 'journey']);

        $to = $settings->get('admin_email') ?: config('mail.from.address');

        if ($to) {
            Mail::to($to)->send(new EnquiryReceived($enquiry));
        }

        $this->reset([
            'name', 'email', 'phone', 'whatsapp', 'message',
            'preferredDestinations', 'destinationOther', 'days', 'travellers',
            'preferredExperiences', 'experienceOther',
            'accommodation', 'accommodationOther', 'investment', 'investmentOther',
            'travelDateFrom', 'travelDateTo', 'preferences',
        ]);
        $this->submitted = true;
        $this->dispatch('enquiry-form-submitted');
    }

    protected function resolvedDestinations(): array
    {
        return $this->resolveListWithOther($this->preferredDestinations, $this->destinationOther);
    }

    protected function resolvedExperiences(): array
    {
        return $this->resolveListWithOther($this->preferredExperiences, $this->experienceOther);
    }

    protected function resolveListWithOther(array $items, string $otherText): array
    {
        $items = array_values(array_filter($items));

        if (! in_array('Other', $items, true)) {
            return $items;
        }

        $items = array_values(array_filter($items, fn ($item) => $item !== 'Other'));
        $label = trim($otherText) !== '' ? 'Other: '.trim($otherText) : 'Other';
        $items[] = $label;

        return $items;
    }

    protected function resolvedChoice(string $value, string $otherText): ?string
    {
        if ($value === '') {
            return null;
        }

        if ($value === 'Other') {
            return trim($otherText) !== '' ? 'Other: '.trim($otherText) : 'Other';
        }

        return $value;
    }

    protected function resolvedTravelDates(): ?string
    {
        if ($this->travelDateFrom && $this->travelDateTo) {
            return $this->travelDateFrom.' to '.$this->travelDateTo;
        }

        if ($this->travelDateFrom) {
            return 'From '.$this->travelDateFrom;
        }

        if ($this->travelDateTo) {
            return 'Until '.$this->travelDateTo;
        }

        return null;
    }

    public function render()
    {
        return view('livewire.enquiry-form', [
            'countries' => Country::query()->published()->orderBy('sort_order')->get(),
            'experiences' => Experience::query()->published()->orderBy('sort_order')->get(),
            'journey' => $this->journeySlug
                ? Journey::query()->where('slug', $this->journeySlug)->first()
                : null,
        ]);
    }
}

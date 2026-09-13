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

    public string $days = '';

    public string $travellers = '';

    public array $preferredExperiences = [];

    public string $accommodation = '';

    public string $investment = '';

    public string $travelDates = '';

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
            'message' => ['required', 'string', 'max:5000'],
            'preferredDestinations' => ['array'],
            'days' => ['nullable', 'string', 'max:40'],
            'travellers' => ['nullable', 'string', 'max:40'],
            'preferredExperiences' => ['array'],
            'accommodation' => ['nullable', 'string', 'max:80'],
            'investment' => ['nullable', 'string', 'max:80'],
            'travelDates' => ['nullable', 'string', 'max:120'],
            'preferences' => ['nullable', 'string', 'max:2000'],
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
            'preferred_destinations' => $this->preferredDestinations ?: null,
            'days' => $this->days ?: null,
            'travellers' => $this->travellers ?: null,
            'preferred_experiences' => $this->preferredExperiences ?: null,
            'accommodation' => $this->accommodation ?: null,
            'investment' => $this->investment ?: null,
            'travel_dates' => $this->travelDates ?: null,
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
            'preferredDestinations', 'days', 'travellers', 'preferredExperiences',
            'accommodation', 'investment', 'travelDates', 'preferences',
        ]);
        $this->submitted = true;
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

<?php

namespace App\Livewire;

use App\Models\Journey;
use App\Models\Review;
use Livewire\Component;

class ReviewForm extends Component
{
    public int $journeyId;

    public string $guest_name = '';

    public string $guest_country = '';

    public string $quote = '';

    public string $website = ''; // honeypot

    public bool $submitted = false;

    public function mount(Journey $journey): void
    {
        $this->journeyId = $journey->id;
    }

    protected function rules(): array
    {
        return [
            'guest_name' => ['required', 'string', 'max:120'],
            'guest_country' => ['nullable', 'string', 'max:120'],
            'quote' => ['required', 'string', 'min:20', 'max:2000'],
            'website' => ['max:0'],
        ];
    }

    protected function messages(): array
    {
        return [
            'quote.min' => 'Please share a little more about your experience.',
            'website.max' => 'Unable to submit this review.',
        ];
    }

    public function submit(): void
    {
        $this->validate();

        Review::query()->create([
            'guest_name' => trim($this->guest_name),
            'guest_country' => trim($this->guest_country) !== '' ? trim($this->guest_country) : null,
            'quote' => trim($this->quote),
            'journey_id' => $this->journeyId,
            'status' => 'draft',
            'sort_order' => 0,
        ]);

        $this->reset(['guest_name', 'guest_country', 'quote', 'website']);
        $this->submitted = true;
    }

    public function render()
    {
        return view('livewire.review-form');
    }
}

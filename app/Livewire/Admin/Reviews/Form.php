<?php

namespace App\Livewire\Admin\Reviews;

use App\Models\Journey;
use App\Models\Review;
use Livewire\Component;

class Form extends Component
{
    public ?Review $review = null;

    public string $guest_name = '';

    public string $guest_country = '';

    public string $quote = '';

    public ?int $journey_id = null;

    public string $status = 'published';

    public int $sort_order = 0;

    public function mount(?Review $review = null): void
    {
        if ($review?->exists) {
            $this->review = $review;
            $this->guest_name = (string) $review->guest_name;
            $this->guest_country = (string) $review->guest_country;
            $this->quote = (string) $review->quote;
            $this->journey_id = $review->journey_id;
            $this->status = (string) $review->status;
            $this->sort_order = (int) $review->sort_order;
        }
    }

    public function save()
    {
        $this->validate(['guest_name' => ['required'], 'quote' => ['required']]);

        $data = [
            'guest_name' => $this->guest_name,
            'guest_country' => $this->guest_country ?: null,
            'quote' => $this->quote,
            'journey_id' => $this->journey_id,
            'status' => $this->status,
            'sort_order' => $this->sort_order,
        ];

        $this->review = $this->review?->exists ? tap($this->review)->update($data) : Review::query()->create($data);

        session()->flash('status', 'Review saved.');

        return $this->redirect(route('admin.reviews.edit', $this->review), navigate: true);
    }

    public function render()
    {
        return view('livewire.admin.reviews.form', [
            'journeys' => Journey::query()->orderBy('name')->get(),
        ])->layout('layouts.admin', ['heading' => $this->review?->exists ? 'Edit review' : 'New review']);
    }
}

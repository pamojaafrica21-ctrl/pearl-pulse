<?php

namespace App\Livewire\Admin\Enquiries;

use App\Models\Enquiry;
use App\Models\Journey;
use App\Models\User;
use Livewire\Component;

class Create extends Component
{
    public string $name = '';

    public string $email = '';

    public string $phone = '';

    public string $whatsapp = '';

    public string $channel = Enquiry::CHANNEL_WHATSAPP;

    public string $status = Enquiry::STATUS_IN_PROGRESS;

    public ?int $journey_id = null;

    public ?int $assigned_user_id = null;

    public string $travellers = '';

    public string $days = '';

    public string $travel_dates = '';

    public string $investment = '';

    public string $message = '';

    public function save()
    {
        $this->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:40'],
            'whatsapp' => ['nullable', 'string', 'max:40'],
            'channel' => ['required', 'in:'.implode(',', Enquiry::CHANNELS)],
            'status' => ['required', 'in:'.implode(',', Enquiry::STATUSES)],
            'journey_id' => ['nullable', 'exists:journeys,id'],
            'assigned_user_id' => ['nullable', 'exists:users,id'],
            'travellers' => ['nullable', 'string', 'max:40'],
            'days' => ['nullable', 'string', 'max:40'],
            'travel_dates' => ['nullable', 'string', 'max:120'],
            'investment' => ['nullable', 'string', 'max:80'],
            'message' => ['nullable', 'string', 'max:20000'],
        ]);

        $enquiry = Enquiry::query()->create([
            'user_id' => Enquiry::matchingUserId($this->email),
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone ?: null,
            'whatsapp' => $this->whatsapp ?: null,
            'channel' => $this->channel,
            'source' => 'admin',
            'status' => $this->status,
            'journey_id' => $this->journey_id,
            'assigned_user_id' => $this->assigned_user_id ?: auth()->id(),
            'travellers' => $this->travellers ?: null,
            'days' => $this->days ?: null,
            'travel_dates' => $this->travel_dates ?: null,
            'investment' => $this->investment ?: null,
            'message' => $this->message ?: 'Logged by admin.',
        ]);

        session()->flash('status', 'Enquiry logged.');

        return $this->redirect(route('admin.enquiries.index', ['open' => $enquiry->id]), navigate: true);
    }

    public function render()
    {
        return view('livewire.admin.enquiries.create', [
            'journeys' => Journey::query()->orderBy('name')->get(),
            'admins' => User::query()->where('role', 'admin')->orderBy('name')->get(),
        ])->layout('layouts.admin', ['heading' => 'Log enquiry']);
    }
}

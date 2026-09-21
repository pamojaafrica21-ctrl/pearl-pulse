<?php

namespace App\Livewire\Admin\Bookings;

use App\Models\Booking;
use App\Models\Enquiry;
use App\Models\Journey;
use App\Models\User;
use Livewire\Component;

class Form extends Component
{
    public ?Booking $booking = null;

    public string $name = '';

    public string $email = '';

    public string $phone = '';

    public string $whatsapp = '';

    public string $channel = Enquiry::CHANNEL_WHATSAPP;

    public string $status = Booking::STATUS_CONFIRMED;

    public ?int $journey_id = null;

    public ?int $assigned_user_id = null;

    public string $travellers = '';

    public string $start_date = '';

    public string $end_date = '';

    public string $investment = '';

    public string $summary = '';

    public string $note = '';

    public function mount(?Booking $booking = null): void
    {
        if ($booking?->exists) {
            $this->booking = $booking;
            $this->name = $booking->name;
            $this->email = $booking->email;
            $this->phone = (string) $booking->phone;
            $this->whatsapp = (string) $booking->whatsapp;
            $this->channel = $booking->channel ?: Enquiry::CHANNEL_WHATSAPP;
            $this->status = $booking->status;
            $this->journey_id = $booking->journey_id;
            $this->assigned_user_id = $booking->assigned_user_id;
            $this->travellers = (string) $booking->travellers;
            $this->start_date = optional($booking->start_date)->format('Y-m-d') ?: '';
            $this->end_date = optional($booking->end_date)->format('Y-m-d') ?: '';
            $this->investment = (string) $booking->investment;
            $this->summary = (string) $booking->summary;
        }
    }

    public function save()
    {
        $this->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:40'],
            'whatsapp' => ['nullable', 'string', 'max:40'],
            'channel' => ['required', 'in:'.implode(',', Enquiry::CHANNELS)],
            'status' => ['required', 'in:'.implode(',', Booking::STATUSES)],
            'journey_id' => ['nullable', 'exists:journeys,id'],
            'assigned_user_id' => ['nullable', 'exists:users,id'],
            'travellers' => ['nullable', 'string', 'max:40'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'investment' => ['nullable', 'string', 'max:80'],
            'summary' => ['nullable', 'string', 'max:20000'],
        ]);

        $travelDates = null;
        if ($this->start_date && $this->end_date) {
            $travelDates = $this->start_date.' to '.$this->end_date;
        } elseif ($this->start_date) {
            $travelDates = 'From '.$this->start_date;
        }

        $data = [
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone ?: null,
            'whatsapp' => $this->whatsapp ?: null,
            'channel' => $this->channel,
            'status' => $this->status,
            'journey_id' => $this->journey_id,
            'assigned_user_id' => $this->assigned_user_id ?: auth()->id(),
            'travellers' => $this->travellers ?: null,
            'start_date' => $this->start_date ?: null,
            'end_date' => $this->end_date ?: null,
            'travel_dates' => $travelDates,
            'investment' => $this->investment ?: null,
            'summary' => $this->summary ?: null,
        ];

        $guestUserId = Enquiry::matchingUserId($this->email);

        if ($this->booking?->exists) {
            if ($guestUserId) {
                $data['user_id'] = $guestUserId;
            }
            $this->booking->update($data);
        } else {
            $enquiry = Enquiry::query()->create([
                'user_id' => $guestUserId,
                'name' => $this->name,
                'email' => $this->email,
                'phone' => $this->phone ?: null,
                'whatsapp' => $this->whatsapp ?: null,
                'channel' => $this->channel,
                'source' => 'admin',
                'status' => Enquiry::STATUS_WON,
                'journey_id' => $this->journey_id,
                'assigned_user_id' => $data['assigned_user_id'],
                'travellers' => $this->travellers ?: null,
                'travel_dates' => $travelDates,
                'investment' => $this->investment ?: null,
                'message' => $this->summary ?: 'Offline booking logged by admin.',
            ]);

            $this->booking = Booking::query()->create($data + [
                'reference' => Booking::nextReference(),
                'enquiry_id' => $enquiry->id,
                'user_id' => $guestUserId,
                'created_by' => auth()->id(),
            ]);
        }

        session()->flash('status', 'Booking '.$this->booking->reference.' saved.');

        return $this->redirect(route('admin.bookings.edit', $this->booking), navigate: true);
    }

    public function setStatus(string $status): void
    {
        if (! $this->booking?->exists || ! in_array($status, Booking::STATUSES, true)) {
            return;
        }

        $this->booking->update(['status' => $status]);
        $this->status = $status;
    }

    public function addNote(): void
    {
        $this->validate([
            'note' => ['required', 'string', 'max:5000'],
        ]);

        if (! $this->booking?->exists) {
            return;
        }

        $this->booking->notes()->create([
            'user_id' => auth()->id(),
            'body' => $this->note,
        ]);

        $this->note = '';
        $this->booking->load('notes.user');
    }

    public function render()
    {
        if ($this->booking?->exists) {
            $this->booking->loadMissing(['enquiry', 'journey', 'assignee']);
        }

        return view('livewire.admin.bookings.form', [
            'journeys' => Journey::query()->orderBy('name')->get(),
            'admins' => User::query()->where('role', 'admin')->orderBy('name')->get(),
            'notes' => $this->booking?->exists
                ? $this->booking->notes()->with('user')->get()
                : collect(),
        ])->layout('layouts.admin', [
            'heading' => $this->booking?->exists
                ? $this->booking->name
                : 'Log booking',
        ]);
    }
}

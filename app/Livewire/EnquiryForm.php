<?php

namespace App\Livewire;

use App\Mail\EnquiryReceived;
use App\Models\Enquiry;
use App\Services\SettingService;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class EnquiryForm extends Component
{
    public ?int $destinationId = null;

    public string $name = '';

    public string $email = '';

    public string $phone = '';

    public string $message = '';

    public bool $submitted = false;

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:40'],
            'message' => ['required', 'string', 'max:5000'],
        ];
    }

    public function submit(SettingService $settings): void
    {
        $this->validate();

        $enquiry = Enquiry::query()->create([
            'destination_id' => $this->destinationId,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone ?: null,
            'message' => $this->message,
            'status' => 'new',
        ]);

        $enquiry->load('destination');

        $to = $settings->get('admin_email') ?: config('mail.from.address');

        if ($to) {
            Mail::to($to)->send(new EnquiryReceived($enquiry));
        }

        $this->reset(['name', 'email', 'phone', 'message']);
        $this->submitted = true;
    }

    public function render()
    {
        return view('livewire.enquiry-form');
    }
}

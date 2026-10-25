<?php

namespace App\Livewire;

use App\Models\NewsletterSubscriber;
use Livewire\Component;

class NewsletterSignup extends Component
{
    public string $email = '';

    public bool $submitted = false;

    public function subscribe(): void
    {
        $this->validate([
            'email' => ['required', 'email', 'max:255'],
        ]);

        NewsletterSubscriber::subscribe($this->email);

        $this->email = '';
        $this->submitted = true;
    }

    public function render()
    {
        return view('livewire.newsletter-signup');
    }
}

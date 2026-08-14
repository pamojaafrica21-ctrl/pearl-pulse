<?php

use Illuminate\Support\Facades\Password;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public string $email = '';

    public function sendPasswordResetLink(): void
    {
        $this->validate([
            'email' => ['required', 'string', 'email'],
        ]);

        $status = Password::sendResetLink(
            $this->only('email')
        );

        if ($status != Password::RESET_LINK_SENT) {
            $this->addError('email', __($status));

            return;
        }

        $this->reset('email');

        session()->flash('status', __($status));
    }
}; ?>

<div>
    <div class="mb-10">
        <p class="text-xs tracking-[0.22em] uppercase text-gold mb-3">Account</p>
        <h1 class="font-display text-4xl text-forest">Reset password</h1>
        <p class="mt-2 text-sm text-muted">Enter your email and we’ll send a reset link.</p>
    </div>

    <x-auth-session-status class="mb-4 text-sm text-forest" :status="session('status')" />

    <form wire:submit="sendPasswordResetLink" class="space-y-5">
        <div>
            <label for="email" class="block text-xs tracking-[0.14em] uppercase text-muted mb-2">Email</label>
            <input
                wire:model="email"
                id="email"
                type="email"
                name="email"
                required
                autofocus
                class="w-full border-sand-deep/50 bg-white text-charcoal focus:border-forest focus:ring-forest"
            >
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <button type="submit" class="btn-primary w-full text-xs">
            Email reset link
        </button>
    </form>

    <p class="mt-10 text-center text-xs text-muted">
        <a href="{{ route('login') }}" class="hover:text-forest" wire:navigate>← Back to login</a>
    </p>
</div>

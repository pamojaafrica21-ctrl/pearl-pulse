<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public LoginForm $form;

    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        $this->redirectIntended(default: route('admin.dashboard', absolute: false), navigate: true);
    }
}; ?>

<div>
    <div class="mb-10">
        <p class="text-xs tracking-[0.22em] uppercase text-gold mb-3">Admin access</p>
        <h1 class="font-display text-4xl text-forest">Welcome back</h1>
        <p class="mt-2 text-sm text-muted">Sign in to manage destinations, enquiries, and site content.</p>
    </div>

    <x-auth-session-status class="mb-4 text-sm text-forest" :status="session('status')" />

    <form wire:submit="login" class="space-y-5">
        <div>
            <label for="email" class="block text-xs tracking-[0.14em] uppercase text-muted mb-2">Email</label>
            <input
                wire:model="form.email"
                id="email"
                type="email"
                name="email"
                required
                autofocus
                autocomplete="username"
                class="w-full border-sand-deep/50 bg-white text-charcoal focus:border-forest focus:ring-forest"
            >
            <x-input-error :messages="$errors->get('form.email')" class="mt-2" />
        </div>

        <div>
            <label for="password" class="block text-xs tracking-[0.14em] uppercase text-muted mb-2">Password</label>
            <input
                wire:model="form.password"
                id="password"
                type="password"
                name="password"
                required
                autocomplete="current-password"
                class="w-full border-sand-deep/50 bg-white text-charcoal focus:border-forest focus:ring-forest"
            >
            <x-input-error :messages="$errors->get('form.password')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between gap-4">
            <label for="remember" class="inline-flex items-center gap-2 text-sm text-muted">
                <input
                    wire:model="form.remember"
                    id="remember"
                    type="checkbox"
                    class="rounded border-sand-deep text-forest focus:ring-forest"
                    name="remember"
                >
                Remember me
            </label>

            @if (Route::has('password.request'))
                <a class="text-sm text-forest hover:text-forest-light underline-offset-2 hover:underline" href="{{ route('password.request') }}" wire:navigate>
                    Forgot password?
                </a>
            @endif
        </div>

        <button type="submit" class="btn-primary w-full text-xs mt-2">
            Log in
        </button>
    </form>

    <p class="mt-10 text-center text-xs text-muted">
        <a href="{{ route('home') }}" class="hover:text-forest">← Back to website</a>
    </p>
</div>

<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public string $name = '';

    public string $email = '';

    public string $password = '';

    public string $password_confirmation = '';

    public function register(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'customer',
        ]);

        event(new Registered($user));

        Auth::login($user);

        $this->redirect(route('account.favorites', absolute: false), navigate: true);
    }
}; ?>

<div>
    <div class="mb-10">
        <p class="text-xs tracking-[0.22em] uppercase text-muted mb-3">Guest account</p>
        <h1 class="font-display text-4xl text-charcoal">Create an account</h1>
        <p class="mt-2 text-sm text-muted">Save journeys you love, then plan a private proposal when you are ready.</p>
    </div>

    <form wire:submit="register" class="space-y-5">
        <div>
            <label for="name" class="block text-xs tracking-[0.14em] uppercase text-muted mb-2">Name</label>
            <input wire:model="name" id="name" type="text" required autofocus autocomplete="name" class="w-full border-charcoal/15 bg-white text-charcoal focus:border-forest focus:ring-forest">
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <div>
            <label for="email" class="block text-xs tracking-[0.14em] uppercase text-muted mb-2">Email</label>
            <input wire:model="email" id="email" type="email" required autocomplete="username" class="w-full border-charcoal/15 bg-white text-charcoal focus:border-forest focus:ring-forest">
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div>
            <label for="password" class="block text-xs tracking-[0.14em] uppercase text-muted mb-2">Password</label>
            <input wire:model="password" id="password" type="password" required autocomplete="new-password" class="w-full border-charcoal/15 bg-white text-charcoal focus:border-forest focus:ring-forest">
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div>
            <label for="password_confirmation" class="block text-xs tracking-[0.14em] uppercase text-muted mb-2">Confirm password</label>
            <input wire:model="password_confirmation" id="password_confirmation" type="password" required autocomplete="new-password" class="w-full border-charcoal/15 bg-white text-charcoal focus:border-forest focus:ring-forest">
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between gap-4 pt-2">
            <a class="text-sm text-forest hover:opacity-70" href="{{ route('login') }}" wire:navigate>Already registered?</a>
            <button type="submit" class="btn-primary text-xs">Register</button>
        </div>
    </form>
</div>

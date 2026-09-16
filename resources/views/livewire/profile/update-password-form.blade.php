<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Livewire\Volt\Component;

new class extends Component
{
    public string $current_password = '';

    public string $password = '';

    public string $password_confirmation = '';

    public function updatePassword(): void
    {
        try {
            $validated = $this->validate([
                'current_password' => ['required', 'string', 'current_password'],
                'password' => ['required', 'string', Password::defaults(), 'confirmed'],
            ]);
        } catch (ValidationException $e) {
            $this->reset('current_password', 'password', 'password_confirmation');

            throw $e;
        }

        Auth::user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        $this->reset('current_password', 'password', 'password_confirmation');

        $this->dispatch('password-updated');
    }
}; ?>

<section>
    <header>
        <p class="text-[11px] tracking-[0.18em] uppercase text-muted">Security</p>
        <h2 class="font-display text-2xl text-charcoal mt-2">Update password</h2>
        <p class="mt-2 text-sm text-muted leading-relaxed">
            Choose a strong password you do not use elsewhere.
        </p>
    </header>

    <form wire:submit="updatePassword" class="mt-8 space-y-5">
        <div>
            <label for="update_password_current_password" class="block text-xs tracking-[0.14em] uppercase text-muted mb-2">Current password</label>
            <input wire:model="current_password" id="update_password_current_password" name="current_password" type="password" autocomplete="current-password" class="w-full border-charcoal/15 bg-white text-charcoal focus:border-forest focus:ring-forest">
            <x-input-error :messages="$errors->get('current_password')" class="mt-2" />
        </div>

        <div>
            <label for="update_password_password" class="block text-xs tracking-[0.14em] uppercase text-muted mb-2">New password</label>
            <input wire:model="password" id="update_password_password" name="password" type="password" autocomplete="new-password" class="w-full border-charcoal/15 bg-white text-charcoal focus:border-forest focus:ring-forest">
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div>
            <label for="update_password_password_confirmation" class="block text-xs tracking-[0.14em] uppercase text-muted mb-2">Confirm password</label>
            <input wire:model="password_confirmation" id="update_password_password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" class="w-full border-charcoal/15 bg-white text-charcoal focus:border-forest focus:ring-forest">
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center gap-4 pt-1">
            <button type="submit" class="btn-primary text-xs">Update password</button>
            <x-action-message class="text-sm text-forest" on="password-updated">
                Saved.
            </x-action-message>
        </div>
    </form>
</section>

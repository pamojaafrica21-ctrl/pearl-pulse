<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Livewire\Volt\Component;

new class extends Component
{
    public string $name = '';

    public string $email = '';

    public function mount(): void
    {
        $this->name = Auth::user()->name;
        $this->email = Auth::user()->email;
    }

    public function updateProfileInformation(): void
    {
        $user = Auth::user();

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($user->id)],
        ]);

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        $this->dispatch('profile-updated', name: $user->name);
    }

    public function sendVerification(): void
    {
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('account.favorites', absolute: false));

            return;
        }

        $user->sendEmailVerificationNotification();

        Session::flash('status', 'verification-link-sent');
    }
}; ?>

<section>
    <header>
        <p class="text-[11px] tracking-[0.18em] uppercase text-muted">Details</p>
        <h2 class="font-display text-2xl text-charcoal mt-2">Profile information</h2>
        <p class="mt-2 text-sm text-muted leading-relaxed">
            Update the name and email we use when we reply about your journeys.
        </p>
    </header>

    <form wire:submit="updateProfileInformation" class="mt-8 space-y-5">
        <div>
            <label for="name" class="block text-xs tracking-[0.14em] uppercase text-muted mb-2">Name</label>
            <input wire:model="name" id="name" name="name" type="text" required autofocus autocomplete="name" class="w-full border-charcoal/15 bg-white text-charcoal focus:border-forest focus:ring-forest">
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <label for="email" class="block text-xs tracking-[0.14em] uppercase text-muted mb-2">Email</label>
            <input wire:model="email" id="email" name="email" type="email" required autocomplete="username" class="w-full border-charcoal/15 bg-white text-charcoal focus:border-forest focus:ring-forest">
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if (auth()->user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! auth()->user()->hasVerifiedEmail())
                <div class="mt-3 text-sm text-muted">
                    <p>Your email address is unverified.</p>
                    <button type="button" wire:click.prevent="sendVerification" class="mt-1 text-forest hover:opacity-70 underline">
                        Re-send the verification email
                    </button>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-forest">
                            A new verification link has been sent.
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4 pt-1">
            <button type="submit" class="btn-primary text-xs">Save changes</button>
            <x-action-message class="text-sm text-forest" on="profile-updated">
                Saved.
            </x-action-message>
        </div>
    </form>
</section>

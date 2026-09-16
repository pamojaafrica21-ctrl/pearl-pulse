<?php

use App\Livewire\Actions\Logout;
use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Component;

new class extends Component
{
    public string $password = '';

    public function deleteUser(Logout $logout): void
    {
        $this->validate([
            'password' => ['required', 'string', 'current_password'],
        ]);

        tap(Auth::user(), $logout(...))->delete();

        $this->redirect('/', navigate: true);
    }
}; ?>

<section>
    <header>
        <p class="text-[11px] tracking-[0.18em] uppercase text-muted">Danger zone</p>
        <h2 class="font-display text-2xl text-charcoal mt-2">Delete account</h2>
        <p class="mt-2 text-sm text-muted leading-relaxed">
            This permanently removes your account and saved journeys. This cannot be undone.
        </p>
    </header>

    <div class="mt-8">
        <button
            type="button"
            class="inline-flex items-center justify-center px-7 py-3 text-sm tracking-[0.12em] uppercase border border-red-700 text-red-700 transition hover:bg-red-700 hover:text-white"
            x-data=""
            x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        >
            Delete account
        </button>
    </div>

    <x-modal name="confirm-user-deletion" :show="$errors->isNotEmpty()" focusable>
        <form wire:submit="deleteUser" class="p-6 sm:p-8">
            <p class="text-[11px] tracking-[0.18em] uppercase text-muted">Confirm</p>
            <h2 class="font-display text-2xl text-charcoal mt-2">
                Delete your account?
            </h2>

            <p class="mt-3 text-sm text-muted leading-relaxed">
                Enter your password to permanently delete your Pearl Pulse account and saved journeys.
            </p>

            <div class="mt-6">
                <label for="password" class="block text-xs tracking-[0.14em] uppercase text-muted mb-2">Password</label>
                <input
                    wire:model="password"
                    id="password"
                    name="password"
                    type="password"
                    class="w-full border-charcoal/15 bg-white text-charcoal focus:border-forest focus:ring-forest"
                    placeholder="Your password"
                >
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <div class="mt-8 flex flex-wrap justify-end gap-3">
                <button type="button" class="btn-outline-dark !py-2.5" x-on:click="$dispatch('close')">
                    Cancel
                </button>
                <button type="submit" class="inline-flex items-center justify-center px-7 py-2.5 text-sm tracking-[0.12em] uppercase bg-red-700 text-white transition hover:bg-red-800">
                    Delete account
                </button>
            </div>
        </form>
    </x-modal>
</section>

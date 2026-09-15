<?php

namespace App\Livewire;

use App\Models\Journey;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class FavoriteButton extends Component
{
    public Journey $journey;

    public bool $saved = false;

    public function mount(Journey $journey): void
    {
        $this->journey = $journey;
        $this->saved = Auth::check() && Auth::user()->hasFavorited($journey);
    }

    public function toggle(): void
    {
        if (! Auth::check()) {
            session()->put('url.intended', route('journeys.show', $this->journey));
            $this->redirect(route('login'));

            return;
        }

        $user = Auth::user();

        if ($user->isAdmin()) {
            return;
        }

        if ($this->saved) {
            $user->favorites()->where('journey_id', $this->journey->id)->delete();
            $this->saved = false;
        } else {
            $user->favorites()->firstOrCreate(['journey_id' => $this->journey->id]);
            $this->saved = true;
        }
    }

    public function render()
    {
        return view('livewire.favorite-button');
    }
}

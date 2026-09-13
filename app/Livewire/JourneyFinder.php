<?php

namespace App\Livewire;

use App\Models\Country;
use App\Models\Experience;
use App\Models\Journey;
use Livewire\Attributes\Url;
use Livewire\Component;

class JourneyFinder extends Component
{
    #[Url(except: '')]
    public string $country = '';

    #[Url(except: '')]
    public string $experience = '';

    #[Url(except: '')]
    public string $duration = '';

    #[Url(except: '', as: 'style')]
    public string $stayStyle = '';

    public function clearFilters(): void
    {
        $this->reset(['country', 'experience', 'duration', 'stayStyle']);
    }

    public function render()
    {
        $journeys = Journey::query()
            ->published()
            ->with(['countries', 'experiences', 'stays'])
            ->when($this->country !== '', fn ($q) => $q->whereHas('countries', fn ($c) => $c->where('slug', $this->country)))
            ->when($this->experience !== '', fn ($q) => $q->whereHas('experiences', fn ($e) => $e->where('slug', $this->experience)))
            ->when($this->duration === 'short', fn ($q) => $q->where('days', '<=', 5))
            ->when($this->duration === 'medium', fn ($q) => $q->whereBetween('days', [6, 9]))
            ->when($this->duration === 'long', fn ($q) => $q->where('days', '>=', 10))
            ->when($this->stayStyle !== '', fn ($q) => $q->whereHas('stays', fn ($s) => $s->where('style', $this->stayStyle)))
            ->orderBy('sort_order')
            ->get();

        return view('livewire.journey-finder', [
            'journeys' => $journeys,
            'countries' => Country::query()->published()->orderBy('sort_order')->get(),
            'experiences' => Experience::query()->published()->orderBy('sort_order')->get(),
        ]);
    }
}

<?php

namespace App\Livewire;

use App\Models\Country;
use App\Models\Experience;
use App\Models\Journey;
use Livewire\Attributes\Url;
use Livewire\Component;

class JourneySearch extends Component
{
    public bool $open = false;

    #[Url(as: 'q', except: '')]
    public string $query = '';

    public function openSearch(): void
    {
        $this->open = true;
    }

    public function closeSearch(): void
    {
        $this->open = false;
        $this->query = '';
    }

    public function render()
    {
        $q = trim($this->query);
        $journeys = collect();
        $countries = collect();
        $experiences = collect();

        if (strlen($q) >= 2) {
            $journeys = Journey::query()
                ->published()
                ->with('countries')
                ->where(function ($builder) use ($q) {
                    $builder->where('name', 'like', "%{$q}%")
                        ->orWhere('teaser', 'like', "%{$q}%")
                        ->orWhere('subtitle', 'like', "%{$q}%");
                })
                ->orderBy('sort_order')
                ->take(6)
                ->get();

            $countries = Country::query()
                ->published()
                ->where(function ($builder) use ($q) {
                    $builder->where('name', 'like', "%{$q}%")
                        ->orWhere('subtitle', 'like', "%{$q}%")
                        ->orWhere('teaser', 'like', "%{$q}%");
                })
                ->orderBy('sort_order')
                ->take(4)
                ->get();

            $experiences = Experience::query()
                ->published()
                ->where(function ($builder) use ($q) {
                    $builder->where('name', 'like', "%{$q}%")
                        ->orWhere('teaser', 'like', "%{$q}%");
                })
                ->orderBy('sort_order')
                ->take(4)
                ->get();
        }

        return view('livewire.journey-search', [
            'journeys' => $journeys,
            'countries' => $countries,
            'experiences' => $experiences,
            'hasQuery' => strlen($q) >= 2,
        ]);
    }
}

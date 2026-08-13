<?php

namespace App\Livewire;

use App\Models\Destination;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class DestinationsIndex extends Component
{
    use WithPagination;

    #[Url(as: 'country', except: '')]
    public string $country = '';

    #[Url(as: 'q', except: '')]
    public string $search = '';

    public function updatingCountry(): void
    {
        $this->resetPage();
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function clearFilters(): void
    {
        $this->country = '';
        $this->search = '';
        $this->resetPage();
    }

    public function render()
    {
        $countries = Destination::query()
            ->published()
            ->select('country')
            ->distinct()
            ->orderBy('country')
            ->pluck('country');

        $destinations = Destination::query()
            ->published()
            ->when($this->country !== '', fn ($q) => $q->where('country', $this->country))
            ->when($this->search !== '', function ($q) {
                $term = '%'.$this->search.'%';
                $q->where(function ($inner) use ($term) {
                    $inner->where('name', 'like', $term)
                        ->orWhere('teaser', 'like', $term)
                        ->orWhere('region', 'like', $term)
                        ->orWhere('country', 'like', $term);
                });
            })
            ->orderBy('sort_order')
            ->orderBy('name')
            ->paginate(12);

        return view('livewire.destinations-index', [
            'destinations' => $destinations,
            'countries' => $countries,
        ]);
    }
}

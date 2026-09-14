<?php

namespace App\Livewire\Admin\Destinations;

use App\Models\Destination;
use App\Services\ImageUploader;
use App\Services\VideoUploader;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    #[Url(except: '')]
    public string $search = '';

    #[Url(except: 'name')]
    public string $sort = 'name';

    public ?int $confirmingDelete = null;

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function confirmDelete(int $id): void
    {
        $this->confirmingDelete = $id;
    }

    public function cancelDelete(): void
    {
        $this->confirmingDelete = null;
    }

    public function delete(ImageUploader $uploader, VideoUploader $videos): void
    {
        $destination = Destination::query()->with('images')->findOrFail($this->confirmingDelete);

        $uploader->delete($destination->cover_path);
        $videos->delete($destination->video_path);
        foreach ($destination->images as $image) {
            $uploader->delete($image->path);
        }

        $destination->delete();
        $this->confirmingDelete = null;
        session()->flash('status', 'Destination deleted.');
    }

    public function render()
    {
        $query = Destination::query()->with('country');

        if ($this->search !== '') {
            $term = '%'.$this->search.'%';
            $query->where(function ($inner) use ($term) {
                $inner->where('name', 'like', $term)
                    ->orWhere('slug', 'like', $term)
                    ->orWhere('region', 'like', $term)
                    ->orWhereHas('country', fn ($c) => $c->where('name', 'like', $term));
            });
        }

        if ($this->sort === 'country') {
            $query->leftJoin('countries', 'destinations.country_id', '=', 'countries.id')
                ->orderBy('countries.name')
                ->select('destinations.*');
        } elseif (in_array($this->sort, ['name', 'status', 'updated_at'], true)) {
            $query->orderBy($this->sort);
        } else {
            $query->orderBy('name');
        }

        return view('livewire.admin.destinations.index', [
            'destinations' => $query->paginate(10),
            'uploader' => app(ImageUploader::class),
        ])->layout('layouts.admin', ['heading' => 'Destinations']);
    }
}

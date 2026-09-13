<?php

namespace App\Livewire\Admin\Destinations;

use App\Models\Destination;
use App\Services\ImageUploader;
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

    public function delete(ImageUploader $uploader): void
    {
        $destination = Destination::query()->with('images')->findOrFail($this->confirmingDelete);

        $uploader->delete($destination->cover_path);
        foreach ($destination->images as $image) {
            $uploader->delete($image->path);
        }

        $destination->delete();
        $this->confirmingDelete = null;
        session()->flash('status', 'Destination deleted.');
    }

    public function render()
    {
        $sort = in_array($this->sort, ['name', 'status', 'updated_at'], true)
            ? $this->sort
            : 'name';

        $destinations = Destination::query()
            ->with('country')
            ->when($this->search !== '', function ($q) {
                $term = '%'.$this->search.'%';
                $q->where(function ($inner) use ($term) {
                    $inner->where('name', 'like', $term)
                        ->orWhere('slug', 'like', $term)
                        ->orWhereHas('country', fn ($c) => $c->where('name', 'like', $term));
                });
            })
            ->orderBy($sort)
            ->paginate(10);

        return view('livewire.admin.destinations.index', [
            'destinations' => $destinations,
            'uploader' => app(ImageUploader::class),
        ])->layout('layouts.admin', ['heading' => 'Destinations']);
    }
}

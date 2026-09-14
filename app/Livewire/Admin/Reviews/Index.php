<?php

namespace App\Livewire\Admin\Reviews;

use App\Models\Review;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    #[Url(except: 'all')]
    public string $filter = 'all';

    public ?int $confirmingDelete = null;

    public function updatingFilter(): void
    {
        $this->resetPage();
    }

    public function publish(int $id): void
    {
        Review::query()->findOrFail($id)->update(['status' => 'published']);
        session()->flash('status', 'Review published — it now appears on the website.');
    }

    public function unpublish(int $id): void
    {
        Review::query()->findOrFail($id)->update(['status' => 'draft']);
        session()->flash('status', 'Review hidden from the website.');
    }

    public function confirmDelete(int $id): void
    {
        $this->confirmingDelete = $id;
    }

    public function cancelDelete(): void
    {
        $this->confirmingDelete = null;
    }

    public function delete(): void
    {
        Review::query()->findOrFail($this->confirmingDelete)->delete();
        $this->confirmingDelete = null;
        session()->flash('status', 'Review deleted.');
    }

    public function render()
    {
        $query = Review::query()->with('journey')->orderByDesc('created_at');

        if ($this->filter === 'pending') {
            $query->where('status', 'draft');
        } elseif ($this->filter === 'published') {
            $query->where('status', 'published');
        }

        return view('livewire.admin.reviews.index', [
            'reviews' => $query->paginate(20),
            'pendingCount' => Review::query()->where('status', 'draft')->count(),
        ])->layout('layouts.admin', ['heading' => 'Reviews']);
    }
}

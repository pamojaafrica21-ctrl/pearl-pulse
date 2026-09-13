<?php

namespace App\Livewire\Admin\Reviews;

use App\Models\Review;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public ?int $confirmingDelete = null;

    public function delete(): void
    {
        Review::query()->findOrFail($this->confirmingDelete)->delete();
        $this->confirmingDelete = null;
        session()->flash('status', 'Review deleted.');
    }

    public function render()
    {
        return view('livewire.admin.simple-index', [
            'rows' => Review::query()->latest()->paginate(20),
            'createRoute' => route('admin.reviews.create'),
            'editRoute' => 'admin.reviews.edit',
            'label' => 'review',
            'columns' => ['guest_name', 'guest_country', 'status'],
        ])->layout('layouts.admin', ['heading' => 'Reviews']);
    }
}

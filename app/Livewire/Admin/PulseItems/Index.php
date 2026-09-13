<?php

namespace App\Livewire\Admin\PulseItems;

use App\Livewire\Admin\Support\DeletesCover;
use App\Models\PulseItem;
use App\Services\ImageUploader;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use DeletesCover, WithPagination;

    public ?int $confirmingDelete = null;

    public function delete(ImageUploader $uploader): void
    {
        $this->deleteRecord(PulseItem::query()->findOrFail($this->confirmingDelete), $uploader);
        $this->confirmingDelete = null;
        session()->flash('status', 'Pulse item deleted.');
    }

    public function render()
    {
        return view('livewire.admin.simple-index', [
            'rows' => PulseItem::query()->orderBy('sort_order')->paginate(20),
            'createRoute' => route('admin.pulse.create'),
            'editRoute' => 'admin.pulse.edit',
            'label' => 'True Pulse item',
            'columns' => ['title', 'type', 'approved', 'status'],
        ])->layout('layouts.admin', ['heading' => 'True Pulse']);
    }
}

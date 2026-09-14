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
        session()->flash('status', 'True Pulse item deleted.');
    }

    public function render()
    {
        return view('livewire.admin.simple-index', [
            'rows' => PulseItem::query()->orderBy('sort_order')->paginate(20),
            'createRoute' => route('admin.pulse.create'),
            'editRoute' => 'admin.pulse.edit',
            'label' => 'True Pulse item',
            'columns' => [
                ['key' => 'cover_path', 'label' => 'Cover', 'type' => 'cover'],
                ['key' => 'title', 'label' => 'Title', 'type' => 'primary'],
                ['key' => 'type', 'label' => 'Type'],
                ['key' => 'guest_name', 'label' => 'Guest'],
                ['key' => 'video', 'label' => 'Video', 'type' => 'video'],
                ['key' => 'approved', 'label' => 'Approved', 'type' => 'bool'],
                ['key' => 'status', 'label' => 'Status', 'type' => 'status'],
                ['key' => 'updated_at', 'label' => 'Updated', 'type' => 'date'],
            ],
        ])->layout('layouts.admin', ['heading' => 'True Pulse']);
    }
}

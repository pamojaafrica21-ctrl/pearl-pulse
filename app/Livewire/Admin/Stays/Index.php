<?php

namespace App\Livewire\Admin\Stays;

use App\Livewire\Admin\Support\DeletesCover;
use App\Models\Stay;
use App\Services\ImageUploader;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use DeletesCover, WithPagination;

    public ?int $confirmingDelete = null;

    public function delete(ImageUploader $uploader): void
    {
        $this->deleteRecord(Stay::query()->findOrFail($this->confirmingDelete), $uploader);
        $this->confirmingDelete = null;
        session()->flash('status', 'Stay deleted.');
    }

    public function render()
    {
        return view('livewire.admin.simple-index', [
            'rows' => Stay::query()->with('destination')->orderBy('sort_order')->paginate(20),
            'createRoute' => route('admin.stays.create'),
            'editRoute' => 'admin.stays.edit',
            'label' => 'selected stay',
            'columns' => [
                ['key' => 'cover_path', 'label' => 'Cover', 'type' => 'cover'],
                ['key' => 'name', 'label' => 'Name', 'type' => 'primary', 'meta' => 'slug'],
                ['key' => 'location', 'label' => 'Location'],
                ['key' => 'destination.name', 'label' => 'Destination', 'type' => 'relation', 'relation' => 'destination.name'],
                ['key' => 'style', 'label' => 'Style'],
                ['key' => 'video', 'label' => 'Video', 'type' => 'video'],
                ['key' => 'status', 'label' => 'Status', 'type' => 'status', 'badges' => [
                    ['key' => 'is_featured', 'label' => 'Featured'],
                ]],
                ['key' => 'updated_at', 'label' => 'Updated', 'type' => 'date'],
            ],
        ])->layout('layouts.admin', ['heading' => 'Selected stays']);
    }
}

<?php

namespace App\Livewire\Admin\Specialists;

use App\Livewire\Admin\Support\DeletesCover;
use App\Models\Specialist;
use App\Services\ImageUploader;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use DeletesCover, WithPagination;

    public ?int $confirmingDelete = null;

    public function delete(ImageUploader $uploader): void
    {
        $this->deleteRecord(Specialist::query()->findOrFail($this->confirmingDelete), $uploader);
        $this->confirmingDelete = null;
        session()->flash('status', 'Specialist journey deleted.');
    }

    public function render()
    {
        return view('livewire.admin.simple-index', [
            'rows' => Specialist::query()->orderBy('sort_order')->paginate(20),
            'createRoute' => route('admin.specialists.create'),
            'editRoute' => 'admin.specialists.edit',
            'label' => 'specialist journey',
            'columns' => [
                ['key' => 'cover_path', 'label' => 'Cover', 'type' => 'cover'],
                ['key' => 'name', 'label' => 'Name', 'type' => 'primary', 'meta' => 'slug'],
                ['key' => 'subtitle', 'label' => 'Eyebrow'],
                ['key' => 'status', 'label' => 'Status', 'type' => 'status'],
                ['key' => 'updated_at', 'label' => 'Updated', 'type' => 'date'],
            ],
        ])->layout('layouts.admin', ['heading' => 'Specialist journeys']);
    }
}

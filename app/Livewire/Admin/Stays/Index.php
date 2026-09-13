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
            'rows' => Stay::query()->orderBy('sort_order')->paginate(20),
            'createRoute' => route('admin.stays.create'),
            'editRoute' => 'admin.stays.edit',
            'label' => 'selected stay',
            'columns' => ['name', 'location', 'status'],
        ])->layout('layouts.admin', ['heading' => 'Selected stays']);
    }
}

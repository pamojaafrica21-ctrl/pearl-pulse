<?php

namespace App\Livewire\Admin\Journeys;

use App\Livewire\Admin\Support\DeletesCover;
use App\Models\Journey;
use App\Services\ImageUploader;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use DeletesCover, WithPagination;

    public ?int $confirmingDelete = null;

    public function delete(ImageUploader $uploader): void
    {
        $this->deleteRecord(Journey::query()->findOrFail($this->confirmingDelete), $uploader);
        $this->confirmingDelete = null;
        session()->flash('status', 'Journey deleted.');
    }

    public function render()
    {
        return view('livewire.admin.simple-index', [
            'rows' => Journey::query()->orderBy('sort_order')->paginate(20),
            'createRoute' => route('admin.journeys.create'),
            'editRoute' => 'admin.journeys.edit',
            'label' => 'journey',
            'columns' => ['name', 'days', 'price_mode', 'status'],
        ])->layout('layouts.admin', ['heading' => 'Journeys']);
    }
}

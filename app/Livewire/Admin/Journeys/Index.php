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
            'columns' => [
                ['key' => 'cover_path', 'label' => 'Cover', 'type' => 'cover'],
                ['key' => 'name', 'label' => 'Name', 'type' => 'primary', 'meta' => 'slug'],
                ['key' => 'duration_label', 'label' => 'Duration'],
                ['key' => 'days', 'label' => 'Days'],
                ['key' => 'price_from', 'label' => 'From'],
                ['key' => 'price_mode', 'label' => 'Pricing'],
                ['key' => 'video', 'label' => 'Video', 'type' => 'video'],
                ['key' => 'status', 'label' => 'Status', 'type' => 'status', 'badges' => [
                    ['key' => 'is_signature', 'label' => 'Signature'],
                    ['key' => 'is_featured', 'label' => 'Featured'],
                    ['key' => 'is_multi_country', 'label' => 'Multi'],
                ]],
                ['key' => 'updated_at', 'label' => 'Updated', 'type' => 'date'],
            ],
        ])->layout('layouts.admin', ['heading' => 'Journeys']);
    }
}

<?php

namespace App\Livewire\Admin\Experiences;

use App\Livewire\Admin\Support\DeletesCover;
use App\Models\Experience;
use App\Services\ImageUploader;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use DeletesCover, WithPagination;

    public ?int $confirmingDelete = null;

    public function delete(ImageUploader $uploader): void
    {
        $this->deleteRecord(Experience::query()->findOrFail($this->confirmingDelete), $uploader);
        $this->confirmingDelete = null;
        session()->flash('status', 'Experience deleted.');
    }

    public function render()
    {
        return view('livewire.admin.simple-index', [
            'rows' => Experience::query()->orderBy('sort_order')->paginate(20),
            'createRoute' => route('admin.experiences.create'),
            'editRoute' => 'admin.experiences.edit',
            'label' => 'experience',
            'columns' => ['name', 'slug', 'status'],
        ])->layout('layouts.admin', ['heading' => 'Experiences']);
    }
}

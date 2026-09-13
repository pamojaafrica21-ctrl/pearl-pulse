<?php

namespace App\Livewire\Admin\Countries;

use App\Livewire\Admin\Support\DeletesCover;
use App\Models\Country;
use App\Services\ImageUploader;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use DeletesCover, WithPagination;

    public ?int $confirmingDelete = null;

    public function delete(ImageUploader $uploader): void
    {
        $this->deleteRecord(Country::query()->findOrFail($this->confirmingDelete), $uploader);
        $this->confirmingDelete = null;
        session()->flash('status', 'Country deleted.');
    }

    public function render()
    {
        return view('livewire.admin.simple-index', [
            'rows' => Country::query()->orderBy('sort_order')->paginate(20),
            'createRoute' => route('admin.countries.create'),
            'editRoute' => 'admin.countries.edit',
            'label' => 'country',
            'columns' => ['name', 'slug', 'status'],
        ])->layout('layouts.admin', ['heading' => 'Countries']);
    }
}

<?php

namespace App\Livewire\Admin\Pages;

use App\Models\Page;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public ?int $confirmingDelete = null;

    public function delete(): void
    {
        Page::query()->findOrFail($this->confirmingDelete)->delete();
        $this->confirmingDelete = null;
        session()->flash('status', 'Page deleted.');
    }

    public function render()
    {
        return view('livewire.admin.simple-index', [
            'rows' => Page::query()->orderBy('title')->paginate(20),
            'createRoute' => route('admin.pages.create'),
            'editRoute' => 'admin.pages.edit',
            'label' => 'page',
            'columns' => [
                ['key' => 'title', 'label' => 'Title', 'type' => 'primary', 'meta' => 'slug'],
                ['key' => 'status', 'label' => 'Status', 'type' => 'status'],
                ['key' => 'updated_at', 'label' => 'Updated', 'type' => 'date'],
            ],
        ])->layout('layouts.admin', ['heading' => 'Pages']);
    }
}

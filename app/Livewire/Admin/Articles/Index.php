<?php

namespace App\Livewire\Admin\Articles;

use App\Livewire\Admin\Support\DeletesCover;
use App\Models\Article;
use App\Services\ImageUploader;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use DeletesCover, WithPagination;

    public ?int $confirmingDelete = null;

    public function delete(ImageUploader $uploader): void
    {
        $this->deleteRecord(Article::query()->findOrFail($this->confirmingDelete), $uploader);
        $this->confirmingDelete = null;
        session()->flash('status', 'Article deleted.');
    }

    public function render()
    {
        return view('livewire.admin.simple-index', [
            'rows' => Article::query()->orderBy('sort_order')->paginate(20),
            'createRoute' => route('admin.articles.create'),
            'editRoute' => 'admin.articles.edit',
            'label' => 'article',
            'columns' => ['title', 'type', 'status'],
        ])->layout('layouts.admin', ['heading' => 'Insiders']);
    }
}

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
            'rows' => Article::query()->orderByDesc('updated_at')->paginate(20),
            'createRoute' => route('admin.articles.create'),
            'editRoute' => 'admin.articles.edit',
            'label' => 'article',
            'columns' => [
                ['key' => 'cover_path', 'label' => 'Cover', 'type' => 'cover'],
                ['key' => 'title', 'label' => 'Title', 'type' => 'primary', 'meta' => 'slug'],
                ['key' => 'type', 'label' => 'Type'],
                ['key' => 'excerpt', 'label' => 'Excerpt'],
                ['key' => 'status', 'label' => 'Status', 'type' => 'status', 'badges' => [
                    ['key' => 'is_featured', 'label' => 'Featured'],
                ]],
                ['key' => 'updated_at', 'label' => 'Updated', 'type' => 'date'],
            ],
        ])->layout('layouts.admin', ['heading' => 'Insiders']);
    }
}

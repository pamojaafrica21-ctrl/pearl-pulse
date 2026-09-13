<?php

namespace App\Livewire\Admin\Articles;

use App\Models\Article;
use App\Services\ImageUploader;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;

class Form extends Component
{
    use WithFileUploads;

    public ?Article $article = null;

    public string $title = '';

    public string $slug = '';

    public string $type = 'guide';

    public string $excerpt = '';

    public string $body = '';

    public string $status = 'draft';

    public bool $is_featured = false;

    public int $sort_order = 0;

    public $cover;

    public function mount(?Article $article = null): void
    {
        if ($article?->exists) {
            $this->article = $article;
            $this->title = (string) $article->title;
            $this->slug = (string) $article->slug;
            $this->type = (string) $article->type;
            $this->excerpt = (string) $article->excerpt;
            $this->body = (string) $article->body;
            $this->status = (string) $article->status;
            $this->is_featured = (bool) $article->is_featured;
            $this->sort_order = (int) $article->sort_order;
        }
    }

    public function updatedTitle(string $value): void
    {
        if (! $this->article) {
            $this->slug = Str::slug($value);
        }
    }

    public function save(ImageUploader $uploader)
    {
        $this->validate([
            'title' => ['required'],
            'slug' => ['required', Rule::unique('articles', 'slug')->ignore($this->article?->id)],
            'type' => ['required', Rule::in(Article::TYPES)],
            'cover' => ['nullable', 'image', 'max:5120'],
        ]);

        $data = [
            'title' => $this->title,
            'slug' => $this->slug,
            'type' => $this->type,
            'excerpt' => $this->excerpt ?: null,
            'body' => $this->body ?: null,
            'status' => $this->status,
            'is_featured' => $this->is_featured,
            'sort_order' => $this->sort_order,
            'meta_title' => $this->title.' | Insiders',
            'meta_description' => $this->excerpt ?: null,
            'published_at' => $this->status === 'published' ? now() : null,
        ];

        $this->article = $this->article?->exists ? tap($this->article)->update($data) : Article::query()->create($data);

        if ($this->cover) {
            $uploader->delete($this->article->cover_path);
            $this->article->update(['cover_path' => $uploader->store($this->cover, 'articles/'.$this->article->id)]);
        }

        session()->flash('status', 'Article saved.');

        return $this->redirect(route('admin.articles.edit', $this->article), navigate: true);
    }

    public function render()
    {
        return view('livewire.admin.articles.form')
            ->layout('layouts.admin', ['heading' => $this->article?->exists ? 'Edit article' : 'New article']);
    }
}

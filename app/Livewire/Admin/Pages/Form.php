<?php

namespace App\Livewire\Admin\Pages;

use App\Models\Page;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Component;

class Form extends Component
{
    public ?Page $page = null;

    public string $title = '';

    public string $slug = '';

    public string $content = '';

    public string $status = 'published';

    public function mount(?Page $page = null): void
    {
        if ($page?->exists) {
            $this->page = $page;
            $this->title = (string) $page->title;
            $this->slug = (string) $page->slug;
            $this->content = (string) $page->content;
            $this->status = (string) $page->status;
        }
    }

    public function updatedTitle(string $value): void
    {
        if (! $this->page) {
            $this->slug = Str::slug($value);
        }
    }

    public function save()
    {
        $this->validate([
            'title' => ['required'],
            'slug' => ['required', Rule::unique('pages', 'slug')->ignore($this->page?->id)],
        ]);

        $data = [
            'title' => $this->title,
            'slug' => $this->slug,
            'content' => $this->content ?: null,
            'status' => $this->status,
            'meta_title' => $this->title.' | Pearl Pulse Safaris',
        ];

        $this->page = $this->page?->exists ? tap($this->page)->update($data) : Page::query()->create($data);

        session()->flash('status', 'Page saved.');

        return $this->redirect(route('admin.pages.edit', $this->page), navigate: true);
    }

    public function render()
    {
        return view('livewire.admin.pages.form')
            ->layout('layouts.admin', ['heading' => $this->page?->exists ? 'Edit page' : 'New page']);
    }
}

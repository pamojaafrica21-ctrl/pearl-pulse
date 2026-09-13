<?php

namespace App\Livewire\Admin\Countries;

use App\Models\Country;
use App\Services\ImageUploader;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;

class Form extends Component
{
    use WithFileUploads;

    public ?Country $country = null;

    public string $name = '';

    public string $slug = '';

    public string $subtitle = '';

    public string $teaser = '';

    public string $description = '';

    public string $practical = '';

    public string $best_time = '';

    public string $meta_title = '';

    public string $meta_description = '';

    public bool $is_featured = true;

    public string $status = 'draft';

    public int $sort_order = 0;

    public $cover;

    public function mount(?Country $country = null): void
    {
        if ($country?->exists) {
            $this->country = $country;
            foreach (['name', 'slug', 'subtitle', 'teaser', 'description', 'practical', 'best_time', 'meta_title', 'meta_description', 'status'] as $field) {
                $this->{$field} = (string) $country->{$field};
            }
            $this->is_featured = (bool) $country->is_featured;
            $this->sort_order = (int) $country->sort_order;
        }
    }

    public function updatedName(string $value): void
    {
        if (! $this->country) {
            $this->slug = Str::slug($value);
        }
    }

    public function save(ImageUploader $uploader)
    {
        $this->validate([
            'name' => ['required', 'max:160'],
            'slug' => ['required', 'max:180', Rule::unique('countries', 'slug')->ignore($this->country?->id)],
            'status' => ['required', Rule::in(['draft', 'published'])],
            'cover' => ['nullable', 'image', 'max:5120'],
        ]);

        $data = [
            'name' => $this->name,
            'slug' => $this->slug,
            'subtitle' => $this->subtitle ?: null,
            'teaser' => $this->teaser ?: null,
            'description' => $this->description ?: null,
            'practical' => $this->practical ?: null,
            'best_time' => $this->best_time ?: null,
            'meta_title' => $this->meta_title ?: null,
            'meta_description' => $this->meta_description ?: null,
            'is_featured' => $this->is_featured,
            'status' => $this->status,
            'sort_order' => $this->sort_order,
        ];

        $this->country = $this->country?->exists
            ? tap($this->country)->update($data)
            : Country::query()->create($data);

        if ($this->cover) {
            $uploader->delete($this->country->cover_path);
            $this->country->update(['cover_path' => $uploader->store($this->cover, 'countries/'.$this->country->id)]);
            $this->cover = null;
        }

        session()->flash('status', 'Country saved.');

        return $this->redirect(route('admin.countries.edit', $this->country), navigate: true);
    }

    public function render()
    {
        return view('livewire.admin.countries.form', [
            'uploader' => app(ImageUploader::class),
        ])->layout('layouts.admin', ['heading' => $this->country?->exists ? 'Edit country' : 'New country']);
    }
}

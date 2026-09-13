<?php

namespace App\Livewire\Admin\Stays;

use App\Models\Destination;
use App\Models\Stay;
use App\Services\ImageUploader;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;

class Form extends Component
{
    use WithFileUploads;

    public ?Stay $stay = null;

    public string $name = '';

    public string $slug = '';

    public string $subtitle = '';

    public string $teaser = '';

    public string $description = '';

    public string $location = '';

    public string $style = '';

    public ?int $destination_id = null;

    public string $status = 'draft';

    public int $sort_order = 0;

    public bool $is_featured = false;

    public $cover;

    public function mount(?Stay $stay = null): void
    {
        if ($stay?->exists) {
            $this->stay = $stay;
            $this->name = (string) $stay->name;
            $this->slug = (string) $stay->slug;
            $this->subtitle = (string) $stay->subtitle;
            $this->teaser = (string) $stay->teaser;
            $this->description = (string) $stay->description;
            $this->location = (string) $stay->location;
            $this->style = (string) $stay->style;
            $this->destination_id = $stay->destination_id;
            $this->status = (string) $stay->status;
            $this->sort_order = (int) $stay->sort_order;
            $this->is_featured = (bool) $stay->is_featured;
        }
    }

    public function updatedName(string $value): void
    {
        if (! $this->stay) {
            $this->slug = Str::slug($value);
        }
    }

    public function save(ImageUploader $uploader)
    {
        $this->validate([
            'name' => ['required', 'max:160'],
            'slug' => ['required', Rule::unique('stays', 'slug')->ignore($this->stay?->id)],
            'cover' => ['nullable', 'image', 'max:5120'],
        ]);

        $data = [
            'name' => $this->name,
            'slug' => $this->slug,
            'subtitle' => $this->subtitle ?: null,
            'teaser' => $this->teaser ?: null,
            'description' => $this->description ?: null,
            'location' => $this->location ?: null,
            'style' => $this->style ?: null,
            'destination_id' => $this->destination_id,
            'status' => $this->status,
            'sort_order' => $this->sort_order,
            'is_featured' => $this->is_featured,
        ];

        $this->stay = $this->stay?->exists ? tap($this->stay)->update($data) : Stay::query()->create($data);

        if ($this->cover) {
            $uploader->delete($this->stay->cover_path);
            $this->stay->update(['cover_path' => $uploader->store($this->cover, 'stays/'.$this->stay->id)]);
        }

        session()->flash('status', 'Stay saved.');

        return $this->redirect(route('admin.stays.edit', $this->stay), navigate: true);
    }

    public function render()
    {
        return view('livewire.admin.stays.form', [
            'destinations' => Destination::query()->orderBy('name')->get(),
        ])->layout('layouts.admin', ['heading' => $this->stay?->exists ? 'Edit stay' : 'New stay']);
    }
}

<?php

namespace App\Livewire\Admin\Specialists;

use App\Models\Specialist;
use App\Services\ImageUploader;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;

class Form extends Component
{
    use WithFileUploads;

    public ?Specialist $specialist = null;

    public string $name = '';

    public string $slug = '';

    public string $subtitle = '';

    public string $teaser = '';

    public string $description = '';

    public string $status = 'draft';

    public int $sort_order = 0;

    public bool $is_featured = false;

    public $cover;

    public function mount(?Specialist $specialist = null): void
    {
        if ($specialist?->exists) {
            $this->specialist = $specialist;
            $this->name = (string) $specialist->name;
            $this->slug = (string) $specialist->slug;
            $this->subtitle = (string) $specialist->subtitle;
            $this->teaser = (string) $specialist->teaser;
            $this->description = (string) $specialist->description;
            $this->status = (string) $specialist->status;
            $this->sort_order = (int) $specialist->sort_order;
        }
    }

    public function updatedName(string $value): void
    {
        if (! $this->specialist) {
            $this->slug = Str::slug($value);
        }
    }

    public function save(ImageUploader $uploader)
    {
        $this->validate([
            'name' => ['required', 'max:160'],
            'slug' => ['required', Rule::unique('specialists', 'slug')->ignore($this->specialist?->id)],
            'cover' => ['nullable', 'image', 'max:5120'],
        ]);

        $data = [
            'name' => $this->name,
            'slug' => $this->slug,
            'subtitle' => $this->subtitle ?: null,
            'teaser' => $this->teaser ?: null,
            'description' => $this->description ?: null,
            'status' => $this->status,
            'sort_order' => $this->sort_order,
            'meta_title' => $this->name.' | Pearl Pulse Safaris',
            'meta_description' => $this->teaser ?: null,
        ];

        $this->specialist = $this->specialist?->exists
            ? tap($this->specialist)->update($data)
            : Specialist::query()->create($data);

        if ($this->cover) {
            $uploader->delete($this->specialist->cover_path);
            $this->specialist->update([
                'cover_path' => $uploader->store($this->cover, 'specialists/'.$this->specialist->id),
            ]);
        }

        session()->flash('status', 'Specialist journey saved.');

        return $this->redirect(route('admin.specialists.edit', $this->specialist), navigate: true);
    }

    public function render()
    {
        return view('livewire.admin.named-form', [
            'record' => $this->specialist,
            'label' => 'specialist journey',
        ])->layout('layouts.admin', [
            'heading' => $this->specialist?->exists ? 'Edit specialist journey' : 'New specialist journey',
        ]);
    }
}

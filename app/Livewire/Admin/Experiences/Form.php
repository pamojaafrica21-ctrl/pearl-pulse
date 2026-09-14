<?php

namespace App\Livewire\Admin\Experiences;

use App\Livewire\Admin\Support\ManagesVideoUpload;
use App\Models\Experience;
use App\Services\ImageUploader;
use App\Services\VideoUploader;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;

class Form extends Component
{
    use ManagesVideoUpload, WithFileUploads;

    public ?Experience $experience = null;

    public string $name = '';

    public string $slug = '';

    public string $subtitle = '';

    public string $teaser = '';

    public string $description = '';

    public string $status = 'draft';

    public int $sort_order = 0;

    public bool $is_featured = false;

    public $cover;

    public function mount(?Experience $experience = null): void
    {
        if ($experience?->exists) {
            $this->experience = $experience;
            $this->name = (string) $experience->name;
            $this->slug = (string) $experience->slug;
            $this->subtitle = (string) $experience->subtitle;
            $this->teaser = (string) $experience->teaser;
            $this->description = (string) $experience->description;
            $this->status = (string) $experience->status;
            $this->sort_order = (int) $experience->sort_order;
            $this->is_featured = (bool) $experience->is_featured;
            $this->loadVideoState($experience);
        }
    }

    public function updatedName(string $value): void
    {
        if (! $this->experience) {
            $this->slug = Str::slug($value);
        }
    }

    public function save(ImageUploader $uploader, VideoUploader $videos)
    {
        $this->validate([
            'name' => ['required', 'max:160'],
            'slug' => ['required', Rule::unique('experiences', 'slug')->ignore($this->experience?->id)],
            'cover' => ['nullable', 'image', 'max:5120'],
            ...$this->videoValidationRules(),
        ]);

        $data = [
            'name' => $this->name,
            'slug' => $this->slug,
            'subtitle' => $this->subtitle ?: null,
            'teaser' => $this->teaser ?: null,
            'description' => $this->description ?: null,
            'status' => $this->status,
            'sort_order' => $this->sort_order,
            'is_featured' => $this->is_featured,
            'meta_title' => $this->name.' | Pearl Pulse Safaris',
            'meta_description' => $this->teaser ?: null,
        ];

        $this->experience = $this->experience?->exists
            ? tap($this->experience)->update($data)
            : Experience::query()->create($data);

        if ($this->cover) {
            $uploader->delete($this->experience->cover_path);
            $this->experience->update(['cover_path' => $uploader->store($this->cover, 'experiences/'.$this->experience->id)]);
        }

        $this->persistVideo($this->experience, $videos, 'experiences/'.$this->experience->id.'/video');

        session()->flash('status', 'Experience saved.');

        return $this->redirect(route('admin.experiences.edit', $this->experience), navigate: true);
    }

    public function render()
    {
        return view('livewire.admin.named-form', [
            'record' => $this->experience,
            'label' => 'experience',
            'videoUploader' => app(VideoUploader::class),
        ])->layout('layouts.admin', ['heading' => $this->experience?->exists ? 'Edit experience' : 'New experience']);
    }
}

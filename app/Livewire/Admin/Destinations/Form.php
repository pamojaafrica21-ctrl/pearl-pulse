<?php

namespace App\Livewire\Admin\Destinations;

use App\Models\Country;
use App\Models\Destination;
use App\Models\DestinationImage;
use App\Services\ImageUploader;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;

class Form extends Component
{
    use WithFileUploads;

    public ?Destination $destination = null;

    public string $name = '';

    public string $subtitle = '';

    public string $slug = '';

    public string $region = '';

    public ?int $country_id = null;

    public string $why = '';

    public string $practical = '';

    public string $teaser = '';

    public string $duration = '';

    public string $best_time = '';

    public string $activities = '';

    public string $price_from = '';

    public string $description = '';

    public array $highlights = [];

    public string $meta_title = '';

    public string $meta_description = '';

    public bool $is_featured = false;

    public string $status = 'draft';

    public int $sort_order = 0;

    public $cover;

    public $gallery = [];

    public bool $slugManual = false;

    public function mount(?Destination $destination = null): void
    {
        if ($destination?->exists) {
            $this->destination = $destination;
            $this->name = $destination->name;
            $this->subtitle = (string) $destination->subtitle;
            $this->slug = $destination->slug;
            $this->region = (string) $destination->region;
            $this->country_id = $destination->country_id;
            $this->why = (string) $destination->why;
            $this->practical = (string) $destination->practical;
            $this->teaser = (string) $destination->teaser;
            $this->duration = (string) $destination->duration;
            $this->best_time = (string) $destination->best_time;
            $this->activities = (string) $destination->activities;
            $this->price_from = (string) $destination->price_from;
            $this->description = (string) $destination->description;
            $this->highlights = $destination->highlights ?: [];
            $this->meta_title = (string) $destination->meta_title;
            $this->meta_description = (string) $destination->meta_description;
            $this->is_featured = (bool) $destination->is_featured;
            $this->status = $destination->status;
            $this->sort_order = (int) $destination->sort_order;
            $this->slugManual = true;
        }

        if (empty($this->highlights)) {
            $this->highlights = [
                ['label' => 'Best time to visit', 'value' => ''],
                ['label' => 'Activities', 'value' => ''],
                ['label' => 'Suggested duration', 'value' => ''],
            ];
        }
    }

    public function updatedName(string $value): void
    {
        if (! $this->slugManual) {
            $this->slug = Str::slug($value);
        }
    }

    public function updatedSlug(): void
    {
        $this->slugManual = true;
        $this->slug = Str::slug($this->slug);
    }

    public function addHighlight(): void
    {
        $this->highlights[] = ['label' => '', 'value' => ''];
    }

    public function removeHighlight(int $index): void
    {
        unset($this->highlights[$index]);
        $this->highlights = array_values($this->highlights);
    }

    public function removeGalleryImage(int $imageId, ImageUploader $uploader): void
    {
        $image = DestinationImage::query()
            ->where('destination_id', $this->destination->id)
            ->findOrFail($imageId);

        $uploader->delete($image->path);
        $image->delete();
        $this->destination->refresh();
    }

    public function removeCover(ImageUploader $uploader): void
    {
        if (! $this->destination) {
            return;
        }

        $uploader->delete($this->destination->cover_path);
        $this->destination->update(['cover_path' => null]);
        $this->destination->refresh();
    }

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:160'],
            'subtitle' => ['nullable', 'string', 'max:255'],
            'slug' => [
                'required',
                'string',
                'max:180',
                Rule::unique('destinations', 'slug')->ignore($this->destination?->id),
            ],
            'region' => ['nullable', 'string', 'max:120'],
            'country_id' => ['required', 'exists:countries,id'],
            'why' => ['nullable', 'string'],
            'practical' => ['nullable', 'string'],
            'teaser' => ['nullable', 'string', 'max:500'],
            'duration' => ['nullable', 'string', 'max:120'],
            'best_time' => ['nullable', 'string', 'max:180'],
            'activities' => ['nullable', 'string', 'max:500'],
            'price_from' => ['nullable', 'string', 'max:120'],
            'description' => ['nullable', 'string'],
            'highlights' => ['array'],
            'highlights.*.label' => ['nullable', 'string', 'max:120'],
            'highlights.*.value' => ['nullable', 'string', 'max:255'],
            'meta_title' => ['nullable', 'string', 'max:180'],
            'meta_description' => ['nullable', 'string', 'max:500'],
            'is_featured' => ['boolean'],
            'status' => ['required', Rule::in(['draft', 'published'])],
            'sort_order' => ['integer', 'min:0'],
            'cover' => ['nullable', 'image', 'max:5120'],
            'gallery.*' => ['nullable', 'image', 'max:5120'],
        ];
    }

    public function save(ImageUploader $uploader)
    {
        $this->validate();

        $highlights = collect($this->highlights)
            ->map(fn ($h) => [
                'label' => trim((string) ($h['label'] ?? '')),
                'value' => trim((string) ($h['value'] ?? '')),
            ])
            ->filter(fn ($h) => $h['label'] !== '' || $h['value'] !== '')
            ->values()
            ->all();

        $data = [
            'name' => $this->name,
            'subtitle' => $this->subtitle ?: null,
            'slug' => $this->slug,
            'region' => $this->region ?: null,
            'country_id' => $this->country_id,
            'why' => $this->why ?: null,
            'practical' => $this->practical ?: null,
            'teaser' => $this->teaser ?: null,
            'duration' => $this->duration ?: null,
            'best_time' => $this->best_time ?: null,
            'activities' => $this->activities ?: null,
            'price_from' => $this->price_from ?: null,
            'description' => $this->description ?: null,
            'highlights' => $highlights,
            'meta_title' => $this->meta_title ?: null,
            'meta_description' => $this->meta_description ?: null,
            'is_featured' => $this->is_featured,
            'status' => $this->status,
            'sort_order' => $this->sort_order,
        ];

        if ($this->destination) {
            $this->destination->update($data);
        } else {
            $this->destination = Destination::query()->create($data);
        }

        if ($this->cover) {
            $uploader->delete($this->destination->cover_path);
            $path = $uploader->store($this->cover, 'destinations/'.$this->destination->id);
            $this->destination->update(['cover_path' => $path]);
            $this->cover = null;
        }

        if (! empty($this->gallery)) {
            $maxSort = (int) $this->destination->images()->max('sort_order');
            foreach ($this->gallery as $file) {
                $maxSort++;
                $path = $uploader->store($file, 'destinations/'.$this->destination->id.'/gallery');
                $this->destination->images()->create([
                    'path' => $path,
                    'alt' => $this->destination->name,
                    'sort_order' => $maxSort,
                ]);
            }
            $this->gallery = [];
        }

        $this->destination->refresh();

        session()->flash('status', 'Destination saved.');

        return $this->redirect(route('admin.destinations.edit', $this->destination), navigate: true);
    }

    public function render()
    {
        $heading = $this->destination?->exists ? 'Edit destination' : 'New destination';

        return view('livewire.admin.destinations.form', [
            'uploader' => app(ImageUploader::class),
            'countries' => Country::query()->orderBy('sort_order')->get(),
        ])->layout('layouts.admin', ['heading' => $heading]);
    }
}

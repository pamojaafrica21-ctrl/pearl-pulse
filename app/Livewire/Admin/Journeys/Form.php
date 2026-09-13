<?php

namespace App\Livewire\Admin\Journeys;

use App\Models\Country;
use App\Models\Destination;
use App\Models\Experience;
use App\Models\Journey;
use App\Models\Stay;
use App\Services\ImageUploader;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;

class Form extends Component
{
    use WithFileUploads;

    public ?Journey $journey = null;

    public string $name = '';

    public string $slug = '';

    public string $subtitle = '';

    public string $teaser = '';

    public string $overview = '';

    public ?int $days = null;

    public string $duration_label = '';

    public string $best_time = '';

    public string $practical = '';

    public string $price_mode = 'from';

    public string $price_from = '';

    public string $map_embed_url = '';

    public bool $is_signature = false;

    public bool $is_multi_country = false;

    public bool $is_featured = false;

    public string $status = 'draft';

    public int $sort_order = 0;

    public string $meta_title = '';

    public string $meta_description = '';

    public array $countryIds = [];

    public array $destinationIds = [];

    public array $experienceIds = [];

    public array $stayIds = [];

    public $cover;

    public string $includedLines = '';

    public string $notIncludedLines = '';

    public function mount(?Journey $journey = null): void
    {
        if ($journey?->exists) {
            $this->journey = $journey;
            $this->name = $journey->name;
            $this->slug = $journey->slug;
            $this->subtitle = (string) $journey->subtitle;
            $this->teaser = (string) $journey->teaser;
            $this->overview = (string) $journey->overview;
            $this->days = $journey->days;
            $this->duration_label = (string) $journey->duration_label;
            $this->best_time = (string) $journey->best_time;
            $this->practical = (string) $journey->practical;
            $this->price_mode = $journey->price_mode;
            $this->price_from = (string) $journey->price_from;
            $this->map_embed_url = (string) $journey->map_embed_url;
            $this->is_signature = $journey->is_signature;
            $this->is_multi_country = $journey->is_multi_country;
            $this->is_featured = $journey->is_featured;
            $this->status = $journey->status;
            $this->sort_order = (int) $journey->sort_order;
            $this->meta_title = (string) $journey->meta_title;
            $this->meta_description = (string) $journey->meta_description;
            $this->includedLines = implode("\n", $journey->included ?? []);
            $this->notIncludedLines = implode("\n", $journey->not_included ?? []);
            $this->countryIds = $journey->countries()->pluck('countries.id')->all();
            $this->destinationIds = $journey->destinations()->pluck('destinations.id')->all();
            $this->experienceIds = $journey->experiences()->pluck('experiences.id')->all();
            $this->stayIds = $journey->stays()->pluck('stays.id')->all();
        }
    }

    public function updatedName(string $value): void
    {
        if (! $this->journey) {
            $this->slug = Str::slug($value);
        }
    }

    public function save(ImageUploader $uploader)
    {
        $this->validate([
            'name' => ['required', 'max:180'],
            'slug' => ['required', Rule::unique('journeys', 'slug')->ignore($this->journey?->id)],
            'price_mode' => ['required', Rule::in(['from', 'tailored', 'proposal'])],
            'status' => ['required', Rule::in(['draft', 'published'])],
            'cover' => ['nullable', 'image', 'max:5120'],
        ]);

        $data = [
            'name' => $this->name,
            'slug' => $this->slug,
            'subtitle' => $this->subtitle ?: null,
            'teaser' => $this->teaser ?: null,
            'overview' => $this->overview ?: null,
            'days' => $this->days,
            'duration_label' => $this->duration_label ?: ($this->days ? $this->days.' days' : null),
            'best_time' => $this->best_time ?: null,
            'practical' => $this->practical ?: null,
            'price_mode' => $this->price_mode,
            'price_from' => $this->price_from ?: null,
            'map_embed_url' => $this->map_embed_url ?: null,
            'is_signature' => $this->is_signature,
            'is_multi_country' => $this->is_multi_country,
            'is_featured' => $this->is_featured,
            'status' => $this->status,
            'sort_order' => $this->sort_order,
            'meta_title' => $this->meta_title ?: null,
            'meta_description' => $this->meta_description ?: null,
            'included' => array_values(array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', $this->includedLines)))),
            'not_included' => array_values(array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', $this->notIncludedLines)))),
        ];

        $this->journey = $this->journey?->exists
            ? tap($this->journey)->update($data)
            : Journey::query()->create($data);

        $this->journey->countries()->sync($this->countryIds);
        $this->journey->destinations()->sync($this->destinationIds);
        $this->journey->experiences()->sync($this->experienceIds);
        $this->journey->stays()->sync($this->stayIds);

        if ($this->cover) {
            $uploader->delete($this->journey->cover_path);
            $this->journey->update(['cover_path' => $uploader->store($this->cover, 'journeys/'.$this->journey->id)]);
            $this->cover = null;
        }

        session()->flash('status', 'Journey saved.');

        return $this->redirect(route('admin.journeys.edit', $this->journey), navigate: true);
    }

    public function render()
    {
        return view('livewire.admin.journeys.form', [
            'uploader' => app(ImageUploader::class),
            'countries' => Country::query()->orderBy('name')->get(),
            'destinations' => Destination::query()->orderBy('name')->get(),
            'experiences' => Experience::query()->orderBy('name')->get(),
            'stays' => Stay::query()->orderBy('name')->get(),
        ])->layout('layouts.admin', ['heading' => $this->journey?->exists ? 'Edit journey' : 'New journey']);
    }
}

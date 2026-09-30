<?php

namespace App\Livewire\Admin\Journeys;

use App\Livewire\Admin\Support\ManagesVideoUpload;
use App\Models\Country;
use App\Models\Destination;
use App\Models\Experience;
use App\Models\Journey;
use App\Models\Stay;
use App\Services\ImageUploader;
use App\Services\VideoUploader;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;

class Form extends Component
{
    use ManagesVideoUpload, WithFileUploads;

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

    public array $itineraryDays = [];

    /** @var array<int, mixed> */
    public array $dayImages = [];

    /** @var array<int, bool> */
    public array $removeDayImages = [];

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
            $this->itineraryDays = $this->normalizeDaysForForm($journey->itinerary ?? []);
            $this->loadVideoState($journey);
        }

        if (empty($this->itineraryDays)) {
            $this->itineraryDays = [
                $this->blankDay(1),
                $this->blankDay(2),
            ];
        }
    }

    public function updatedName(string $value): void
    {
        if (! $this->journey) {
            $this->slug = Str::slug($value);
        }
    }

    public function addDay(): void
    {
        $this->itineraryDays[] = $this->blankDay(count($this->itineraryDays) + 1);
    }

    public function removeDay(int $index): void
    {
        if (! isset($this->itineraryDays[$index])) {
            return;
        }

        unset($this->itineraryDays[$index], $this->dayImages[$index], $this->removeDayImages[$index]);
        $this->itineraryDays = array_values($this->itineraryDays);
        $this->dayImages = array_values($this->dayImages);
        $this->removeDayImages = array_values($this->removeDayImages);
        $this->renumberDays();
    }

    public function moveDayUp(int $index): void
    {
        if ($index < 1 || ! isset($this->itineraryDays[$index])) {
            return;
        }

        $this->swapDays($index, $index - 1);
    }

    public function moveDayDown(int $index): void
    {
        if (! isset($this->itineraryDays[$index + 1])) {
            return;
        }

        $this->swapDays($index, $index + 1);
    }

    public function clearDayImage(int $index): void
    {
        $this->removeDayImages[$index] = true;
        unset($this->dayImages[$index]);
    }

    public function save(ImageUploader $uploader, VideoUploader $videos)
    {
        $this->validate([
            'name' => ['required', 'max:180'],
            'slug' => ['required', Rule::unique('journeys', 'slug')->ignore($this->journey?->id)],
            'price_mode' => ['required', Rule::in(['from', 'tailored', 'proposal'])],
            'status' => ['required', Rule::in(['draft', 'published'])],
            'cover' => ['nullable', 'image', 'max:5120'],
            'itineraryDays' => ['array'],
            'itineraryDays.*.title' => ['nullable', 'string', 'max:180'],
            'itineraryDays.*.description' => ['nullable', 'string', 'max:5000'],
            'itineraryDays.*.stay_id' => ['nullable'],
            'itineraryDays.*.stay_name' => ['nullable', 'string', 'max:180'],
            'itineraryDays.*.meals' => ['nullable', 'array'],
            'dayImages.*' => ['nullable', 'image', 'max:5120'],
            ...$this->videoValidationRules(),
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
            'itinerary' => $this->serializeItineraryWithoutImages(),
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

        $this->persistVideo($this->journey, $videos, 'journeys/'.$this->journey->id.'/video');
        $this->persistDayImages($uploader);

        session()->flash('status', 'Journey saved.');

        return $this->redirect(route('admin.journeys.edit', $this->journey), navigate: true);
    }

    public function render()
    {
        return view('livewire.admin.journeys.form', [
            'uploader' => app(ImageUploader::class),
            'videoUploader' => app(VideoUploader::class),
            'countries' => Country::query()->orderBy('name')->get(),
            'destinations' => Destination::query()->orderBy('name')->get(),
            'experiences' => Experience::query()->orderBy('name')->get(),
            'stays' => Stay::query()->orderBy('name')->get(),
        ])->layout('layouts.admin', ['heading' => $this->journey?->exists ? 'Edit journey' : 'New journey']);
    }

    protected function blankDay(int $number): array
    {
        return [
            'day' => $number,
            'title' => '',
            'description' => '',
            'stay_id' => '',
            'stay_name' => '',
            'meals' => [],
            'image_path' => '',
        ];
    }

    protected function normalizeDaysForForm(array $days): array
    {
        $normalized = [];

        foreach (array_values($days) as $i => $day) {
            $meals = $day['meals'] ?? [];
            if (is_string($meals)) {
                $meals = array_values(array_filter(array_map('trim', explode(',', $meals))));
            }

            $normalized[] = [
                'day' => (int) ($day['day'] ?? $i + 1),
                'title' => (string) ($day['title'] ?? ''),
                'description' => (string) ($day['description'] ?? ''),
                'stay_id' => isset($day['stay_id']) && $day['stay_id'] !== '' && $day['stay_id'] !== null
                    ? (string) $day['stay_id']
                    : '',
                'stay_name' => (string) ($day['stay_name'] ?? ''),
                'meals' => array_values(array_intersect(['B', 'L', 'D'], $meals)),
                'image_path' => (string) ($day['image_path'] ?? ''),
            ];
        }

        return $normalized;
    }

    protected function renumberDays(): void
    {
        foreach ($this->itineraryDays as $i => $day) {
            $this->itineraryDays[$i]['day'] = $i + 1;
        }
    }

    protected function swapDays(int $a, int $b): void
    {
        $tmpDay = $this->itineraryDays[$a];
        $this->itineraryDays[$a] = $this->itineraryDays[$b];
        $this->itineraryDays[$b] = $tmpDay;

        $tmpImage = $this->dayImages[$a] ?? null;
        $this->dayImages[$a] = $this->dayImages[$b] ?? null;
        $this->dayImages[$b] = $tmpImage;

        $tmpRemove = $this->removeDayImages[$a] ?? false;
        $this->removeDayImages[$a] = $this->removeDayImages[$b] ?? false;
        $this->removeDayImages[$b] = $tmpRemove;

        $this->renumberDays();
    }

    protected function serializeItineraryWithoutImages(): array
    {
        $out = [];

        foreach (array_values($this->itineraryDays) as $i => $day) {
            $title = trim((string) ($day['title'] ?? ''));
            $description = trim((string) ($day['description'] ?? ''));
            $stayName = trim((string) ($day['stay_name'] ?? ''));
            $stayId = $day['stay_id'] ?? '';
            $meals = array_values(array_intersect(['B', 'L', 'D'], $day['meals'] ?? []));
            $imagePath = (string) ($day['image_path'] ?? '');

            if ($title === '' && $description === '' && $stayName === '' && $stayId === '' && empty($meals) && $imagePath === '') {
                continue;
            }

            $row = [
                'day' => $i + 1,
                'title' => $title,
                'description' => $description,
            ];

            if ($stayId !== '' && $stayId !== null) {
                $row['stay_id'] = (int) $stayId;
            }

            if ($stayName !== '') {
                $row['stay_name'] = $stayName;
            }

            if ($meals !== []) {
                $row['meals'] = $meals;
            }

            if ($imagePath !== '' && empty($this->removeDayImages[$i])) {
                $row['image_path'] = $imagePath;
            }

            $out[] = $row;
        }

        return $out;
    }

    protected function persistDayImages(ImageUploader $uploader): void
    {
        $itinerary = $this->journey->itinerary ?? [];
        $changed = false;
        $directory = 'journeys/'.$this->journey->id.'/days';

        foreach ($itinerary as $i => $day) {
            if (! empty($this->removeDayImages[$i]) && ! empty($day['image_path'])) {
                $uploader->delete($day['image_path']);
                unset($itinerary[$i]['image_path']);
                $changed = true;
            }

            if (! empty($this->dayImages[$i])) {
                if (! empty($day['image_path'])) {
                    $uploader->delete($day['image_path']);
                }
                $itinerary[$i]['image_path'] = $uploader->store($this->dayImages[$i], $directory);
                $changed = true;
            }
        }

        if ($changed) {
            $this->journey->update(['itinerary' => array_values($itinerary)]);
        }

        $this->dayImages = [];
        $this->removeDayImages = [];
    }
}

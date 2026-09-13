<?php

namespace App\Livewire\Admin\PulseItems;

use App\Models\PulseItem;
use App\Services\ImageUploader;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;

class Form extends Component
{
    use WithFileUploads;

    public ?PulseItem $item = null;

    public string $title = '';

    public string $type = 'photo';

    public string $caption = '';

    public string $guest_name = '';

    public string $video_url = '';

    public bool $approved = false;

    public string $status = 'draft';

    public int $sort_order = 0;

    public $cover;

    public function mount(?PulseItem $item = null): void
    {
        if ($item?->exists) {
            $this->item = $item;
            $this->title = (string) $item->title;
            $this->type = (string) $item->type;
            $this->caption = (string) $item->caption;
            $this->guest_name = (string) $item->guest_name;
            $this->video_url = (string) $item->video_url;
            $this->approved = (bool) $item->approved;
            $this->status = (string) $item->status;
            $this->sort_order = (int) $item->sort_order;
        }
    }

    public function save(ImageUploader $uploader)
    {
        $this->validate([
            'type' => ['required', Rule::in(PulseItem::TYPES)],
            'cover' => ['nullable', 'image', 'max:5120'],
        ]);

        $data = [
            'title' => $this->title ?: null,
            'type' => $this->type,
            'caption' => $this->caption ?: null,
            'guest_name' => $this->guest_name ?: null,
            'video_url' => $this->video_url ?: null,
            'approved' => $this->approved,
            'status' => $this->status,
            'sort_order' => $this->sort_order,
        ];

        $this->item = $this->item?->exists ? tap($this->item)->update($data) : PulseItem::query()->create($data);

        if ($this->cover) {
            $uploader->delete($this->item->cover_path);
            $this->item->update(['cover_path' => $uploader->store($this->cover, 'pulse/'.$this->item->id)]);
        }

        session()->flash('status', 'True Pulse item saved. It only appears publicly when published and approved.');

        return $this->redirect(route('admin.pulse.edit', $this->item), navigate: true);
    }

    public function render()
    {
        return view('livewire.admin.pulse-items.form')
            ->layout('layouts.admin', ['heading' => $this->item?->exists ? 'Edit True Pulse' : 'New True Pulse']);
    }
}

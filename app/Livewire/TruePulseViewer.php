<?php

namespace App\Livewire;

use App\Models\InstagramMedia;
use App\Services\InstagramGraphService;
use App\Services\SettingService;
use Livewire\Component;

class TruePulseViewer extends Component
{
    public int $limit = 12;

    public bool $compact = false;

    public ?int $selectedId = null;

    public function open(int $id, InstagramGraphService $instagram): void
    {
        $media = InstagramMedia::query()->find($id);
        if (! $media) {
            return;
        }

        if ($instagram->isConfigured()) {
            $media = $instagram->refreshMediaUrls($media);
            $instagram->syncComments($media);
        }

        $this->selectedId = $media->id;
    }

    public function close(): void
    {
        $this->selectedId = null;
    }

    public function render(SettingService $settings, InstagramGraphService $instagram)
    {
        $items = InstagramMedia::query()
            ->orderByDesc('posted_at')
            ->orderByDesc('id')
            ->limit($this->limit)
            ->get();

        $selected = $this->selectedId
            ? InstagramMedia::query()->with('comments')->find($this->selectedId)
            : null;

        return view('livewire.true-pulse-viewer', [
            'items' => $items,
            'selected' => $selected,
            'instagramUrl' => $settings->social()['instagram'] ?? '',
            'hashtag' => $instagram->hashtag(),
            'configured' => $instagram->isConfigured() || $items->isNotEmpty(),
        ]);
    }
}

<?php

namespace App\Livewire\Admin\Bookings;

use App\Models\Booking;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    #[Url(except: '')]
    public string $status = '';

    #[Url(except: '')]
    public string $q = '';

    public function updatingStatus(): void
    {
        $this->resetPage();
    }

    public function updatingQ(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $bookings = Booking::query()
            ->with(['journey', 'assignee', 'enquiry'])
            ->withCount('notes')
            ->when($this->status !== '', fn ($query) => $query->where('status', $this->status))
            ->when($this->q !== '', function ($query) {
                $term = '%'.trim($this->q).'%';
                $query->where(function ($inner) use ($term) {
                    $inner->where('name', 'like', $term)
                        ->orWhere('email', 'like', $term)
                        ->orWhere('reference', 'like', $term)
                        ->orWhere('phone', 'like', $term)
                        ->orWhere('whatsapp', 'like', $term);
                });
            })
            ->orderByRaw('case when start_date is null then 1 else 0 end')
            ->orderBy('start_date')
            ->orderByDesc('id')
            ->paginate(20);

        $counts = Booking::query()
            ->selectRaw('status, count(*) as aggregate')
            ->groupBy('status')
            ->pluck('aggregate', 'status');

        return view('livewire.admin.bookings.index', [
            'bookings' => $bookings,
            'counts' => $counts,
            'total' => (int) $counts->sum(),
        ])->layout('layouts.admin', ['heading' => 'Bookings']);
    }
}

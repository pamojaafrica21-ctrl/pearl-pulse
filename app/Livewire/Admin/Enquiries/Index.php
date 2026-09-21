<?php

namespace App\Livewire\Admin\Enquiries;

use App\Models\Booking;
use App\Models\Enquiry;
use App\Models\User;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    #[Url(except: '')]
    public string $status = '';

    #[Url(except: '')]
    public string $channel = '';

    #[Url(except: '')]
    public string $assigned = '';

    public ?int $viewing = null;

    public string $note = '';

    public function mount(): void
    {
        $open = (int) request('open');
        if ($open > 0) {
            $this->view($open);
        }
    }

    public function updatingStatus(): void
    {
        $this->resetPage();
    }

    public function updatingChannel(): void
    {
        $this->resetPage();
    }

    public function updatingAssigned(): void
    {
        $this->resetPage();
    }

    public function view(int $id): void
    {
        $this->viewing = $id;
        $this->note = '';
        $enquiry = Enquiry::query()->find($id);
        if ($enquiry && $enquiry->status === Enquiry::STATUS_NEW) {
            $enquiry->markAs(Enquiry::STATUS_IN_PROGRESS);
        }
    }

    public function mark(int $id, string $status): void
    {
        Enquiry::query()->find($id)?->markAs($status);
    }

    public function assign(int $id, mixed $userId = null): void
    {
        Enquiry::query()->whereKey($id)->update([
            'assigned_user_id' => filled($userId) ? (int) $userId : null,
        ]);
    }

    public function addNote(int $id): void
    {
        $this->validate([
            'note' => ['required', 'string', 'max:5000'],
        ]);

        $enquiry = Enquiry::query()->find($id);
        if (! $enquiry) {
            return;
        }

        $enquiry->notes()->create([
            'user_id' => auth()->id(),
            'body' => $this->note,
        ]);

        $this->note = '';
    }

    public function convert(int $id)
    {
        $enquiry = Enquiry::query()->findOrFail($id);

        if ($enquiry->booking) {
            return $this->redirect(route('admin.bookings.edit', $enquiry->booking), navigate: true);
        }

        $booking = Booking::fromEnquiry($enquiry);

        session()->flash('status', 'Booking '.$booking->reference.' created.');

        return $this->redirect(route('admin.bookings.edit', $booking), navigate: true);
    }

    public function render()
    {
        $enquiries = Enquiry::query()
            ->with(['destination', 'journey', 'user', 'assignee', 'booking'])
            ->when($this->status !== '', fn ($q) => $q->where('status', $this->status))
            ->when($this->channel !== '', fn ($q) => $q->where('channel', $this->channel))
            ->when($this->assigned === 'mine', fn ($q) => $q->where('assigned_user_id', auth()->id()))
            ->when($this->assigned === 'unassigned', fn ($q) => $q->whereNull('assigned_user_id'))
            ->latest()
            ->paginate(15);

        $viewingEnquiry = $this->viewing
            ? Enquiry::query()
                ->with(['destination', 'journey', 'user', 'assignee', 'booking', 'notes.user'])
                ->find($this->viewing)
            : null;

        return view('livewire.admin.enquiries.index', [
            'enquiries' => $enquiries,
            'viewingEnquiry' => $viewingEnquiry,
            'admins' => User::query()->where('role', 'admin')->orderBy('name')->get(),
        ])->layout('layouts.admin', ['heading' => 'Enquiries']);
    }
}

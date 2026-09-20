<?php

namespace App\Livewire\Admin\Enquiries;

use App\Models\Enquiry;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    #[Url(except: '')]
    public string $status = '';

    public ?int $viewing = null;

    public function updatingStatus(): void
    {
        $this->resetPage();
    }

    public function view(int $id): void
    {
        $this->viewing = $id;
        $enquiry = Enquiry::query()->find($id);
        if ($enquiry && $enquiry->status === 'new') {
            $enquiry->markAs('read');
        }
    }

    public function mark(int $id, string $status): void
    {
        if (! in_array($status, ['new', 'read', 'responded'], true)) {
            return;
        }

        Enquiry::query()->whereKey($id)->update(['status' => $status]);
    }

    public function render()
    {
        $enquiries = Enquiry::query()
            ->with(['destination', 'journey', 'user'])
            ->when($this->status !== '', fn ($q) => $q->where('status', $this->status))
            ->latest()
            ->paginate(15);

        $viewingEnquiry = $this->viewing
            ? Enquiry::query()->with(['destination', 'journey', 'user'])->find($this->viewing)
            : null;

        return view('livewire.admin.enquiries.index', [
            'enquiries' => $enquiries,
            'viewingEnquiry' => $viewingEnquiry,
        ])->layout('layouts.admin', ['heading' => 'Enquiries']);
    }
}

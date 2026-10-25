<?php

namespace App\Livewire\Admin\Newsletter;

use App\Models\NewsletterSubscriber;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use Symfony\Component\HttpFoundation\StreamedResponse;

class Index extends Component
{
    use WithPagination;

    #[Url(except: '')]
    public string $search = '';

    public ?int $confirmingDelete = null;

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function confirmDelete(int $id): void
    {
        $this->confirmingDelete = $id;
    }

    public function cancelDelete(): void
    {
        $this->confirmingDelete = null;
    }

    public function delete(): void
    {
        NewsletterSubscriber::query()->findOrFail($this->confirmingDelete)->delete();
        $this->confirmingDelete = null;
        session()->flash('status', 'Subscriber removed.');
    }

    public function export(): StreamedResponse
    {
        $filename = 'newsletter-subscribers-'.now()->format('Y-m-d').'.csv';

        return response()->streamDownload(function () {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['email', 'subscribed_at', 'created_at']);

            NewsletterSubscriber::query()
                ->orderByDesc('subscribed_at')
                ->orderByDesc('id')
                ->chunk(200, function ($subscribers) use ($handle) {
                    foreach ($subscribers as $subscriber) {
                        fputcsv($handle, [
                            $subscriber->email,
                            $subscriber->subscribed_at?->toDateTimeString(),
                            $subscriber->created_at?->toDateTimeString(),
                        ]);
                    }
                });

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv',
        ]);
    }

    public function render()
    {
        $query = NewsletterSubscriber::query()
            ->orderByDesc('subscribed_at')
            ->orderByDesc('id');

        if ($this->search !== '') {
            $query->where('email', 'like', '%'.$this->search.'%');
        }

        return view('livewire.admin.newsletter.index', [
            'subscribers' => $query->paginate(25),
            'total' => NewsletterSubscriber::query()->count(),
        ])->layout('layouts.admin', ['heading' => 'Newsletter']);
    }
}

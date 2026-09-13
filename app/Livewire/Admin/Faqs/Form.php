<?php

namespace App\Livewire\Admin\Faqs;

use App\Models\Faq;
use Livewire\Component;

class Form extends Component
{
    public ?Faq $faq = null;

    public string $question = '';

    public string $answer = '';

    public string $group = 'site';

    public string $status = 'published';

    public int $sort_order = 0;

    public function mount(?Faq $faq = null): void
    {
        if ($faq?->exists) {
            $this->faq = $faq;
            $this->question = (string) $faq->question;
            $this->answer = (string) $faq->answer;
            $this->group = (string) $faq->group;
            $this->status = (string) $faq->status;
            $this->sort_order = (int) $faq->sort_order;
        }
    }

    public function save()
    {
        $this->validate(['question' => ['required']]);

        $data = [
            'question' => $this->question,
            'answer' => $this->answer ?: null,
            'group' => $this->group,
            'status' => $this->status,
            'sort_order' => $this->sort_order,
        ];

        $this->faq = $this->faq?->exists ? tap($this->faq)->update($data) : Faq::query()->create($data);

        session()->flash('status', 'FAQ saved.');

        return $this->redirect(route('admin.faqs.edit', $this->faq), navigate: true);
    }

    public function render()
    {
        return view('livewire.admin.faqs.form')
            ->layout('layouts.admin', ['heading' => $this->faq?->exists ? 'Edit FAQ' : 'New FAQ']);
    }
}

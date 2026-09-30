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

    public string $topic = '';

    public string $status = 'published';

    public int $sort_order = 0;

    public function mount(?Faq $faq = null): void
    {
        if ($faq?->exists) {
            $this->faq = $faq;
            $this->question = (string) $faq->question;
            $this->answer = (string) $faq->answer;
            $this->group = (string) $faq->group;
            $this->topic = (string) ($faq->topic ?? '');
            $this->status = (string) $faq->status;
            $this->sort_order = (int) $faq->sort_order;
        }
    }

    public function save()
    {
        $this->validate([
            'question' => ['required'],
            'topic' => ['nullable', 'in:'.implode(',', array_keys(Faq::TOPICS))],
        ]);

        $data = [
            'question' => $this->question,
            'answer' => $this->answer ?: null,
            'group' => $this->group,
            'topic' => $this->group === 'site' ? ($this->topic ?: null) : null,
            'status' => $this->status,
            'sort_order' => $this->sort_order,
        ];

        $this->faq = $this->faq?->exists ? tap($this->faq)->update($data) : Faq::query()->create($data);

        session()->flash('status', 'FAQ saved.');

        return $this->redirect(route('admin.faqs.edit', $this->faq), navigate: true);
    }

    public function render()
    {
        return view('livewire.admin.faqs.form', [
            'topics' => Faq::TOPICS,
        ])->layout('layouts.admin', ['heading' => $this->faq?->exists ? 'Edit FAQ' : 'New FAQ']);
    }
}

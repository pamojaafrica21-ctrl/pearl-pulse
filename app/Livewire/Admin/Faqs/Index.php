<?php

namespace App\Livewire\Admin\Faqs;

use App\Models\Faq;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public ?int $confirmingDelete = null;

    public function delete(): void
    {
        Faq::query()->findOrFail($this->confirmingDelete)->delete();
        $this->confirmingDelete = null;
        session()->flash('status', 'FAQ deleted.');
    }

    public function render()
    {
        return view('livewire.admin.simple-index', [
            'rows' => Faq::query()->orderBy('sort_order')->paginate(20),
            'createRoute' => route('admin.faqs.create'),
            'editRoute' => 'admin.faqs.edit',
            'label' => 'FAQ',
            'columns' => ['question', 'group', 'status'],
        ])->layout('layouts.admin', ['heading' => 'FAQs']);
    }
}

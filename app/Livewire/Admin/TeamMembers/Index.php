<?php

namespace App\Livewire\Admin\TeamMembers;

use App\Livewire\Admin\Support\DeletesCover;
use App\Models\TeamMember;
use App\Services\ImageUploader;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use DeletesCover, WithPagination;

    public ?int $confirmingDelete = null;

    public function delete(ImageUploader $uploader): void
    {
        $this->deleteRecord(TeamMember::query()->findOrFail($this->confirmingDelete), $uploader);
        $this->confirmingDelete = null;
        session()->flash('status', 'Team member deleted.');
    }

    public function render()
    {
        return view('livewire.admin.simple-index', [
            'rows' => TeamMember::query()->orderBy('sort_order')->paginate(20),
            'createRoute' => route('admin.team.create'),
            'editRoute' => 'admin.team.edit',
            'label' => 'team member',
            'columns' => ['name', 'role', 'status'],
        ])->layout('layouts.admin', ['heading' => 'Team']);
    }
}

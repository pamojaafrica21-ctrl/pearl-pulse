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
            'columns' => [
                ['key' => 'cover_path', 'label' => 'Photo', 'type' => 'cover'],
                ['key' => 'name', 'label' => 'Name', 'type' => 'primary'],
                ['key' => 'role', 'label' => 'Role'],
                ['key' => 'status', 'label' => 'Status', 'type' => 'status'],
                ['key' => 'updated_at', 'label' => 'Updated', 'type' => 'date'],
            ],
        ])->layout('layouts.admin', ['heading' => 'Team']);
    }
}

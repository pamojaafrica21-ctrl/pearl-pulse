<?php

namespace App\Livewire\Admin\TeamMembers;

use App\Models\TeamMember;
use App\Services\ImageUploader;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;

class Form extends Component
{
    use WithFileUploads;

    public ?TeamMember $member = null;

    public string $name = '';

    public string $slug = '';

    public string $role = '';

    public string $bio = '';

    public string $status = 'draft';

    public int $sort_order = 0;

    public $cover;

    public function mount(?TeamMember $member = null): void
    {
        if ($member?->exists) {
            $this->member = $member;
            $this->name = (string) $member->name;
            $this->slug = (string) $member->slug;
            $this->role = (string) $member->role;
            $this->bio = (string) $member->bio;
            $this->status = (string) $member->status;
            $this->sort_order = (int) $member->sort_order;
        }
    }

    public function updatedName(string $value): void
    {
        if (! $this->member) {
            $this->slug = Str::slug($value);
        }
    }

    public function save(ImageUploader $uploader)
    {
        $this->validate([
            'name' => ['required', 'max:160'],
            'slug' => ['required', Rule::unique('team_members', 'slug')->ignore($this->member?->id)],
            'cover' => ['nullable', 'image', 'max:5120'],
        ]);

        $data = [
            'name' => $this->name,
            'slug' => $this->slug,
            'role' => $this->role ?: null,
            'bio' => $this->bio ?: null,
            'status' => $this->status,
            'sort_order' => $this->sort_order,
        ];

        $this->member = $this->member?->exists ? tap($this->member)->update($data) : TeamMember::query()->create($data);

        if ($this->cover) {
            $uploader->delete($this->member->cover_path);
            $this->member->update(['cover_path' => $uploader->store($this->cover, 'team/'.$this->member->id)]);
        }

        session()->flash('status', 'Team member saved.');

        return $this->redirect(route('admin.team.edit', $this->member), navigate: true);
    }

    public function render()
    {
        return view('livewire.admin.team-members.form')
            ->layout('layouts.admin', ['heading' => $this->member?->exists ? 'Edit team member' : 'New team member']);
    }
}

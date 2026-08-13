<?php

namespace App\Livewire\Admin;

use App\Models\Destination;
use App\Models\Enquiry;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Dashboard')]
class Dashboard extends Component
{
    public function render()
    {
        return view('livewire.admin.dashboard', [
            'destinationCount' => Destination::query()->count(),
            'publishedCount' => Destination::query()->published()->count(),
            'newEnquiries' => Enquiry::query()->where('status', 'new')->count(),
            'recentEnquiries' => Enquiry::query()->with('destination')->latest()->take(5)->get(),
        ])->layout('layouts.admin', ['heading' => 'Dashboard']);
    }
}

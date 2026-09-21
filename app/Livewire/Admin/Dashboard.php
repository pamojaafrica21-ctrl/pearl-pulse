<?php

namespace App\Livewire\Admin;

use App\Models\Booking;
use App\Models\Destination;
use App\Models\Enquiry;
use App\Models\PageVisit;
use App\Services\SettingService;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Dashboard')]
class Dashboard extends Component
{
    public bool $sitePublic = true;

    public string $maintenanceMessage = '';

    public function mount(SettingService $settings): void
    {
        $this->sitePublic = $settings->isSitePublic();
        $this->maintenanceMessage = (string) $settings->get('maintenance_message', '');
    }

    public function toggleSiteVisibility(SettingService $settings): void
    {
        $this->sitePublic = ! $this->sitePublic;
        $settings->setSitePublic($this->sitePublic);

        session()->flash('status', $this->sitePublic
            ? 'Public site is now live.'
            : 'Public site is now hidden.');
    }

    public function saveMaintenanceMessage(SettingService $settings): void
    {
        $this->validate([
            'maintenanceMessage' => ['nullable', 'string', 'max:500'],
        ]);

        $settings->set('maintenance_message', $this->maintenanceMessage);
        session()->flash('status', 'Maintenance message saved.');
    }

    public function render()
    {
        return view('livewire.admin.dashboard', [
            'destinationCount' => Destination::query()->count(),
            'publishedCount' => Destination::query()->published()->count(),
            'newEnquiries' => Enquiry::query()->where('status', Enquiry::STATUS_NEW)->count(),
            'openEnquiries' => Enquiry::query()->open()->count(),
            'confirmedBookings' => Booking::query()->where('status', Booking::STATUS_CONFIRMED)->count(),
            'recentEnquiries' => Enquiry::query()->with(['destination', 'assignee'])->latest()->take(5)->get(),
            'visitStats' => PageVisit::stats(),
        ])->layout('layouts.admin', ['heading' => 'Dashboard']);
    }
}

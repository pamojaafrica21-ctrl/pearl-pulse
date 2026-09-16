@extends('layouts.public')

@section('title', 'Profile | Pearl Pulse Safaris')
@section('meta_description', 'Manage your Pearl Pulse Safaris account.')

@section('content')
<section class="bg-white">
    <x-account-nav active="profile" />
</section>

<section class="bg-cream py-14 lg:py-20">
    <div class="mx-auto max-w-3xl px-5 lg:px-8 space-y-8">
        <div class="bg-white border border-charcoal/10 p-6 sm:p-8">
            <livewire:profile.update-profile-information-form />
        </div>

        <div class="bg-white border border-charcoal/10 p-6 sm:p-8">
            <livewire:profile.update-password-form />
        </div>

        <div class="bg-white border border-charcoal/10 p-6 sm:p-8">
            <livewire:profile.delete-user-form />
        </div>
    </div>
</section>
@endsection

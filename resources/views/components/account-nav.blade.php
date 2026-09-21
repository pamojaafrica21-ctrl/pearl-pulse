@props([
    'active' => 'favorites',
])

@php
    $user = auth()->user();
    $links = [
        'favorites' => ['label' => 'Saved journeys', 'href' => route('account.favorites')],
        'requests' => ['label' => 'My requests', 'href' => route('account.requests')],
        'profile' => ['label' => 'Profile', 'href' => route('account.profile')],
    ];
    $headings = [
        'favorites' => 'Saved journeys',
        'requests' => 'My requests',
        'profile' => 'Profile',
    ];
    $intros = [
        'favorites' => 'Favourites you have saved while exploring. When you are ready, plan a private proposal with us.',
        'requests' => 'Journey Finder, plan submissions, and any confirmed bookings linked to your account.',
        'profile' => 'Update your details and keep your Pearl Pulse account secure.',
    ];
@endphp

<div class="border-b border-charcoal/10">
    <div class="mx-auto max-w-7xl px-5 lg:px-8">
        <div class="flex flex-col gap-6 py-10 sm:py-12 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="section-eyebrow">Your account</p>
                <h1 class="font-display text-4xl sm:text-5xl md:text-6xl text-charcoal mt-2">
                    {{ $headings[$active] ?? 'Your account' }}
                </h1>
                <p class="mt-3 max-w-xl text-muted leading-relaxed">
                    {{ $intros[$active] ?? '' }}
                </p>
                @if($user)
                    <p class="mt-4 text-sm text-charcoal/70">
                        Signed in as <span class="text-charcoal">{{ $user->name }}</span>
                        <span class="text-muted">· {{ $user->email }}</span>
                    </p>
                @endif
            </div>

            <nav class="flex flex-wrap gap-1" aria-label="Account">
                @foreach($links as $key => $link)
                    <a
                        href="{{ $link['href'] }}"
                        @class([
                            'px-4 py-2.5 text-[11px] tracking-[0.14em] uppercase transition border',
                            'border-forest bg-forest text-white' => $active === $key,
                            'border-charcoal/15 text-charcoal/70 hover:border-forest hover:text-forest' => $active !== $key,
                        ])
                    >
                        {{ $link['label'] }}
                    </a>
                @endforeach
                <a href="{{ route('plan') }}" class="px-4 py-2.5 text-[11px] tracking-[0.14em] uppercase border border-charcoal/15 text-charcoal/70 hover:border-forest hover:text-forest transition">
                    Plan a journey
                </a>
            </nav>
        </div>
    </div>
</div>

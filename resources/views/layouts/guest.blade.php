<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="referrer" content="strict-origin-when-cross-origin">

        <title>{{ config('app.name', 'Pearl Pulse Safaris') }} — Account</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=cormorant-garamond:500,600,700|outfit:300,400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
    </head>
    <body class="font-sans text-charcoal antialiased">
        @php
            $guestHero = null;
            try {
                $guestHero = app(\App\Services\SettingService::class)->heroImageUrl();
            } catch (\Throwable) {
                $guestHero = null;
            }
        @endphp
        <div class="min-h-screen flex flex-col lg:flex-row">
            {{-- Brand panel --}}
            <div class="relative lg:w-[46%] min-h-[34vh] lg:min-h-screen bg-forest overflow-hidden flex items-end">
                @if($guestHero)
                    <img src="{{ $guestHero }}" alt="" class="absolute inset-0 h-full w-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-charcoal/85 via-charcoal/45 to-charcoal/20"></div>
                @else
                    <div class="absolute inset-0 bg-gradient-to-br from-forest via-forest-light to-[#3d4f35]"></div>
                    <div class="absolute inset-0 opacity-30" style="background-image: radial-gradient(circle at 20% 20%, rgba(232,223,208,0.25), transparent 45%), radial-gradient(circle at 80% 80%, rgba(166,139,75,0.2), transparent 40%);"></div>
                @endif
                <div class="relative z-10 w-full px-8 py-10 lg:px-12 lg:py-16">
                    <a href="{{ route('home') }}" class="inline-block">
                        <p class="font-display text-3xl md:text-4xl text-white tracking-wide">Pearl Pulse</p>
                        <p class="mt-1 text-[11px] tracking-[0.28em] uppercase text-white/70">Safaris</p>
                    </a>
                    <p class="mt-8 max-w-sm font-display text-2xl md:text-3xl text-white/95 leading-snug">
                        Save journeys. Plan privately. Travel beautifully paced.
                    </p>
                    <p class="mt-4 max-w-sm text-sm text-white/70 leading-relaxed">
                        Guest accounts keep your shortlist in one place until you are ready for a private proposal.
                    </p>
                </div>
            </div>

            {{-- Form panel --}}
            <div class="flex-1 flex items-center justify-center bg-cream px-5 py-12 lg:px-10">
                <div class="w-full max-w-md bg-white border border-charcoal/10 p-6 sm:p-8">
                    {{ $slot }}
                </div>
            </div>
        </div>
        @livewireScripts
    </body>
</html>

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Pearl Pulse Safaris') }} — Admin</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=cormorant-garamond:500,600,700|outfit:300,400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
    </head>
    <body class="font-sans text-charcoal antialiased">
        <div class="min-h-screen flex flex-col lg:flex-row">
            {{-- Brand panel --}}
            <div class="relative lg:w-[46%] min-h-[28vh] lg:min-h-screen bg-forest overflow-hidden flex items-end">
                <div class="absolute inset-0 bg-gradient-to-br from-forest via-forest-light to-[#3d4f35]"></div>
                <div class="absolute inset-0 opacity-30" style="background-image: radial-gradient(circle at 20% 20%, rgba(232,223,208,0.25), transparent 45%), radial-gradient(circle at 80% 80%, rgba(166,139,75,0.2), transparent 40%);"></div>
                <div class="relative z-10 w-full px-8 py-10 lg:px-12 lg:py-16">
                    <a href="{{ route('home') }}" class="inline-block">
                        <p class="font-display text-3xl md:text-4xl text-sand tracking-wide">Pearl Pulse</p>
                        <p class="mt-1 text-[11px] tracking-[0.28em] uppercase text-sand/70">Safaris</p>
                    </a>
                    <p class="mt-8 max-w-sm font-display text-2xl md:text-3xl text-sand/90 leading-snug">
                        East Africa, beautifully paced.
                    </p>
                </div>
            </div>

            {{-- Form panel --}}
            <div class="flex-1 flex items-center justify-center bg-white px-5 py-12 lg:px-10">
                <div class="w-full max-w-md">
                    {{ $slot }}
                </div>
            </div>
        </div>
        @livewireScripts
    </body>
</html>

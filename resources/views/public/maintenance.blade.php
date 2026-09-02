<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }} — Coming soon</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=cormorant-garamond:500,600|outfit:300,400,500&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css'])
</head>
<body class="min-h-screen bg-forest text-sand flex items-center justify-center px-6">
    <div class="max-w-lg text-center">
        <p class="font-display text-4xl md:text-5xl">Pearl Pulse Safaris</p>
        <p class="mt-6 text-sand/80 leading-relaxed">{{ $message }}</p>
        <p class="mt-10 text-xs tracking-[0.2em] uppercase text-sand/50">
            <a href="{{ route('login') }}" class="hover:text-sand">Admin login</a>
        </p>
    </div>
</body>
</html>

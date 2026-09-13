@props(['items' => [], 'tone' => 'light'])

@php
    $base = $tone === 'dark' ? 'text-muted' : 'text-sand/60';
    $link = $tone === 'dark' ? 'hover:text-forest' : 'hover:text-sand';
    $last = $tone === 'dark' ? 'text-forest/80' : 'text-sand/80';
@endphp

@if(count($items))
<nav {{ $attributes->merge(['class' => 'text-xs tracking-[0.16em] uppercase '.$base, 'aria-label' => 'Breadcrumb']) }}>
    <ol class="flex flex-wrap items-center gap-2">
        @foreach($items as $item)
            <li class="flex items-center gap-2">
                @if(!empty($item['href']) && ! $loop->last)
                    <a href="{{ $item['href'] }}" class="{{ $link }}">{{ $item['label'] }}</a>
                @else
                    <span class="{{ $loop->last ? $last : '' }}">{{ $item['label'] }}</span>
                @endif
                @if(! $loop->last)
                    <span aria-hidden="true">/</span>
                @endif
            </li>
        @endforeach
    </ol>
</nav>
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'BreadcrumbList',
    'itemListElement' => collect([['label' => 'Home', 'href' => url('/')]])->concat($items)->values()->map(fn ($item, $i) => [
        '@type' => 'ListItem',
        'position' => $i + 1,
        'name' => $item['label'],
        'item' => $item['href'] ?? url()->current(),
    ])->all(),
], JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE) !!}
</script>
@endif

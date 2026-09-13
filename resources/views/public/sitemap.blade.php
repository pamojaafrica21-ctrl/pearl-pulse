<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    @foreach(['/', '/journeys', '/journeys/finder', '/destinations', '/experiences', '/true-pulse', '/insiders', '/plan-your-journey', '/about', '/about/our-people', '/about/travel-with-a-reason', '/stays'] as $path)
    <url>
        <loc>{{ url($path) }}</loc>
        <changefreq>weekly</changefreq>
        <priority>0.8</priority>
    </url>
    @endforeach
    @foreach($countries as $country)
    <url>
        <loc>{{ route('destinations.country', $country) }}</loc>
        <lastmod>{{ $country->updated_at->toAtomString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.85</priority>
    </url>
    <url>
        <loc>{{ route('journeys.country', $country) }}</loc>
        <changefreq>weekly</changefreq>
        <priority>0.8</priority>
    </url>
    @endforeach
    @foreach($destinations as $destination)
    @if($destination->country)
    <url>
        <loc>{{ route('destinations.show', [$destination->country, $destination]) }}</loc>
        <lastmod>{{ $destination->updated_at->toAtomString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.8</priority>
    </url>
    @endif
    @endforeach
    @foreach($journeys as $journey)
    <url>
        <loc>{{ route('journeys.show', $journey) }}</loc>
        <lastmod>{{ $journey->updated_at->toAtomString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.9</priority>
    </url>
    @endforeach
    @foreach($experiences as $experience)
    <url>
        <loc>{{ route('experiences.show', $experience) }}</loc>
        <lastmod>{{ $experience->updated_at->toAtomString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.75</priority>
    </url>
    @endforeach
    @foreach($articles as $article)
    <url>
        <loc>{{ route('insiders.show', $article) }}</loc>
        <lastmod>{{ $article->updated_at->toAtomString() }}</lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.6</priority>
    </url>
    @endforeach
    @foreach($stays as $stay)
    <url>
        <loc>{{ route('stays.show', $stay) }}</loc>
        <lastmod>{{ $stay->updated_at->toAtomString() }}</lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.5</priority>
    </url>
    @endforeach
    @foreach($pages as $page)
    <url>
        <loc>{{ route('legal', $page->slug) }}</loc>
        <changefreq>yearly</changefreq>
        <priority>0.3</priority>
    </url>
    @endforeach
</urlset>

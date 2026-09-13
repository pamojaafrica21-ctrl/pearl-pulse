@extends('layouts.public')

@section('title', $page->seoTitle())
@section('meta_description', \Illuminate\Support\Str::limit($page->seoDescription(), 160))

@section('content')
<section class="bg-cream pt-16 pb-20">
    <div class="mx-auto max-w-3xl px-5 lg:px-8">
        <h1 class="font-display text-5xl text-forest">{{ $page->title }}</h1>
        <div class="prose-safari mt-10">{!! $page->content !!}</div>
    </div>
</section>
@endsection

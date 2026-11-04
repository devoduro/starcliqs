@extends('layouts.site')

@section('title', $post->title)
@section('description', $post->excerptText(160))
@if ($post->cover_image)
    @section('og_image', media_url($post->cover_image))
@endif

@section('content')
    <section class="page-hero">
        <div class="container" style="max-width:860px">
            <div class="crumbs"><a href="{{ route('home') }}">Home</a><i class="fas fa-chevron-right"></i><a href="{{ route('blog.index') }}">Blog</a></div>
            @if ($post->category)<span class="eyebrow mb-3">{{ $post->category }}</span>@endif
            <h1 class="mt-2">{{ $post->title }}</h1>
            <p class="mt-3 mb-0 small">
                @if ($post->author){{ $post->author }} · @endif{{ $post->published_at->format('d F Y') }} · {{ $post->readingMinutes() }} min read
            </p>
        </div>
    </section>

    <section class="section pt-5">
        <div class="container" style="max-width:860px">
            @if ($post->cover_image)
                <div class="cover"><img src="{{ media_url($post->cover_image) }}" alt="{{ $post->title }}"></div>
            @endif
            <article class="prose">{!! rich_text($post->body) !!}</article>

            <div class="d-flex flex-wrap align-items-center gap-2 mt-5 pt-4 border-top border-themed">
                <span class="small fw-semibold me-2">Share:</span>
                @php $shareUrl = urlencode(route('blog.show', $post)); $shareText = urlencode($post->title); @endphp
                <a class="chip" href="https://wa.me/?text={{ $shareText }}%20{{ $shareUrl }}" target="_blank" rel="noopener"><i class="fab fa-whatsapp"></i> WhatsApp</a>
                <a class="chip" href="https://www.facebook.com/sharer/sharer.php?u={{ $shareUrl }}" target="_blank" rel="noopener"><i class="fab fa-facebook-f"></i> Facebook</a>
                <a class="chip" href="https://www.linkedin.com/sharing/share-offsite/?url={{ $shareUrl }}" target="_blank" rel="noopener"><i class="fab fa-linkedin-in"></i> LinkedIn</a>
                <a class="chip" href="https://x.com/intent/post?text={{ $shareText }}&url={{ $shareUrl }}" target="_blank" rel="noopener"><i class="fab fa-x-twitter"></i> X</a>
            </div>
        </div>

        @if ($recent->isNotEmpty())
            <div class="container mt-5 pt-4">
                <h2 class="h3 mb-4">Keep reading</h2>
                <div class="row g-5">
                    @foreach ($recent as $post)
                        <div class="col-md-6 col-lg-4">@include('site.partials.post-card')</div>
                    @endforeach
                </div>
            </div>
        @endif
    </section>
@endsection

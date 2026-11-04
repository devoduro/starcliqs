@extends('layouts.site')

@section('title', 'Blog')

@section('content')
    @include('site.partials.page-hero', ['crumbs' => ['Blog' => null], 'eyebrow' => 'Insights', 'title' => 'News, tips & ideas', 'lead' => 'Practical advice on technology for schools, churches and businesses — plus news from the StarCliqs team.'])

    <section class="section">
        <div class="container">
            @if ($categories->count() > 1)
                <div class="filter-chips">
                    <a href="{{ route('blog.index') }}" class="chip {{ request('category') ? '' : 'active' }}">All</a>
                    @foreach ($categories as $category)
                        <a href="{{ route('blog.index', ['category' => $category]) }}" class="chip {{ request('category') === $category ? 'active' : '' }}">{{ $category }}</a>
                    @endforeach
                </div>
            @endif
            <div class="row g-5">
                @forelse ($posts as $post)
                    <div class="col-md-6 col-lg-4 reveal">@include('site.partials.post-card')</div>
                @empty
                    <div class="col-12"><div class="empty-state"><i class="fas fa-newspaper d-block"></i>No articles yet — check back soon.</div></div>
                @endforelse
            </div>
            <div class="d-flex justify-content-center mt-5">{{ $posts->links('pagination::bootstrap-5') }}</div>
        </div>
    </section>
@endsection

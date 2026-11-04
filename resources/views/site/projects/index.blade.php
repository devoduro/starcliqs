@extends('layouts.site')

@section('title', 'Projects')

@section('content')
    @include('site.partials.page-hero', ['crumbs' => ['Projects' => null], 'eyebrow' => 'Our work', 'title' => 'Projects & case studies', 'lead' => 'Real systems for real organisations — schools, assemblies, hospitals and businesses.'])

    <section class="section">
        <div class="container">
            @if ($categories->count() > 1)
                <div class="filter-chips">
                    <a href="{{ route('projects.index') }}" class="chip {{ request('category') ? '' : 'active' }}">All</a>
                    @foreach ($categories as $category)
                        <a href="{{ route('projects.index', ['category' => $category]) }}" class="chip {{ request('category') === $category ? 'active' : '' }}">{{ $category }}</a>
                    @endforeach
                </div>
            @endif
            <div class="row g-4">
                @forelse ($projects as $project)
                    <div class="col-md-6 col-lg-4 reveal">@include('site.partials.project-card')</div>
                @empty
                    <div class="col-12"><div class="empty-state"><i class="fas fa-briefcase d-block"></i>No projects in this category yet.</div></div>
                @endforelse
            </div>
            <div class="d-flex justify-content-center mt-5">{{ $projects->links('pagination::bootstrap-5') }}</div>
        </div>
    </section>
@endsection

@extends('layouts.site')

@section('title', $project->title)
@section('description', $project->summary)
@if ($project->image)
    @section('og_image', media_url($project->image))
@endif

@section('content')
    @include('site.partials.page-hero', ['crumbs' => ['Projects' => route('projects.index'), $project->title => null], 'eyebrow' => $project->category ?: 'Project', 'title' => $project->title, 'lead' => $project->summary])

    <section class="section">
        <div class="container">
            <div class="row g-5">
                <div class="col-lg-8">
                    @if ($project->image)
                        <div class="cover"><img src="{{ media_url($project->image) }}" alt="{{ $project->title }}"></div>
                    @endif
                    <div class="prose">{!! rich_text($project->body ?: $project->summary) !!}</div>
                </div>
                <div class="col-lg-4">
                    <div class="side-card">
                        <h5>Project details</h5>
                        @if ($project->client)<div class="kv"><span>Client</span><strong>{{ $project->client }}</strong></div>@endif
                        @if ($project->category)<div class="kv"><span>Category</span><strong>{{ $project->category }}</strong></div>@endif
                        @if ($project->completed_on)<div class="kv"><span>Completed</span><strong>{{ $project->completed_on->format('F Y') }}</strong></div>@endif
                        @if (! empty($project->technologies))
                            <div class="pt-3">
                                <span class="small opacity-75 d-block mb-2">Delivered</span>
                                <div class="d-flex flex-wrap gap-2">
                                    @foreach ($project->technologies as $tech)<span class="tag tag-gold">{{ $tech }}</span>@endforeach
                                </div>
                            </div>
                        @endif
                        @if ($project->project_url)
                            <a href="{{ $project->project_url }}" target="_blank" rel="noopener" class="btn-dark-soft w-100 justify-content-center mt-4">Visit project <i class="fas fa-arrow-up-right-from-square"></i></a>
                        @endif
                    </div>
                    <div class="side-card accent">
                        <h5>Want results like these?</h5>
                        <p class="small">Tell us about your organisation and we'll suggest the right solution.</p>
                        <a href="{{ route('home') }}#contact" class="btn-gold w-100 justify-content-center">Start a project</a>
                    </div>
                </div>
            </div>

            @if ($related->isNotEmpty())
                <h2 class="h3 mt-5 pt-4 mb-4">More projects</h2>
                <div class="row g-4">
                    @foreach ($related as $project)
                        <div class="col-md-6 col-lg-4">@include('site.partials.project-card')</div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>
@endsection

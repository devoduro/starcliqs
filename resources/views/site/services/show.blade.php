@extends('layouts.site')

@section('title', $service->title)
@section('description', $service->summary)

@section('content')
    @include('site.partials.page-hero', ['crumbs' => ['Services' => route('home') . '#services', $service->title => null], 'eyebrow' => 'Service', 'title' => $service->title, 'lead' => $service->summary])

    <section class="section">
        <div class="container">
            <div class="row g-5">
                <div class="col-lg-8">
                    @if ($service->image)
                        <div class="cover"><img src="{{ media_url($service->image) }}" alt="{{ $service->title }}"></div>
                    @endif
                    @if (! empty($service->features))
                        <h2 class="h3 mb-4">What's included</h2>
                        <div class="row g-3 mb-5">
                            @foreach ($service->features as $feature)
                                <div class="col-sm-6"><div class="feature-box"><i class="fas fa-circle-check"></i><span>{{ $feature }}</span></div></div>
                            @endforeach
                        </div>
                    @endif
                    @if ($service->body)
                        <div class="prose">{!! rich_text($service->body) !!}</div>
                    @endif
                </div>
                <div class="col-lg-4">
                    <div class="side-card accent text-center">
                        <span class="service-icon mx-auto mb-3"><i class="fas {{ $service->icon }}"></i></span>
                        <h5>Need {{ \Illuminate\Support\Str::lower($service->title) }}?</h5>
                        <p class="small opacity-75">Tell us what you have in mind and get a free quote.</p>
                        <a href="{{ route('home') }}#contact" class="btn-gold w-100 justify-content-center">Request a quote</a>
                    </div>
                    @if ($others->isNotEmpty())
                        <div class="side-card">
                            <h5>Other services</h5>
                            @foreach ($others as $other)
                                <a href="{{ route('services.show', $other) }}" class="kv text-decoration-none"><span><i class="fas {{ $other->icon }} text-gold me-2"></i>{{ $other->title }}</span><i class="fas fa-chevron-right small opacity-50"></i></a>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection

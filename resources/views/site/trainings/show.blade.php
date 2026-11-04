@extends('layouts.site')

@section('title', $training->title)
@section('description', $training->summary)
@if ($training->image)
    @section('og_image', media_url($training->image))
@endif

@section('content')
    @include('site.partials.page-hero', ['crumbs' => ['Academy' => route('academy'), $training->title => null], 'eyebrow' => $training->level ?: $training->modeLabel() . ' programme', 'title' => $training->title, 'lead' => $training->summary])

    <section class="section">
        <div class="container">
            <div class="row g-5">
                <div class="col-lg-7">
                    @if ($training->image)
                        <div class="cover"><img src="{{ media_url($training->image) }}" alt="{{ $training->title }}"></div>
                    @endif
                    <div class="row g-3 mb-5">
                        <div class="col-6 col-md-3"><div class="stat-box"><div class="l">Starts</div><div class="fw-bold mt-1">{{ $training->starts_on?->format('d M Y') ?? 'Anytime' }}</div></div></div>
                        <div class="col-6 col-md-3"><div class="stat-box"><div class="l">Duration</div><div class="fw-bold mt-1">{{ $training->duration ?: 'Flexible' }}</div></div></div>
                        <div class="col-6 col-md-3"><div class="stat-box"><div class="l">Mode</div><div class="fw-bold mt-1">{{ $training->modeLabel() }}</div></div></div>
                        <div class="col-6 col-md-3"><div class="stat-box"><div class="l">Fee</div><div class="fw-bold mt-1">{{ $training->feeLabel() }}</div></div></div>
                    </div>
                    @if ($training->registration_closes_on || $training->schedule)
                        <ul class="program-meta mb-5">
                            @if ($training->registration_closes_on)
                                <li class="{{ $training->registrationOpen() ? 'deadline' : 'closed' }}"><i class="far fa-clock"></i>
                                    {{ $training->registrationOpen() ? 'Registration closes ' . $training->registration_closes_on->format('l, j F Y') : 'Registration closed' }}</li>
                            @endif
                            @if ($training->schedule)<li><i class="far fa-calendar-check"></i> {{ $training->schedule }}</li>@endif
                        </ul>
                    @endif
                    @if (! empty($training->outline))
                        <h2 class="h3 mb-4">Course outline</h2>
                        <ol class="list-unstyled mb-5">
                            @foreach ($training->outline as $i => $module)
                                <li class="d-flex gap-3 align-items-center py-3 outline-row">
                                    <span class="outline-num">{{ $i + 1 }}</span>
                                    <span class="fw-semibold">{{ $module }}</span>
                                </li>
                            @endforeach
                        </ol>
                    @endif
                    @if ($training->body)
                        <div class="prose">{!! rich_text($training->body) !!}</div>
                    @endif
                </div>
                <div class="col-lg-5" id="register">
                    <div style="position:sticky;top:100px">
                        @unless ($training->registrationOpen())
                            <div class="side-card accent mb-3"><i class="fas fa-circle-info text-gold me-2"></i>Registration for this cohort has closed. Register your interest and we'll tell you about the next one.</div>
                        @endunless
                        @include('site.partials.enquiry-form', ['course' => $training])
                    </div>
                </div>
            </div>

            @if ($others->isNotEmpty())
                <h2 class="h3 mt-5 pt-4 mb-4">Other courses</h2>
                <div class="row g-4">
                    @foreach ($others as $training)
                        <div class="col-md-6 col-lg-4">@include('site.partials.training-card')</div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>
@endsection

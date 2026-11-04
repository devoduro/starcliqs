@extends('layouts.site')

@section('title', 'Academy')
@section('description', setting('academy_subtitle', 'Hands-on technology programmes from StarCliqs.'))

@php
    $siteName = setting('site_name', 'StarCliqs');
    $onsitePoints = \App\Models\Setting::lines('academy_onsite_points');
    $onlinePoints = \App\Models\Setting::lines('academy_online_points');
    $perks = collect(\App\Models\Setting::lines('academy_perks'))->map(function ($line) {
        [$title, $text] = array_pad(array_map('trim', explode('|', $line, 2)), 2, '');
        return ['title' => $title, 'text' => $text];
    });
    $stats = array_filter([
        ['n' => setting('academy_completion_rate'), 'suffix' => '%', 'label' => 'Completion rate'],
        ['n' => setting('academy_students_trained'), 'suffix' => '+', 'label' => 'Students trained'],
    ], fn ($s) => $s['n'] !== null);
@endphp

@section('content')
    <!-- Hero -->
    <section class="academy-hero">
        <div class="hero-bg"></div>
        <div class="container">
            <div class="row align-items-center g-5">
                <div class="col-lg-6" data-aos="fade-up">
                    <span class="kicker">{{ $siteName }} Academy</span>
                    <h1 class="academy-title">
                        {{ setting('academy_title', 'Learn tech by') }}
                        <span class="hl">{{ setting('academy_highlight', 'building') }}</span>
                        {{ setting('academy_title_end', 'real products.') }}
                    </h1>
                    <p class="academy-lead">{{ setting('academy_subtitle') }}</p>
                    <div class="d-flex flex-wrap gap-2 mb-4">
                        <span class="pill"><i class="fas fa-building"></i> Onsite classes{{ setting('academy_onsite_location') ? ' · ' . setting('academy_onsite_location') : '' }}</span>
                        <span class="pill"><i class="fas fa-laptop"></i> Online classes</span>
                    </div>
                    <div class="d-flex flex-wrap gap-3">
                        <a href="#programs" class="btn btn-primary-custom">View Programs <i class="fas fa-arrow-right"></i></a>
                        <a href="{{ route('home') }}#contact" class="btn btn-outline-custom">Talk to us</a>
                    </div>
                </div>
                <div class="col-lg-6" data-aos="fade-left">
                    @if ($programmes->isNotEmpty())
                        <div class="track-panel">
                            @foreach ($programmes->take(4) as $programme)
                                <a href="{{ route('trainings.show', $programme) }}" class="track-tile">
                                    <span class="track-num">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                                    <i class="fas {{ $programme->icon ?: 'fa-code' }} track-icon"></i>
                                    <span class="track-name">{{ $programme->title }}</span>
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <!-- Programs -->
    <section id="programs" class="section-padding bg-light-custom">
        <div class="container">
            <div class="row align-items-end g-4 mb-5">
                <div class="col-lg-7" data-aos="fade-up">
                    <span class="kicker">Programs</span>
                    <h2 class="mb-0">Pick your <span class="hl">path</span></h2>
                </div>
                <div class="col-lg-5" data-aos="fade-up">
                    <p class="text-muted-custom mb-0">Every programme includes live instruction, project reviews and a certificate on completion.</p>
                </div>
            </div>

            <div class="row g-4">
                @forelse ($programmes as $programme)
                    <div class="col-lg-6" data-aos="fade-up" data-aos-delay="{{ ($loop->index % 2) * 100 }}">
                        <div class="program-card">
                            <div class="d-flex justify-content-between align-items-start gap-3 mb-4">
                                <span class="program-icon"><i class="fas {{ $programme->icon ?: 'fa-code' }}"></i></span>
                                @if ($programme->level)<span class="level-badge">{{ $programme->level }}</span>@endif
                            </div>
                            <h3 class="program-title">{{ $programme->title }}</h3>
                            <p class="program-summary">{{ $programme->summary }}</p>
                            <ul class="program-meta">
                                @if ($programme->registration_closes_on)
                                    <li class="{{ $programme->registrationOpen() ? 'deadline' : 'closed' }}">
                                        <i class="far fa-clock"></i>
                                        {{ $programme->registrationOpen() ? 'Registration closes ' . $programme->registration_closes_on->format('M j') : 'Registration closed' }}
                                    </li>
                                @endif
                                @if ($programme->starts_on)
                                    <li><i class="far fa-calendar"></i> Course start date — {{ $programme->starts_on->format('l, j F Y') }}</li>
                                @endif
                                @if ($programme->schedule)
                                    <li><i class="far fa-calendar-check"></i> {{ $programme->schedule }}</li>
                                @endif
                                <li><i class="fas fa-location-dot"></i> {{ $programme->modeLabel() }}{{ $programme->mode !== 'online' && setting('academy_onsite_location') ? ' · ' . setting('academy_onsite_location') : '' }}</li>
                            </ul>
                            <div class="program-foot">
                                <div>
                                    <span class="tuition-label">Tuition{{ $programme->duration ? ' · ' . $programme->duration : '' }}</span>
                                    <span class="tuition">{{ $programme->feeLabel() }}</span>
                                </div>
                                <a href="{{ route('trainings.show', $programme) }}#register" class="btn btn-primary-custom">View &amp; enroll <i class="fas fa-arrow-right"></i></a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12"><div class="empty-state"><i class="fas fa-graduation-cap d-block"></i>New programmes are being scheduled. Subscribe below to hear first.</div></div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Learning format -->
    <section class="section-padding">
        <div class="container">
            <div class="text-center mb-5" data-aos="fade-up">
                <span class="kicker justify-content-center">Learning format</span>
                <h2>Learn your <span class="hl">way</span></h2>
                <p class="text-muted-custom mx-auto mt-3" style="max-width:620px">Join us in person{{ setting('academy_onsite_location') ? ' in ' . setting('academy_onsite_location') : '' }} or from anywhere. Same curriculum, same instructors, same hands-on projects.</p>
            </div>
            <div class="row g-4">
                <div class="col-lg-6" data-aos="fade-up">
                    <div class="format-card">
                        <span class="program-icon"><i class="fas fa-building"></i></span>
                        <h3 class="format-title">Onsite Classes</h3>
                        @if (setting('academy_onsite_location'))<span class="format-place">{{ setting('academy_onsite_location') }}</span>@endif
                        <p>{{ setting('academy_onsite_text') }}</p>
                        @if ($onsitePoints)
                            <ul class="tick-list">
                                @foreach ($onsitePoints as $point)<li>{{ $point }}</li>@endforeach
                            </ul>
                        @endif
                    </div>
                </div>
                <div class="col-lg-6" data-aos="fade-up" data-aos-delay="100">
                    <div class="format-card">
                        <span class="program-icon"><i class="fas fa-laptop"></i></span>
                        <h3 class="format-title">Online Classes</h3>
                        <span class="format-place">Anywhere</span>
                        <p>{{ setting('academy_online_text') }}</p>
                        @if ($onlinePoints)
                            <ul class="tick-list">
                                @foreach ($onlinePoints as $point)<li>{{ $point }}</li>@endforeach
                            </ul>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Outcomes -->
    @if ($perks->isNotEmpty() || $stats)
        <section class="section-padding bg-light-custom">
            <div class="container">
                <div class="row g-5 align-items-center">
                    <div class="col-lg-5" data-aos="fade-right">
                        <span class="kicker">Outcomes</span>
                        <h2>Real skills, real <span class="hl">results</span></h2>
                        <p class="text-muted-custom mt-3">Our learners don't just finish courses. They build working projects, grow a portfolio and leave ready to work.</p>
                        @if ($stats)
                            <div class="d-flex flex-wrap gap-5 mt-4">
                                @foreach ($stats as $stat)
                                    <div>
                                        <span class="counter-number" data-target="{{ (int) $stat['n'] }}" data-suffix="{{ $stat['suffix'] }}">{{ number_format((int) $stat['n']) }}{{ $stat['suffix'] }}</span>
                                        <span class="counter-label">{{ $stat['label'] }}</span>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                    <div class="col-lg-7">
                        <div class="row g-3">
                            @foreach ($perks as $perk)
                                <div class="col-sm-6" data-aos="fade-up" data-aos-delay="{{ $loop->index * 75 }}">
                                    <div class="perk-card">
                                        <h4>{{ $perk['title'] }}</h4>
                                        @if ($perk['text'])<p class="mb-0">{{ $perk['text'] }}</p>@endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif

    <!-- Call to action -->
    <section class="section-padding">
        <div class="container">
            <div class="academy-cta" data-aos="fade-up">
                <div>
                    <h2 class="mb-2">Ready to start building?</h2>
                    <p class="mb-0">Reserve your seat in the next cohort, or ask us which programme suits you.</p>
                </div>
                <div class="d-flex flex-wrap gap-3">
                    <a href="#programs" class="btn btn-dark-solid">Choose a programme <i class="fas fa-arrow-right"></i></a>
                    @if (setting('whatsapp'))
                        <a href="https://wa.me/{{ setting('whatsapp') }}" target="_blank" rel="noopener" class="btn btn-dark-outline"><i class="fab fa-whatsapp"></i> Ask on WhatsApp</a>
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection

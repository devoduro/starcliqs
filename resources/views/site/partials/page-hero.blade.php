<section class="page-hero">
    <div class="hero-bg"></div>
    <div class="hero-shape" style="top: 10%; right: 8%; width: 260px; height: 260px;"></div>
    <div class="hero-floating-icon" style="top: 30%; right: 20%; font-size: 3rem; animation: floatIcon2 22s infinite linear;"><i class="fas fa-star"></i></div>
    <div class="container position-relative">
        <div class="crumbs">
            <a href="{{ route('home') }}">Home</a>
            @foreach ($crumbs ?? [] as $label => $url)
                <i class="fas fa-chevron-right"></i>
                @if ($url)<a href="{{ $url }}">{{ $label }}</a>@else<span>{{ $label }}</span>@endif
            @endforeach
        </div>
        @isset($eyebrow)<span class="eyebrow mb-2">{{ $eyebrow }}</span>@endisset
        <h1 class="mt-1 mb-0">{{ $title }}</h1>
        @isset($lead)<p class="lead mt-3 mb-0">{{ $lead }}</p>@endisset
    </div>
</section>

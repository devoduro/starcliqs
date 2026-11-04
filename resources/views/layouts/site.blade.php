@php
    $siteName = setting('site_name', 'StarCliqs');
    $home = route('home');
    $onHome = request()->routeIs('home');
    $socials = array_filter([
        'facebook-f' => setting('facebook_url'),
        'x-twitter' => setting('x_url'),
        'linkedin-in' => setting('linkedin_url'),
        'instagram' => setting('instagram_url'),
        'youtube' => setting('youtube_url'),
    ]);
    $footerProducts = \App\Models\Product::published()->ordered()->take(5)->get(['name', 'slug']);
@endphp
<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@hasSection('title')@yield('title') | {{ $siteName }}@else{{ $siteName }} | {{ setting('tagline', 'IT consultancy & solutions') }}@endif</title>
    <meta name="description" content="@yield('description', setting('meta_description'))">
    <meta property="og:title" content="@yield('title', $siteName)">
    <meta property="og:description" content="@yield('description', setting('meta_description'))">
    <meta property="og:image" content="@yield('og_image', asset('images/logo.webp'))">
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    {{-- Apply the saved theme before anything paints (dark by default, like the original design). --}}
    <script>
        try { document.documentElement.setAttribute('data-theme', localStorage.getItem('theme') || 'dark'); } catch (e) {}
    </script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@400;500;600;700;800&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/site.css') }}?v={{ @filemtime(public_path('css/site.css')) }}">
    @stack('head')
</head>
<body>
    <nav class="navbar navbar-expand-lg fixed-top animate-slide-down">
        <div class="container">
            <a class="navbar-brand py-0" href="{{ $home }}" aria-label="{{ $siteName }} home">
                <img src="{{ asset('images/logo.webp') }}" alt="{{ $siteName }}" class="logo-light-theme" width="117" height="44">
                <img src="{{ asset('images/logo-light.webp') }}" alt="{{ $siteName }}" class="logo-dark-theme" width="117" height="44">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
                <ul class="navbar-nav align-items-lg-center">
                    <li class="nav-item"><a class="nav-link {{ $onHome ? 'active' : '' }}" href="{{ $home }}#home">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ $home }}#about">About</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ $home }}#services">Services</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}" href="{{ $home }}#products">Products</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('projects.*') ? 'active' : '' }}" href="{{ $home }}#projects">Projects</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('academy', 'trainings.*') ? 'active' : '' }}" href="{{ route('academy') }}">Academy</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('blog.*') ? 'active' : '' }}" href="{{ $home }}#blog">Blog</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ $home }}#contact">Contact</a></li>
                    <li class="nav-item ms-lg-3 mt-2 mt-lg-0">
                        <button class="theme-switch" id="theme-toggle" type="button" aria-label="Toggle dark mode">
                            <i class="fas fa-moon"></i>
                        </button>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <main>
        @yield('content')
    </main>

    <footer>
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4 mb-lg-0">
                    <a class="footer-logo mb-3 d-inline-block" href="{{ $home }}"><img src="{{ asset('images/logo-light.webp') }}" alt="{{ $siteName }}"></a>
                    <p class="small mb-4">{{ setting('meta_description', 'Delivering reliable, scalable, and user-friendly IT systems.') }}</p>
                    @if ($socials)
                        <div class="social-icons">
                            @foreach ($socials as $icon => $url)
                                <a href="{{ $url }}" target="_blank" rel="noopener" aria-label="{{ $icon }}"><i class="fab fa-{{ $icon }}"></i></a>
                            @endforeach
                        </div>
                    @endif
                </div>
                <div class="col-lg-2 col-6 mb-4 mb-lg-0">
                    <h5 class="mb-3 fs-6 text-uppercase">Company</h5>
                    <a href="{{ $home }}#about" class="footer-link">About Us</a>
                    <a href="{{ $home }}#services" class="footer-link">Services</a>
                    <a href="{{ route('projects.index') }}" class="footer-link">Projects</a>
                    <a href="{{ route('academy') }}" class="footer-link">Academy</a>
                    <a href="{{ route('blog.index') }}" class="footer-link">Blog</a>
                    <a href="{{ $home }}#contact" class="footer-link">Contact</a>
                </div>
                <div class="col-lg-2 col-6 mb-4 mb-lg-0">
                    <h5 class="mb-3 fs-6 text-uppercase">Products</h5>
                    @foreach ($footerProducts as $p)
                        <a href="{{ route('products.show', $p) }}" class="footer-link">{{ $p->name }}</a>
                    @endforeach
                </div>
                <div class="col-lg-4">
                    <h5 class="mb-3 fs-6 text-uppercase">Newsletter</h5>
                    <p class="small">Subscribe to get the latest news and updates.</p>
                    <form method="POST" action="{{ route('subscribe') }}" class="input-group mb-3">
                        @csrf
                        <input type="email" name="email" class="form-control newsletter-input" placeholder="Enter email" aria-label="Email address" required>
                        <button class="btn btn-primary-custom newsletter-btn" type="submit">Subscribe</button>
                    </form>
                    <div class="small" style="color:#a0a0a0">
                        @if (setting('phone'))<div class="mb-1"><i class="fas fa-phone-alt text-primary-custom me-2"></i><a href="tel:{{ setting('phone') }}">{{ setting('phone') }}</a></div>@endif
                        @if (setting('email'))<div><i class="fas fa-envelope text-primary-custom me-2"></i><a href="mailto:{{ setting('email') }}">{{ setting('email') }}</a></div>@endif
                    </div>
                </div>
            </div>
            <hr style="border-color: rgba(255,255,255,0.1); margin: 30px 0;">
            @if (setting('dpc_show') === '1')
                <div class="text-center mb-4">
                    <a href="{{ setting('dpc_url', 'https://www.dataprotection.org.gh') }}" target="_blank" rel="noopener" class="dpc-badge" title="Registered with the Data Protection Commission, Ghana">
                        <span class="dpc-logo">
                            @if (setting('dpc_logo'))
                                <img src="{{ media_url(setting('dpc_logo')) }}" alt="Data Protection Commission, Ghana">
                            @else
                                <i class="fas fa-shield-halved"></i>
                            @endif
                        </span>
                        <span class="text-start">
                            <strong>Registered with the Data Protection Commission</strong>
                            <small>Ghana &middot; Data Protection Act, 2012 (Act 843)@if (setting('dpc_registration_number')) &middot; Reg. No. {{ setting('dpc_registration_number') }}@endif</small>
                        </span>
                    </a>
                </div>
            @endif
            <div class="text-center small text-muted">
                &copy; {{ date('Y') }} {{ $siteName }}. All Rights Reserved.
            </div>
        </div>
    </footer>

    @if (setting('whatsapp'))
        <a href="https://wa.me/{{ setting('whatsapp') }}" target="_blank" rel="noopener" class="whatsapp-float" aria-label="Chat on WhatsApp"><i class="fab fa-whatsapp"></i></a>
    @endif

    <!-- Scroll progress / back to top -->
    <div class="progress-wrap" role="button" aria-label="Back to top">
        <svg class="progress-circle svg-content" width="100%" height="100%" viewBox="-1 -1 102 102">
            <path d="M50,1 a49,49 0 0,1 0,98 a49,49 0 0,1 0,-98" />
        </svg>
    </div>

    @if (session('success'))
        <div class="flash" role="status"><i class="fas fa-circle-check mt-1"></i><div>{{ session('success') }}</div></div>
    @endif
    @if ($errors->any())
        <div class="flash err" role="alert"><i class="fas fa-circle-exclamation mt-1"></i><div>{{ $errors->first() }}</div></div>
    @endif

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/vanilla-tilt/1.8.0/vanilla-tilt.min.js"></script>
    <script src="{{ asset('js/site.js') }}?v={{ @filemtime(public_path('js/site.js')) }}"></script>
    @stack('scripts')
</body>
</html>

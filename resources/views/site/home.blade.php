@extends('layouts.site')

@php
    $highlights = \App\Models\Setting::lines('about_highlights');
    $stats = array_values(array_filter([
        ['n' => setting('stat_years'), 'suffix' => '+', 'label' => 'Years'],
        ['n' => setting('stat_projects'), 'suffix' => '+', 'label' => 'Projects'],
        ['n' => setting('stat_clients'), 'suffix' => '+', 'label' => 'Clients'],
        ['n' => setting('stat_success'), 'suffix' => '%', 'label' => 'Success'],
    ], fn ($s) => $s['n'] !== null));
    $statCol = count($stats) >= 4 ? 'col-6 col-md-3' : 'col-6 col-md-4';
    $categories = $projects->pluck('category')->filter()->unique()->values();
    $aboutTiles = $services->take(4);
@endphp

@section('content')
    <!-- Hero Section -->
    <section id="home" class="hero-section">
        <div class="hero-bg"></div>
        <div class="hero-shape" style="top: 10%; right: 10%; width: 300px; height: 300px;"></div>
        <div class="hero-shape" style="bottom: 10%; left: 10%; width: 200px; height: 200px; animation-delay: 1s;"></div>

        <div class="hero-floating-icon" style="top: 15%; left: 15%; font-size: 3rem; animation: floatIcon1 20s infinite linear;"><i class="fas fa-code"></i></div>
        <div class="hero-floating-icon" style="top: 20%; right: 15%; font-size: 4rem; animation: floatIcon2 25s infinite linear;"><i class="fas fa-network-wired"></i></div>
        <div class="hero-floating-icon" style="bottom: 20%; left: 20%; font-size: 2.5rem; animation: floatIcon3 18s infinite linear;"><i class="fas fa-database"></i></div>
        <div class="hero-floating-icon" style="bottom: 25%; right: 10%; font-size: 3.5rem; animation: floatIcon1 22s infinite linear;"><i class="fas fa-laptop-code"></i></div>
        <div class="hero-floating-icon" style="top: 50%; left: 5%; font-size: 2rem; animation: floatIcon2 30s infinite linear;"><i class="fas fa-mobile-alt"></i></div>
        <div class="hero-floating-icon" style="top: 40%; right: 5%; font-size: 2.8rem; animation: floatIcon3 24s infinite linear;"><i class="fas fa-star"></i></div>

        <div class="container">
            <div class="row align-items-center justify-content-center text-center">
                <div class="col-lg-10 hero-content text-center" data-aos="fade-up">
                    @if (setting('hero_eyebrow'))
                        <span class="hero-eyebrow animate-slide-down"><i class="fas fa-star"></i> {{ setting('hero_eyebrow') }}</span>
                    @endif
                    <h1 class="animate-slide-down" style="animation-delay: 0.2s; min-height: 1.2em;">
                        <span id="typewriter-text"><span data-type="{{ setting('hero_title', 'Empowering Ghana with') }} ">{{ setting('hero_title', 'Empowering Ghana with') }} </span><span class="hl" data-type="{{ setting('hero_highlight', 'Digital Innovation') }}">{{ setting('hero_highlight', 'Digital Innovation') }}</span></span><span class="typewriter-cursor"></span>
                    </h1>
                    <p class="mx-auto fade-in-late" style="animation-delay: 0.4s;">{{ setting('hero_subtitle', 'Reliable, Scalable, and User-Friendly IT Systems tailored for your success.') }}</p>
                    <div class="d-flex flex-wrap justify-content-center gap-3 fade-in-late" style="animation-delay: 0.6s;">
                        <a href="#projects" class="btn btn-primary-custom animate-pulse">View Our Work</a>
                        <a href="#contact" class="btn btn-outline-custom">Get a Quote</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="section-padding">
        <div class="hero-floating-icon" style="top: 10%; right: 5%; font-size: 2rem; animation: floatIcon1 15s infinite linear;"><i class="fas fa-user-secret"></i></div>
        <div class="hero-floating-icon" style="bottom: 15%; left: 5%; font-size: 3rem; animation: floatIcon2 18s infinite linear;"><i class="fas fa-bullseye"></i></div>

        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-5 mb-lg-0" data-aos="fade-right">
                    <div class="about-img-grid">
                        @forelse ($aboutTiles as $i => $service)
                            <a href="{{ route('services.show', $service) }}" class="about-img-item animate-float" style="animation-delay: {{ [0, 1, 0.5, 1.5][$i] ?? 0 }}s;">
                                <i class="fas {{ $service->icon }}"></i><span>{{ $service->title }}</span>
                            </a>
                        @empty
                            <div class="about-img-item animate-float"><i class="fas fa-code"></i></div>
                            <div class="about-img-item animate-float" style="animation-delay: 1s;"><i class="fas fa-server"></i></div>
                            <div class="about-img-item animate-float" style="animation-delay: .5s;"><i class="fas fa-users-cog"></i></div>
                            <div class="about-img-item animate-float" style="animation-delay: 1.5s;"><i class="fas fa-chart-line"></i></div>
                        @endforelse
                    </div>
                </div>
                <div class="col-lg-6 ps-lg-5" data-aos="fade-left">
                    <h5 class="text-primary-custom text-uppercase ls-2">Who We Are</h5>
                    <h2 class="mb-4">{{ setting('about_title', 'Decade of Excellence in IT Solutions') }}</h2>
                    <p class="mb-4">{{ setting('about_body') }}</p>

                    @if ($stats)
                        <div class="row g-3 mb-4">
                            @foreach ($stats as $stat)
                                <div class="{{ $statCol }}">
                                    <div class="stats-item">
                                        <span class="counter-number" data-target="{{ (int) $stat['n'] }}" data-suffix="{{ $stat['suffix'] }}">{{ (int) $stat['n'] }}{{ $stat['suffix'] }}</span>
                                        <span class="counter-label">{{ $stat['label'] }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    @if ($highlights)
                        <ul class="list-unstyled feature-list">
                            @foreach ($highlights as $item)
                                <li><i class="fas fa-check-circle"></i> {{ $item }}</li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    @if ($services->isNotEmpty())
        <section id="services" class="section-padding bg-light-custom">
            <div class="hero-floating-icon" style="top: 10%; left: 10%; font-size: 2.5rem; animation: floatIcon3 20s infinite linear;"><i class="fas fa-tv"></i></div>
            <div class="hero-floating-icon" style="bottom: 20%; right: 10%; font-size: 2rem; animation: floatIcon1 25s infinite linear;"><i class="fas fa-tools"></i></div>

            <div class="container">
                <div class="text-center mb-5" data-aos="fade-up">
                    <h5 class="text-primary-custom text-uppercase">What We Do</h5>
                    <h2>Our <span class="hl">Services</span></h2>
                    @if (setting('services_intro'))
                        <p class="opacity-75 mt-3 mx-auto" style="max-width:560px">{{ setting('services_intro') }}</p>
                    @endif
                </div>

                <div class="row g-4">
                    @foreach ($services as $service)
                        <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="{{ min(($loop->index % 3 + 1) * 100, 300) }}">
                            <a href="{{ route('services.show', $service) }}" class="glass-card service-card text-center">
                                <div class="service-icon mx-auto"><i class="fas {{ $service->icon }}"></i></div>
                                <h3 class="service-title">{{ $service->title }}</h3>
                                <p class="small opacity-75">{{ $service->summary }}</p>
                                @if (! empty($service->features))
                                    <div><ul class="service-feats">
                                        @foreach (array_slice($service->features, 0, 3) as $feature)
                                            <li><i class="fas fa-check"></i>{{ $feature }}</li>
                                        @endforeach
                                    </ul></div>
                                @endif
                                <span class="link-arrow justify-content-center">Learn more <i class="fas fa-arrow-right"></i></span>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <!-- Products Section -->
    @if ($products->isNotEmpty())
        <section id="products" class="products-section">
            <div class="hero-floating-icon" style="top: 15%; right: 15%; font-size: 3rem; animation: floatIcon2 22s infinite linear;"><i class="fas fa-box-open"></i></div>
            <div class="hero-floating-icon" style="bottom: 10%; left: 10%; font-size: 2.5rem; animation: floatIcon3 19s infinite linear;"><i class="fas fa-shipping-fast"></i></div>

            <div class="container">
                <div class="row mb-5 justify-content-center">
                    <div class="col-lg-8 text-center" data-aos="fade-up">
                        <h5 class="text-primary-custom text-uppercase ls-2">Our Innovations</h5>
                        <h2 class="display-5 fw-bold">Specialized <span class="hl">Products</span></h2>
                        <p class="mt-3 opacity-75">Cutting-edge solutions designed to solve real-world problems. Explore our suite of specialized software products.</p>
                    </div>
                </div>

                <div class="product-grid">
                    @foreach ($products as $product)
                        <div class="product-card {{ $product->is_featured ? 'featured' : '' }}" data-aos="fade-up" data-aos-delay="{{ min(($loop->index % 3 + 1) * 100, 300) }}">
                            @if ($product->is_featured)<span class="product-badge">Featured</span>@endif
                            <div class="product-icon-wrapper"><i class="fas {{ $product->icon }}"></i></div>
                            <div class="product-content">
                                <h4>{{ $product->name }}</h4>
                                @if ($product->tagline)<div class="product-tagline">{{ $product->tagline }}</div>@endif
                                <p>{{ $product->summary }}</p>
                                @if (! empty($product->features))
                                    <ul class="product-feats">
                                        @foreach (array_slice($product->features, 0, 4) as $feature)
                                            <li><i class="fas fa-star"></i>{{ $feature }}</li>
                                        @endforeach
                                    </ul>
                                @endif
                                <div class="product-foot">
                                    <a href="{{ route('products.show', $product) }}" class="product-link">Explore {{ $product->name }} <i class="fas fa-arrow-right"></i></a>
                                    @if ($product->price_label)<span class="product-price">{{ $product->price_label }}</span>@endif
                                </div>
                                @if ($product->linkLabel())
                                    <a href="{{ $product->demo_url }}" target="_blank" rel="noopener" class="product-site-link"><i class="fas fa-globe"></i> Visit {{ $product->linkLabel() }} <i class="fas fa-arrow-up-right-from-square"></i></a>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <!-- Projects Section -->
    <section id="projects" class="section-padding bg-light-custom">
        <div class="container">
            <div class="row mb-5">
                <div class="col-lg-6 mx-auto text-center" data-aos="fade-up">
                    <h5 class="text-primary-custom text-uppercase">Why Choose Us</h5>
                    <h2>Trusted <span class="hl">Nationwide</span></h2>
                    <p class="mt-3">{{ setting('site_name', 'StarCliqs') }} is trusted for delivering reliable, scalable, and user-friendly IT systems.</p>
                </div>
            </div>

            @if ($projects->isNotEmpty())
                @if ($categories->count() > 1)
                    <div class="filter-chips" data-aos="fade-up">
                        <button type="button" class="chip active" data-filter="*">All</button>
                        @foreach ($categories as $category)
                            <button type="button" class="chip" data-filter="{{ $category }}">{{ $category }}</button>
                        @endforeach
                    </div>
                @endif
                <div class="row g-4">
                    @foreach ($projects as $project)
                        <div class="col-md-6 col-lg-4 proj-item" data-cat="{{ $project->category }}" data-aos="fade-up" data-aos-delay="{{ min(($loop->index % 3 + 1) * 100, 300) }}">
                            @include('site.partials.project-card')
                        </div>
                    @endforeach
                </div>
                <div class="text-center mt-5" data-aos="fade-up">
                    <a href="{{ route('projects.index') }}" class="btn btn-outline-custom">View all {{ $projectCount }} projects <i class="fas fa-arrow-right"></i></a>
                </div>
            @endif
        </div>
    </section>

    <!-- Happy Clients Section -->
    @if ($clients->isNotEmpty())
        <section id="clients" class="section-padding">
            <div class="hero-floating-icon" style="top: 12%; left: 6%; font-size: 2.4rem; animation: floatIcon2 22s infinite linear;"><i class="fas fa-handshake"></i></div>
            <div class="hero-floating-icon" style="bottom: 14%; right: 7%; font-size: 2rem; animation: floatIcon1 19s infinite linear;"><i class="fas fa-heart"></i></div>

            <div class="container">
                <div class="text-center mb-5" data-aos="fade-up">
                    <h5 class="text-primary-custom text-uppercase">Our Clients</h5>
                    <h2>Our Happy <span class="hl">Clients</span></h2>
                    <p class="opacity-75 mt-3 mx-auto" style="max-width:600px">Schools, churches, hospitals, universities and public institutions that trust {{ setting('site_name', 'StarCliqs') }} with their systems.</p>
                </div>
            </div>

            {{-- Scrolling strip: two rows in opposite directions once there are enough clients. Each row is
                 padded to at least 8 cards and then repeated, so the loop never shows a gap. --}}
            @php
                $clientRows = $clients->count() > 8 ? $clients->chunk((int) ceil($clients->count() / 2)) : collect([$clients]);
            @endphp
            <div class="client-marquee" data-aos="fade-up">
                @foreach ($clientRows as $rowIndex => $row)
                    @php
                        $set = $row->values();
                        while ($set->count() < 8) { $set = $set->concat($row->values()); }
                    @endphp
                    <div class="marquee-row {{ $rowIndex % 2 ? 'reverse' : '' }}" style="--marquee-duration: {{ $set->count() * 5 }}s">
                        <div class="marquee-track">
                            @foreach ($set as $i => $client)
                                @include('site.partials.client-card', ['client' => $client, 'copy' => $i >= $row->count()])
                            @endforeach
                            @foreach ($set as $client)
                                @include('site.partials.client-card', ['client' => $client, 'copy' => true])
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    @endif

    <!-- Trainings Section -->
    @if ($trainings->isNotEmpty())
        <section id="trainings" class="section-padding">
            <div class="hero-floating-icon" style="top: 12%; right: 8%; font-size: 2.5rem; animation: floatIcon2 21s infinite linear;"><i class="fas fa-graduation-cap"></i></div>
            <div class="hero-floating-icon" style="bottom: 12%; left: 6%; font-size: 2rem; animation: floatIcon3 17s infinite linear;"><i class="fas fa-chalkboard-teacher"></i></div>

            <div class="container">
                <div class="text-center mb-5" data-aos="fade-up">
                    <h5 class="text-primary-custom text-uppercase">{{ setting('site_name', 'StarCliqs') }} Academy</h5>
                    <h2>Learn tech by <span class="hl">building</span></h2>
                    <p class="opacity-75 mt-3 mx-auto" style="max-width:600px">Hands-on courses taught by the engineers who build and install our systems.</p>
                </div>
                <div class="row g-4">
                    @foreach ($trainings as $training)
                        <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="{{ ($loop->index + 1) * 100 }}">@include('site.partials.training-card')</div>
                    @endforeach
                </div>
                <div class="text-center mt-5" data-aos="fade-up">
                    <a href="{{ route('academy') }}" class="btn btn-primary-custom">Visit the Academy <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>
        </section>
    @endif

    <!-- FAQ Section (Help Center Grid) -->
    @if ($faqs->isNotEmpty())
        <section id="faq" class="section-padding bg-light-custom">
            <div class="hero-floating-icon" style="top: 10%; left: 5%; font-size: 2rem; animation: floatIcon1 25s infinite linear;"><i class="fas fa-question-circle"></i></div>
            <div class="hero-floating-icon" style="bottom: 20%; right: 10%; font-size: 3rem; animation: floatIcon2 20s infinite linear;"><i class="fas fa-life-ring"></i></div>
            <div class="hero-floating-icon" style="top: 40%; right: 5%; font-size: 1.5rem; animation: floatIcon3 15s infinite linear;"><i class="fas fa-comments"></i></div>

            <div class="container">
                <div class="text-center mb-5" data-aos="fade-up">
                    <h5 class="text-primary-custom text-uppercase">Support Center</h5>
                    <h2 class="mb-3">Looking for <span class="hl">help?</span></h2>
                    <p class="mb-4 opacity-75">Browse our most frequently asked questions about our services.</p>

                    <form class="help-search-container mb-5" data-aos="fade-up" data-aos-delay="100" role="search">
                        <input type="search" id="faq-search" class="help-search-input" placeholder="Search for answers..." aria-label="Search the FAQs">
                        <button class="help-search-btn" type="submit" aria-label="Search"><i class="fas fa-arrow-right"></i></button>
                    </form>

                    <div class="d-flex flex-wrap justify-content-center gap-3 mb-5" data-aos="fade-up" data-aos-delay="150">
                        <a href="#contact" class="btn btn-outline-custom"><i class="fas fa-envelope me-2"></i>I've got a question</a>
                        @if (setting('whatsapp'))
                            <a href="https://wa.me/{{ setting('whatsapp') }}" target="_blank" rel="noopener" class="btn btn-primary-custom"><i class="fas fa-comment-dots me-2"></i>Chat to our team</a>
                        @else
                            <a href="#contact" class="btn btn-primary-custom"><i class="fas fa-comment-dots me-2"></i>Chat to our team</a>
                        @endif
                    </div>
                </div>

                <div class="row g-4 justify-content-center">
                    @foreach ($faqs as $faq)
                        <div class="col-md-6 col-lg-4 faq-item" data-aos="fade-up" data-aos-delay="{{ min(($loop->index % 3 + 2) * 100, 400) }}">
                            <div class="help-card">
                                <div class="help-icon-wrapper"><i class="fas {{ $faq->icon }}"></i></div>
                                <h4 class="help-card-title">{{ $faq->topic }}</h4>
                                <p class="help-card-text"><strong>{{ $faq->question }}</strong> {{ $faq->answer }}</p>
                            </div>
                        </div>
                    @endforeach
                    <div id="faq-empty" class="col-12 text-center opacity-75 d-none">
                        No answers match that search. <a href="#contact" class="text-primary-custom fw-semibold">Ask us directly</a>.
                    </div>
                </div>
            </div>
        </section>
    @endif

    <!-- Testimonials Section -->
    @if ($testimonials->isNotEmpty())
        <section id="testimonials" class="section-padding">
            <div class="hero-floating-icon" style="top: 10%; left: 10%; font-size: 1.5rem; animation: floatIcon1 18s infinite linear;"><i class="fas fa-quote-right"></i></div>

            <div class="container">
                <div class="row justify-content-center mb-5">
                    <div class="col-lg-8 text-center" data-aos="fade-up">
                        <h5 class="text-primary-custom text-uppercase">Client Stories</h5>
                        <h2>Trusted by <span class="hl">Leaders</span></h2>
                        <p class="opacity-75">Don't just take our word for it. Here's what our partners say about working with {{ setting('site_name', 'StarCliqs') }}.</p>
                    </div>
                </div>

                <div class="row justify-content-center" data-aos="fade-up" data-aos-delay="100">
                    <div class="col-lg-10">
                        <div id="testimonialCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="7000">
                            <div class="carousel-inner">
                                @foreach ($testimonials as $t)
                                    <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
                                        <div class="testimonial-card glass-card no-tilt">
                                            <div class="testimonial-quote-icon">“</div>
                                            <div class="star-rating" aria-label="{{ $t->rating }} out of 5 stars">
                                                @for ($i = 1; $i <= 5; $i++)<i class="{{ $i <= $t->rating ? 'fas' : 'far' }} fa-star"></i>@endfor
                                            </div>
                                            <p class="testimonial-text">"{{ $t->quote }}"</p>
                                            <div class="client-info">
                                                @if ($t->photo)
                                                    <img src="{{ media_url($t->photo) }}" alt="" class="client-avatar">
                                                @else
                                                    <div class="client-avatar">{{ $t->initials() }}</div>
                                                @endif
                                                <div class="client-details">
                                                    <h5>{{ $t->name }}</h5>
                                                    <span>{{ collect([$t->name === $t->role ? null : $t->role, $t->company])->filter()->implode(', ') }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            @if ($testimonials->count() > 1)
                                <button class="carousel-control-prev" type="button" data-bs-target="#testimonialCarousel" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Previous</span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#testimonialCarousel" data-bs-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Next</span>
                                </button>
                                <div class="carousel-indicators">
                                    @foreach ($testimonials as $t)
                                        <button type="button" data-bs-target="#testimonialCarousel" data-bs-slide-to="{{ $loop->index }}" class="{{ $loop->first ? 'active' : '' }}" @if ($loop->first) aria-current="true" @endif aria-label="Slide {{ $loop->iteration }}"></button>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif

    <!-- Blog Section -->
    @if ($posts->isNotEmpty())
        <section id="blog" class="section-padding bg-light-custom">
            <div class="hero-floating-icon" style="top: 12%; left: 8%; font-size: 2.2rem; animation: floatIcon3 20s infinite linear;"><i class="fas fa-newspaper"></i></div>

            <div class="container">
                <div class="text-center mb-5" data-aos="fade-up">
                    <h5 class="text-primary-custom text-uppercase">Insights</h5>
                    <h2>From Our <span class="hl">Blog</span></h2>
                </div>
                <div class="row g-4">
                    @foreach ($posts as $post)
                        <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="{{ ($loop->index + 1) * 100 }}">@include('site.partials.post-card')</div>
                    @endforeach
                </div>
                <div class="text-center mt-5" data-aos="fade-up">
                    <a href="{{ route('blog.index') }}" class="btn btn-outline-custom">Read all articles <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>
        </section>
    @endif

    <!-- Contact Section -->
    <section id="contact" class="section-padding">
        <div class="hero-floating-icon" style="top: 10%; right: 10%; font-size: 2.5rem; animation: floatIcon3 18s infinite linear;"><i class="fas fa-envelope-open-text"></i></div>
        <div class="hero-floating-icon" style="bottom: 10%; left: 10%; font-size: 3rem; animation: floatIcon1 23s infinite linear;"><i class="fas fa-map-marked-alt"></i></div>

        <div class="container">
            <div class="row">
                <div class="col-lg-5 mb-5 mb-lg-0" data-aos="fade-right">
                    <h5 class="text-primary-custom text-uppercase">Get in Touch</h5>
                    <h2>Let's Build Something <span class="hl">Great</span></h2>
                    <p class="mb-5">Have a project in mind? Contact us today for a consultation.</p>

                    @if (setting('address'))
                        <div class="contact-line">
                            <div class="service-icon"><i class="fas fa-map-marker-alt"></i></div>
                            <div class="ms-3"><h5>Location</h5><p>{{ setting('address') }}</p></div>
                        </div>
                    @endif
                    @if (setting('phone'))
                        <div class="contact-line">
                            <div class="service-icon"><i class="fas fa-phone-alt"></i></div>
                            <div class="ms-3"><h5>Phone</h5><p><a href="tel:{{ setting('phone') }}">{{ setting('phone') }}</a></p></div>
                        </div>
                    @endif
                    @if (setting('email'))
                        <div class="contact-line">
                            <div class="service-icon"><i class="fas fa-envelope"></i></div>
                            <div class="ms-3"><h5>Email</h5><p><a href="mailto:{{ setting('email') }}">{{ setting('email') }}</a></p></div>
                        </div>
                    @endif
                    @if (setting('office_hours'))
                        <div class="contact-line">
                            <div class="service-icon"><i class="fas fa-clock"></i></div>
                            <div class="ms-3"><h5>Office Hours</h5><p>{{ setting('office_hours') }}</p></div>
                        </div>
                    @endif
                </div>

                <div class="col-lg-7" data-aos="fade-left">
                    @include('site.partials.enquiry-form', ['course' => null, 'topics' => $services->pluck('title')->merge($products->pluck('name'))])
                </div>
            </div>
        </div>
    </section>
@endsection

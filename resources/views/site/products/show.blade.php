@extends('layouts.site')

@section('title', $product->name)
@section('description', $product->summary)
@if ($product->image)
    @section('og_image', media_url($product->image))
@endif

@section('content')
    @include('site.partials.page-hero', ['crumbs' => ['Products' => route('products.index'), $product->name => null], 'eyebrow' => $product->tagline ?: 'Product', 'title' => $product->name, 'lead' => $product->summary])

    <section class="section">
        <div class="container">
            <div class="row g-5">
                <div class="col-lg-8">
                    @if ($product->image)
                        <div class="cover"><img src="{{ media_url($product->image) }}" alt="{{ $product->name }}"></div>
                    @endif
                    @if (! empty($product->features))
                        <h2 class="h3 mb-4">Features</h2>
                        <div class="row g-3 mb-5">
                            @foreach ($product->features as $feature)
                                <div class="col-sm-6"><div class="feature-box"><i class="fas fa-star"></i><span>{{ $feature }}</span></div></div>
                            @endforeach
                        </div>
                    @endif
                    @if ($product->body)
                        <h2 class="h3 mb-3">Details</h2>
                        <div class="prose">{!! rich_text($product->body) !!}</div>
                    @endif
                </div>
                <div class="col-lg-4">
                    <div class="side-card">
                        <h5>At a glance</h5>
                        <div class="kv"><span>Product</span><strong>{{ $product->name }}</strong></div>
                        @if ($product->linkLabel())<div class="kv"><span>Website</span><strong><a href="{{ $product->demo_url }}" target="_blank" rel="noopener" class="text-primary-custom">{{ $product->linkLabel() }}</a></strong></div>@endif
                        @if ($product->price_label)<div class="kv"><span>Pricing</span><strong>{{ $product->price_label }}</strong></div>@endif
                        <div class="kv"><span>Features</span><strong>{{ count($product->features ?? []) }}</strong></div>
                        <div class="kv"><span>Setup & training</span><strong>Included</strong></div>
                        <div class="d-grid gap-2 mt-4">
                            @if ($product->linkLabel())
                                <a href="{{ $product->demo_url }}" target="_blank" rel="noopener" class="btn-gold justify-content-center">Visit {{ $product->linkLabel() }} <i class="fas fa-arrow-up-right-from-square"></i></a>
                                <a href="{{ route('home') }}#contact" class="btn-ghost justify-content-center">Talk to us</a>
                            @else
                                <a href="{{ route('home') }}#contact" class="btn-gold justify-content-center">Request a demo</a>
                            @endif
                        </div>
                    </div>
                    @if ($others->isNotEmpty())
                        <div class="side-card">
                            <h5>More products</h5>
                            @foreach ($others as $other)
                                <a href="{{ route('products.show', $other) }}" class="kv text-decoration-none"><span><i class="fas {{ $other->icon }} text-gold me-2"></i>{{ $other->name }}</span><i class="fas fa-chevron-right small opacity-50"></i></a>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection

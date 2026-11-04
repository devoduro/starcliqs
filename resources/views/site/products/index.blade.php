@extends('layouts.site')

@section('title', 'Products')

@section('content')
    @include('site.partials.page-hero', ['crumbs' => ['Products' => null], 'eyebrow' => 'Our products', 'title' => 'Software built for Ghanaian organisations', 'lead' => 'Ready-made platforms we install, customise and support — so you get proven tools without starting from scratch.'])

    <section class="section">
        <div class="container">
            @forelse ($products as $product)
                <div class="row g-5 align-items-center {{ ! $loop->last ? 'mb-5 pb-5 border-bottom border-themed' : '' }} reveal">
                    <div class="col-lg-6 {{ $loop->even ? 'order-lg-2' : '' }}">
                        <div class="cover mb-0" style="aspect-ratio:16/10">
                            @include('site.partials.media', ['path' => $product->image, 'icon' => $product->icon, 'alt' => $product->name])
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <span class="eyebrow">{{ $product->tagline ?: 'Product' }}</span>
                        <h2 class="display-6 fw-bold mt-3">{{ $product->name }}</h2>
                        <p>{{ $product->summary }}</p>
                        @if (! empty($product->features))
                            <ul class="check-list my-4">
                                @foreach (array_slice($product->features, 0, 6) as $feature)
                                    <li><i class="fas fa-circle-check"></i> {{ $feature }}</li>
                                @endforeach
                            </ul>
                        @endif
                        <div class="d-flex flex-wrap gap-3 align-items-center">
                            <a href="{{ route('products.show', $product) }}" class="btn-dark-soft">See details <i class="fas fa-arrow-right"></i></a>
                            @if ($product->linkLabel())<a href="{{ $product->demo_url }}" target="_blank" rel="noopener" class="btn-ghost">Visit {{ $product->linkLabel() }} <i class="fas fa-arrow-up-right-from-square"></i></a>@endif
                            @if ($product->price_label)<span class="tag tag-gold">{{ $product->price_label }}</span>@endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="empty-state"><i class="fas fa-cubes d-block"></i>Products are coming soon.</div>
            @endforelse
        </div>
    </section>
@endsection

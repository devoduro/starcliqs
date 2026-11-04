{{-- An uploaded image, or a branded placeholder with an icon when none has been uploaded. --}}
@if ($path)
    <img src="{{ media_url($path) }}" alt="{{ $alt ?? '' }}" loading="lazy">
@else
    <div class="ph" aria-hidden="true"><i class="fas {{ $icon ?? 'fa-star' }}"></i></div>
@endif

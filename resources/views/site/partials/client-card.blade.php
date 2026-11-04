{{-- One client in the scrolling strip. $copy marks the repeated set that makes the loop seamless:
     it is hidden from screen readers and skipped by the keyboard. --}}
@php
    $link = $client->link();
    $copy = $copy ?? false;
    $tag = $link ? 'a' : 'div';
@endphp
<{{ $tag }} class="client-card marquee-item"
    @if ($link) href="{{ $link['url'] }}" title="{{ $link['label'] }}" @if ($link['external']) target="_blank" rel="noopener" @endif @endif
    @if ($copy) aria-hidden="true" tabindex="-1" @endif>
    <span class="client-logo">
        @if ($client->logo)
            <img src="{{ media_url($client->logo) }}" alt="{{ $copy ? '' : $client->name . ' logo' }}">
        @else
            <span class="client-initials">{{ $client->initials() }}</span>
        @endif
    </span>
    <span class="client-name">{{ $client->name }}</span>
    @if ($link)
        <span class="client-visit">{{ $link['label'] }} <i class="fas {{ $link['external'] ? 'fa-arrow-up-right-from-square' : 'fa-arrow-right' }}"></i></span>
    @endif
</{{ $tag }}>

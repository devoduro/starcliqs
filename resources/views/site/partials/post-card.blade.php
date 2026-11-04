<a href="{{ route('blog.show', $post) }}" class="glass-card post-card">
    <div class="media">
        @include('site.partials.media', ['path' => $post->cover_image, 'icon' => 'fa-newspaper', 'alt' => $post->title])
    </div>
    <div class="body">
        <div class="meta">
            @if ($post->category)<span class="cat">{{ $post->category }}</span>@endif
            <span><i class="far fa-calendar me-1"></i>{{ $post->published_at->format('d M Y') }}</span>
            <span><i class="far fa-clock me-1"></i>{{ $post->readingMinutes() }} min read</span>
        </div>
        <h4>{{ $post->title }}</h4>
        <p>{{ $post->excerptText(130) }}</p>
        <span class="link-arrow">Read article <i class="fas fa-arrow-right"></i></span>
    </div>
</a>

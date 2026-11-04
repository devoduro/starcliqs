<a href="{{ route('projects.show', $project) }}" class="glass-card project-card">
    <div class="media">
        @include('site.partials.media', ['path' => $project->image, 'icon' => 'fa-briefcase', 'alt' => $project->title])
        @if ($project->category)<span class="cat">{{ $project->category }}</span>@endif
    </div>
    <div class="body">
        @if ($project->client)<div class="client">{{ $project->client }}</div>@endif
        <h4>{{ $project->title }}</h4>
        <p>{{ \Illuminate\Support\Str::limit($project->summary, 120) }}</p>
        <span class="link-arrow">View project <i class="fas fa-arrow-right"></i></span>
    </div>
</a>

<div class="glass-card training-card">
    <div class="top">
        <div class="date-badge">
            @if ($training->starts_on)
                <div class="m">{{ $training->starts_on->format('M') }}</div>
                <div class="d">{{ $training->starts_on->format('d') }}</div>
            @else
                <div class="m">Open</div>
                <div class="d"><i class="fas fa-infinity" style="font-size:1.1rem"></i></div>
            @endif
        </div>
        <div>
            <h4><a href="{{ route('trainings.show', $training) }}">{{ $training->title }}</a></h4>
            <div class="d-flex flex-wrap gap-1">
                <span class="tag tag-gold"><i class="fas fa-location-dot"></i>{{ $training->modeLabel() }}</span>
                @if ($training->duration)<span class="tag"><i class="far fa-clock"></i>{{ $training->duration }}</span>@endif
            </div>
        </div>
    </div>
    <p>{{ \Illuminate\Support\Str::limit($training->summary, 130) }}</p>
    <div class="foot">
        <span class="fee">{{ $training->feeLabel() }}</span>
        <a href="{{ route('trainings.show', $training) }}#register" class="btn-primary-custom btn-sm-pill">Register <i class="fas fa-arrow-right"></i></a>
    </div>
</div>

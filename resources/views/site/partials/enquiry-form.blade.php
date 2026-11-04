{{-- Contact form; pass a Training as $course to turn it into a course registration. Always pass
     'course' explicitly: included views inherit the parent's variables, including loop variables. --}}
@php $course = $course ?? null; @endphp
<div class="glass-card no-tilt no-lift">
    <form method="POST" action="{{ route('enquiries.store') }}" class="contact-form">
        @csrf
        @if ($course)<input type="hidden" name="training_id" value="{{ $course->id }}">@endif
        {{-- Honeypot: invisible to people, bots fill it and get rejected. --}}
        <div style="position:absolute;left:-9999px" aria-hidden="true"><input type="text" name="website" tabindex="-1" autocomplete="off"></div>

        @if ($course)
            <h4 class="mb-1">Register for this course</h4>
            <p class="small opacity-75 mb-4">Reserve your seat — we will call to confirm and share payment details.</p>
        @endif

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label small fw-bold" for="f-name">Name</label>
                <input id="f-name" type="text" name="name" class="form-control" placeholder="Your Name" value="{{ old('name') }}" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label small fw-bold" for="f-email">Email</label>
                <input id="f-email" type="email" name="email" class="form-control" placeholder="Your Email" value="{{ old('email') }}" required>
            </div>
        </div>
        <div class="row">
            <div class="{{ $course ? 'col-12' : 'col-md-6' }} mb-3">
                <label class="form-label small fw-bold" for="f-phone">Phone</label>
                <input id="f-phone" type="tel" name="phone" class="form-control" placeholder="Your Phone{{ $course ? '' : ' (optional)' }}" value="{{ old('phone') }}" {{ $course ? 'required' : '' }}>
            </div>
            @unless ($course)
                <div class="col-md-6 mb-3">
                    <label class="form-label small fw-bold" for="f-subject">Subject</label>
                    <select id="f-subject" name="subject" class="form-select">
                        <option value="">Project Inquiry</option>
                        @foreach (($topics ?? collect()) as $topic)
                            <option @selected(old('subject') === $topic)>{{ $topic }}</option>
                        @endforeach
                        <option @selected(old('subject') === 'Something else')>Something else</option>
                    </select>
                </div>
            @endunless
        </div>
        <div class="mb-3">
            <label class="form-label small fw-bold" for="f-message">{{ $course ? 'Anything we should know? (optional)' : 'Message' }}</label>
            <textarea id="f-message" name="message" class="form-control" rows="5" placeholder="{{ $course ? 'Your experience level, preferred schedule…' : 'Tell us about your project...' }}" {{ $course ? '' : 'required' }}>{{ old('message') }}</textarea>
        </div>
        <button type="submit" class="btn btn-primary-custom w-100">{{ $course ? 'Reserve My Seat' : 'Send Message' }}</button>
    </form>
</div>

@php
    $retry = (int) (($exception ?? null)?->getHeaders()['Retry-After'] ?? 60);
    $wait = $retry >= 120 ? ceil($retry / 60) . ' minutes' : $retry . ' seconds';
@endphp
<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex">
    <title>Please slow down | {{ setting('site_name', 'StarCliqs') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Space+Grotesk:wght@600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/site.css') }}?v={{ @filemtime(public_path('css/site.css')) }}">
</head>
<body class="d-flex align-items-center min-vh-100">
    <main class="container py-5 text-center" style="max-width:560px">
        <a href="{{ route('home') }}"><img src="{{ asset('images/logo-light.webp') }}" alt="{{ setting('site_name', 'StarCliqs') }}" style="height:48px"></a>
        <div class="mx-auto my-4 d-grid program-icon" style="width:64px;height:64px;font-size:1.6rem"><i class="fas fa-hourglass-half"></i></div>
        <span class="kicker justify-content-center">Too many requests</span>
        <h1 class="mb-3" style="font-size:2.4rem">Please slow <span class="hl">down</span></h1>
        <p class="text-muted-custom mb-4">We received a lot of requests from your connection in a short time, so we've paused them for a moment to keep the site fast for everyone. Please try again in about {{ $wait }}.</p>
        <div class="d-flex flex-wrap justify-content-center gap-3">
            <a href="{{ url()->previous() !== url()->current() ? url()->previous() : route('home') }}" class="btn btn-primary-custom"><i class="fas fa-arrow-left"></i> Go back</a>
            <a href="{{ route('home') }}" class="btn btn-outline-custom">Home</a>
        </div>
    </main>
</body>
</html>

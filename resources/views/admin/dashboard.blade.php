<x-app-layout title="Dashboard">
    @php
        $firstName = \Illuminate\Support\Str::of(auth()->user()->name)->explode(' ')->first();
        $hour = now()->hour;
        $greeting = $hour < 12 ? 'Good morning' : ($hour < 17 ? 'Good afternoon' : 'Good evening');
        $maxDay = max(1, collect($activity)->max(fn ($d) => $d['contact'] + $d['training']));
        $messagesTotal = collect($activity)->sum(fn ($d) => $d['contact'] + $d['training']);
        $levelStyles = [
            'danger' => 'bg-red-50 text-red-600',
            'warn' => 'bg-amber-50 text-amber-600',
            'info' => 'bg-slate-100 text-slate-500',
        ];
    @endphp

    {{-- Welcome + quick actions --}}
    <div class="relative overflow-hidden rounded-xl bg-[#07080c] p-6 text-white">
        <div class="absolute -right-16 -top-20 h-64 w-64 rounded-full bg-primary-500/25 blur-3xl"></div>
        <div class="relative flex flex-wrap items-center justify-between gap-5">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-primary-400">— {{ now()->format('l, d F Y') }}</p>
                <h1 class="mt-1 text-2xl font-bold">{{ $greeting }}, <span class="text-primary-400">{{ $firstName }}</span></h1>
                <p class="mt-1 text-sm text-slate-300">Everything you change here updates the website straight away.</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('admin.projects.create') }}" class="inline-flex items-center gap-2 rounded-lg bg-primary-400 px-4 py-2 text-sm font-semibold text-slate-900 hover:bg-primary-300"><i class="fas fa-upload"></i> Upload project</a>
                <a href="{{ route('admin.trainings.create') }}" class="inline-flex items-center gap-2 rounded-lg border border-slate-700 px-4 py-2 text-sm font-medium text-white hover:border-primary-400"><i class="fas fa-graduation-cap"></i> Add programme</a>
                <a href="{{ route('admin.clients.create') }}" class="inline-flex items-center gap-2 rounded-lg border border-slate-700 px-4 py-2 text-sm font-medium text-white hover:border-primary-400"><i class="fas fa-handshake"></i> Add client</a>
                <a href="{{ route('admin.posts.create') }}" class="inline-flex items-center gap-2 rounded-lg border border-slate-700 px-4 py-2 text-sm font-medium text-white hover:border-primary-400"><i class="fas fa-pen"></i> Write post</a>
            </div>
        </div>
        <div class="relative mt-5 flex flex-wrap gap-4 border-t border-slate-800 pt-4 text-sm">
            <a href="{{ route('home') }}" target="_blank" class="text-slate-300 hover:text-primary-400"><i class="fas fa-globe mr-1"></i> View website</a>
            <a href="{{ route('academy') }}" target="_blank" class="text-slate-300 hover:text-primary-400"><i class="fas fa-graduation-cap mr-1"></i> View Academy</a>
            <a href="{{ route('admin.settings.edit') }}" class="text-slate-300 hover:text-primary-400"><i class="fas fa-sliders mr-1"></i> Site settings</a>
        </div>
    </div>

    {{-- What needs action --}}
    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <x-stat-tile color="red" icon="fa-inbox" title="Unread messages" sub="Contact form & registrations" :value="$priority['unread']" :href="route('admin.enquiries.index', ['status' => 'unread'])" />
        <x-stat-tile color="purple" icon="fa-user-plus" title="New registrations" sub="Academy, last 7 days" :value="$priority['registrations_week']" :href="route('admin.enquiries.index', ['type' => 'training'])" />
        <x-stat-tile color="green" icon="fa-door-open" title="Open programmes" sub="Taking registrations now" :value="$priority['open_programmes']" :href="route('admin.trainings.index')" />
        <x-stat-tile color="orange" icon="fa-envelope-open-text" title="New subscribers" sub="Last 30 days" :value="$priority['subscribers_month']" :badge="$subscriberTotal . ' in total'" :href="route('admin.subscribers.index')" />
    </div>

    {{-- Website content at a glance --}}
    <div class="bg-white shadow-sm sm:rounded-lg p-5">
        <div class="mb-4 flex items-center justify-between">
            <h3 class="font-semibold text-gray-800">Website content</h3>
            <span class="text-xs text-gray-400">Live on the site / total</span>
        </div>
        <div class="grid grid-cols-2 gap-3 sm:grid-cols-4 xl:grid-cols-8">
            @foreach ($content as $item)
                <a href="{{ route($item['route']) }}" class="group rounded-lg border border-gray-200 p-3 transition hover:border-primary-400 hover:shadow-sm">
                    <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-primary-50 text-primary-600 transition group-hover:bg-primary-500 group-hover:text-white"><i class="fas {{ $item['icon'] }}"></i></span>
                    <span class="mt-3 block text-2xl font-bold text-gray-900">{{ $item['live'] }}<span class="text-sm font-normal text-gray-400">/{{ $item['total'] }}</span></span>
                    <span class="block text-xs font-medium text-gray-500">{{ $item['label'] }}</span>
                </a>
            @endforeach
        </div>
    </div>

    <div class="grid gap-4 lg:grid-cols-3 lg:items-start">
        {{-- Messages: activity chart + latest --}}
        <div class="lg:col-span-2 bg-white shadow-sm sm:rounded-lg">
            <div class="flex flex-wrap items-center justify-between gap-2 border-b border-gray-100 px-6 py-4">
                <h3 class="font-semibold text-gray-800">Messages <span class="ml-1 text-sm font-normal text-gray-400">{{ $messagesTotal }} in the last 14 days</span></h3>
                <div class="flex items-center gap-4 text-xs text-gray-500">
                    <span><span class="mr-1 inline-block h-2 w-2 rounded-sm bg-sky-500"></span>Contact</span>
                    <span><span class="mr-1 inline-block h-2 w-2 rounded-sm bg-primary-500"></span>Registrations</span>
                    <a href="{{ route('admin.enquiries.index') }}" class="text-primary-700 hover:underline">View all</a>
                </div>
            </div>
            <div class="px-6 pt-5">
                <div class="flex h-28 items-end gap-1.5" role="img" aria-label="Messages per day for the last 14 days">
                    @foreach ($activity as $day)
                        @php $total = $day['contact'] + $day['training']; @endphp
                        <div class="group relative flex h-full flex-1 flex-col justify-end" title="{{ $day['label'] }}: {{ $day['contact'] }} contact, {{ $day['training'] }} registrations">
                            @if ($total)
                                <div class="w-full rounded-t bg-primary-500" style="height: {{ $day['training'] / $maxDay * 100 }}%"></div>
                                <div class="w-full bg-sky-500 {{ $day['training'] ? '' : 'rounded-t' }}" style="height: {{ $day['contact'] / $maxDay * 100 }}%"></div>
                            @else
                                <div class="h-1 w-full rounded bg-gray-100"></div>
                            @endif
                        </div>
                    @endforeach
                </div>
                <div class="mt-1 flex justify-between text-[10px] text-gray-400">
                    <span>{{ collect($activity)->first()['label'] }}</span><span>Today</span>
                </div>
            </div>
            <div class="mt-3">
                @forelse ($recentEnquiries as $e)
                    <a href="{{ route('admin.enquiries.show', $e) }}" class="flex items-start gap-3 border-t border-gray-100 px-6 py-3 hover:bg-gray-50">
                        <span class="mt-0.5 flex h-9 w-9 shrink-0 items-center justify-center rounded-full {{ $e->type === 'training' ? 'bg-primary-50 text-primary-600' : 'bg-sky-50 text-sky-600' }}">
                            <i class="fas {{ $e->type === 'training' ? 'fa-graduation-cap' : 'fa-envelope' }} text-sm"></i>
                        </span>
                        <span class="min-w-0 flex-1">
                            <span class="flex justify-between gap-2 text-sm">
                                <span class="{{ $e->read_at ? 'text-gray-700' : 'font-semibold text-gray-900' }}">@unless ($e->read_at)<i class="fas fa-circle mr-1 text-[7px] text-primary-500"></i>@endunless{{ $e->name }}</span>
                                <span class="shrink-0 text-xs text-gray-400">{{ $e->created_at->diffForHumans() }}</span>
                            </span>
                            <span class="block truncate text-sm text-gray-500">{{ $e->subject ?: \Illuminate\Support\Str::limit($e->message, 90) }}</span>
                        </span>
                    </a>
                @empty
                    <p class="border-t border-gray-100 px-6 py-10 text-center text-sm text-gray-400">No messages yet. Contact-form messages and Academy registrations will appear here.</p>
                @endforelse
            </div>
        </div>

        {{-- Needs attention --}}
        <div class="bg-white shadow-sm sm:rounded-lg">
            <div class="flex items-center justify-between border-b border-gray-100 px-6 py-4">
                <h3 class="font-semibold text-gray-800">Needs attention</h3>
                @if (count($attention))<span class="rounded-full bg-red-50 px-2 py-0.5 text-xs font-semibold text-red-600">{{ count($attention) }}</span>@endif
            </div>
            @forelse ($attention as $item)
                <a href="{{ $item['url'] }}" class="flex items-start gap-3 border-b border-gray-50 px-6 py-3 text-sm last:border-0 hover:bg-gray-50">
                    <span class="mt-0.5 flex h-7 w-7 shrink-0 items-center justify-center rounded-lg {{ $levelStyles[$item['level']] }}"><i class="fas {{ $item['icon'] }} text-xs"></i></span>
                    <span class="flex-1 text-gray-700">{{ $item['text'] }}</span>
                    <i class="fas fa-chevron-right mt-1 text-xs text-gray-300"></i>
                </a>
            @empty
                <p class="px-6 py-10 text-center text-sm text-green-600"><i class="fas fa-circle-check mr-1"></i> Everything looks good.</p>
            @endforelse
        </div>
    </div>

    <div class="grid gap-4 lg:grid-cols-3 lg:items-start">
        {{-- Academy programmes --}}
        <div class="lg:col-span-2 bg-white shadow-sm sm:rounded-lg">
            <div class="flex items-center justify-between border-b border-gray-100 px-6 py-4">
                <h3 class="font-semibold text-gray-800">Academy programmes</h3>
                <a href="{{ route('admin.trainings.index') }}" class="text-sm text-primary-700 hover:underline">Manage</a>
            </div>
            @forelse ($programmes as $p)
                @php
                    $deadline = $p->registration_closes_on ?? $p->starts_on;
                    $daysLeft = $deadline ? (int) today()->diffInDays($deadline, false) : null;
                    if (! $p->registrationOpen()) {
                        [$status, $statusClass] = ['Closed', 'bg-gray-100 text-gray-500'];
                    } elseif ($daysLeft !== null && $daysLeft <= 7) {
                        [$status, $statusClass] = [$daysLeft === 0 ? 'Closes today' : 'Closes in ' . $daysLeft . ' ' . str('day')->plural($daysLeft), 'bg-red-50 text-red-600'];
                    } else {
                        [$status, $statusClass] = ['Open', 'bg-green-50 text-green-700'];
                    }
                @endphp
                <a href="{{ route('admin.trainings.edit', $p) }}" class="flex flex-wrap items-center gap-3 border-b border-gray-50 px-6 py-3 last:border-0 hover:bg-gray-50">
                    <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-primary-50 text-primary-600"><i class="fas {{ $p->icon ?: 'fa-code' }}"></i></span>
                    <span class="min-w-0 flex-1">
                        <span class="block truncate text-sm font-medium text-gray-900">{{ $p->title }}</span>
                        <span class="block text-xs text-gray-500">
                            {{ $p->starts_on ? 'Starts ' . $p->starts_on->format('d M Y') : 'Rolling start' }}
                            @if ($p->registration_closes_on) · registration closes {{ $p->registration_closes_on->format('d M') }} @endif
                            · {{ $p->feeLabel() }}
                        </span>
                    </span>
                    <span class="rounded-full px-2.5 py-0.5 text-xs font-medium {{ $statusClass }}">{{ $status }}</span>
                    <span class="w-24 text-right text-sm"><span class="font-bold text-gray-900">{{ $p->registrations_count }}</span> <span class="text-xs text-gray-500">registered</span></span>
                </a>
            @empty
                <p class="px-6 py-10 text-center text-sm text-gray-400">No published programmes. <a href="{{ route('admin.trainings.create') }}" class="text-primary-700 underline">Add one</a> to open the Academy.</p>
            @endforelse
        </div>

        <div class="space-y-4">
            {{-- Website visitors --}}
            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="flex items-center justify-between border-b border-gray-100 px-6 py-4">
                    <h3 class="font-semibold text-gray-800">Website visitors</h3>
                    <a href="{{ route('admin.visitors.index') }}" class="text-sm text-primary-700 hover:underline">Details</a>
                </div>
                <div class="grid grid-cols-2 gap-px bg-gray-100">
                    <div class="bg-white px-5 py-4"><span class="block text-2xl font-bold text-gray-900">{{ number_format($traffic['today_visitors']) }}</span><span class="text-xs text-gray-500">Visitors today</span></div>
                    <div class="bg-white px-5 py-4"><span class="block text-2xl font-bold text-gray-900">{{ number_format($traffic['today_views']) }}</span><span class="text-xs text-gray-500">Page views today</span></div>
                    <div class="bg-white px-5 py-4"><span class="block text-2xl font-bold text-gray-900">{{ number_format($traffic['week_views']) }}</span><span class="text-xs text-gray-500">Page views, 7 days</span></div>
                    <a href="{{ route('admin.visitors.index', ['show' => 'blocked']) }}" class="bg-white px-5 py-4 hover:bg-red-50"><span class="block text-2xl font-bold {{ $traffic['week_blocked'] ? 'text-red-600' : 'text-gray-900' }}">{{ number_format($traffic['week_blocked']) }}</span><span class="text-xs text-gray-500">Blocked, 7 days</span></a>
                </div>
                @if ($traffic['top_page'])
                    <p class="truncate border-t border-gray-100 px-6 py-3 text-xs text-gray-500">Most visited this week: <span class="font-medium text-gray-800">{{ $traffic['top_page'] === '/' ? 'Homepage' : $traffic['top_page'] }}</span></p>
                @endif
            </div>

            {{-- Newest clients --}}
            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="flex items-center justify-between border-b border-gray-100 px-6 py-4">
                    <h3 class="font-semibold text-gray-800">Newest clients</h3>
                    <a href="{{ route('admin.clients.index') }}" class="text-sm text-primary-700 hover:underline">All clients</a>
                </div>
                <div class="grid grid-cols-2 gap-3 p-4">
                    @foreach ($latestClients as $c)
                        <a href="{{ route('admin.clients.edit', $c) }}" class="flex flex-col items-center gap-2 rounded-lg border border-gray-200 p-3 text-center hover:border-primary-400">
                            <span class="flex h-12 w-full items-center justify-center">
                                @if ($c->logo)
                                    <img src="{{ media_url($c->logo) }}" alt="" class="max-h-12 max-w-full object-contain">
                                @else
                                    <span class="text-sm font-bold text-gray-400">{{ $c->initials() }}</span>
                                @endif
                            </span>
                            <span class="line-clamp-2 text-xs font-medium text-gray-700">{{ $c->name }}</span>
                        </a>
                    @endforeach
                </div>
            </div>

            {{-- Drafts --}}
            @if ($drafts->isNotEmpty())
                <div class="bg-white shadow-sm sm:rounded-lg">
                    <div class="border-b border-gray-100 px-6 py-4"><h3 class="font-semibold text-gray-800">Draft posts</h3></div>
                    @foreach ($drafts as $d)
                        <a href="{{ route('admin.posts.edit', $d) }}" class="block truncate px-6 py-3 text-sm text-gray-700 hover:bg-gray-50"><i class="fas fa-file-pen mr-2 text-gray-400"></i>{{ $d->title }}</a>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-app-layout>

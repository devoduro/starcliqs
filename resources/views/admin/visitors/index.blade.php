<x-app-layout title="Visitors">
    @php
        $max = max(1, collect($series)->max('views'));
        $icons = ['desktop' => 'fa-desktop', 'mobile' => 'fa-mobile-screen', 'tablet' => 'fa-tablet-screen-button'];
    @endphp

    <x-slot name="header">
        <div class="flex flex-wrap gap-3 justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Website visitors</h2>
            <div class="flex flex-wrap gap-2 text-sm">
                @foreach (\App\Http\Controllers\Admin\VisitorController::RANGES as $key => $label)
                    <a href="{{ route('admin.visitors.index', ['range' => $key]) }}"
                       class="rounded-lg px-3 py-1.5 {{ $range === (string) $key ? 'bg-slate-900 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">{{ $label }}</a>
                @endforeach
            </div>
        </div>
    </x-slot>

    @unless ($logEnabled)
        <div class="p-3 bg-yellow-50 border-l-4 border-yellow-500 text-yellow-800 rounded">
            <i class="fas fa-pause-circle mr-2"></i>The visitor log is switched off, so no new visits are being recorded.
            <a href="{{ route('admin.settings.edit') }}" class="underline">Turn it on in Site settings</a>.
        </div>
    @endunless

    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <x-stat-tile color="blue" icon="fa-eye" title="Page views" :sub="\App\Http\Controllers\Admin\VisitorController::RANGES[$range]" :value="number_format($stats['views'])" />
        <x-stat-tile color="green" icon="fa-users" title="Visitors" sub="Each person counted once a day" :value="number_format($stats['visitors'])" />
        <x-stat-tile color="purple" icon="fa-layer-group" title="Pages per visitor" sub="Average" :value="$stats['pages_per_visitor']" />
        <x-stat-tile color="red" icon="fa-ban" title="Blocked requests" sub="Stopped by the rate limit" :value="number_format($stats['blocked'])" :href="route('admin.visitors.index', ['range' => $range, 'show' => 'blocked'])" />
    </div>

    {{-- Traffic chart --}}
    <div class="bg-white shadow-sm sm:rounded-lg p-6">
        <div class="mb-4 flex flex-wrap items-center justify-between gap-2">
            <h3 class="font-semibold text-gray-800">Traffic {{ $range === 'today' ? 'by hour' : 'by day' }}</h3>
            <div class="flex items-center gap-4 text-xs text-gray-500">
                <span><span class="mr-1 inline-block h-2 w-2 rounded-sm bg-primary-500"></span>Page views</span>
                <span><span class="mr-1 inline-block h-2 w-2 rounded-sm bg-slate-800"></span>Visitors</span>
            </div>
        </div>
        <div class="flex h-40 items-end gap-1" role="img" aria-label="Page views and visitors over time">
            @foreach ($series as $point)
                <div class="relative flex h-full flex-1 items-end justify-center gap-px" title="{{ $point['label'] }}: {{ $point['views'] }} views, {{ $point['visitors'] }} visitors">
                    @if ($point['views'])
                        <div class="w-1/2 rounded-t bg-primary-500" style="height: {{ $point['views'] / $max * 100 }}%"></div>
                        <div class="w-1/2 rounded-t bg-slate-800" style="height: {{ $point['visitors'] / $max * 100 }}%"></div>
                    @else
                        <div class="h-1 w-full rounded bg-gray-100"></div>
                    @endif
                </div>
            @endforeach
        </div>
        <div class="mt-1 flex justify-between text-[10px] text-gray-400">
            <span>{{ $series[0]['label'] }}</span><span>{{ end($series)['label'] }}</span>
        </div>
    </div>

    <div class="grid gap-4 lg:grid-cols-3 lg:items-start">
        {{-- Top pages --}}
        <div class="lg:col-span-2 bg-white shadow-sm sm:rounded-lg">
            <div class="border-b border-gray-100 px-6 py-4"><h3 class="font-semibold text-gray-800">Most visited pages</h3></div>
            @forelse ($topPages as $page)
                @php $pct = $stats['views'] ? round($page->views / $stats['views'] * 100) : 0; @endphp
                <div class="relative border-b border-gray-50 px-6 py-2.5 last:border-0">
                    <div class="absolute inset-y-1 left-0 rounded-r bg-primary-50" style="width: {{ $pct }}%"></div>
                    <div class="relative flex items-center justify-between gap-3 text-sm">
                        <a href="{{ url($page->path) }}" target="_blank" class="truncate text-gray-800 hover:text-primary-700">{{ $page->path === '/' ? '/ (Homepage)' : $page->path }}</a>
                        <span class="shrink-0 text-gray-500"><strong class="text-gray-900">{{ number_format($page->views) }}</strong> views · {{ number_format($page->visitors) }} visitors</span>
                    </div>
                </div>
            @empty
                <p class="px-6 py-10 text-center text-sm text-gray-400">No visits recorded in this period yet.</p>
            @endforelse
        </div>

        {{-- Where visitors come from --}}
        <div class="bg-white shadow-sm sm:rounded-lg">
            <div class="border-b border-gray-100 px-6 py-4"><h3 class="font-semibold text-gray-800">Where visitors come from</h3></div>
            @forelse ($referrers as $ref)
                <div class="flex items-center justify-between gap-3 border-b border-gray-50 px-6 py-2.5 text-sm last:border-0">
                    <span class="truncate text-gray-700"><i class="fas fa-arrow-right-to-bracket mr-2 text-gray-300"></i>{{ $ref->referrer_host }}</span>
                    <span class="font-semibold text-gray-900">{{ number_format($ref->views) }}</span>
                </div>
            @empty
                <p class="px-6 py-8 text-center text-sm text-gray-400">No referrals yet. Visitors arrived directly or from search apps that hide the source.</p>
            @endforelse
        </div>
    </div>

    {{-- Devices, browsers, platforms --}}
    <div class="grid gap-4 md:grid-cols-3">
        @foreach (['Devices' => $devices, 'Browsers' => $browsers, 'Operating systems' => $platforms] as $title => $rows)
            <div class="bg-white shadow-sm sm:rounded-lg p-5">
                <h3 class="mb-4 font-semibold text-gray-800">{{ $title }}</h3>
                @forelse ($rows as $row)
                    <div class="mb-3 last:mb-0">
                        <div class="mb-1 flex justify-between text-sm">
                            <span class="text-gray-700">@if ($title === 'Devices')<i class="fas {{ $icons[$row['label']] ?? 'fa-circle' }} mr-2 w-4 text-gray-400"></i>@endif{{ ucfirst($row['label']) }}</span>
                            <span class="text-gray-500">{{ $row['percent'] }}%</span>
                        </div>
                        <div class="h-2 rounded-full bg-gray-100"><div class="h-2 rounded-full bg-primary-500" style="width: {{ $row['percent'] }}%"></div></div>
                    </div>
                @empty
                    <p class="text-sm text-gray-400">No data yet.</p>
                @endforelse
            </div>
        @endforeach
    </div>

    {{-- Visit log --}}
    <div class="bg-white shadow-sm sm:rounded-lg">
        <div class="flex flex-wrap items-center justify-between gap-2 border-b border-gray-100 px-6 py-4">
            <h3 class="font-semibold text-gray-800">{{ request('show') === 'blocked' ? 'Blocked requests' : 'Visit log' }}</h3>
            <div class="flex gap-2 text-sm">
                <a href="{{ route('admin.visitors.index', ['range' => $range]) }}" class="rounded-lg px-3 py-1 {{ request('show') !== 'blocked' ? 'bg-slate-900 text-white' : 'bg-gray-100 text-gray-700' }}">All</a>
                <a href="{{ route('admin.visitors.index', ['range' => $range, 'show' => 'blocked']) }}" class="rounded-lg px-3 py-1 {{ request('show') === 'blocked' ? 'bg-red-600 text-white' : 'bg-gray-100 text-gray-700' }}">Blocked only</a>
            </div>
        </div>
        @if ($recent->isEmpty())
            <p class="px-6 py-10 text-center text-sm text-gray-400">Nothing to show for this period.</p>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">When</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Page</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">From</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Device</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Network</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($recent as $visit)
                            <tr class="{{ $visit->wasBlocked() ? 'bg-red-50/50' : '' }}">
                                <td class="whitespace-nowrap px-6 py-2.5 text-gray-500" title="{{ $visit->created_at->format('d M Y H:i:s') }}">{{ $visit->created_at->diffForHumans() }}</td>
                                <td class="px-6 py-2.5">
                                    <span class="text-gray-800">{{ $visit->path }}</span>
                                    @if ($visit->wasBlocked())<span class="ml-2 rounded-full bg-red-100 px-2 py-0.5 text-[11px] font-semibold text-red-700">Blocked</span>@endif
                                </td>
                                <td class="px-6 py-2.5 text-gray-500">{{ $visit->referrer_host ?: 'Direct' }}</td>
                                <td class="whitespace-nowrap px-6 py-2.5 text-gray-500"><i class="fas {{ $icons[$visit->device] ?? 'fa-circle' }} mr-1 text-gray-400"></i>{{ $visit->browser }} · {{ $visit->platform }}</td>
                                <td class="whitespace-nowrap px-6 py-2.5 font-mono text-xs text-gray-400">{{ $visit->ip_masked }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4">{{ $recent->links() }}</div>
        @endif
    </div>

    <p class="text-xs text-gray-400">
        <i class="fas fa-user-shield mr-1"></i>
        Privacy: full IP addresses are never stored. The last part of each address is removed, visitors are recognised only within the same day,
        and entries older than {{ $retentionDays }} days are deleted automatically. Bots, link previews and signed-in admins are not counted.
    </p>
</x-app-layout>

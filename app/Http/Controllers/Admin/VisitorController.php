<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\Visit;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class VisitorController extends Controller
{
    public const RANGES = ['today' => 'Today', '7' => 'Last 7 days', '30' => 'Last 30 days', '90' => 'Last 90 days'];

    public function index(Request $request)
    {
        $range = array_key_exists($request->query('range'), self::RANGES) ? $request->query('range') : '7';
        $from = $range === 'today' ? today() : today()->subDays((int) $range - 1);

        $views = Visit::where('created_at', '>=', $from)->where('status', '!=', 429);
        $totalViews = (clone $views)->count();
        // visitor_id changes daily, so this counts each person once per day.
        $visitors = (clone $views)->distinct()->count('visitor_id');

        $stats = [
            'views' => $totalViews,
            'visitors' => $visitors,
            'pages_per_visitor' => $visitors ? round($totalViews / $visitors, 1) : 0,
            'blocked' => Visit::where('created_at', '>=', $from)->where('status', 429)->count(),
        ];

        $recent = Visit::where('created_at', '>=', $from)
            ->when($request->query('show') === 'blocked', fn ($q) => $q->where('status', 429))
            ->latest('created_at')->paginate(25)->withQueryString();

        return view('admin.visitors.index', [
            'range' => $range,
            'stats' => $stats,
            'series' => $this->series($range, $from),
            'topPages' => (clone $views)->selectRaw('path, COUNT(*) as views, COUNT(DISTINCT visitor_id) as visitors')
                ->groupBy('path')->orderByDesc('views')->take(10)->get(),
            'referrers' => (clone $views)->whereNotNull('referrer_host')->selectRaw('referrer_host, COUNT(*) as views')
                ->groupBy('referrer_host')->orderByDesc('views')->take(8)->get(),
            'devices' => $this->share(clone $views, 'device'),
            'browsers' => $this->share(clone $views, 'browser'),
            'platforms' => $this->share(clone $views, 'platform'),
            'recent' => $recent,
            'logEnabled' => Setting::get('visit_log_enabled', '1') === '1',
            'retentionDays' => (int) Setting::get('visit_log_days', '90'),
        ]);
    }

    /** Views and visitors per day, or per hour when looking at today. */
    private function series(string $range, Carbon $from): array
    {
        $hourly = $range === 'today';
        $bucket = $hourly ? 'HOUR(created_at)' : 'DATE(created_at)';
        $rows = Visit::where('created_at', '>=', $from)->where('status', '!=', 429)
            ->selectRaw("$bucket as bucket, COUNT(*) as views, COUNT(DISTINCT visitor_id) as visitors")
            ->groupBy('bucket')->get()->keyBy(fn ($r) => (string) $r->bucket);

        $series = [];
        $steps = $hourly ? 24 : (int) $range;
        for ($i = 0; $i < $steps; $i++) {
            $key = $hourly ? (string) $i : $from->copy()->addDays($i)->toDateString();
            $series[] = [
                'label' => $hourly ? sprintf('%02d:00', $i) : Carbon::parse($key)->format('D j M'),
                'views' => (int) ($rows[$key]->views ?? 0),
                'visitors' => (int) ($rows[$key]->visitors ?? 0),
            ];
        }

        return $series;
    }

    /** Share of views per value of a column, largest first, as [label, views, percent]. */
    private function share($query, string $column): array
    {
        $rows = $query->selectRaw("$column as label, COUNT(*) as views")->groupBy($column)->orderByDesc('views')->get();
        $total = max(1, $rows->sum('views'));

        return $rows->map(fn ($r) => ['label' => $r->label ?: 'Unknown', 'views' => (int) $r->views, 'percent' => round($r->views / $total * 100)])->all();
    }
}

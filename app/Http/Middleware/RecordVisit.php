<?php

namespace App\Http\Middleware;

use App\Models\Setting;
use App\Models\Visit;
use App\Support\UserAgent;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

/**
 * Logs public page views for the admin Visitors screen. Requests blocked by the rate limiter (429)
 * are logged too, so abuse shows up. Skips the admin area, signed-in admins, bots, and anything that
 * is not a normal HTML page view. Logging problems are reported but never break the page.
 */
class RecordVisit
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        try {
            if ($this->shouldRecord($request, $response)) {
                $this->record($request, $response);
            }
        } catch (\Throwable $e) {
            Log::warning('Visit not recorded: ' . $e->getMessage());
        }

        return $response;
    }

    private function shouldRecord(Request $request, Response $response): bool
    {
        if (Setting::get('visit_log_enabled', '1') !== '1') {
            return false;
        }
        if ($request->is('admin', 'admin/*', 'up') || $request->user()) {
            return false;
        }
        if (UserAgent::isBot($request->userAgent())) {
            return false;
        }
        if ($response->getStatusCode() === 429) {
            return true;
        }

        return $request->isMethod('GET')
            && $response->isSuccessful()
            && ! $request->ajax()
            && str_contains((string) $response->headers->get('Content-Type'), 'text/html');
    }

    private function record(Request $request, Response $response): void
    {
        $ua = (string) $request->userAgent();
        $referrer = (string) $request->headers->get('referer');
        $referrerHost = $referrer ? parse_url($referrer, PHP_URL_HOST) : null;
        if ($referrerHost === $request->getHost()) {
            $referrerHost = null; // moving between our own pages is not a referral
            $referrer = '';
        }

        Visit::create([
            'path' => mb_substr('/' . ltrim($request->path(), '/'), 0, 255),
            'route_name' => $request->route()?->getName(),
            // Same visitor on the same day gives the same id; it cannot be turned back into an IP.
            'visitor_id' => hash('sha256', $request->ip() . '|' . $ua . '|' . today()->toDateString() . '|' . config('app.key')),
            'ip_masked' => UserAgent::maskIp($request->ip()),
            'device' => UserAgent::device($ua),
            'browser' => UserAgent::browser($ua),
            'platform' => UserAgent::platform($ua),
            'referrer_host' => $referrerHost ? mb_substr(preg_replace('/^www\./', '', $referrerHost), 0, 191) : null,
            'referrer' => $referrer ? mb_substr($referrer, 0, 500) : null,
            'status' => $response->getStatusCode(),
        ]);

        // Occasionally clear out entries older than the retention period.
        if (random_int(1, 200) === 1) {
            $days = max(1, (int) Setting::get('visit_log_days', '90'));
            Visit::where('created_at', '<', now()->subDays($days))->delete();
        }
    }
}

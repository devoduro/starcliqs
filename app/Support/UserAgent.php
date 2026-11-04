<?php

namespace App\Support;

/**
 * Just enough user-agent parsing for the visitor log: device type, browser, platform, and whether
 * the request comes from a bot or link-preview fetcher (those are not counted as visitors).
 */
class UserAgent
{
    private const BOT_PATTERN = '/bot|crawl|spider|slurp|bingpreview|facebookexternalhit|whatsapp|telegram|discord|slack|skype|embedly|preview|headless|lighthouse|pingdom|uptime|monitor|curl|wget|python|httpclient|okhttp|go-http|java\/|php\/|axios|node-fetch|postman|insomnia|scrapy|semrush|ahrefs|mj12|dotbot|petalbot|yandex|baidu/i';

    public static function isBot(?string $ua): bool
    {
        return $ua === null || trim($ua) === '' || (bool) preg_match(self::BOT_PATTERN, $ua);
    }

    public static function device(string $ua): string
    {
        if (preg_match('/ipad|tablet|playbook|silk|(android(?!.*mobile))/i', $ua)) {
            return 'tablet';
        }

        return preg_match('/mobi|iphone|ipod|android|blackberry|opera mini|iemobile/i', $ua) ? 'mobile' : 'desktop';
    }

    public static function browser(string $ua): string
    {
        // Order matters: Edge and Opera also say "Chrome", Chrome also says "Safari".
        return match (true) {
            (bool) preg_match('/edg(e|a|ios)?\//i', $ua) => 'Edge',
            (bool) preg_match('/opr\/|opera/i', $ua) => 'Opera',
            (bool) preg_match('/samsungbrowser/i', $ua) => 'Samsung Internet',
            (bool) preg_match('/firefox|fxios/i', $ua) => 'Firefox',
            (bool) preg_match('/chrome|crios/i', $ua) => 'Chrome',
            (bool) preg_match('/safari/i', $ua) => 'Safari',
            default => 'Other',
        };
    }

    public static function platform(string $ua): string
    {
        return match (true) {
            (bool) preg_match('/android/i', $ua) => 'Android',
            (bool) preg_match('/iphone|ipad|ipod/i', $ua) => 'iOS',
            (bool) preg_match('/windows/i', $ua) => 'Windows',
            (bool) preg_match('/mac os x|macintosh/i', $ua) => 'macOS',
            (bool) preg_match('/cros/i', $ua) => 'ChromeOS',
            (bool) preg_match('/linux/i', $ua) => 'Linux',
            default => 'Other',
        };
    }

    /** 154.160.12.87 → 154.160.12.0 ; IPv6 keeps only its network half (2001:db8:a:b::). */
    public static function maskIp(?string $ip): ?string
    {
        if (! $ip || ! filter_var($ip, FILTER_VALIDATE_IP)) {
            return null;
        }
        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            return preg_replace('/\.\d+$/', '.0', $ip);
        }

        return inet_ntop(substr(inet_pton($ip), 0, 8) . str_repeat("\0", 8));
    }
}

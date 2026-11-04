<?php

use App\Models\Setting;

if (! function_exists('media_url')) {
    /** Public URL of a file on the `uploads` disk, or null when there is no file. */
    function media_url(?string $path): ?string
    {
        return $path ? asset('uploads/' . $path) : null;
    }
}

if (! function_exists('rich_text')) {
    /**
     * Turns the plain text typed in the admin into safe HTML: blank lines separate paragraphs,
     * "## " starts a sub-heading and lines starting with "- " become a bullet list.
     */
    function rich_text(?string $text): string
    {
        // Read line by line, so a heading can sit directly above its list or paragraph.
        $html = '';
        $paragraph = [];
        $items = [];
        $flush = function () use (&$html, &$paragraph, &$items) {
            if ($paragraph) {
                $html .= '<p>' . implode('<br>', array_map('e', $paragraph)) . '</p>';
                $paragraph = [];
            }
            if ($items) {
                $html .= '<ul>' . implode('', array_map(fn ($item) => '<li>' . e($item) . '</li>', $items)) . '</ul>';
                $items = [];
            }
        };

        foreach (preg_split('/\R/', trim((string) $text)) as $line) {
            $line = trim($line);
            if ($line === '') {
                $flush();
            } elseif (str_starts_with($line, '## ')) {
                $flush();
                $html .= '<h2>' . e(trim(substr($line, 3))) . '</h2>';
            } elseif (preg_match('/^[-*•]\s+(.*)$/u', $line, $m)) {
                if ($paragraph) {
                    $flush();
                }
                $items[] = $m[1];
            } else {
                if ($items) {
                    $flush();
                }
                $paragraph[] = $line;
            }
        }
        $flush();

        return $html;
    }
}

if (! function_exists('setting')) {
    function setting(string $key, ?string $default = null): ?string
    {
        return Setting::get($key, $default);
    }
}

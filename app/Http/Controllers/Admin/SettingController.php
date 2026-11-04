<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\SavesContent;
use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    use SavesContent;

    /**
     * Every editable site setting, grouped into the cards shown on the settings screen.
     * type: text | textarea | lines (one item per line) | url | email | number
     */
    public const GROUPS = [
        'Company' => [
            'icon' => 'fa-building',
            'fields' => [
                'site_name' => ['label' => 'Company name', 'type' => 'text', 'rules' => 'required|string|max:100'],
                'tagline' => ['label' => 'Tagline', 'type' => 'text', 'rules' => 'nullable|string|max:255'],
                'meta_description' => ['label' => 'Search engine description', 'type' => 'textarea', 'rules' => 'nullable|string|max:300'],
                'founded_year' => ['label' => 'Year founded', 'type' => 'number', 'rules' => 'nullable|integer|min:1900|max:2100'],
            ],
        ],
        'Contact' => [
            'icon' => 'fa-address-book',
            'fields' => [
                'phone' => ['label' => 'Phone', 'type' => 'text', 'rules' => 'nullable|string|max:50'],
                'whatsapp' => ['label' => 'WhatsApp number (international, digits only)', 'type' => 'text', 'rules' => 'nullable|regex:/^[0-9]{8,15}$/'],
                'email' => ['label' => 'Email', 'type' => 'email', 'rules' => 'nullable|email|max:255'],
                'address' => ['label' => 'Address', 'type' => 'text', 'rules' => 'nullable|string|max:255'],
                'office_hours' => ['label' => 'Office hours', 'type' => 'text', 'rules' => 'nullable|string|max:255'],
            ],
        ],
        'Homepage hero' => [
            'icon' => 'fa-star',
            'fields' => [
                'hero_eyebrow' => ['label' => 'Small label above the headline (optional)', 'type' => 'text', 'rules' => 'nullable|string|max:100'],
                'hero_title' => ['label' => 'Headline — start (typed out letter by letter)', 'type' => 'text', 'rules' => 'required|string|max:150'],
                'hero_highlight' => ['label' => 'Headline — end (typed straight after the start)', 'type' => 'text', 'rules' => 'nullable|string|max:100'],
                'hero_subtitle' => ['label' => 'Supporting sentence', 'type' => 'textarea', 'rules' => 'nullable|string|max:400'],
            ],
        ],
        'About & numbers' => [
            'icon' => 'fa-chart-simple',
            'fields' => [
                'about_title' => ['label' => 'About heading', 'type' => 'text', 'rules' => 'nullable|string|max:150'],
                'about_body' => ['label' => 'About text', 'type' => 'textarea', 'rules' => 'nullable|string|max:2000'],
                'services_intro' => ['label' => 'Services section intro (under "Our Services")', 'type' => 'textarea', 'rules' => 'nullable|string|max:300'],
                'about_highlights' => ['label' => 'Why choose us (one per line)', 'type' => 'lines', 'rules' => 'nullable|string|max:2000'],
                'stat_years' => ['label' => 'Years of experience', 'type' => 'number', 'rules' => 'nullable|integer|min:0'],
                'stat_projects' => ['label' => 'Projects delivered', 'type' => 'number', 'rules' => 'nullable|integer|min:0'],
                'stat_clients' => ['label' => 'Clients served', 'type' => 'number', 'rules' => 'nullable|integer|min:0'],
                'stat_success' => ['label' => 'Success rate (%)', 'type' => 'number', 'rules' => 'nullable|integer|min:0|max:100'],
            ],
        ],
        'Partners' => [
            'icon' => 'fa-handshake',
            'fields' => [
                'partners' => ['label' => 'Clients & partners shown in the scrolling band (one per line)', 'type' => 'lines', 'rules' => 'nullable|string|max:5000'],
            ],
        ],
        'Social media' => [
            'icon' => 'fa-share-nodes',
            'fields' => [
                'facebook_url' => ['label' => 'Facebook', 'type' => 'url', 'rules' => 'nullable|url|max:255'],
                'x_url' => ['label' => 'X (Twitter)', 'type' => 'url', 'rules' => 'nullable|url|max:255'],
                'linkedin_url' => ['label' => 'LinkedIn', 'type' => 'url', 'rules' => 'nullable|url|max:255'],
                'instagram_url' => ['label' => 'Instagram', 'type' => 'url', 'rules' => 'nullable|url|max:255'],
                'youtube_url' => ['label' => 'YouTube', 'type' => 'url', 'rules' => 'nullable|url|max:255'],
            ],
        ],
        'Academy' => [
            'icon' => 'fa-graduation-cap',
            'fields' => [
                'academy_title' => ['label' => 'Headline — start', 'type' => 'text', 'rules' => 'nullable|string|max:120'],
                'academy_highlight' => ['label' => 'Headline — gold words', 'type' => 'text', 'rules' => 'nullable|string|max:80'],
                'academy_title_end' => ['label' => 'Headline — end', 'type' => 'text', 'rules' => 'nullable|string|max:80'],
                'academy_subtitle' => ['label' => 'Supporting sentence', 'type' => 'textarea', 'rules' => 'nullable|string|max:400'],
                'academy_onsite_location' => ['label' => 'Onsite campus (city)', 'type' => 'text', 'rules' => 'nullable|string|max:100'],
                'academy_onsite_text' => ['label' => 'Onsite classes description', 'type' => 'textarea', 'rules' => 'nullable|string|max:500'],
                'academy_onsite_points' => ['label' => 'Onsite highlights (one per line)', 'type' => 'lines', 'rules' => 'nullable|string|max:1000'],
                'academy_online_text' => ['label' => 'Online classes description', 'type' => 'textarea', 'rules' => 'nullable|string|max:500'],
                'academy_online_points' => ['label' => 'Online highlights (one per line)', 'type' => 'lines', 'rules' => 'nullable|string|max:1000'],
                'academy_completion_rate' => ['label' => 'Completion rate (%) — leave blank to hide', 'type' => 'number', 'rules' => 'nullable|integer|min:0|max:100'],
                'academy_students_trained' => ['label' => 'Students trained — leave blank to hide', 'type' => 'number', 'rules' => 'nullable|integer|min:0'],
                'academy_perks' => ['label' => 'Why learn with us (one per line, "Title | description")', 'type' => 'lines', 'rules' => 'nullable|string|max:1500'],
            ],
        ],
        'Security & visitors' => [
            'icon' => 'fa-shield',
            'fields' => [
                'rate_limit_per_minute' => ['label' => 'Page requests allowed per visitor, per minute', 'type' => 'number', 'rules' => 'required|integer|min:20|max:2000'],
                'form_limit_per_hour' => ['label' => 'Form submissions allowed per visitor, per hour', 'type' => 'number', 'rules' => 'required|integer|min:1|max:200'],
                'visit_log_enabled' => ['label' => 'Keep a log of website visitors', 'type' => 'toggle', 'rules' => 'boolean'],
                'visit_log_days' => ['label' => 'Keep visitor log entries for (days)', 'type' => 'number', 'rules' => 'required|integer|min:7|max:730'],
            ],
        ],
        'Data protection' => [
            'icon' => 'fa-shield-halved',
            'fields' => [
                'dpc_show' => ['label' => 'Show the Data Protection Commission badge in the website footer', 'type' => 'toggle', 'rules' => 'boolean'],
                'dpc_logo' => ['label' => 'Data Protection Commission logo', 'type' => 'image', 'rules' => 'nullable|image|max:2048'],
                'dpc_registration_number' => ['label' => 'Registration number (optional)', 'type' => 'text', 'rules' => 'nullable|string|max:100'],
                'dpc_url' => ['label' => 'Link for the badge', 'type' => 'url', 'rules' => 'nullable|url|max:255'],
            ],
        ],
    ];

    public function edit()
    {
        return view('admin.settings.edit', ['groups' => self::GROUPS, 'values' => Setting::values()]);
    }

    public function update(Request $request)
    {
        $rules = [];
        $labels = [];
        foreach (self::GROUPS as $group) {
            foreach ($group['fields'] as $key => $field) {
                $rules[$key] = $field['rules'];
                $labels[$key] = $field['label'];
            }
        }

        $data = $request->validate($rules, [], $labels);

        // Toggles post 0/1; image fields keep their file unless a new one is uploaded or "remove" is ticked.
        foreach (self::GROUPS as $group) {
            foreach ($group['fields'] as $key => $field) {
                if ($field['type'] === 'toggle') {
                    $data[$key] = $request->boolean($key) ? '1' : '0';
                } elseif ($field['type'] === 'image') {
                    $data[$key] = $this->storeImage($request, $key, 'settings', Setting::get($key));
                }
            }
        }

        Setting::put(array_map(fn ($v) => $v === null ? null : trim((string) $v), $data));

        return redirect()->route('admin.settings.edit')->with('success', 'Settings saved. The website now shows the new details.');
    }
}

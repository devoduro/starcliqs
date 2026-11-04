<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Enquiry;
use App\Models\Faq;
use App\Models\Post;
use App\Models\Product;
use App\Models\Project;
use App\Models\Service;
use App\Models\Setting;
use App\Models\Subscriber;
use App\Models\Testimonial;
use App\Models\Training;
use App\Models\Visit;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;

class DashboardController extends Controller
{
    public function index()
    {
        $programmes = Training::withCount('registrations')->published()->ordered()->get();

        $priority = [
            'unread' => Enquiry::whereNull('read_at')->count(),
            'registrations_week' => Enquiry::where('type', 'training')->where('created_at', '>=', now()->subDays(7))->count(),
            'open_programmes' => $programmes->filter->registrationOpen()->count(),
            'subscribers_month' => Subscriber::where('created_at', '>=', now()->subDays(30))->count(),
        ];

        $content = [
            ['label' => 'Services', 'icon' => 'fa-screwdriver-wrench', 'route' => 'admin.services.index', 'total' => Service::count(), 'live' => Service::published()->count()],
            ['label' => 'Products', 'icon' => 'fa-cubes', 'route' => 'admin.products.index', 'total' => Product::count(), 'live' => Product::published()->count()],
            ['label' => 'Projects', 'icon' => 'fa-briefcase', 'route' => 'admin.projects.index', 'total' => Project::count(), 'live' => Project::published()->count()],
            ['label' => 'Clients', 'icon' => 'fa-handshake', 'route' => 'admin.clients.index', 'total' => Client::count(), 'live' => Client::published()->count()],
            ['label' => 'Academy', 'icon' => 'fa-graduation-cap', 'route' => 'admin.trainings.index', 'total' => Training::count(), 'live' => $programmes->count()],
            ['label' => 'Testimonials', 'icon' => 'fa-quote-left', 'route' => 'admin.testimonials.index', 'total' => Testimonial::count(), 'live' => Testimonial::published()->count()],
            ['label' => 'FAQs', 'icon' => 'fa-circle-question', 'route' => 'admin.faqs.index', 'total' => Faq::count(), 'live' => Faq::published()->count()],
            ['label' => 'Blog posts', 'icon' => 'fa-newspaper', 'route' => 'admin.posts.index', 'total' => Post::count(), 'live' => Post::published()->count()],
        ];

        return view('admin.dashboard', [
            'priority' => $priority,
            'content' => $content,
            'programmes' => $programmes->take(5),
            'recentEnquiries' => Enquiry::with('training')->latest()->take(6)->get(),
            'activity' => $this->messagesPerDay(14),
            'attention' => $this->attentionItems(),
            'drafts' => Post::whereNull('published_at')->latest()->take(4)->get(),
            'latestClients' => Client::latest()->take(4)->get(),
            'subscriberTotal' => Subscriber::count(),
            'traffic' => $this->traffic(),
        ]);
    }

    /** Headline visitor numbers for the dashboard card (blocked requests are not page views). */
    private function traffic(): array
    {
        $views = fn ($from) => Visit::where('created_at', '>=', $from)->where('status', '!=', 429);
        $topPage = $views(today()->subDays(6))->selectRaw('path, COUNT(*) as views')->groupBy('path')->orderByDesc('views')->first();

        return [
            'today_views' => $views(today())->count(),
            'today_visitors' => $views(today())->distinct()->count('visitor_id'),
            'week_views' => $views(today()->subDays(6))->count(),
            'week_blocked' => Visit::where('created_at', '>=', today()->subDays(6))->where('status', 429)->count(),
            'top_page' => $topPage?->path,
        ];
    }

    /** Website messages (contact + registrations) per day, oldest first, for the activity chart. */
    private function messagesPerDay(int $days): array
    {
        $from = today()->subDays($days - 1);
        $counts = Enquiry::where('created_at', '>=', $from)
            ->selectRaw('DATE(created_at) as day, type, COUNT(*) as total')
            ->groupBy('day', 'type')->get();

        $series = [];
        for ($i = 0; $i < $days; $i++) {
            $day = $from->copy()->addDays($i)->toDateString();
            $series[$day] = [
                'label' => Carbon::parse($day)->format('D j'),
                'contact' => (int) $counts->where('day', $day)->where('type', 'contact')->sum('total'),
                'training' => (int) $counts->where('day', $day)->where('type', 'training')->sum('total'),
            ];
        }

        return $series;
    }

    /**
     * Gaps worth fixing before the site goes live, each with a link to where it is fixed.
     * Only items that currently apply are returned.
     */
    private function attentionItems(): array
    {
        $items = [];
        $add = function (bool $applies, string $icon, string $text, string $url, string $level = 'warn') use (&$items) {
            if ($applies) {
                $items[] = compact('icon', 'text', 'url', 'level');
            }
        };

        $admin = auth()->user();
        $seedPassword = env('ADMIN_PASSWORD');
        $add($seedPassword && $admin->email === env('ADMIN_EMAIL') && Hash::check($seedPassword, $admin->password),
            'fa-key', 'You are still using the setup password. Change it.', route('admin.profile'), 'danger');

        $unread = Enquiry::whereNull('read_at')->count();
        $add($unread > 0, 'fa-inbox', $unread . ' unread ' . str('message')->plural($unread) . ' waiting for a reply', route('admin.enquiries.index', ['status' => 'unread']), 'danger');

        $samplesProgrammes = Training::where(fn ($q) => $q->where('body', 'like', '%Sample programme%')->orWhere('body', 'like', '%Sample course%'))->count();
        $add($samplesProgrammes > 0, 'fa-graduation-cap', $samplesProgrammes . ' sample Academy ' . str('programme')->plural($samplesProgrammes) . ' with placeholder dates and fees', route('admin.trainings.index'));

        $samplePosts = Post::where('body', 'like', '%sample post created during setup%')->count();
        $add($samplePosts > 0, 'fa-newspaper', $samplePosts . ' sample blog ' . str('post')->plural($samplePosts) . ' still published', route('admin.posts.index'));

        $noLogo = Client::published()->whereNull('logo')->count();
        $add($noLogo > 0, 'fa-image', $noLogo . ' ' . str('client')->plural($noLogo) . ' showing initials instead of a logo', route('admin.clients.index'));

        $noLink = Client::published()->whereNull('website_url')->whereNull('product_id')->count();
        $add($noLink > 0, 'fa-link', $noLink . ' ' . str('client')->plural($noLink) . ' without a website link', route('admin.clients.index'), 'info');

        $noCover = Project::published()->whereNull('image')->count();
        $add($noCover > 0, 'fa-briefcase', $noCover . ' ' . str('project')->plural($noCover) . ' without a cover image', route('admin.projects.index'), 'info');

        $noProductImage = Product::published()->whereNull('image')->count();
        $add($noProductImage > 0, 'fa-cubes', $noProductImage . ' ' . str('product')->plural($noProductImage) . ' without a screenshot', route('admin.products.index'), 'info');

        $add(str_contains((string) Setting::get('email'), 'hicliqs'), 'fa-envelope', 'Contact email still uses the old hicliqs.com address', route('admin.settings.edit'));
        $add(Setting::get('dpc_show') === '1' && ! Setting::get('dpc_registration_number'), 'fa-shield-halved', 'Data Protection registration number not added to the footer badge', route('admin.settings.edit'), 'info');
        $add(! collect(['facebook_url', 'x_url', 'linkedin_url', 'instagram_url', 'youtube_url'])->contains(fn ($k) => Setting::get($k)),
            'fa-share-nodes', 'No social media links set, so the footer shows none', route('admin.settings.edit'), 'info');
        $add(! Setting::get('academy_students_trained') && ! Setting::get('academy_completion_rate'),
            'fa-chart-simple', 'Academy outcome numbers (students trained, completion rate) not set', route('admin.settings.edit'), 'info');

        return $items;
    }
}

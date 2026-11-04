<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Enquiry;
use App\Models\Faq;
use App\Models\Post;
use App\Models\Product;
use App\Models\Project;
use App\Models\Service;
use App\Models\Subscriber;
use App\Models\Testimonial;
use App\Models\Training;
use Illuminate\Http\Request;

/**
 * The public website. Everything it shows comes from the admin panel; only published records
 * (and blog posts whose publish date has passed) are visible here.
 */
class SiteController extends Controller
{
    public function home()
    {
        return view('site.home', [
            'services' => Service::published()->ordered()->get(),
            'products' => Product::published()->orderByDesc('is_featured')->ordered()->get(),
            'projects' => Project::published()->ordered()->take(6)->get(),
            'projectCount' => Project::published()->count(),
            'testimonials' => Testimonial::published()->ordered()->get(),
            'trainings' => Training::published()->ordered()->take(3)->get(),
            'posts' => Post::published()->latest('published_at')->take(3)->get(),
            'faqs' => Faq::published()->ordered()->get(),
            'clients' => Client::with('product')->published()->ordered()->get(),
        ]);
    }

    public function service(Service $service)
    {
        abort_unless($service->is_published, 404);

        return view('site.services.show', [
            'service' => $service,
            'others' => Service::published()->ordered()->whereKeyNot($service->id)->get(),
        ]);
    }

    public function products()
    {
        return view('site.products.index', ['products' => Product::published()->orderByDesc('is_featured')->ordered()->get()]);
    }

    public function product(Product $product)
    {
        abort_unless($product->is_published, 404);

        return view('site.products.show', [
            'product' => $product,
            'others' => Product::published()->ordered()->whereKeyNot($product->id)->take(3)->get(),
        ]);
    }

    public function projects(Request $request)
    {
        $projects = Project::published()
            ->when($request->filled('category'), fn ($q) => $q->where('category', $request->category))
            ->ordered()->paginate(9)->withQueryString();

        return view('site.projects.index', [
            'projects' => $projects,
            'categories' => Project::published()->whereNotNull('category')->distinct()->orderBy('category')->pluck('category'),
        ]);
    }

    public function project(Project $project)
    {
        abort_unless($project->is_published, 404);

        return view('site.projects.show', [
            'project' => $project,
            'related' => Project::published()->whereKeyNot($project->id)
                ->orderByRaw('category = ? DESC', [$project->category])->ordered()->take(3)->get(),
        ]);
    }

    public function academy()
    {
        return view('site.academy', ['programmes' => Training::published()->ordered()->get()]);
    }

    public function training(Training $training)
    {
        abort_unless($training->is_published, 404);

        return view('site.trainings.show', [
            'training' => $training,
            'others' => Training::published()->whereKeyNot($training->id)->ordered()->take(3)->get(),
        ]);
    }

    public function blog(Request $request)
    {
        $posts = Post::published()
            ->when($request->filled('category'), fn ($q) => $q->where('category', $request->category))
            ->latest('published_at')->paginate(9)->withQueryString();

        return view('site.blog.index', [
            'posts' => $posts,
            'categories' => Post::published()->whereNotNull('category')->distinct()->orderBy('category')->pluck('category'),
        ]);
    }

    public function post(Post $post)
    {
        abort_unless($post->isLive(), 404);

        return view('site.blog.show', [
            'post' => $post,
            'recent' => Post::published()->whereKeyNot($post->id)->latest('published_at')->take(3)->get(),
        ]);
    }

    /** Contact form and training registrations both land in the admin "Messages" inbox. */
    public function enquire(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:50',
            'subject' => 'nullable|string|max:255',
            'message' => 'nullable|string|max:5000',
            'training_id' => 'nullable|exists:trainings,id',
            'website' => 'prohibited', // honeypot: hidden from people, filled by bots
        ]);

        $data['type'] = ! empty($data['training_id']) ? 'training' : 'contact';
        if ($data['type'] === 'training') {
            $data['subject'] = 'Registration: ' . Training::find($data['training_id'])->title;
        }

        Enquiry::create($data);

        $message = $data['type'] === 'training'
            ? 'Thank you! Your registration has been received. We will contact you with the next steps.'
            : 'Thank you! Your message has been sent. We will get back to you shortly.';

        return back()->with('success', $message);
    }

    public function subscribe(Request $request)
    {
        $data = $request->validate(['email' => 'required|email|max:255']);
        Subscriber::firstOrCreate(['email' => strtolower($data['email'])]);

        return back()->with('success', 'You are subscribed. Thanks for following StarCliqs!');
    }
}

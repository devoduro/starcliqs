<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\SavesContent;
use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    use SavesContent;

    public function index(Request $request)
    {
        $posts = Post::query()
            ->when($request->filled('q'), fn ($q) => $q->where('title', 'like', '%' . $request->q . '%'))
            ->orderByRaw('published_at IS NULL DESC')
            ->latest('published_at')
            ->paginate(20)->withQueryString();

        return view('admin.posts.index', compact('posts'));
    }

    public function create()
    {
        return view('admin.posts.form', [
            'post' => new Post(['author' => auth()->user()->name, 'published_at' => now()]),
            'categories' => $this->categories(),
        ]);
    }

    public function store(Request $request)
    {
        $this->save($request, new Post());

        return redirect()->route('admin.posts.index')->with('success', 'Blog post saved successfully.');
    }

    public function edit(Post $post)
    {
        return view('admin.posts.form', ['post' => $post, 'categories' => $this->categories()]);
    }

    public function update(Request $request, Post $post)
    {
        $this->save($request, $post);

        return redirect()->route('admin.posts.index')->with('success', 'Blog post updated successfully.');
    }

    public function destroy(Post $post)
    {
        $this->deleteImage($post->cover_image);
        $post->delete();

        return redirect()->route('admin.posts.index')->with('success', 'Blog post deleted successfully.');
    }

    private function categories()
    {
        return Post::whereNotNull('category')->distinct()->orderBy('category')->pluck('category');
    }

    private function save(Request $request, Post $post): void
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'category' => 'nullable|string|max:100',
            'excerpt' => 'nullable|string|max:500',
            'body' => 'required|string',
            'cover_image' => 'nullable|image|max:4096',
            'author' => 'nullable|string|max:255',
            'status' => 'required|in:draft,published',
            'published_at' => 'nullable|date',
        ]);

        $data['cover_image'] = $this->storeImage($request, 'cover_image', 'posts', $post->cover_image);
        // A draft has no publish date; publishing without a date goes live now.
        $data['published_at'] = $data['status'] === 'draft' ? null : ($data['published_at'] ?? now());
        unset($data['status']);

        $post->fill($data)->save();
    }
}

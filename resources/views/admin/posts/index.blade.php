<x-app-layout title="Blog">
    <x-slot name="header">
        <div class="flex flex-wrap gap-3 justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Blog posts</h2>
            <div class="flex gap-2">
                <form method="GET" class="relative">
                    <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                    <input type="search" name="q" value="{{ request('q') }}" placeholder="Search titles" class="w-52 rounded-lg border-gray-300 text-sm focus:border-primary-500 focus:ring-primary-200" style="padding-left:2.25rem">
                </form>
                <x-button href="{{ route('admin.posts.create') }}" icon="fas fa-pen">Write Post</x-button>
            </div>
        </div>
    </x-slot>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6">
            @if ($posts->isEmpty())
                <x-empty-state icon="fa-newspaper" title="No posts found" text="The three latest posts show on the homepage." :href="route('admin.posts.create')" action="Write Post" />
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Author</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($posts as $post)
                                <tr>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            @if ($post->cover_image)
                                                <img src="{{ media_url($post->cover_image) }}" alt="" class="h-10 w-14 shrink-0 rounded-md object-cover">
                                            @else
                                                <span class="flex h-10 w-14 shrink-0 items-center justify-center rounded-md bg-gray-100 text-gray-400"><i class="fas fa-image"></i></span>
                                            @endif
                                            <div class="text-sm font-medium text-gray-900">{{ $post->title }}</div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $post->category ?: '—' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $post->author ?: '—' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        @if (! $post->published_at)
                                            <span class="rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-600">Draft</span>
                                        @elseif ($post->published_at->isFuture())
                                            <span class="rounded-full bg-blue-50 px-2.5 py-0.5 text-xs font-medium text-blue-700">Scheduled {{ $post->published_at->format('d M') }}</span>
                                        @else
                                            <span class="rounded-full bg-green-50 px-2.5 py-0.5 text-xs font-medium text-green-700">Live · {{ $post->published_at->format('d M Y') }}</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <x-row-actions :view="$post->isLive() ? route('blog.show', $post) : null" :edit="route('admin.posts.edit', $post)" :destroy="route('admin.posts.destroy', $post)" confirm="Delete this post?" />
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">{{ $posts->links() }}</div>
            @endif
        </div>
    </div>
</x-app-layout>

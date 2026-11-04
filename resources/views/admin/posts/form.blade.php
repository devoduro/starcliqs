@php $editing = $post->exists; @endphp
<x-app-layout :title="$editing ? 'Edit Post' : 'Write Post'">
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $editing ? 'Edit Post' : 'Write Post' }}</h2>
            <x-button href="{{ route('admin.posts.index') }}" variant="secondary" icon="fas fa-arrow-left">Back to Blog</x-button>
        </div>
    </x-slot>

    <form method="POST" action="{{ $editing ? route('admin.posts.update', $post) : route('admin.posts.store') }}" enctype="multipart/form-data" class="grid gap-4 lg:grid-cols-3">
        @csrf
        @if ($editing) @method('PUT') @endif

        <div class="lg:col-span-2 bg-white shadow-sm sm:rounded-lg p-6 space-y-6">
            <x-input name="title" label="Title" :value="old('title', $post->title)" required autofocus />
            <x-textarea name="excerpt" label="Excerpt" :value="old('excerpt', $post->excerpt)" rows="2" helper="Shown on blog cards. Leave blank to use the start of the post." />
            <x-textarea name="body" label="Post" :value="old('body', $post->body)" rows="18" required helper="Leave a blank line between paragraphs. Lines starting with ## become sub-headings." />
        </div>

        <div class="space-y-4">
            @php $status = old('status', $post->published_at ? 'published' : 'draft'); @endphp
            <div class="bg-white shadow-sm sm:rounded-lg p-6 space-y-5" x-data="{ status: @js($status) }">
                <div>
                    <span class="block text-sm font-medium text-gray-700 mb-1">Status</span>
                    <div class="grid grid-cols-2 gap-2">
                        <label class="flex cursor-pointer items-center justify-center gap-2 rounded-lg border px-3 py-2 text-sm" :class="status === 'draft' ? 'border-primary-500 bg-primary-50 text-primary-700' : 'border-gray-300 text-gray-600'">
                            <input type="radio" name="status" value="draft" x-model="status" class="sr-only"> <i class="fas fa-file-pen"></i> Draft
                        </label>
                        <label class="flex cursor-pointer items-center justify-center gap-2 rounded-lg border px-3 py-2 text-sm" :class="status === 'published' ? 'border-primary-500 bg-primary-50 text-primary-700' : 'border-gray-300 text-gray-600'">
                            <input type="radio" name="status" value="published" x-model="status" class="sr-only"> <i class="fas fa-globe"></i> Publish
                        </label>
                    </div>
                </div>
                <div x-show="status === 'published'">
                    <x-input name="published_at" type="datetime-local" label="Publish date" :value="old('published_at', $post->published_at?->format('Y-m-d\TH:i'))" helper="A future date schedules the post." />
                </div>
                <div>
                    <x-input name="category" label="Category" :value="old('category', $post->category)" list="post-categories" placeholder="e.g. Education" />
                    <datalist id="post-categories">
                        @foreach ($categories as $category)<option value="{{ $category }}">@endforeach
                    </datalist>
                </div>
                <x-input name="author" label="Author" :value="old('author', $post->author)" />
                <x-input name="slug" label="Web address (slug)" :value="old('slug', $post->slug)" helper="Leave blank to create one from the title." />
            </div>
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <x-image-field name="cover_image" label="Cover image" :current="$post->cover_image" />
            </div>
            <div class="flex justify-end space-x-3">
                <x-button href="{{ route('admin.posts.index') }}" variant="secondary">Cancel</x-button>
                <x-button type="submit" icon="fas fa-save">Save post</x-button>
            </div>
        </div>
    </form>
</x-app-layout>

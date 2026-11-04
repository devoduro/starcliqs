@php $editing = $project->exists; @endphp
<x-app-layout :title="$editing ? 'Edit Project' : 'Upload Project'">
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $editing ? 'Edit Project' : 'Upload Project' }}</h2>
            <x-button href="{{ route('admin.projects.index') }}" variant="secondary" icon="fas fa-arrow-left">Back to Projects</x-button>
        </div>
    </x-slot>

    <form method="POST" action="{{ $editing ? route('admin.projects.update', $project) : route('admin.projects.store') }}" enctype="multipart/form-data" class="grid gap-4 lg:grid-cols-3">
        @csrf
        @if ($editing) @method('PUT') @endif

        <div class="lg:col-span-2 bg-white shadow-sm sm:rounded-lg p-6 space-y-6">
            <x-input name="title" label="Project title" :value="old('title', $project->title)" required autofocus placeholder="e.g. DigiSchool rollout for Prempeh College" />
            <div class="grid gap-6 sm:grid-cols-2">
                <x-input name="client" label="Client" :value="old('client', $project->client)" placeholder="e.g. Prempeh College" />
                <div>
                    <x-input name="category" label="Category" :value="old('category', $project->category)" list="project-categories" placeholder="e.g. Education" helper="Used for the filter buttons on the homepage." />
                    <datalist id="project-categories">
                        @foreach ($categories as $category)<option value="{{ $category }}">@endforeach
                    </datalist>
                </div>
            </div>
            <x-textarea name="summary" label="Short summary" :value="old('summary', $project->summary)" rows="2" required helper="Shown on the project card." />
            <x-textarea name="body" label="Project story" :value="old('body', $project->body)" rows="10" helper="The challenge, what you built and the results. Leave a blank line between paragraphs." />
            <x-textarea name="technologies" label="Technologies / deliverables" :value="old('technologies', implode(PHP_EOL, $project->technologies ?? []))" rows="4" helper="One per line, e.g. Laravel, MySQL, Bulk SMS." />
        </div>

        <div class="space-y-4">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <x-image-field name="image" label="Cover image" :current="$project->image" />
            </div>
            <div class="bg-white shadow-sm sm:rounded-lg p-6 space-y-5">
                <x-toggle name="is_published" label="Published" :checked="$project->is_published" />
                <x-toggle name="is_featured" label="Featured" :checked="$project->is_featured" helper="Featured projects are shown first on the homepage." />
                <x-input name="completed_on" type="date" label="Completed on" :value="old('completed_on', $project->completed_on?->format('Y-m-d'))" />
                <x-input name="project_url" type="url" label="Live link" :value="old('project_url', $project->project_url)" placeholder="https://" />
                <x-input name="slug" label="Web address (slug)" :value="old('slug', $project->slug)" helper="Leave blank to create one from the title." />
            </div>
            <div class="flex justify-end space-x-3">
                <x-button href="{{ route('admin.projects.index') }}" variant="secondary">Cancel</x-button>
                <x-button type="submit" icon="fas fa-save">{{ $editing ? 'Save changes' : 'Upload Project' }}</x-button>
            </div>
        </div>
    </form>
</x-app-layout>

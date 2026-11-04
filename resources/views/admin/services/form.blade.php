@php $editing = $service->exists; @endphp
<x-app-layout :title="$editing ? 'Edit Service' : 'Add Service'">
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $editing ? 'Edit Service' : 'Add Service' }}</h2>
            <x-button href="{{ route('admin.services.index') }}" variant="secondary" icon="fas fa-arrow-left">Back to Services</x-button>
        </div>
    </x-slot>

    <form method="POST" action="{{ $editing ? route('admin.services.update', $service) : route('admin.services.store') }}" enctype="multipart/form-data" class="grid gap-4 lg:grid-cols-3">
        @csrf
        @if ($editing) @method('PUT') @endif

        <div class="lg:col-span-2 bg-white shadow-sm sm:rounded-lg p-6 space-y-6">
            <x-input name="title" label="Service name" :value="old('title', $service->title)" required autofocus placeholder="e.g. Application Development" />
            <x-textarea name="summary" label="Short summary" :value="old('summary', $service->summary)" rows="2" required helper="One or two sentences, shown on the homepage card." />
            <x-textarea name="features" label="Key features" :value="old('features', implode(PHP_EOL, $service->features ?? []))" rows="6" helper="One feature per line. The first three show on the homepage card." placeholder="Web and mobile apps&#10;Cloud hosting&#10;Ongoing support" />
            <x-textarea name="body" label="Full details" :value="old('body', $service->body)" rows="10" helper="Shown on the service's own page. Leave a blank line between paragraphs." />
        </div>

        <div class="space-y-4">
            <div class="bg-white shadow-sm sm:rounded-lg p-6 space-y-5">
                <x-toggle name="is_published" label="Published" :checked="$service->is_published" helper="Hidden services are kept but not shown on the website." />
                @include('admin.partials.icon-field', ['value' => old('icon', $service->icon)])
                <x-input name="sort_order" type="number" label="Display order" :value="old('sort_order', $service->sort_order ?? 0)" helper="Lower numbers show first." min="0" />
                <x-input name="slug" label="Web address (slug)" :value="old('slug', $service->slug)" helper="Leave blank to create one from the name." />
            </div>
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <x-image-field name="image" label="Banner image" :current="$service->image" />
            </div>
            <div class="flex justify-end space-x-3">
                <x-button href="{{ route('admin.services.index') }}" variant="secondary">Cancel</x-button>
                <x-button type="submit" icon="fas fa-save">{{ $editing ? 'Save changes' : 'Add Service' }}</x-button>
            </div>
        </div>
    </form>
</x-app-layout>

@php $editing = $product->exists; @endphp
<x-app-layout :title="$editing ? 'Edit Product' : 'Add Product'">
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $editing ? 'Edit Product' : 'Add Product' }}</h2>
            <x-button href="{{ route('admin.products.index') }}" variant="secondary" icon="fas fa-arrow-left">Back to Products</x-button>
        </div>
    </x-slot>

    <form method="POST" action="{{ $editing ? route('admin.products.update', $product) : route('admin.products.store') }}" enctype="multipart/form-data" class="grid gap-4 lg:grid-cols-3">
        @csrf
        @if ($editing) @method('PUT') @endif

        <div class="lg:col-span-2 bg-white shadow-sm sm:rounded-lg p-6 space-y-6">
            <div class="grid gap-6 sm:grid-cols-2">
                <x-input name="name" label="Product name" :value="old('name', $product->name)" required autofocus placeholder="e.g. DigiSchool" />
                <x-input name="tagline" label="Tagline" :value="old('tagline', $product->tagline)" placeholder="e.g. School management, simplified" />
            </div>
            <x-textarea name="summary" label="Short description" :value="old('summary', $product->summary)" rows="3" required helper="Shown in the homepage showcase." />
            <x-textarea name="features" label="Features" :value="old('features', implode(PHP_EOL, $product->features ?? []))" rows="8" helper="One feature per line." placeholder="Online fee payments&#10;Terminal reports&#10;Parent SMS alerts" />
            <x-textarea name="body" label="Full details" :value="old('body', $product->body)" rows="10" helper="Shown on the product page. Leave a blank line between paragraphs." />
        </div>

        <div class="space-y-4">
            <div class="bg-white shadow-sm sm:rounded-lg p-6 space-y-5">
                <x-toggle name="is_published" label="Published" :checked="$product->is_published" />
                <x-toggle name="is_featured" label="Featured" :checked="$product->is_featured" helper="Featured products are listed first." />
                @include('admin.partials.icon-field', ['value' => old('icon', $product->icon)])
                <x-input name="price_label" label="Price label" :value="old('price_label', $product->price_label)" placeholder="e.g. From GH₵ 2,000 / term" />
                <x-input name="demo_url" type="url" label="Demo or product link" :value="old('demo_url', $product->demo_url)" placeholder="https://" />
                <x-input name="sort_order" type="number" label="Display order" :value="old('sort_order', $product->sort_order ?? 0)" min="0" />
                <x-input name="slug" label="Web address (slug)" :value="old('slug', $product->slug)" helper="Leave blank to create one from the name." />
            </div>
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <x-image-field name="image" label="Screenshot / image" :current="$product->image" />
            </div>
            <div class="flex justify-end space-x-3">
                <x-button href="{{ route('admin.products.index') }}" variant="secondary">Cancel</x-button>
                <x-button type="submit" icon="fas fa-save">{{ $editing ? 'Save changes' : 'Add Product' }}</x-button>
            </div>
        </div>
    </form>
</x-app-layout>

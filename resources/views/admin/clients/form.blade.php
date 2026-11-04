@php $editing = $client->exists; @endphp
<x-app-layout :title="$editing ? 'Edit Client' : 'Add Client'">
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $editing ? 'Edit Client' : 'Add Client' }}</h2>
            <x-button href="{{ route('admin.clients.index') }}" variant="secondary" icon="fas fa-arrow-left">Back to Clients</x-button>
        </div>
    </x-slot>

    <form method="POST" action="{{ $editing ? route('admin.clients.update', $client) : route('admin.clients.store') }}" enctype="multipart/form-data" class="grid gap-4 lg:grid-cols-3">
        @csrf
        @if ($editing) @method('PUT') @endif

        <div class="lg:col-span-2 bg-white shadow-sm sm:rounded-lg p-6 space-y-6">
            <x-input name="name" label="Client name" :value="old('name', $client->name)" required autofocus placeholder="e.g. Prempeh College" />
            <x-input name="website_url" type="url" label="Website" :value="old('website_url', $client->website_url)" placeholder="https://" helper="The logo on the homepage links here. Leave blank if they have no website." />
            <div>
                <label for="product_id" class="block text-sm font-medium text-gray-700 mb-1">Linked product (optional)</label>
                <select id="product_id" name="product_id" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-200 focus:ring-opacity-50">
                    <option value="">None</option>
                    @foreach ($products as $product)
                        <option value="{{ $product->id }}" @selected((string) old('product_id', $client->product_id) === (string) $product->id)>{{ $product->name }}</option>
                    @endforeach
                </select>
                <p class="mt-1 text-sm text-gray-500">The product we built for this client. If there is no website, the logo links to that product's page.</p>
                @error('product_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <x-image-field name="logo" label="Logo" :current="$client->logo" helper="PNG with a transparent background works best. Up to 2 MB." />
        </div>

        <div class="space-y-4">
            <div class="bg-white shadow-sm sm:rounded-lg p-6 space-y-5">
                <x-toggle name="is_published" label="Show on the website" :checked="$client->is_published" />
                <x-input name="sort_order" type="number" label="Display order" :value="old('sort_order', $client->sort_order ?? 0)" min="0" helper="Lower numbers show first." />
            </div>
            <div class="flex justify-end space-x-3">
                <x-button href="{{ route('admin.clients.index') }}" variant="secondary">Cancel</x-button>
                <x-button type="submit" icon="fas fa-save">{{ $editing ? 'Save changes' : 'Add Client' }}</x-button>
            </div>
        </div>
    </form>
</x-app-layout>

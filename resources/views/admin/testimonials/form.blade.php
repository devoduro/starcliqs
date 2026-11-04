@php $editing = $testimonial->exists; @endphp
<x-app-layout :title="$editing ? 'Edit Testimonial' : 'Add Testimonial'">
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $editing ? 'Edit Testimonial' : 'Add Testimonial' }}</h2>
            <x-button href="{{ route('admin.testimonials.index') }}" variant="secondary" icon="fas fa-arrow-left">Back to Testimonials</x-button>
        </div>
    </x-slot>

    <form method="POST" action="{{ $editing ? route('admin.testimonials.update', $testimonial) : route('admin.testimonials.store') }}" enctype="multipart/form-data" class="grid gap-4 lg:grid-cols-3">
        @csrf
        @if ($editing) @method('PUT') @endif

        <div class="lg:col-span-2 bg-white shadow-sm sm:rounded-lg p-6 space-y-6">
            <x-input name="name" label="Client name" :value="old('name', $testimonial->name)" required autofocus placeholder="e.g. Mrs. Akua Mensah" />
            <div class="grid gap-6 sm:grid-cols-2">
                <x-input name="role" label="Role / title" :value="old('role', $testimonial->role)" placeholder="e.g. Headmistress" />
                <x-input name="company" label="Organisation" :value="old('company', $testimonial->company)" placeholder="e.g. Prempeh College" />
            </div>
            <x-textarea name="quote" label="Testimony" :value="old('quote', $testimonial->quote)" rows="6" required />
            <div x-data="{ rating: {{ (int) old('rating', $testimonial->rating ?? 5) }} }">
                <label class="block text-sm font-medium text-gray-700 mb-1">Rating</label>
                <input type="hidden" name="rating" :value="rating">
                <div class="flex gap-1 text-2xl">
                    @for ($i = 1; $i <= 5; $i++)
                        <button type="button" @click="rating = {{ $i }}" class="text-primary-500 focus:outline-none" aria-label="{{ $i }} stars"><i :class="rating >= {{ $i }} ? 'fas' : 'far'" class="fa-star"></i></button>
                    @endfor
                </div>
            </div>
        </div>

        <div class="space-y-4">
            <div class="bg-white shadow-sm sm:rounded-lg p-6 space-y-5">
                <x-toggle name="is_published" label="Published" :checked="$testimonial->is_published" />
                <x-input name="sort_order" type="number" label="Display order" :value="old('sort_order', $testimonial->sort_order ?? 0)" min="0" />
            </div>
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <x-image-field name="photo" label="Photo (optional)" :current="$testimonial->photo" helper="A square headshot works best. Up to 2 MB." />
            </div>
            <div class="flex justify-end space-x-3">
                <x-button href="{{ route('admin.testimonials.index') }}" variant="secondary">Cancel</x-button>
                <x-button type="submit" icon="fas fa-save">{{ $editing ? 'Save changes' : 'Add Testimonial' }}</x-button>
            </div>
        </div>
    </form>
</x-app-layout>

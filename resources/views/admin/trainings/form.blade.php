@php $editing = $training->exists; @endphp
<x-app-layout :title="$editing ? 'Edit Training' : 'Add Training'">
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $editing ? 'Edit Training' : 'Add Training' }}</h2>
            <x-button href="{{ route('admin.trainings.index') }}" variant="secondary" icon="fas fa-arrow-left">Back to Trainings</x-button>
        </div>
    </x-slot>

    <form method="POST" action="{{ $editing ? route('admin.trainings.update', $training) : route('admin.trainings.store') }}" enctype="multipart/form-data" class="grid gap-4 lg:grid-cols-3">
        @csrf
        @if ($editing) @method('PUT') @endif

        <div class="lg:col-span-2 bg-white shadow-sm sm:rounded-lg p-6 space-y-6">
            <x-input name="title" label="Programme title" :value="old('title', $training->title)" required autofocus placeholder="e.g. Fullstack Software Engineering Program" />
            <div class="grid gap-6 sm:grid-cols-2">
                <x-input name="level" label="Level" :value="old('level', $training->level)" placeholder="e.g. Beginner → Intermediate" />
                <x-input name="schedule" label="Class schedule" :value="old('schedule', $training->schedule)" placeholder="e.g. Mon – Wed (weekly) · 6:00pm GMT" />
            </div>
            <x-textarea name="summary" label="Short summary" :value="old('summary', $training->summary)" rows="2" required />
            <x-textarea name="outline" label="Course outline" :value="old('outline', implode(PHP_EOL, $training->outline ?? []))" rows="7" helper="One module or topic per line." />
            <x-textarea name="body" label="Full details" :value="old('body', $training->body)" rows="8" helper="Who it is for, requirements, certificate, venue… Leave a blank line between paragraphs." />
        </div>

        <div class="space-y-4">
            <div class="bg-white shadow-sm sm:rounded-lg p-6 space-y-5">
                <x-toggle name="is_published" label="Published" :checked="$training->is_published" helper="Published courses accept registrations." />
                <x-input name="starts_on" type="date" label="Start date" :value="old('starts_on', $training->starts_on?->format('Y-m-d'))" helper="Leave blank for rolling admission." />
                <x-input name="registration_closes_on" type="date" label="Registration closes" :value="old('registration_closes_on', $training->registration_closes_on?->format('Y-m-d'))" helper="Shown as a deadline on the Academy page." />
                @include('admin.partials.icon-field', ['value' => old('icon', $training->icon ?: 'fa-code')])
                <x-input name="duration" label="Duration" :value="old('duration', $training->duration)" placeholder="e.g. 6 months" />
                <div>
                    <label for="mode" class="block text-sm font-medium text-gray-700 mb-1">Mode <span class="text-red-500">*</span></label>
                    <select id="mode" name="mode" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-200 focus:ring-opacity-50">
                        @foreach (\App\Models\Training::MODES as $value => $label)
                            <option value="{{ $value }}" @selected(old('mode', $training->mode) === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <x-input name="fee" type="number" step="0.01" min="0" label="Fee (GH₵)" :value="old('fee', $training->fee)" helper="0 shows as Free; blank shows Contact us." />
                <x-input name="sort_order" type="number" label="Display order" :value="old('sort_order', $training->sort_order ?? 0)" min="0" />
                <x-input name="slug" label="Web address (slug)" :value="old('slug', $training->slug)" helper="Leave blank to create one from the title." />
            </div>
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <x-image-field name="image" label="Course image" :current="$training->image" />
            </div>
            <div class="flex justify-end space-x-3">
                <x-button href="{{ route('admin.trainings.index') }}" variant="secondary">Cancel</x-button>
                <x-button type="submit" icon="fas fa-save">{{ $editing ? 'Save changes' : 'Add Training' }}</x-button>
            </div>
        </div>
    </form>
</x-app-layout>

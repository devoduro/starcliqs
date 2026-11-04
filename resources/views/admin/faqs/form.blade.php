@php $editing = $faq->exists; @endphp
<x-app-layout :title="$editing ? 'Edit FAQ' : 'Add FAQ'">
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $editing ? 'Edit FAQ' : 'Add FAQ' }}</h2>
            <x-button href="{{ route('admin.faqs.index') }}" variant="secondary" icon="fas fa-arrow-left">Back to FAQs</x-button>
        </div>
    </x-slot>

    <form method="POST" action="{{ $editing ? route('admin.faqs.update', $faq) : route('admin.faqs.store') }}" class="grid gap-4 lg:grid-cols-3">
        @csrf
        @if ($editing) @method('PUT') @endif

        <div class="lg:col-span-2 bg-white shadow-sm sm:rounded-lg p-6 space-y-6">
            <x-input name="topic" label="Card title" :value="old('topic', $faq->topic)" required autofocus placeholder="e.g. School Management" />
            <x-input name="question" label="Question" :value="old('question', $faq->question)" required placeholder="e.g. What does DigiSchool cover?" />
            <x-textarea name="answer" label="Answer" :value="old('answer', $faq->answer)" rows="5" required />
        </div>

        <div class="space-y-4">
            <div class="bg-white shadow-sm sm:rounded-lg p-6 space-y-5">
                <x-toggle name="is_published" label="Published" :checked="$faq->is_published" />
                @include('admin.partials.icon-field', ['value' => old('icon', $faq->icon)])
                <x-input name="sort_order" type="number" label="Display order" :value="old('sort_order', $faq->sort_order ?? 0)" min="0" />
            </div>
            <div class="flex justify-end space-x-3">
                <x-button href="{{ route('admin.faqs.index') }}" variant="secondary">Cancel</x-button>
                <x-button type="submit" icon="fas fa-save">{{ $editing ? 'Save changes' : 'Add FAQ' }}</x-button>
            </div>
        </div>
    </form>
</x-app-layout>

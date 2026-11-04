@props([
    'name',
    'id' => null,
    'label' => null,
    'value' => null,
    'rows' => 4,
    'required' => false,
    'helper' => null,
    'placeholder' => null,
])

@php
    $id = $id ?? $name;
    $hasError = $errors->has($name);
@endphp

<div>
    @if ($label)
        <label for="{{ $id }}" class="block text-sm font-medium text-gray-700 mb-1">
            {{ $label }} @if ($required)<span class="text-red-500">*</span>@endif
        </label>
    @endif
    <textarea name="{{ $name }}" id="{{ $id }}" rows="{{ $rows }}"
        @if ($placeholder) placeholder="{{ $placeholder }}" @endif
        @if ($required) required @endif
        {{ $attributes->merge(['class' => 'w-full rounded-lg shadow-sm focus:ring focus:ring-opacity-50 ' . ($hasError ? 'border-red-300 focus:border-red-500 focus:ring-red-200' : 'border-gray-300 focus:border-primary-500 focus:ring-primary-200')]) }}>{{ $value }}</textarea>
    @if ($helper && ! $hasError)
        <p class="mt-1 text-sm text-gray-500">{{ $helper }}</p>
    @endif
    @error($name)
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>

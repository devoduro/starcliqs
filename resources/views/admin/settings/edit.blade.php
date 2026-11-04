<x-app-layout title="Site settings">
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Site settings</h2>
            <x-button href="{{ route('home') }}" target="_blank" variant="secondary" icon="fas fa-arrow-up-right-from-square">View website</x-button>
        </div>
    </x-slot>

    <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data" class="space-y-4">
        @csrf
        @method('PUT')

        <div class="grid gap-4 xl:grid-cols-2">
            @foreach ($groups as $groupName => $group)
                <div class="bg-white shadow-sm sm:rounded-lg p-6 {{ $groupName === 'About & numbers' ? 'xl:row-span-2' : '' }}">
                    <h3 class="mb-5 flex items-center gap-2 font-semibold text-gray-800"><i class="fas {{ $group['icon'] }} text-primary-500"></i> {{ $groupName }}</h3>
                    <div class="grid gap-5 sm:grid-cols-2">
                        @foreach ($group['fields'] as $key => $field)
                            @php $value = old($key, $values[$key] ?? ''); $required = str_contains($field['rules'], 'required'); @endphp
                            @if ($field['type'] === 'toggle')
                                <div class="sm:col-span-2">
                                    <x-toggle :name="$key" :label="$field['label']" :checked="($values[$key] ?? '0') === '1'" />
                                </div>
                            @elseif ($field['type'] === 'image')
                                <div class="sm:col-span-2">
                                    <x-image-field :name="$key" :label="$field['label']" :current="$values[$key] ?? null" helper="PNG with a transparent background works best. Up to 2 MB." />
                                </div>
                            @elseif (in_array($field['type'], ['textarea', 'lines']))
                                <div class="sm:col-span-2">
                                    <x-textarea :name="$key" :label="$field['label']" :value="$value" :rows="$field['type'] === 'lines' ? 6 : 3" :required="$required" />
                                </div>
                            @else
                                <div class="{{ in_array($field['type'], ['number']) ? '' : 'sm:col-span-2' }}">
                                    <x-input :name="$key" :type="$field['type'] === 'number' ? 'number' : $field['type']" :label="$field['label']" :value="$value" :required="$required" />
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>

        <div class="sticky bottom-0 -mx-4 md:-mx-6 border-t border-gray-200 bg-white/90 px-4 md:px-6 py-3 backdrop-blur flex justify-end">
            <x-button type="submit" icon="fas fa-save">Save settings</x-button>
        </div>
    </form>
</x-app-layout>

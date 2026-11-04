@props(['name', 'label' => 'Image', 'current' => null, 'helper' => 'JPG, PNG or WebP, up to 4 MB.'])

{{-- File picker with a live preview, plus a "remove" tick-box when a file is already saved. --}}
<div x-data="{ preview: @js(media_url($current)), removed: false }">
    <label class="block text-sm font-medium text-gray-700 mb-1">{{ $label }}</label>
    <div class="flex flex-col sm:flex-row gap-4 sm:items-center">
        <div class="h-28 w-44 shrink-0 overflow-hidden rounded-lg border border-dashed border-gray-300 bg-gray-50 flex items-center justify-center">
            <template x-if="preview && !removed"><img :src="preview" alt="" class="h-full w-full object-cover"></template>
            <template x-if="!preview || removed"><i class="fas fa-image text-3xl text-gray-300"></i></template>
        </div>
        <div class="space-y-2 text-sm">
            <input type="file" name="{{ $name }}" accept="image/*"
                   @change="const f = $event.target.files[0]; if (f) { preview = URL.createObjectURL(f); removed = false; }"
                   class="block text-sm text-gray-600 file:mr-3 file:rounded-lg file:border-0 file:bg-primary-50 file:px-4 file:py-2 file:text-sm file:font-medium file:text-primary-700 hover:file:bg-primary-100">
            <p class="text-gray-500">{{ $helper }}</p>
            @if ($current)
                <label class="inline-flex items-center gap-2 text-red-600">
                    <input type="checkbox" name="remove_{{ $name }}" value="1" x-model="removed" class="rounded border-gray-300 text-red-600 focus:ring-red-500">
                    Remove current image
                </label>
            @endif
            @error($name)<p class="text-red-600">{{ $message }}</p>@enderror
        </div>
    </div>
</div>

@props(['name', 'label', 'checked' => false, 'helper' => null])

<label class="flex items-start gap-3 cursor-pointer select-none">
    <input type="hidden" name="{{ $name }}" value="0">
    <input type="checkbox" name="{{ $name }}" value="1" @checked(old($name, $checked)) class="peer sr-only">
    <span class="relative mt-0.5 h-6 w-11 shrink-0 rounded-full bg-gray-300 transition peer-checked:bg-primary-500 after:absolute after:left-0.5 after:top-0.5 after:h-5 after:w-5 after:rounded-full after:bg-white after:shadow after:transition peer-checked:after:translate-x-5"></span>
    <span>
        <span class="block text-sm font-medium text-gray-800">{{ $label }}</span>
        @if ($helper)<span class="block text-xs text-gray-500">{{ $helper }}</span>@endif
    </span>
</label>

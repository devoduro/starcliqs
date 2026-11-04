@props(['on' => true, 'onLabel' => 'Published', 'offLabel' => 'Hidden'])

@if ($on)
    <span class="inline-flex items-center gap-1 rounded-full bg-green-50 px-2.5 py-0.5 text-xs font-medium text-green-700"><i class="fas fa-circle text-[6px]"></i> {{ $onLabel }}</span>
@else
    <span class="inline-flex items-center gap-1 rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-600"><i class="fas fa-circle text-[6px]"></i> {{ $offLabel }}</span>
@endif

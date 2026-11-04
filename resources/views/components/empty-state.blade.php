@props(['icon' => 'fa-folder-open', 'title', 'text' => null, 'href' => null, 'action' => null])

<div class="text-center py-10">
    <i class="fas {{ $icon }} text-gray-300 text-5xl mb-4"></i>
    <h3 class="text-lg font-medium text-gray-500">{{ $title }}</h3>
    @if ($text)<p class="text-gray-400 mt-1">{{ $text }}</p>@endif
    @if ($href)
        <div class="mt-6"><x-button :href="$href" icon="fas fa-plus">{{ $action }}</x-button></div>
    @endif
</div>

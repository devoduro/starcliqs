{{-- Font Awesome icon picker: a free-text class name with a live preview and quick picks. --}}
@php
    $quick = ['fa-code', 'fa-laptop-code', 'fa-mobile-screen', 'fa-database', 'fa-network-wired', 'fa-server', 'fa-video', 'fa-comment-sms',
              'fa-lightbulb', 'fa-shield-halved', 'fa-cloud', 'fa-school', 'fa-church', 'fa-check-to-slot', 'fa-box-archive', 'fa-user-graduate',
              'fa-chart-line', 'fa-cart-shopping', 'fa-hospital', 'fa-wifi'];
@endphp
<div x-data="{ icon: @js($value ?: 'fa-gear') }">
    <label for="icon" class="block text-sm font-medium text-gray-700 mb-1">Icon <span class="text-red-500">*</span></label>
    <div class="flex items-center gap-3">
        <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-lg bg-primary-50 text-primary-600 text-lg"><i class="fas" :class="icon"></i></span>
        <input id="icon" name="icon" type="text" x-model="icon" required class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-200 focus:ring-opacity-50">
    </div>
    <div class="mt-2 flex flex-wrap gap-1.5">
        @foreach ($quick as $q)
            <button type="button" @click="icon = '{{ $q }}'" class="flex h-8 w-8 items-center justify-center rounded-md border text-gray-600 hover:border-primary-400 hover:text-primary-600" :class="icon === '{{ $q }}' ? 'border-primary-500 bg-primary-50 text-primary-600' : 'border-gray-200'" title="{{ $q }}"><i class="fas {{ $q }} text-sm"></i></button>
        @endforeach
    </div>
    <p class="mt-1 text-xs text-gray-500">Any free icon name from <a href="https://fontawesome.com/search?o=r&m=free&s=solid" target="_blank" rel="noopener" class="text-primary-600 underline">Font Awesome</a>, e.g. fa-rocket.</p>
    @error('icon')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
</div>

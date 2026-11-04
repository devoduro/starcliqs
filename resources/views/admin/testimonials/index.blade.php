<x-app-layout title="Testimonials">
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Testimonials</h2>
            <x-button href="{{ route('admin.testimonials.create') }}" icon="fas fa-plus">Add Testimonial</x-button>
        </div>
    </x-slot>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6">
            @if ($testimonials->isEmpty())
                <x-empty-state icon="fa-quote-left" title="No testimonials yet" text="Client words of praise appear on the homepage." :href="route('admin.testimonials.create')" action="Add Testimonial" />
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Client</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Testimony</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rating</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($testimonials as $t)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center gap-3">
                                            @if ($t->photo)
                                                <img src="{{ media_url($t->photo) }}" alt="" class="h-10 w-10 rounded-full object-cover">
                                            @else
                                                <span class="flex h-10 w-10 items-center justify-center rounded-full gradient-bg text-sm font-bold text-slate-900">{{ $t->initials() }}</span>
                                            @endif
                                            <div>
                                                <div class="text-sm font-medium text-gray-900">{{ $t->name }}</div>
                                                <div class="text-xs text-gray-500">{{ collect([$t->role, $t->company])->filter()->implode(', ') }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500"><span class="line-clamp-2 max-w-md">{{ $t->quote }}</span></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-primary-500">
                                        @for ($i = 1; $i <= 5; $i++)<i class="{{ $i <= $t->rating ? 'fas' : 'far' }} fa-star text-xs"></i>@endfor
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap"><x-status-badge :on="$t->is_published" /></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <x-row-actions :edit="route('admin.testimonials.edit', $t)" :destroy="route('admin.testimonials.destroy', $t)" confirm="Delete this testimonial?" />
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">{{ $testimonials->links() }}</div>
            @endif
        </div>
    </div>
</x-app-layout>

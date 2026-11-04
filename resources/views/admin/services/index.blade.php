<x-app-layout title="Services">
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Services</h2>
            <x-button href="{{ route('admin.services.create') }}" icon="fas fa-plus">Add Service</x-button>
        </div>
    </x-slot>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6">
            @if ($services->isEmpty())
                <x-empty-state icon="fa-screwdriver-wrench" title="No services yet" text="Services appear on the homepage and each gets its own detail page." :href="route('admin.services.create')" action="Add Service" />
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Service</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Features</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($services as $service)
                                <tr>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-primary-50 text-primary-600"><i class="fas {{ $service->icon }}"></i></span>
                                            <div class="min-w-0">
                                                <div class="text-sm font-medium text-gray-900">{{ $service->title }}</div>
                                                <div class="text-xs text-gray-500 truncate max-w-md">{{ $service->summary }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ count($service->features ?? []) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $service->sort_order }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap"><x-status-badge :on="$service->is_published" /></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <x-row-actions :view="$service->is_published ? route('services.show', $service) : null" :edit="route('admin.services.edit', $service)" :destroy="route('admin.services.destroy', $service)" confirm="Delete this service?" />
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">{{ $services->links() }}</div>
            @endif
        </div>
    </div>
</x-app-layout>

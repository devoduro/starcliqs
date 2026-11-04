<x-app-layout title="Clients">
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Clients <span class="ml-1 text-sm font-normal text-gray-500">— the homepage "Our Happy Clients" logos</span></h2>
            <x-button href="{{ route('admin.clients.create') }}" icon="fas fa-plus">Add Client</x-button>
        </div>
    </x-slot>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6">
            @if ($clients->isEmpty())
                <x-empty-state icon="fa-handshake" title="No clients yet" text="Add client names, logos and website links to show them on the homepage." :href="route('admin.clients.create')" action="Add Client" />
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Client</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Website</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($clients as $client)
                                <tr>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <span class="flex h-12 w-20 shrink-0 items-center justify-center rounded-md border border-gray-200 bg-white p-1">
                                                @if ($client->logo)
                                                    <img src="{{ media_url($client->logo) }}" alt="" class="max-h-full max-w-full object-contain">
                                                @else
                                                    <span class="text-sm font-bold text-gray-400">{{ $client->initials() }}</span>
                                                @endif
                                            </span>
                                            <span class="text-sm font-medium text-gray-900">{{ $client->name }}
                                                @unless ($client->logo)<span class="block text-xs font-normal text-orange-600">No logo yet</span>@endunless
                                            </span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm">
                                        @if ($client->website_url)
                                            <a href="{{ $client->website_url }}" target="_blank" rel="noopener" class="text-primary-700 hover:underline">{{ preg_replace('#^https?://(www\.)?#', '', rtrim($client->website_url, '/')) }}</a>
                                        @elseif ($client->product)
                                            <span class="text-gray-600"><i class="fas fa-cube mr-1 text-primary-500"></i>{{ $client->product->name }} page</span>
                                        @else
                                            <span class="text-gray-400">—</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $client->sort_order }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap"><x-status-badge :on="$client->is_published" /></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <x-row-actions :edit="route('admin.clients.edit', $client)" :destroy="route('admin.clients.destroy', $client)" confirm="Remove this client?" />
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">{{ $clients->links() }}</div>
            @endif
        </div>
    </div>
</x-app-layout>

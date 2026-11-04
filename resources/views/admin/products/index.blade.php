<x-app-layout title="Products">
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Products</h2>
            <x-button href="{{ route('admin.products.create') }}" icon="fas fa-plus">Add Product</x-button>
        </div>
    </x-slot>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6">
            @if ($products->isEmpty())
                <x-empty-state icon="fa-cubes" title="No products yet" text="Products appear in the homepage showcase with their features." :href="route('admin.products.create')" action="Add Product" />
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Features</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($products as $product)
                                <tr>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            @if ($product->image)
                                                <img src="{{ media_url($product->image) }}" alt="" class="h-10 w-14 shrink-0 rounded-md object-cover">
                                            @else
                                                <span class="flex h-10 w-14 shrink-0 items-center justify-center rounded-md bg-slate-800 text-primary-300"><i class="fas {{ $product->icon }}"></i></span>
                                            @endif
                                            <div class="min-w-0">
                                                <div class="text-sm font-medium text-gray-900">{{ $product->name }}
                                                    @if ($product->is_featured)<i class="fas fa-star ml-1 text-xs text-primary-500" title="Featured"></i>@endif
                                                </div>
                                                <div class="text-xs text-gray-500 truncate max-w-md">{{ $product->tagline ?: $product->summary }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ count($product->features ?? []) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $product->price_label ?: '—' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap"><x-status-badge :on="$product->is_published" /></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <x-row-actions :view="$product->is_published ? route('products.show', $product) : null" :edit="route('admin.products.edit', $product)" :destroy="route('admin.products.destroy', $product)" confirm="Delete this product?" />
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">{{ $products->links() }}</div>
            @endif
        </div>
    </div>
</x-app-layout>

<x-app-layout title="FAQs">
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">FAQs <span class="ml-1 text-sm font-normal text-gray-500">— the homepage "Looking for help?" cards</span></h2>
            <x-button href="{{ route('admin.faqs.create') }}" icon="fas fa-plus">Add FAQ</x-button>
        </div>
    </x-slot>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6">
            @if ($faqs->isEmpty())
                <x-empty-state icon="fa-circle-question" title="No FAQs yet" text="Common questions appear as help cards on the homepage, with a search box." :href="route('admin.faqs.create')" action="Add FAQ" />
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Topic</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Question</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($faqs as $faq)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center gap-3">
                                            <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-primary-50 text-primary-600"><i class="fas {{ $faq->icon }}"></i></span>
                                            <span class="text-sm font-medium text-gray-900">{{ $faq->topic }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600">
                                        <span class="block font-medium text-gray-800">{{ $faq->question }}</span>
                                        <span class="line-clamp-1 max-w-lg text-gray-500">{{ $faq->answer }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $faq->sort_order }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap"><x-status-badge :on="$faq->is_published" /></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <x-row-actions :edit="route('admin.faqs.edit', $faq)" :destroy="route('admin.faqs.destroy', $faq)" confirm="Delete this FAQ?" />
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">{{ $faqs->links() }}</div>
            @endif
        </div>
    </div>
</x-app-layout>

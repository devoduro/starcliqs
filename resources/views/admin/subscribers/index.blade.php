<x-app-layout title="Subscribers">
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Newsletter subscribers <span class="ml-1 text-base font-normal text-gray-500">({{ $subscribers->total() }})</span></h2>
            @if ($subscribers->total())
                <x-button href="{{ route('admin.subscribers.export') }}" variant="outline-primary" icon="fas fa-file-csv">Export CSV</x-button>
            @endif
        </div>
    </x-slot>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6">
            @if ($subscribers->isEmpty())
                <x-empty-state icon="fa-envelope-open-text" title="No subscribers yet" text="Emails entered in the website footer's newsletter box appear here." />
            @else
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subscribed</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider"></th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($subscribers as $s)
                            <tr>
                                <td class="px-6 py-3 text-sm text-gray-900">{{ $s->email }}</td>
                                <td class="px-6 py-3 text-sm text-gray-500">{{ $s->created_at->format('d M Y') }}</td>
                                <td class="px-6 py-3 text-right text-sm">
                                    <form method="POST" action="{{ route('admin.subscribers.destroy', $s) }}" onsubmit="return confirm('Remove this subscriber?')">
                                        @csrf @method('DELETE')
                                        <button class="text-red-600 hover:text-red-900" title="Remove"><i class="fas fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="mt-4">{{ $subscribers->links() }}</div>
            @endif
        </div>
    </div>
</x-app-layout>

<x-app-layout title="Messages">
    <x-slot name="header">
        <div class="flex flex-wrap gap-3 justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Messages</h2>
            <div class="flex flex-wrap gap-2 text-sm">
                @php
                    $tabs = ['' => 'All', 'contact' => 'Contact form', 'training' => 'Training registrations'];
                @endphp
                @foreach ($tabs as $value => $label)
                    <a href="{{ route('admin.enquiries.index', array_filter(['type' => $value, 'status' => request('status')])) }}"
                       class="rounded-lg px-3 py-1.5 {{ request('type', '') === $value ? 'bg-slate-900 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">{{ $label }}</a>
                @endforeach
                <a href="{{ route('admin.enquiries.index', array_filter(['type' => request('type'), 'status' => request('status') === 'unread' ? null : 'unread'])) }}"
                   class="rounded-lg px-3 py-1.5 {{ request('status') === 'unread' ? 'bg-primary-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}"><i class="fas fa-circle text-[7px] mr-1"></i> Unread only</a>
            </div>
        </div>
    </x-slot>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6">
            @if ($enquiries->isEmpty())
                <x-empty-state icon="fa-inbox" title="No messages" text="Contact form messages and training registrations from the website arrive here." />
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">From</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subject</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Received</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider"></th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($enquiries as $e)
                                <tr class="{{ $e->read_at ? '' : 'bg-primary-50/40' }}">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <a href="{{ route('admin.enquiries.show', $e) }}" class="block">
                                            <span class="text-sm {{ $e->read_at ? 'text-gray-800' : 'font-semibold text-gray-900' }}">
                                                @unless ($e->read_at)<i class="fas fa-circle text-[7px] text-primary-500 mr-1"></i>@endunless{{ $e->name }}
                                            </span>
                                            <span class="block text-xs text-gray-500">{{ $e->email }}</span>
                                        </a>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600">
                                        <a href="{{ route('admin.enquiries.show', $e) }}" class="line-clamp-1 max-w-md hover:text-primary-700">{{ $e->subject ?: \Illuminate\Support\Str::limit($e->message, 70) ?: '(no subject)' }}</a>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        @if ($e->type === 'training')
                                            <span class="rounded-full bg-purple-50 px-2.5 py-0.5 text-xs font-medium text-purple-700">Training</span>
                                        @else
                                            <span class="rounded-full bg-blue-50 px-2.5 py-0.5 text-xs font-medium text-blue-700">Contact</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" title="{{ $e->created_at->format('d M Y H:i') }}">{{ $e->created_at->diffForHumans() }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <a href="{{ route('admin.enquiries.show', $e) }}" class="text-indigo-600 hover:text-indigo-900" title="Open"><i class="fas fa-envelope-open"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">{{ $enquiries->links() }}</div>
            @endif
        </div>
    </div>
</x-app-layout>

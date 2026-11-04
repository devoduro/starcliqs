<x-app-layout title="Message">
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $enquiry->subject ?: 'Message from ' . $enquiry->name }}</h2>
            <x-button href="{{ route('admin.enquiries.index') }}" variant="secondary" icon="fas fa-arrow-left">Back to Messages</x-button>
        </div>
    </x-slot>

    <div class="grid gap-4 lg:grid-cols-3">
        <div class="lg:col-span-2 bg-white shadow-sm sm:rounded-lg p-6">
            <p class="text-xs uppercase tracking-wider text-gray-400 mb-2">Received {{ $enquiry->created_at->format('l, d M Y \a\t H:i') }}</p>
            @if ($enquiry->training)
                <p class="mb-4 rounded-lg bg-purple-50 px-4 py-3 text-sm text-purple-800"><i class="fas fa-graduation-cap mr-2"></i>Registration for <strong>{{ $enquiry->training->title }}</strong>
                    @if ($enquiry->training->starts_on) (starts {{ $enquiry->training->starts_on->format('d M Y') }}) @endif</p>
            @endif
            <div class="prose max-w-none whitespace-pre-line text-gray-700">{{ $enquiry->message ?: 'No message was included.' }}</div>
        </div>

        <div class="space-y-4">
            <div class="bg-white shadow-sm sm:rounded-lg p-6 space-y-3 text-sm">
                <div><span class="block text-xs text-gray-400">Name</span><span class="font-medium text-gray-900">{{ $enquiry->name }}</span></div>
                <div><span class="block text-xs text-gray-400">Email</span><a href="mailto:{{ $enquiry->email }}" class="text-primary-700 hover:underline">{{ $enquiry->email }}</a></div>
                @if ($enquiry->phone)
                    <div><span class="block text-xs text-gray-400">Phone</span><a href="tel:{{ $enquiry->phone }}" class="text-primary-700 hover:underline">{{ $enquiry->phone }}</a></div>
                @endif
            </div>
            <div class="flex flex-wrap gap-2">
                <x-button href="mailto:{{ $enquiry->email }}?subject={{ rawurlencode('Re: ' . ($enquiry->subject ?: 'Your message to ' . setting('site_name', 'StarCliqs'))) }}" icon="fas fa-reply">Reply by email</x-button>
                <form method="POST" action="{{ route('admin.enquiries.unread', $enquiry) }}">
                    @csrf @method('PATCH')
                    <x-button type="submit" variant="secondary" icon="fas fa-envelope">Mark unread</x-button>
                </form>
                <form method="POST" action="{{ route('admin.enquiries.destroy', $enquiry) }}" onsubmit="return confirm('Delete this message?')">
                    @csrf @method('DELETE')
                    <x-button type="submit" variant="outline-danger" icon="fas fa-trash">Delete</x-button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

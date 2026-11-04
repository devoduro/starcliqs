<x-app-layout title="Trainings">
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Trainings</h2>
            <x-button href="{{ route('admin.trainings.create') }}" icon="fas fa-plus">Add Training</x-button>
        </div>
    </x-slot>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6">
            @if ($trainings->isEmpty())
                <x-empty-state icon="fa-graduation-cap" title="No trainings yet" text="Courses appear on the homepage and visitors can register online." :href="route('admin.trainings.create')" action="Add Training" />
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Course</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Starts</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mode</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fee</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Registrations</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($trainings as $training)
                                <tr>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $training->title }}</div>
                                        <div class="text-xs text-gray-500">{{ $training->duration }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $training->starts_on?->format('d M Y') ?? 'Rolling' }}
                                        @if ($training->starts_on?->isPast())<span class="ml-1 rounded bg-gray-100 px-1.5 py-0.5 text-[11px] text-gray-500">past</span>@endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $training->modeLabel() }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $training->feeLabel() }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <a href="{{ route('admin.enquiries.index', ['type' => 'training']) }}" class="text-primary-700 hover:underline">{{ $training->registrations_count }}</a>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap"><x-status-badge :on="$training->is_published" /></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <x-row-actions :view="$training->is_published ? route('trainings.show', $training) : null" :edit="route('admin.trainings.edit', $training)" :destroy="route('admin.trainings.destroy', $training)" confirm="Delete this training? Registrations stay in Messages." />
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">{{ $trainings->links() }}</div>
            @endif
        </div>
    </div>
</x-app-layout>

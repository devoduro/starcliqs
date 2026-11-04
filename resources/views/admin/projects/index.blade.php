<x-app-layout title="Projects">
    <x-slot name="header">
        <div class="flex flex-wrap gap-3 justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Projects</h2>
            <div class="flex gap-2">
                <form method="GET" class="relative">
                    <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                    <input type="search" name="q" value="{{ request('q') }}" placeholder="Search title or client" class="w-56 rounded-lg border-gray-300 pl-9 text-sm focus:border-primary-500 focus:ring-primary-200" style="padding-left:2.25rem">
                </form>
                <x-button href="{{ route('admin.projects.create') }}" icon="fas fa-upload">Upload Project</x-button>
            </div>
        </div>
    </x-slot>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6">
            @if ($projects->isEmpty())
                <x-empty-state icon="fa-briefcase" title="No projects found" text="Upload finished work to show it in the homepage portfolio." :href="route('admin.projects.create')" action="Upload Project" />
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Project</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Client</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Completed</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($projects as $project)
                                <tr>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            @if ($project->image)
                                                <img src="{{ media_url($project->image) }}" alt="" class="h-10 w-14 shrink-0 rounded-md object-cover">
                                            @else
                                                <span class="flex h-10 w-14 shrink-0 items-center justify-center rounded-md bg-gray-100 text-gray-400"><i class="fas fa-image"></i></span>
                                            @endif
                                            <div class="text-sm font-medium text-gray-900">{{ $project->title }}
                                                @if ($project->is_featured)<i class="fas fa-star ml-1 text-xs text-primary-500" title="Featured"></i>@endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $project->client ?: '—' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $project->category ?: '—' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $project->completed_on?->format('M Y') ?? '—' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap"><x-status-badge :on="$project->is_published" /></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <x-row-actions :view="$project->is_published ? route('projects.show', $project) : null" :edit="route('admin.projects.edit', $project)" :destroy="route('admin.projects.destroy', $project)" confirm="Delete this project and its image?" />
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">{{ $projects->links() }}</div>
            @endif
        </div>
    </div>
</x-app-layout>

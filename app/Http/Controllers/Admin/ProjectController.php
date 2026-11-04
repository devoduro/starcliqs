<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\SavesContent;
use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    use SavesContent;

    public function index(Request $request)
    {
        $projects = Project::ordered()
            ->when($request->filled('q'), fn ($q) => $q->where(fn ($w) => $w->where('title', 'like', '%' . $request->q . '%')->orWhere('client', 'like', '%' . $request->q . '%')))
            ->paginate(20)->withQueryString();

        return view('admin.projects.index', compact('projects'));
    }

    public function create()
    {
        return view('admin.projects.form', ['project' => new Project(['is_published' => true]), 'categories' => $this->categories()]);
    }

    public function store(Request $request)
    {
        $this->save($request, new Project());

        return redirect()->route('admin.projects.index')->with('success', 'Project uploaded successfully.');
    }

    public function edit(Project $project)
    {
        return view('admin.projects.form', ['project' => $project, 'categories' => $this->categories()]);
    }

    public function update(Request $request, Project $project)
    {
        $this->save($request, $project);

        return redirect()->route('admin.projects.index')->with('success', 'Project updated successfully.');
    }

    public function destroy(Project $project)
    {
        $this->deleteImage($project->image);
        $project->delete();

        return redirect()->route('admin.projects.index')->with('success', 'Project deleted successfully.');
    }

    /** Categories already in use, offered as suggestions so the homepage filter stays tidy. */
    private function categories()
    {
        return Project::whereNotNull('category')->distinct()->orderBy('category')->pluck('category');
    }

    private function save(Request $request, Project $project): void
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'client' => 'nullable|string|max:255',
            'category' => 'nullable|string|max:100',
            'summary' => 'required|string|max:500',
            'body' => 'nullable|string',
            'technologies' => 'nullable|string',
            'image' => 'nullable|image|max:4096',
            'project_url' => 'nullable|url|max:255',
            'completed_on' => 'nullable|date',
        ]);

        $data['technologies'] = $this->lines($data['technologies'] ?? '');
        $data['image'] = $this->storeImage($request, 'image', 'projects', $project->image);
        $data['is_featured'] = $request->boolean('is_featured');
        $data['is_published'] = $request->boolean('is_published');

        $project->fill($data)->save();
    }
}

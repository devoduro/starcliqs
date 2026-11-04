<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\SavesContent;
use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    use SavesContent;

    public function index()
    {
        $services = Service::ordered()->paginate(20);

        return view('admin.services.index', compact('services'));
    }

    public function create()
    {
        return view('admin.services.form', ['service' => new Service(['icon' => 'fa-gear', 'is_published' => true])]);
    }

    public function store(Request $request)
    {
        $service = new Service();
        $this->save($request, $service);

        return redirect()->route('admin.services.index')->with('success', 'Service created successfully.');
    }

    public function edit(Service $service)
    {
        return view('admin.services.form', compact('service'));
    }

    public function update(Request $request, Service $service)
    {
        $this->save($request, $service);

        return redirect()->route('admin.services.index')->with('success', 'Service updated successfully.');
    }

    public function destroy(Service $service)
    {
        $this->deleteImage($service->image);
        $service->delete();

        return redirect()->route('admin.services.index')->with('success', 'Service deleted successfully.');
    }

    private function save(Request $request, Service $service): void
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'icon' => 'required|string|max:60',
            'summary' => 'required|string|max:500',
            'body' => 'nullable|string',
            'features' => 'nullable|string',
            'image' => 'nullable|image|max:4096',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $data['features'] = $this->lines($data['features'] ?? '');
        $data['image'] = $this->storeImage($request, 'image', 'services', $service->image);
        $data['sort_order'] = $data['sort_order'] ?? 0;
        $data['is_published'] = $request->boolean('is_published');

        $service->fill($data)->save();
    }
}

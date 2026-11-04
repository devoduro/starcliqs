<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\SavesContent;
use App\Http\Controllers\Controller;
use App\Models\Training;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TrainingController extends Controller
{
    use SavesContent;

    public function index()
    {
        $trainings = Training::withCount('registrations')->ordered()->paginate(20);

        return view('admin.trainings.index', compact('trainings'));
    }

    public function create()
    {
        return view('admin.trainings.form', ['training' => new Training(['mode' => 'in-person', 'is_published' => true])]);
    }

    public function store(Request $request)
    {
        $this->save($request, new Training());

        return redirect()->route('admin.trainings.index')->with('success', 'Training created successfully.');
    }

    public function edit(Training $training)
    {
        return view('admin.trainings.form', compact('training'));
    }

    public function update(Request $request, Training $training)
    {
        $this->save($request, $training);

        return redirect()->route('admin.trainings.index')->with('success', 'Training updated successfully.');
    }

    public function destroy(Training $training)
    {
        $this->deleteImage($training->image);
        $training->delete();

        return redirect()->route('admin.trainings.index')->with('success', 'Training deleted successfully.');
    }

    private function save(Request $request, Training $training): void
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'summary' => 'required|string|max:500',
            'body' => 'nullable|string',
            'outline' => 'nullable|string',
            'icon' => 'required|string|max:60',
            'level' => 'nullable|string|max:100',
            'duration' => 'nullable|string|max:100',
            'schedule' => 'nullable|string|max:150',
            'mode' => ['required', Rule::in(array_keys(Training::MODES))],
            'fee' => 'nullable|numeric|min:0',
            'starts_on' => 'nullable|date',
            'registration_closes_on' => 'nullable|date',
            'image' => 'nullable|image|max:4096',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $data['outline'] = $this->lines($data['outline'] ?? '');
        $data['image'] = $this->storeImage($request, 'image', 'trainings', $training->image);
        $data['sort_order'] = $data['sort_order'] ?? 0;
        $data['is_published'] = $request->boolean('is_published');

        $training->fill($data)->save();
    }
}

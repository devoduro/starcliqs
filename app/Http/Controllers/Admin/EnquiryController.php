<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Enquiry;
use Illuminate\Http\Request;

class EnquiryController extends Controller
{
    public function index(Request $request)
    {
        $enquiries = Enquiry::with('training')
            ->when($request->filled('type'), fn ($q) => $q->where('type', $request->type))
            ->when($request->status === 'unread', fn ($q) => $q->whereNull('read_at'))
            ->latest()
            ->paginate(25)->withQueryString();

        return view('admin.enquiries.index', compact('enquiries'));
    }

    public function show(Enquiry $enquiry)
    {
        if (! $enquiry->read_at) {
            $enquiry->update(['read_at' => now()]);
        }

        return view('admin.enquiries.show', compact('enquiry'));
    }

    public function markUnread(Enquiry $enquiry)
    {
        $enquiry->update(['read_at' => null]);

        return redirect()->route('admin.enquiries.index')->with('success', 'Message marked as unread.');
    }

    public function destroy(Enquiry $enquiry)
    {
        $enquiry->delete();

        return redirect()->route('admin.enquiries.index')->with('success', 'Message deleted.');
    }
}

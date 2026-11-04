<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subscriber;

class SubscriberController extends Controller
{
    public function index()
    {
        $subscribers = Subscriber::latest()->paginate(50);

        return view('admin.subscribers.index', compact('subscribers'));
    }

    public function export()
    {
        $rows = Subscriber::orderBy('email')->get(['email', 'created_at']);

        return response()->streamDownload(function () use ($rows) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['Email', 'Subscribed on']);
            foreach ($rows as $row) {
                fputcsv($out, [$row->email, $row->created_at->format('Y-m-d H:i')]);
            }
            fclose($out);
        }, 'subscribers-' . now()->format('Y-m-d') . '.csv', ['Content-Type' => 'text/csv']);
    }

    public function destroy(Subscriber $subscriber)
    {
        $subscriber->delete();

        return redirect()->route('admin.subscribers.index')->with('success', 'Subscriber removed.');
    }
}

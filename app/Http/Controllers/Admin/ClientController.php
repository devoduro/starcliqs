<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\SavesContent;
use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Product;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    use SavesContent;

    public function index()
    {
        $clients = Client::with('product')->ordered()->paginate(50);

        return view('admin.clients.index', compact('clients'));
    }

    public function create()
    {
        return view('admin.clients.form', ['client' => new Client(['is_published' => true]), 'products' => $this->products()]);
    }

    public function store(Request $request)
    {
        $this->save($request, new Client());

        return redirect()->route('admin.clients.index')->with('success', 'Client added successfully.');
    }

    public function edit(Client $client)
    {
        return view('admin.clients.form', ['client' => $client, 'products' => $this->products()]);
    }

    public function update(Request $request, Client $client)
    {
        $this->save($request, $client);

        return redirect()->route('admin.clients.index')->with('success', 'Client updated successfully.');
    }

    public function destroy(Client $client)
    {
        $this->deleteImage($client->logo);
        $client->delete();

        return redirect()->route('admin.clients.index')->with('success', 'Client removed.');
    }

    private function products()
    {
        return Product::orderBy('name')->get(['id', 'name']);
    }

    private function save(Request $request, Client $client): void
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'website_url' => 'nullable|url|max:255',
            'product_id' => 'nullable|exists:products,id',
            'logo' => 'nullable|image|max:2048',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $data['logo'] = $this->storeImage($request, 'logo', 'clients', $client->logo);
        $data['sort_order'] = $data['sort_order'] ?? 0;
        $data['is_published'] = $request->boolean('is_published');

        $client->fill($data)->save();
    }
}

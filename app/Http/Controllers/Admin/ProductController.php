<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\SavesContent;
use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    use SavesContent;

    public function index()
    {
        $products = Product::ordered()->paginate(20);

        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        return view('admin.products.form', ['product' => new Product(['icon' => 'fa-cube', 'is_published' => true])]);
    }

    public function store(Request $request)
    {
        $this->save($request, new Product());

        return redirect()->route('admin.products.index')->with('success', 'Product created successfully.');
    }

    public function edit(Product $product)
    {
        return view('admin.products.form', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $this->save($request, $product);

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        $this->deleteImage($product->image);
        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully.');
    }

    private function save(Request $request, Product $product): void
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'tagline' => 'nullable|string|max:255',
            'icon' => 'required|string|max:60',
            'summary' => 'required|string|max:500',
            'body' => 'nullable|string',
            'features' => 'nullable|string',
            'image' => 'nullable|image|max:4096',
            'demo_url' => 'nullable|url|max:255',
            'price_label' => 'nullable|string|max:100',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $data['features'] = $this->lines($data['features'] ?? '');
        $data['image'] = $this->storeImage($request, 'image', 'products', $product->image);
        $data['sort_order'] = $data['sort_order'] ?? 0;
        $data['is_featured'] = $request->boolean('is_featured');
        $data['is_published'] = $request->boolean('is_published');

        $product->fill($data)->save();
    }
}

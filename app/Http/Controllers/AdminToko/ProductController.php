<?php

namespace App\Http\Controllers\AdminToko;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        // retrieve all product data from the database
        $products = Product::with('category')->get();
        // Send the data to the admintoko.product.view
        return view('admintoko.product.view', compact('products'));
    }

    public function show(Product $product)
    {
        return view('admintoko.product.detail', compact('product'));
    }

    public function management()
    {
        $products = Product::with('category')->get();
        return view('admintoko.product.management', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admintoko.product.form', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|exists:categories,code',
            'name' => 'required',
            'stock' => 'required|integer',
            'description' => 'required',
            'price' => 'required|numeric',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = $request->all();
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }
        Product::create($data);
        return redirect()->route('products.management')->with('success', 'Product berhasil ditambahkan');
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('admintoko.product.form', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'code' => 'required|exists:categories,code',
            'name' => 'required',
            'stock' => 'required|integer',
            'description' => 'required',
            'price' => 'required|numeric',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            // Hapus gambar lama, jika ada
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }
            // Simpan gambar baru
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($data);
        return redirect()->route('products.management')->with('success', 'Product berhasil diperbarui');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('products.management')->with('success', 'Product berhasil dihapus');
    }
}

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
            'code'        => 'required|exists:categories,code',
            'name'        => 'required|string|max:255',
            'stock'       => 'required|integer|min:0',
            'description' => 'required|string',
            'price'       => 'required|numeric|min:0',

            // gambar utama wajib di images[0]
            'images.0'    => ['required','image','mimes:jpg,jpeg,png,webp','max:5048'],
            'images.1'    => ['nullable','image','mimes:jpg,jpeg,png,webp','max:5048'],
            'images.2'    => ['nullable','image','mimes:jpg,jpeg,png,webp','max:5048'],
            'images.3'    => ['nullable','image','mimes:jpg,jpeg,png,webp','max:5048'],
            'images.4'    => ['nullable','image','mimes:jpg,jpeg,png,webp','max:5048'],
        ]);

        // simpan gambar utama
        $mainPath = $request->file('images')[0]->store('products', 'public');

        // buat product sekali saja
        $product = Product::create([
            'code'        => $request->code,
            'name'        => $request->name,
            'stock'       => $request->stock,
            'description' => $request->description,
            'price'       => $request->price,
            'image'       => $mainPath,
        ]);

        // simpan gambar galeri opsional (maks 4)
        $files = $request->file('images');
        foreach ([1,2,3,4] as $i) {
            if (!empty($files[$i])) {
                $path = $files[$i]->store('products', 'public');
                $product->images()->create([
                    'path'       => $path,
                    'sort_order' => $i,
                ]);
            }
        }

        return redirect()->route('products.management')->with('success', 'Product berhasil ditambahkan');
    }


    public function edit(Product $product)
    {
        $categories = Category::all();
        $product->load('images');
        return view('admintoko.product.form', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'code'        => 'required|exists:categories,code',
            'name'        => 'required|string|max:255',
            'stock'       => 'required|integer|min:0',
            'description' => 'required|string',
            'price'       => 'required|numeric|min:0',

            'images.0'    => ['nullable','image','mimes:jpg,jpeg,png,webp','max:5048'],
            'images.1'    => ['nullable','image','mimes:jpg,jpeg,png,webp','max:5048'],
            'images.2'    => ['nullable','image','mimes:jpg,jpeg,png,webp','max:5048'],
            'images.3'    => ['nullable','image','mimes:jpg,jpeg,png,webp','max:5048'],
            'images.4'    => ['nullable','image','mimes:jpg,jpeg,png,webp','max:5048'],

            'remove_image_ids'   => ['array'],
            'remove_image_ids.*' => ['integer','exists:product_images,id'],
        ]);

        // update field dasar
        $product->update($request->only('code','name','stock','description','price'));

        $files = $request->file('images', []);

        // ganti gambar utama jika ada upload baru di images[0]
        if (!empty($files[0])) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $product->image = $files[0]->store('products', 'public');
            $product->save();
        }

        // hapus galeri yang dicentang
        if ($request->filled('remove_image_ids')) {
            $toDelete = $product->images()->whereIn('id', $request->remove_image_ids)->get();
            foreach ($toDelete as $img) {
                Storage::disk('public')->delete($img->path);
                $img->delete();
            }
        }

        // tambah galeri baru (batasi total 4)
        $existing = $product->images()->count();
        foreach ([1,2,3,4] as $i) {
            if (!empty($files[$i])) {
                if ($existing >= 4) break; // jaga batas
                $path = $files[$i]->store('products', 'public');
                $product->images()->create([
                    'path'       => $path,
                    'sort_order' => $existing + 1,
                ]);
                $existing++;
            }
        }

        return redirect()->route('products.management')->with('success', 'Product berhasil diperbarui');
    }


    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('products.management')->with('success', 'Product berhasil dihapus');
    }
}

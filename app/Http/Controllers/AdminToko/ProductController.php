<?php

namespace App\Http\Controllers\AdminToko;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->get();
        return view('admintoko.catalog.view', compact('products'));
    }

    public function show(Product $product)
    {
        return view('admintoko.catalog.detail', compact('product'));
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
            'description' => 'required|string|max:600',
            'price'       => 'required|numeric|min:0',
            'images'      => 'required|array|min:1|max:5', // Wajib minimal 1 gambar
            'images.*'    => 'required|image|mimes:jpg,jpeg,png,webp|max:5048',
            'ordered'     => 'nullable|array',
        ]);

        $files   = array_values($request->file('images', []));
        $ordered = (array) $request->input('ordered', []);

        // Auto-generate ordered jika kosong
        if (empty($ordered) && !empty($files)) {
            $ordered = array_fill(0, count($files), 'file');
        }

        DB::beginTransaction();
        $uploadedPaths = []; // Track untuk cleanup jika error

        try {
            $product = Product::create([
                'code'        => $request->code,
                'name'        => $request->name,
                'stock'       => $request->stock,
                'description' => $request->description,
                'price'       => $request->price,
                'image'       => null,
            ]);

            $finalPaths = [];
            $fileIdx = 0;

            foreach ($ordered as $tok) {
                if ($tok === 'file') {
                    if (!isset($files[$fileIdx])) continue;
                    $stored = $files[$fileIdx]->store('products', 'public');
                    $uploadedPaths[] = $stored; // Track untuk cleanup
                    $finalPaths[] = $stored;
                    $fileIdx++;
                }
            }

            if (empty($finalPaths)) {
                throw new \Exception('Tidak ada gambar yang berhasil diupload.');
            }

            // Set gambar pertama sebagai main image
            $product->image = $finalPaths[0];
            $product->save();

            // Sisanya masuk ke gallery
            $rest = array_slice($finalPaths, 1);
            foreach ($rest as $i => $p) {
                ProductImage::create([
                    'product_id' => $product->id,
                    'path'       => $p,
                    'sort_order' => $i + 1,
                ]);
            }

            DB::commit();
            return redirect()
                ->route('products.management')
                ->with('success', 'Product berhasil ditambahkan');
        } catch (\Throwable $e) {
            DB::rollBack();

            // Cleanup uploaded files jika terjadi error
            foreach ($uploadedPaths as $path) {
                if (Storage::disk('public')->exists($path)) {
                    Storage::disk('public')->delete($path);
                }
            }

            return back()
                ->withErrors(['error' => 'Gagal menyimpan produk: ' . $e->getMessage()])
                ->withInput();
        }
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
            'code'         => 'required|exists:categories,code',
            'name'         => 'required|string|max:255',
            'stock'        => 'required|integer|min:0',
            'description'  => 'required|string|max:600',
            'price'        => 'required|numeric|min:0',
            'images.*'     => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5048',
            'ordered'      => 'nullable|array',
            'remove_main'  => 'nullable|in:1',
            'remove_image_ids'   => 'nullable|array',
            'remove_image_ids.*' => 'integer|exists:product_images,id',
        ]);

        // Update basic info
        $product->update($request->only('code', 'name', 'stock', 'description', 'price'));
        $product->load('images');

        $oldMainPath = $product->image;
        $files   = array_values($request->file('images', []));
        $ordered = (array) $request->input('ordered', []);

        DB::beginTransaction();
        $uploadedPaths = []; // Track new uploads untuk cleanup jika error
        $pathsToDelete = []; // Track paths yang akan dihapus

        try {
            // === HAPUS GAMBAR YANG DITANDAI ===
            if ($request->boolean('remove_main')) {
                if ($product->image) {
                    $pathsToDelete[] = $product->image;
                    $oldMainPath = null;
                    $product->image = null;
                }
            }

            $toDeleteIds = (array) $request->input('remove_image_ids', []);
            if (!empty($toDeleteIds)) {
                $delImgs = $product->images()->whereIn('id', $toDeleteIds)->get();
                foreach ($delImgs as $im) {
                    if ($im->path) {
                        $pathsToDelete[] = $im->path;
                    }
                    $im->delete();
                }
                $product->load('images');
            }

            // === REBUILD DARI ORDERED[] ===
            if (empty($ordered)) {
                // Fallback mode lama (tidak ada ordered)
                $this->handleLegacyUpdate($product, $files, $oldMainPath, $uploadedPaths, $pathsToDelete);
            } else {
                // Mode baru dengan ordered
                $this->handleOrderedUpdate($product, $files, $ordered, $oldMainPath, $uploadedPaths);
            }

            DB::commit();

            // Hapus file setelah commit berhasil
            foreach ($pathsToDelete as $path) {
                if (Storage::disk('public')->exists($path)) {
                    Storage::disk('public')->delete($path);
                }
            }

            return redirect()
                ->route('products.management')
                ->with('success', 'Product berhasil diperbarui');
        } catch (\Throwable $e) {
            DB::rollBack();

            // Cleanup new uploads jika error
            foreach ($uploadedPaths as $path) {
                if (Storage::disk('public')->exists($path)) {
                    Storage::disk('public')->delete($path);
                }
            }

            return back()
                ->withErrors(['error' => 'Gagal memperbarui produk: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Handle update tanpa ordered array (legacy mode)
     */
    private function handleLegacyUpdate($product, $files, $oldMainPath, &$uploadedPaths, &$pathsToDelete)
    {
        if (!empty($files[0])) {
            if ($oldMainPath) {
                $pathsToDelete[] = $oldMainPath;
            }
            $newMain = $files[0]->store('products', 'public');
            $uploadedPaths[] = $newMain;
            $product->image = $newMain;
        }

        $existing = $product->images()->count();
        for ($i = 1; $i < count($files); $i++) {
            if ($existing >= 4) break; // Max 5 total (1 main + 4 gallery)

            $p = $files[$i]->store('products', 'public');
            $uploadedPaths[] = $p;

            ProductImage::create([
                'product_id' => $product->id,
                'path'       => $p,
                'sort_order' => ++$existing,
            ]);
        }

        $product->save();
    }

    /**
     * Handle update dengan ordered array (drag & drop mode)
     */
    private function handleOrderedUpdate($product, $files, $ordered, $oldMainPath, &$uploadedPaths)
    {
        $galleryById = $product->images->keyBy('id');
        $finalPaths = [];
        $fileIdx = 0;

        foreach ($ordered as $tok) {
            // Handle existing image
            if (is_string($tok) && Str::startsWith($tok, 'img:')) {
                $id = substr($tok, 4);

                if ($id === '__main__') {
                    if ($oldMainPath) {
                        $finalPaths[] = $oldMainPath;
                    }
                } else {
                    $img = $galleryById->get((int)$id);
                    if ($img && $img->path) {
                        $finalPaths[] = $img->path;
                    }
                }
            }
            // Handle new file
            elseif ($tok === 'file') {
                if (isset($files[$fileIdx])) {
                    $stored = $files[$fileIdx]->store('products', 'public');
                    $uploadedPaths[] = $stored;
                    $finalPaths[] = $stored;
                    $fileIdx++;
                }
            }
        }

        // Validasi minimal 1 gambar
        if (empty($finalPaths)) {
            throw new \Exception('Produk harus memiliki minimal 1 gambar.');
        }

        // Set main image dan gallery
        $newMain = $finalPaths[0];
        $rest    = array_slice($finalPaths, 1);

        // Hapus semua gallery lama dan buat ulang
        $product->images()->delete();

        foreach ($rest as $i => $path) {
            ProductImage::create([
                'product_id' => $product->id,
                'path'       => $path,
                'sort_order' => $i + 1,
            ]);
        }

        $product->image = $newMain;
        $product->save();
    }

    public function destroy(Product $product)
    {
        DB::beginTransaction();

        try {
            // Hapus main image
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }

            // Hapus gallery images
            foreach ($product->images as $im) {
                if ($im->path && Storage::disk('public')->exists($im->path)) {
                    Storage::disk('public')->delete($im->path);
                }
            }

            $product->images()->delete();
            $product->delete();

            DB::commit();

            return redirect()
                ->route('products.management')
                ->with('success', 'Product berhasil dihapus');
        } catch (\Throwable $e) {
            DB::rollBack();

            return back()
                ->withErrors(['error' => 'Gagal menghapus produk: ' . $e->getMessage()]);
        }
    }
}

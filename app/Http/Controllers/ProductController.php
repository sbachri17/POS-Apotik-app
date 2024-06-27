<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        // jumlah data yang ditampilkan per paginasi halaman
        $pagination = 10;

        if ($request->search) {
            // menampilkan pencarian data
            $products = Product::select('id', 'category_id', 'name', 'price', 'image')->with('category:id,name')
                ->whereAny(['name', 'price'], 'LIKE', '%' . $request->search . '%')
                ->paginate($pagination)
                ->withQueryString();
        } else {
            // menampilkan semua data
            $products = Product::select('id', 'category_id', 'name', 'price', 'image')->with('category:id,name')
                ->latest()
                ->paginate($pagination);
        }

        // tampilkan data ke view
        return view('products.index', compact('products'))->with('i', ($request->input('page', 1) - 1) * $pagination);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        // get data kategori
        $categories = Category::get(['id', 'name']);

        // tampilkan form add data
        return view('products.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        // validasi form
        $request->validate([
            'category'    => 'required|exists:categories,id',
            'name'        => 'required',
            'description' => 'required',
            'price'       => 'required',
            'image'       => 'required|image|mimes: jpeg,jpg,png|max: 1024'
        ]);

        // upload image
        $image = $request->file('image');
        $image->storeAs('public/products', $image->hashName());

        // create data
        Product::create([
            'category_id' => $request->category,
            'name'        => $request->name,
            'description' => $request->description,
            'price'       => str_replace('.', '', $request->price),
            'image'       => $image->hashName()
        ]);

        // redirect ke halaman index dan tampilkan pesan berhasil simpan data
        return redirect()->route('products.index')->with(['success' => 'Pruduk baru telah disimpan.']);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): View
    {
        // get data by ID
        $product = Product::findOrFail($id);

        // tampilkan form detail data
        return view('products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id): View
    {
        // get data product by ID
        $product = Product::findOrFail($id);
        // get data kategori
        $categories = Category::get(['id', 'name']);

        // tampilkan form edit data
        return view('products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): RedirectResponse
    {
        // validasi form
        $request->validate([
            'category'    => 'required|exists:categories,id',
            'name'        => 'required',
            'description' => 'required',
            'price'       => 'required',
            'image'       => 'image|mimes: jpeg,jpg,png|max: 1024'
        ]);

        // get data by ID
        $product = Product::findOrFail($id);

        // cek jika image diubah
        if ($request->hasFile('image')) {
            // upload image baru
            $image = $request->file('image');
            $image->storeAs('public/products', $image->hashName());

            // delete image lama
            Storage::delete('public/products/' . $product->image);

            // update data
            $product->update([
                'category_id' => $request->category,
                'name'        => $request->name,
                'description' => $request->description,
                'price'       => str_replace('.', '', $request->price),
                'image'       => $image->hashName()
            ]);
        }
        // jika image tidak diubah
        else {
            // update data
            $product->update([
                'category_id' => $request->category,
                'name'        => $request->name,
                'description' => $request->description,
                'price'       => str_replace('.', '', $request->price)
            ]);
        }

        // redirect ke halaman index dan tampilkan pesan berhasil ubah data
        return redirect()->route('products.index')->with(['success' => 'Produk baru telah di update.']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id): RedirectResponse
    {
        // get data by ID
        $product = Product::findOrFail($id);

        // delete image
        Storage::delete('public/products/' . $product->image);

        // delete data
        $product->delete();

        // redirect ke halaman index dan tampilkan pesan berhasil hapus data
        return redirect()->route('products.index')->with(['success' => 'Produk baru telah dihapus!']);
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $kategori = $request->query('kategori');
        $query = Product::query();

        if ($kategori && $kategori !== 'Semua') {
            $query->where('kategori', $kategori);
        }

        $products = $query->orderBy('id', 'desc')->get();
        return view('toko.index', compact('products', 'kategori'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_produk' => 'required|string|max:150',
            'kategori'    => 'required|string|max:50',
            'harga'       => 'required|numeric|min:0',
            'stok'        => 'required|integer|min:0',
            'ukuran'      => 'nullable|string|max:20',
            'deskripsi'   => 'nullable|string',
            'gambar'      => 'nullable|string',
        ]);

        Product::create($validated);

        return redirect()->route('toko.index')->with('success', 'Produk berhasil ditambahkan ke katalog!');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return redirect()->route('toko.index')->with('success', 'Produk berhasil dihapus!');
    }
}

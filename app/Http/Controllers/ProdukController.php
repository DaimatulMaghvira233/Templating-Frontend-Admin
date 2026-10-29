<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Barryvdh\DomPDF\Facade\Pdf;

class ProdukController extends Controller
{
    public function index()
    {
        $produk = Produk::latest()->get();
        return view('admin.produk', compact('produk'));
    }

    public function create()
    {
        return view('admin.produk-create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_produk' => 'required|min:3',
            'deskripsi'   => 'nullable',
            'harga'       => 'required|numeric',
            'stok'        => 'required|integer',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $this->simpanGambar($request->file('image'));
        }

        Produk::create($data);

        return redirect()->route('admin.produk')->with('success', 'Produk berhasil ditambahkan');
    }

    public function edit(Produk $produk)
    {
        return view('admin.produk-edit', compact('produk'));
    }

    public function update(Request $request, Produk $produk)
    {
        $data = $request->validate([
            'nama_produk' => 'required|min:3',
            'deskripsi'   => 'nullable',
            'harga'       => 'required|numeric',
            'stok'        => 'required|integer',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($produk->image) {
                Storage::disk('public')->delete($produk->image);
            }
            $data['image'] = $this->simpanGambar($request->file('image'));
        }

        $produk->update($data);

        return redirect()->route('admin.produk')->with('success', 'Produk berhasil diupdate');
    }

    public function destroy(Produk $produk)
    {
        if ($produk->image) {
            Storage::disk('public')->delete($produk->image);
        }
        $produk->delete();

        return redirect()->route('admin.produk')->with('success', 'Produk berhasil dihapus');
    }

    private function simpanGambar($file): string
    {
        $original = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $ext      = strtolower($file->getClientOriginalExtension());
        $filename = time() . '_' . preg_replace('/[^A-Za-z0-9\-_]/', '', $original) . '.' . $ext;
        $path     = 'products/' . $filename;

        $manager = new ImageManager(Driver::class);
        $image   = $manager->decodePath($file->getRealPath())->cover(800, 800);

        $encoded = $ext === 'png'
            ? $image->encodeUsingFileExtension('png', quality: 90)
            : $image->encodeUsingFileExtension('jpg', quality: 85);

        Storage::disk('public')->put($path, $encoded->toString());

        return $path;
    }
}
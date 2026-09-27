<?php

namespace App\Http\Controllers;

use App\Models\Material;
use Illuminate\Http\Request;

class MaterialController extends Controller
{
    // Tampilkan List Material
    public function index()
    {
        $materials = Material::latest()->paginate(10);
        return view('admin.materials.index', compact('materials'));
    }

    // Simpan Material Baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:materials,code',
            'type' => 'required|in:fabric,accessories,packaging',
            'stock' => 'required|integer|min:0',
            'unit' => 'required|string',
        ]);

        Material::create($validated);
        return redirect()->back()->with('success', 'Material berhasil ditambahkan!');
    }

    // Update Material
    public function update(Request $request, Material $material)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'stock' => 'required|integer',
            'unit' => 'required|string',
        ]);

        $material->update($validated);
        return redirect()->back()->with('success', 'Stok/Data berhasil diupdate!');
    }

    // Hapus
    public function destroy(Material $material)
    {
        $material->delete();
        return redirect()->back()->with('success', 'Material dihapus.');
    }
}

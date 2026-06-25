<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PengaturanWebsite;
use Illuminate\Http\Request;

class PengaturanController extends Controller
{
    public function index()
    {
        // Because there's only one row for settings
        $pengaturan = PengaturanWebsite::first();
        return view('admin.pengaturan.index', compact('pengaturan'));
    }

    public function create() {}
    public function store(Request $request) {}
    public function show(string $id) {}
    public function edit(string $id) {}

    public function update(Request $request, string $id)
    {
        $request->validate([
            'nama_instansi' => 'required|string|max:255',
            'deskripsi_singkat' => 'required|string',
            'alamat' => 'required|string',
            'telepon' => 'required|string|max:50',
            'email' => 'required|email|max:255',
            'maps_embed' => 'nullable|string',
        ]);

        $pengaturan = PengaturanWebsite::findOrFail($id);
        $pengaturan->update($request->all());

        return redirect()->route('admin.pengaturan.index')->with('success', 'Pengaturan website berhasil diperbarui.');
    }

    public function destroy(string $id) {}
}

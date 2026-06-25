<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MasterPersyaratan;
use Illuminate\Http\Request;

class PersyaratanController extends Controller
{
    public function index()
    {
        $persyaratan = MasterPersyaratan::orderBy('id', 'asc')->get();
        return view('admin.persyaratan.index', compact('persyaratan'));
    }

    public function create()
    {
        return view('admin.persyaratan.form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_persyaratan' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'is_required' => 'required|boolean',
        ]);

        MasterPersyaratan::create($request->all());

        return redirect()->route('admin.persyaratan.index')->with('success', 'Syarat berhasil ditambahkan.');
    }

    public function edit(string $id)
    {
        $persyaratan = MasterPersyaratan::findOrFail($id);
        return view('admin.persyaratan.form', compact('persyaratan'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'nama_persyaratan' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'is_required' => 'required|boolean',
        ]);

        $persyaratan = MasterPersyaratan::findOrFail($id);
        $persyaratan->update($request->all());

        return redirect()->route('admin.persyaratan.index')->with('success', 'Syarat berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $persyaratan = MasterPersyaratan::findOrFail($id);
        $persyaratan->delete();
        
        return redirect()->route('admin.persyaratan.index')->with('success', 'Syarat berhasil dihapus.');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Signatory;
use Illuminate\Http\Request;

class SignatoryController extends Controller
{
    public function index()
    {
        $signatories = Signatory::orderBy('name', 'asc')->paginate(10);
        return view('admin.signatory.index', compact('signatories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:signatories,name',
        ]);

        Signatory::create([
            'name' => trim($request->name),
        ]);

        return redirect()->route('admin.signatory.index')->with('success', 'Nama Penandatangan berhasil ditambahkan.');
    }

    public function destroy($id)
    {
        $signatory = Signatory::findOrFail($id);
        $signatory->delete();

        return redirect()->route('admin.signatory.index')->with('success', 'Nama Penandatangan berhasil dihapus.');
    }
}

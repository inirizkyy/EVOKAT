<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Leader;
use Illuminate\Http\Request;

class LeaderController extends Controller
{
    public function index()
    {
        $leaders = Leader::orderBy('name', 'asc')->paginate(10);
        return view('admin.leader.index', compact('leaders'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:leaders,name',
        ]);

        Leader::create([
            'name' => trim($request->name),
        ]);

        return redirect()->route('admin.leader.index')->with('success', 'Pejabat Sumpah berhasil ditambahkan.');
    }

    public function destroy($id)
    {
        $leader = Leader::findOrFail($id);
        $leader->delete();

        return redirect()->route('admin.leader.index')->with('success', 'Pejabat Sumpah berhasil dihapus.');
    }
}

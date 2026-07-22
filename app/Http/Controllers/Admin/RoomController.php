<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Room;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index()
    {
        $rooms = Room::orderBy('name', 'asc')->paginate(10);
        return view('admin.room.index', compact('rooms'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:rooms,name',
        ]);

        Room::create([
            'name' => trim($request->name),
        ]);

        return redirect()->route('admin.room.index')->with('success', 'Ruang Sidang berhasil ditambahkan.');
    }

    public function destroy($id)
    {
        $room = Room::findOrFail($id);
        $room->delete();

        return redirect()->route('admin.room.index')->with('success', 'Ruang Sidang berhasil dihapus.');
    }
}

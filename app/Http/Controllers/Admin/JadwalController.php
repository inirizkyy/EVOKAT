<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JadwalSumpah;
use Illuminate\Http\Request;

class JadwalController extends Controller
{
    public function index()
    {
        $jadwals = JadwalSumpah::with('permohonan.pemohon')->orderBy('tanggal', 'desc')->paginate(10);
        return view('admin.jadwal.index', compact('jadwals'));
    }

    public function create()
    {
        return view('admin.jadwal.form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'permohonan_id' => 'required|exists:permohonans,id|unique:jadwal_sumpahs,permohonan_id',
            'tanggal' => 'required|date',
            'jam' => 'required',
            'lokasi' => 'required|string',
            'keterangan' => 'nullable|string',
        ]);

        $jadwal = JadwalSumpah::create($request->all());
        
        $permohonan = \App\Models\Permohonan::find($jadwal->permohonan_id);
        if($permohonan && $permohonan->status == 'Disetujui') {
            $permohonan->update(['status' => 'Dijadwalkan Sumpah']);
        }

        try {
            if ($permohonan) {
                \Illuminate\Support\Facades\Mail::to($permohonan->pemohon->email, $permohonan->pemohon->nama_lengkap)
                    ->send(new \App\Mail\JadwalSumpahMail($jadwal));
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Gagal mengirim email jadwal: ' . $e->getMessage());
        }

        return redirect()->route('admin.jadwal.index')->with('success', 'Jadwal berhasil ditambahkan.');
    }

    public function edit(JadwalSumpah $jadwal)
    {
        return view('admin.jadwal.form', compact('jadwal'));
    }

    public function update(Request $request, JadwalSumpah $jadwal)
    {
        $request->validate([
            'permohonan_id' => 'required|exists:permohonans,id|unique:jadwal_sumpahs,permohonan_id,' . $jadwal->id,
            'tanggal' => 'required|date',
            'jam' => 'required',
            'lokasi' => 'required|string',
            'keterangan' => 'nullable|string',
        ]);

        $jadwal->update($request->all());

        try {
            $permohonan = \App\Models\Permohonan::find($jadwal->permohonan_id);
            if ($permohonan) {
                \Illuminate\Support\Facades\Mail::to($permohonan->pemohon->email, $permohonan->pemohon->nama_lengkap)
                    ->send(new \App\Mail\JadwalSumpahMail($jadwal));
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Gagal mengirim email update jadwal: ' . $e->getMessage());
        }

        return redirect()->route('admin.jadwal.index')->with('success', 'Jadwal berhasil diperbarui.');
    }

    public function destroy(JadwalSumpah $jadwal)
    {
        $jadwal->delete();
        return redirect()->route('admin.jadwal.index')->with('success', 'Jadwal berhasil dihapus.');
    }
}

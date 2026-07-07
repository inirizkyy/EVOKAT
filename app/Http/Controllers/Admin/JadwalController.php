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

        \Illuminate\Support\Facades\DB::beginTransaction();
        try {
            $jadwal = JadwalSumpah::create($request->all());
            
            $permohonan = \App\Models\Permohonan::find($jadwal->permohonan_id);
            if ($permohonan) {
                $permohonan->syncStatusAndNotify();
            }

            \Illuminate\Support\Facades\DB::commit();
            return redirect()->route('admin.jadwal.index')->with('success', 'Jadwal berhasil ditambahkan.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return back()->with('error', 'Gagal menambahkan jadwal: ' . $e->getMessage())->withInput();
        }
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

        \Illuminate\Support\Facades\DB::beginTransaction();
        try {
            $jadwal->update($request->all());

            $permohonan = \App\Models\Permohonan::find($jadwal->permohonan_id);
            if ($permohonan) {
                // Sync status and notify. It will return true if status transitioned to Dijadwalkan Sumpah and sent mail.
                $transitioned = $permohonan->syncStatusAndNotify();

                // If status did not transition but is already Dijadwalkan Sumpah, we explicitly send the updated schedule email.
                if (!$transitioned && $permohonan->status === 'Dijadwalkan Sumpah') {
                    try {
                        \Illuminate\Support\Facades\Mail::to($permohonan->pemohon->email, $permohonan->pemohon->nama_lengkap)
                            ->send(new \App\Mail\JadwalSumpahMail($jadwal));
                    } catch (\Exception $e) {
                        \Illuminate\Support\Facades\Log::error('Gagal mengirim email update jadwal: ' . $e->getMessage());
                    }
                }
            }

            \Illuminate\Support\Facades\DB::commit();
            return redirect()->route('admin.jadwal.index')->with('success', 'Jadwal berhasil diperbarui.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return back()->with('error', 'Gagal memperbarui jadwal: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy(JadwalSumpah $jadwal)
    {
        \Illuminate\Support\Facades\DB::beginTransaction();
        try {
            $permohonan = \App\Models\Permohonan::find($jadwal->permohonan_id);
            $jadwal->delete();

            if ($permohonan) {
                $permohonan->syncStatusAndNotify();
            }

            \Illuminate\Support\Facades\DB::commit();
            return redirect()->route('admin.jadwal.index')->with('success', 'Jadwal berhasil dihapus.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return back()->with('error', 'Gagal menghapus jadwal: ' . $e->getMessage());
        }
    }
}

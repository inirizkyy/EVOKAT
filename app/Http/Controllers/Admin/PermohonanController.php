<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permohonan;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class PermohonanController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Permohonan::with(['pemohon.organisasi'])->select('permohonans.*');
            return DataTables::eloquent($data)
                ->addIndexColumn()
                ->addColumn('nama_pemohon', function($row){
                    return $row->pemohon->nama_lengkap;
                })
                ->addColumn('nik', function($row){
                    return $row->pemohon->nik;
                })
                ->addColumn('organisasi', function($row){
                    return $row->pemohon->organisasi->nama_organisasi ?? '-';
                })
                ->addColumn('status_badge', function($row){
                    if($row->status == 'Menunggu Verifikasi') return '<span class="inline-flex items-center px-2 py-0.5 rounded-default text-xs font-medium bg-neutral-secondary-medium border border-border-default text-heading">Menunggu</span>';
                    if($row->status == 'Disetujui') return '<span class="inline-flex items-center px-2 py-0.5 rounded-default text-xs font-medium bg-success-soft border border-border-success-subtle text-fg-success-strong">Disetujui</span>';
                    if($row->status == 'Selesai') return '<span class="inline-flex items-center px-2 py-0.5 rounded-default text-xs font-medium bg-brand-softer border border-border-brand-subtle text-fg-brand-strong">Selesai</span>';
                    if($row->status == 'Ditolak') return '<span class="inline-flex items-center px-2 py-0.5 rounded-default text-xs font-medium bg-danger-soft border border-border-danger-subtle text-fg-danger-strong">Ditolak</span>';
                    return '<span class="inline-flex items-center px-2 py-0.5 rounded-default text-xs font-medium bg-warning-soft border border-border-warning-subtle text-fg-warning">'.$row->status.'</span>';
                })
                ->addColumn('action', function($row){
                    $detailUrl = route('admin.permohonan.show', $row->id);
                    return '<a href="'.$detailUrl.'" class="inline-flex items-center px-3 py-1.5 rounded-base text-sm font-medium bg-neutral-primary-soft text-brand shadow-sm hover:shadow-md hover:text-brand-strong active:shadow-inset transition-all border border-brand"><i class="fa-solid fa-eye mr-2"></i> Detail</a>';
                })
                ->rawColumns(['status_badge', 'action'])
                ->make(true);
        }
        return view('admin.permohonan.index');
    }

    public function create() {}
    public function store(Request $request) {}
    public function show(string $id) {
        $permohonan = Permohonan::with(['pemohon.organisasi', 'dokumenPersyaratan.masterPersyaratan', 'riwayatStatus', 'verifikasi'])->findOrFail($id);
        return view('admin.permohonan.show', compact('permohonan'));
    }
    public function edit(string $id) {}
    public function update(Request $request, string $id) {}
    public function destroy(string $id) {}
    public function verifikasi(Request $request, string $id) {
        $request->validate([
            'status' => 'required|in:Disetujui,Ditolak,Selesai',
            'catatan' => 'nullable|string'
        ]);

        $permohonan = Permohonan::findOrFail($id);
        $permohonan->status = $request->status;
        $permohonan->catatan = $request->catatan;
        $permohonan->save();

        return redirect()->route('admin.permohonan.index')->with('success', 'Status permohonan berhasil diperbarui.');
    }
}

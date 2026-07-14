<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;

class OrganizationController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Organization::withCount(['pemohons', 'permohonans']);
            
            return DataTables::eloquent($data)
                ->addIndexColumn()
                ->addColumn('status_badge', function ($row) {
                    if ($row->status === 'Aktif') {
                        return '<span class="px-2.5 py-1 text-xs font-bold rounded-full bg-success-soft text-fg-success-strong">Aktif</span>';
                    } elseif ($row->status === 'Nonaktif') {
                        return '<span class="px-2.5 py-1 text-xs font-bold rounded-full bg-neutral-secondary-medium text-body-subtle">Nonaktif</span>';
                    } else {
                        return '<span class="px-2.5 py-1 text-xs font-bold rounded-full bg-warning-soft text-fg-warning">Menunggu Persetujuan</span>';
                    }
                })
                ->addColumn('tanggal_dibuat', function ($row) {
                    return Carbon::parse($row->created_at)->translatedFormat('d M Y');
                })
                ->addColumn('action', function ($row) {
                    $btn = '<div class="flex gap-2">';
                    $btn .= '<a href="' . route('admin.organisasi.show', $row->id) . '" class="inline-flex items-center justify-center w-8 h-8 rounded-base bg-neutral-primary-soft text-brand shadow-sm hover:shadow-md active:shadow-inset transition-all border border-border-default"><i class="fa-solid fa-eye text-xs"></i></a>';
                    $btn .= '<a href="' . route('admin.organisasi.edit', $row->id) . '" class="inline-flex items-center justify-center w-8 h-8 rounded-base bg-neutral-primary-soft text-warning shadow-sm hover:shadow-md active:shadow-inset transition-all border border-border-default"><i class="fa-solid fa-pen text-xs"></i></a>';
                    $btn .= '<form action="' . route('admin.organisasi.destroy', $row->id) . '" method="POST" class="inline-block" onsubmit="return confirm(\'Hapus organisasi ini?\')">';
                    $btn .= csrf_field() . method_field('DELETE');
                    $btn .= '<button type="submit" class="inline-flex items-center justify-center w-8 h-8 rounded-base bg-neutral-primary-soft text-danger shadow-sm hover:shadow-md active:shadow-inset transition-all border border-border-default"><i class="fa-solid fa-trash text-xs"></i></button>';
                    $btn .= '</form>';
                    $btn .= '</div>';
                    return $btn;
                })
                ->rawColumns(['status_badge', 'action'])
                ->make(true);
        }

        return view('admin.organisasi.index');
    }

    public function create()
    {
        return view('admin.organisasi.form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_organisasi' => [
                'required',
                'string',
                'max:255',
                function ($attribute, $value, $fail) {
                    $exists = Organization::whereRaw('LOWER(nama_organisasi) = ?', [strtolower(trim($value))])
                        ->whereNull('deleted_at')
                        ->exists();
                    if ($exists) {
                        $fail('Nama organisasi sudah terdaftar.');
                    }
                }
            ],
            'singkatan' => 'nullable|string|max:50',
            'status' => 'required|in:Aktif,Nonaktif,Menunggu Persetujuan',
        ]);

        Organization::create([
            'nama_organisasi' => trim($request->nama_organisasi),
            'singkatan' => $request->singkatan ? strtoupper(trim($request->singkatan)) : null,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.organisasi.index')->with('success', 'Organisasi berhasil ditambahkan.');
    }

    public function show($id)
    {
        $organization = Organization::withCount(['pemohons', 'permohonans'])->findOrFail($id);

        // Get status statistics
        $stats = [
            'sedang_diverifikasi' => $organization->permohonans()->whereIn('status', ['Menunggu Verifikasi', 'Verifikasi Berkas Fisik'])->count(),
            'disetujui' => $organization->permohonans()->whereIn('status', ['Siap Penjadwalan Pengecekan Berkas Fisik', 'Menentukan Jadwal Verifikasi', 'Menentukan Jadwal Sumpah', 'Proses Pembuatan Surat', 'Surat Selesai', 'Disetujui'])->count(),
            'ditolak' => $organization->permohonans()->where('status', 'Ditolak')->count(),
            'sudah_disumpah' => $organization->permohonans()->whereIn('status', ['Selesai', 'Dijadwalkan Sumpah'])->count(),
        ];

        $pemohons = $organization->pemohons()->with('permohonan')->paginate(10);

        return view('admin.organisasi.show', compact('organization', 'stats', 'pemohons'));
    }

    public function edit($id)
    {
        $organization = Organization::findOrFail($id);
        return view('admin.organisasi.form', compact('organization'));
    }

    public function update(Request $request, $id)
    {
        $organization = Organization::findOrFail($id);

        $request->validate([
            'nama_organisasi' => [
                'required',
                'string',
                'max:255',
                function ($attribute, $value, $fail) use ($id) {
                    $exists = Organization::whereRaw('LOWER(nama_organisasi) = ?', [strtolower(trim($value))])
                        ->where('id', '!=', $id)
                        ->whereNull('deleted_at')
                        ->exists();
                    if ($exists) {
                        $fail('Nama organisasi sudah terdaftar.');
                    }
                }
            ],
            'singkatan' => 'nullable|string|max:50',
            'status' => 'required|in:Aktif,Nonaktif,Menunggu Persetujuan',
        ]);

        $organization->update([
            'nama_organisasi' => trim($request->nama_organisasi),
            'singkatan' => $request->singkatan ? strtoupper(trim($request->singkatan)) : null,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.organisasi.index')->with('success', 'Organisasi berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $organization = Organization::findOrFail($id);
        $organization->delete();

        return redirect()->route('admin.organisasi.index')->with('success', 'Organisasi berhasil dihapus.');
    }
}

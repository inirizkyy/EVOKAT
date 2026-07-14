<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BukuRegistrasiAdvokat;
use App\Models\Permohonan;
use App\Models\Pemohon;
use Illuminate\Http\Request;
use App\Exports\BukuRegistrasiExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class BukuRegistrasiController extends Controller
{
    public function index(Request $request)
    {
        $query = BukuRegistrasiAdvokat::with(['pemohon.organisasi', 'permohonan']);

        // Search by name
        if ($request->filled('search_name')) {
            $query->whereHas('pemohon', function ($q) use ($request) {
                $q->where('nama_lengkap', 'like', '%' . $request->search_name . '%');
            });
        }

        // Search by organisasi
        if ($request->filled('search_organisasi')) {
            $query->whereHas('pemohon.organisasi', function ($q) use ($request) {
                $q->where('nama_organisasi', 'like', '%' . $request->search_organisasi . '%');
            });
        }

        // Search by Nomor SK
        if ($request->filled('search_sk')) {
            $query->whereHas('pemohon', function ($q) use ($request) {
                $q->where('nomor_sk', 'like', '%' . $request->search_sk . '%');
            });
        }

        // Filter by tanggal SK
        if ($request->filled('filter_tanggal_sk_start') && $request->filled('filter_tanggal_sk_end')) {
            $query->whereHas('pemohon', function ($q) use ($request) {
                $q->whereBetween('tanggal_sk', [$request->filter_tanggal_sk_start, $request->filter_tanggal_sk_end]);
            });
        }

        // Filter by tanggal disumpah
        if ($request->filled('filter_tanggal_sumpah_start') && $request->filled('filter_tanggal_sumpah_end')) {
            $query->whereBetween('tanggal_disumpah', [$request->filter_tanggal_sumpah_start, $request->filter_tanggal_sumpah_end]);
        }

        $status = $request->query('status', 'belum_lengkap');

        // Belum Lengkap: BukuRegistrasiAdvokat where nomor_bas is null
        $countBelumLengkap = (clone $query)->whereNull('nomor_bas')->count();

        // Sudah Lengkap: BukuRegistrasiAdvokat where nomor_bas is not null
        $countSudahLengkap = (clone $query)->whereNotNull('nomor_bas')->count();

        // Apply status filter
        if ($status === 'lengkap') {
            $query->whereNotNull('nomor_bas');
        } else {
            $query->whereNull('nomor_bas');
        }

        $registrasi = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        return view('admin.buku-registrasi.index', compact('registrasi', 'countBelumLengkap', 'countSudahLengkap', 'status'));
    }

    public function show($id)
    {
        $permohonan = Permohonan::with(['organisasi', 'pemohons.bukuRegistrasi', 'pemohons.dokumenPersyaratan'])->findOrFail($id);
        return view('admin.buku-registrasi.show', compact('permohonan'));
    }

    public function showMember($id)
    {
        $reg = BukuRegistrasiAdvokat::with(['pemohon.organisasi', 'pemohon.dokumenPersyaratan', 'permohonan.verifikasi'])->findOrFail($id);
        return view('admin.buku-registrasi.show-member', compact('reg'));
    }

    public function edit($id)
    {
        $reg = BukuRegistrasiAdvokat::with(['pemohon.organisasi', 'permohonan'])->findOrFail($id);
        return view('admin.buku-registrasi.edit', compact('reg'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nomor_bas' => 'required|string|max:255',
            'tanggal_disumpah' => 'required|date',
            'ketua_pengadilan_tinggi' => 'required|string|max:255',
            'saksi' => 'required|string',
        ]);

        $reg = BukuRegistrasiAdvokat::findOrFail($id);
        $reg->update($request->all());

        return redirect()->route('admin.buku-registrasi.show', $reg->permohonan_id)
            ->with('success', 'Data Buku Registrasi berhasil diperbarui.');
    }

    public function print($id)
    {
        $reg = BukuRegistrasiAdvokat::with(['pemohon.organisasi', 'pemohon.dokumenPersyaratan', 'permohonan'])->findOrFail($id);
        return view('admin.buku-registrasi.print', compact('reg'));
    }

    public function exportPdf(Request $request)
    {
        $query = BukuRegistrasiAdvokat::with(['pemohon.organisasi']);

        if ($request->filled('search_name')) {
            $query->whereHas('pemohon', function ($q) use ($request) {
                $q->where('nama_lengkap', 'like', '%' . $request->search_name . '%');
            });
        }
        if ($request->filled('search_organisasi')) {
            $query->whereHas('pemohon.organisasi', function ($q) use ($request) {
                $q->where('nama_organisasi', 'like', '%' . $request->search_organisasi . '%');
            });
        }
        if ($request->filled('search_sk')) {
            $query->whereHas('pemohon', function ($q) use ($request) {
                $q->where('nomor_sk', 'like', '%' . $request->search_sk . '%');
            });
        }
        if ($request->filled('filter_tanggal_sk_start') && $request->filled('filter_tanggal_sk_end')) {
            $query->whereHas('pemohon', function ($q) use ($request) {
                $q->whereBetween('tanggal_sk', [$request->filter_tanggal_sk_start, $request->filter_tanggal_sk_end]);
            });
        }
        if ($request->filled('filter_tanggal_sumpah_start') && $request->filled('filter_tanggal_sumpah_end')) {
            $query->whereBetween('tanggal_disumpah', [$request->filter_tanggal_sumpah_start, $request->filter_tanggal_sumpah_end]);
        }

        $status = $request->query('status');
        if ($status === 'lengkap') {
            $query->whereNotNull('nomor_bas');
        } elseif ($status === 'belum_lengkap') {
            $query->whereNull('nomor_bas');
        }

        $registrasi = $query->orderBy('created_at', 'desc')->get();
        $pdf = Pdf::loadView('admin.buku-registrasi.export_pdf', compact('registrasi'))->setPaper('a4', 'landscape');
        
        return $pdf->download('Buku_Registrasi_Advokat.pdf');
    }

    public function exportExcel(Request $request)
    {
        $query = BukuRegistrasiAdvokat::with(['pemohon.organisasi']);

        if ($request->filled('search_name')) {
            $query->whereHas('pemohon', function ($q) use ($request) {
                $q->where('nama_lengkap', 'like', '%' . $request->search_name . '%');
            });
        }
        if ($request->filled('search_organisasi')) {
            $query->whereHas('pemohon.organisasi', function ($q) use ($request) {
                $q->where('nama_organisasi', 'like', '%' . $request->search_organisasi . '%');
            });
        }
        if ($request->filled('search_sk')) {
            $query->whereHas('pemohon', function ($q) use ($request) {
                $q->where('nomor_sk', 'like', '%' . $request->search_sk . '%');
            });
        }
        if ($request->filled('filter_tanggal_sk_start') && $request->filled('filter_tanggal_sk_end')) {
            $query->whereHas('pemohon', function ($q) use ($request) {
                $q->whereBetween('tanggal_sk', [$request->filter_tanggal_sk_start, $request->filter_tanggal_sk_end]);
            });
        }
        if ($request->filled('filter_tanggal_sumpah_start') && $request->filled('filter_tanggal_sumpah_end')) {
            $query->whereBetween('tanggal_disumpah', [$request->filter_tanggal_sumpah_start, $request->filter_tanggal_sumpah_end]);
        }

        $status = $request->query('status');
        if ($status === 'lengkap') {
            $query->whereNotNull('nomor_bas');
        } elseif ($status === 'belum_lengkap') {
            $query->whereNull('nomor_bas');
        }

        return Excel::download(new BukuRegistrasiExport($query), 'Buku_Registrasi_Advokat.xlsx');
    }
}

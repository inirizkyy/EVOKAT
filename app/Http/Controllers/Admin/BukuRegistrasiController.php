<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BukuRegistrasiAdvokat;
use App\Models\Permohonan;
use App\Models\Pemohon;
use App\Models\Leader;
use Illuminate\Http\Request;
use App\Exports\BukuRegistrasiExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class BukuRegistrasiController extends Controller
{
    public function index(Request $request)
    {
        $query = BukuRegistrasiAdvokat::with(['pemohon.organisasi', 'permohonan']);
        $query = $this->applySearchFilters($request, $query);

        // Belum Lengkap: BukuRegistrasiAdvokat where nomor_bas is null
        $countBelumLengkap = (clone $query)->whereNull('nomor_bas')->count();

        // Sudah Lengkap: BukuRegistrasiAdvokat where nomor_bas is not null
        $countSudahLengkap = (clone $query)->whereNotNull('nomor_bas')->count();

        $status = $request->query('status', 'belum_lengkap');
        if ($status === 'lengkap') {
            $query->whereNotNull('nomor_bas');
        } else {
            $query->whereNull('nomor_bas');
        }

        $registrasi = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        return view('admin.buku-registrasi.index', compact('registrasi', 'countBelumLengkap', 'countSudahLengkap', 'status'));
    }

    private function applySearchFilters(Request $request, $query)
    {
        // Search by name
        if ($request->filled('search_name')) {
            $query->whereHas('pemohon', function ($q) use ($request) {
                $q->where('nama_lengkap', 'like', '%' . $request->search_name . '%');
            });
        }

        // Search by organisasi
        if ($request->filled('search_organisasi')) {
            $searchOrg = $request->search_organisasi;
            if ($searchOrg === '+ Diajukan') {
                $query->whereHas('pemohon.organisasi', function ($q) {
                    $q->where('status', 'Menunggu Persetujuan');
                });
            } else {
                $query->whereHas('pemohon.organisasi', function ($q) use ($searchOrg) {
                    $q->where('nama_organisasi', $searchOrg);
                });
            }
        }

        // Search by Nomor SK
        if ($request->filled('search_sk')) {
            $query->where(function ($query) use ($request) {
                $query->whereHas('permohonan', function ($q) use ($request) {
                    $q->where('nomor_sk', 'like', '%' . $request->search_sk . '%');
                })->orWhereHas('pemohon', function ($q) use ($request) {
                    $q->where('nomor_sk', 'like', '%' . $request->search_sk . '%');
                });
            });
        }

        // Filter by tanggal SK
        if ($request->filled('filter_tanggal_sk_start') && $request->filled('filter_tanggal_sk_end')) {
            $query->where(function ($query) use ($request) {
                $query->whereHas('permohonan', function ($q) use ($request) {
                    $q->whereBetween('tanggal_sk', [$request->filter_tanggal_sk_start, $request->filter_tanggal_sk_end]);
                })->orWhereHas('pemohon', function ($q) use ($request) {
                    $q->whereBetween('tanggal_sk', [$request->filter_tanggal_sk_start, $request->filter_tanggal_sk_end]);
                });
            });
        }

        // Filter by tanggal disumpah
        if ($request->filled('filter_tanggal_sumpah_start') && $request->filled('filter_tanggal_sumpah_end')) {
            $query->whereBetween('tanggal_disumpah', [$request->filter_tanggal_sumpah_start, $request->filter_tanggal_sumpah_end]);
        }

        return $query;
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
        
        $saksiArray = explode(';', $reg->saksi);
        $saksi_1 = trim($saksiArray[0] ?? '');
        $saksi_2 = trim($saksiArray[1] ?? '');
        
        $leaders = Leader::orderBy('name', 'asc')->get();

        return view('admin.buku-registrasi.edit', compact('reg', 'saksi_1', 'saksi_2', 'leaders'));
    }

    public function update(Request $request, $id)
    {
        $reg = BukuRegistrasiAdvokat::findOrFail($id);
        
        if ($reg->status_pemeriksa === 'Disetujui') {
            return redirect()->route('admin.buku-registrasi.show', $reg->permohonan_id)
                ->with('error', 'Data ini sudah disetujui oleh Pemeriksa dan dikunci.');
        }

        $request->validate([
            'nomor_bas' => 'required|string|max:255',
            'tanggal_disumpah' => 'required|date',
            'ketua_pengadilan_tinggi' => 'required|string|max:255',
            'saksi_1' => 'required|string|max:255',
            'saksi_2' => 'required|string|max:255',
        ]);
        
        $data = $request->except(['saksi_1', 'saksi_2']);
        $data['saksi'] = trim($request->saksi_1) . ';' . trim($request->saksi_2);

        $reg->update($data);

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
        $query = BukuRegistrasiAdvokat::with(['pemohon.organisasi', 'permohonan']);
        $query = $this->applySearchFilters($request, $query);

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
        $query = BukuRegistrasiAdvokat::with(['pemohon.organisasi', 'permohonan']);
        $query = $this->applySearchFilters($request, $query);

        $status = $request->query('status');
        if ($status === 'lengkap') {
            $query->whereNotNull('nomor_bas');
        } elseif ($status === 'belum_lengkap') {
            $query->whereNull('nomor_bas');
        }

        return Excel::download(new BukuRegistrasiExport($query), 'Buku_Registrasi_Advokat.xlsx');
    }
}

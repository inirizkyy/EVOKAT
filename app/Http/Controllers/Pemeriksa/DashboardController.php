<?php

namespace App\Http\Controllers\Pemeriksa;

use App\Http\Controllers\Controller;
use App\Models\BukuRegistrasiAdvokat;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $query = BukuRegistrasiAdvokat::with(['pemohon.organisasi', 'permohonan'])
            ->whereNotNull('nomor_bas'); // Hanya yang sudah di-input BAS & Sumpah oleh Admin

        // Filter status_pemeriksa
        $status = $request->query('status', 'all');
        if ($status === 'pending') {
            $query->where('status_pemeriksa', 'Pending');
        } elseif ($status === 'disetujui') {
            $query->where('status_pemeriksa', 'Disetujui');
        }

        // Search by name
        if ($request->filled('search_name')) {
            $query->whereHas('pemohon', function ($q) use ($request) {
                $q->where('nama_lengkap', 'like', '%' . $request->search_name . '%');
            });
        }

        $registrasi = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        // Count for stats badges
        $countAll = BukuRegistrasiAdvokat::whereNotNull('nomor_bas')->count();
        $countPending = BukuRegistrasiAdvokat::whereNotNull('nomor_bas')->where('status_pemeriksa', 'Pending')->count();
        $countApproved = BukuRegistrasiAdvokat::whereNotNull('nomor_bas')->where('status_pemeriksa', 'Disetujui')->count();

        return view('pemeriksa.dashboard', compact('registrasi', 'status', 'countAll', 'countPending', 'countApproved'));
    }

    public function approve(Request $request, $id)
    {
        $reg = BukuRegistrasiAdvokat::findOrFail($id);

        if ($reg->status_pemeriksa === 'Disetujui') {
            return back()->with('info', 'Data registrasi advokat ini sudah disetujui sebelumnya.');
        }

        $reg->update([
            'status_pemeriksa' => 'Disetujui'
        ]);

        return back()->with('success', 'Data registrasi advokat berhasil disetujui dan dikunci.');
    }

    public function unlock(Request $request, $id)
    {
        $reg = BukuRegistrasiAdvokat::findOrFail($id);

        if ($reg->status_pemeriksa !== 'Disetujui') {
            return back()->with('info', 'Data registrasi advokat ini belum disetujui.');
        }

        $reg->update([
            'status_pemeriksa' => 'Pending'
        ]);

        return back()->with('success', 'Kunci data berhasil dibuka. Admin dapat mengedit data kembali.');
    }

    public function showMember($id)
    {
        $reg = BukuRegistrasiAdvokat::with(['pemohon.organisasi', 'pemohon.dokumenPersyaratan', 'permohonan.verifikasi'])->findOrFail($id);
        return view('pemeriksa.show-member', compact('reg'));
    }
}

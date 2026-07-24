<?php

namespace App\Http\Controllers\Verifikator;

use App\Http\Controllers\Controller;
use App\Models\Permohonan;
use App\Models\BukuRegistrasiAdvokat;
use App\Models\RiwayatStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PermohonanController extends Controller
{
    private function getTargetStatusForRole($role)
    {
        switch ($role) {
            case 'verifikator1':
                return 'Menunggu Verifikasi Verifikator 1';
            case 'verifikator2':
                return 'Menunggu Verifikasi Verifikator 2';
            case 'verifikator3':
                return 'Menunggu Verifikasi Verifikator 3';
            case 'verifikator4':
                return 'Menunggu Verifikasi Verifikator 4';
            default:
                return null;
        }
    }

    public function dashboard()
    {
        $role = Auth::user()->role;
        $targetStatus = $this->getTargetStatusForRole($role);

        $countQueue = $targetStatus ? Permohonan::where('status', $targetStatus)->count() : 0;
        $countAll = Permohonan::count();
        $countSelesai = Permohonan::where('status', 'Selesai')->count();
        $countBukuReg = BukuRegistrasiAdvokat::whereNotNull('nomor_bas')->count();

        return view('verifikator.dashboard', compact('countQueue', 'countAll', 'countSelesai', 'countBukuReg'));
    }

    public function index(Request $request)
    {
        $role = Auth::user()->role;
        $targetStatus = $this->getTargetStatusForRole($role);

        if (!$targetStatus) {
            abort(403, 'Akses ditolak.');
        }

        $statusPerbaikanMap = [
            'verifikator1' => 'Menunggu Perbaikan Dokumen Verifikator 1',
            'verifikator2' => 'Menunggu Perbaikan Dokumen Verifikator 2',
            'verifikator3' => 'Menunggu Perbaikan Dokumen Verifikator 3',
            'verifikator4' => 'Menunggu Perbaikan Dokumen Verifikator 4',
        ];
        $statusPerbaikan = $statusPerbaikanMap[$role] ?? null;

        $statusFieldMap = [
            'verifikator1' => 'status_verifikator1',
            'verifikator2' => 'status_verifikator2',
            'verifikator3' => 'status_verifikator3',
            'verifikator4' => 'status_verifikator4',
        ];
        $statusField = $statusFieldMap[$role] ?? null;

        // Filter berdasarkan Tab
        $tab = $request->query('tab', 'antrean');

        $query = Permohonan::with(['organisasi', 'pemohons']);

        $activeStatuses = array_filter([$targetStatus, $statusPerbaikan]);

        if ($tab === 'antrean') {
            // HANYA MUNCULKAN PERMOHONAN YANG SEKARANG ADA DI ANTREAN ROLE INI
            $query->whereIn('status', $activeStatuses);
        } else {
            // TAB RIWAYAT: Permohonan yang sudah pernah diverifikasi/disetujui oleh role ini
            if ($statusField) {
                $query->whereNotNull($statusField)
                      ->whereNotIn('status', $activeStatuses);
            }
        }

        $permohonans = $query->orderBy('updated_at', 'desc')
                            ->orderBy('created_at', 'desc')
                            ->paginate(10)
                            ->withQueryString();

        // Hitung badge count untuk antrean & riwayat
        $countAntrean = Permohonan::whereIn('status', $activeStatuses)->count();
        $countRiwayat = $statusField 
            ? Permohonan::whereNotNull($statusField)->whereNotIn('status', $activeStatuses)->count()
            : 0;

        return view('verifikator.permohonan.index', compact('permohonans', 'role', 'targetStatus', 'tab', 'countAntrean', 'countRiwayat'));
    }

    public function show($id)
    {
        $role = Auth::user()->role;
        $targetStatus = $this->getTargetStatusForRole($role);

        if (!$targetStatus) {
            abort(403, 'Akses ditolak.');
        }

        $statusPerbaikanMap = [
            'verifikator1' => 'Menunggu Perbaikan Dokumen Verifikator 1',
            'verifikator2' => 'Menunggu Perbaikan Dokumen Verifikator 2',
            'verifikator3' => 'Menunggu Perbaikan Dokumen Verifikator 3',
            'verifikator4' => 'Menunggu Perbaikan Dokumen Verifikator 4',
        ];
        $statusPerbaikan = $statusPerbaikanMap[$role] ?? null;

        $statusFieldMap = [
            'verifikator1' => 'status_verifikator1',
            'verifikator2' => 'status_verifikator2',
            'verifikator3' => 'status_verifikator3',
            'verifikator4' => 'status_verifikator4',
        ];
        $statusField = $statusFieldMap[$role] ?? null;

        $activeStatuses = array_filter([$targetStatus, $statusPerbaikan]);

        $permohonan = Permohonan::with([
            'pemohon.organisasi',
            'pemohons.organisasi',
            'pemohons.dokumenPersyaratan.masterPersyaratan',
            'riwayatStatus',
            'verifikasi',
            'jadwalSumpah',
            'organisasi'
        ])->where(function($query) use ($activeStatuses, $statusField) {
            $query->whereIn('status', $activeStatuses);
            if ($statusField) {
                $query->orWhereNotNull($statusField);
            }
        })->findOrFail($id);

        return view('verifikator.permohonan.show', compact('permohonan', 'role', 'targetStatus', 'statusPerbaikan'));
    }

    public function approve(Request $request, $id)
    {
        $role = Auth::user()->role;
        $targetStatus = $this->getTargetStatusForRole($role);

        if (!$targetStatus) {
            abort(403, 'Akses ditolak.');
        }

        $statusPerbaikanMap = [
            'verifikator1' => 'Menunggu Perbaikan Dokumen Verifikator 1',
            'verifikator2' => 'Menunggu Perbaikan Dokumen Verifikator 2',
            'verifikator3' => 'Menunggu Perbaikan Dokumen Verifikator 3',
            'verifikator4' => 'Menunggu Perbaikan Dokumen Verifikator 4',
        ];
        $statusPerbaikan = $statusPerbaikanMap[$role] ?? null;

        $permohonan = Permohonan::where(function($q) use ($targetStatus, $statusPerbaikan) {
            $q->where('status', $targetStatus);
            if ($statusPerbaikan) {
                $q->orWhere('status', $statusPerbaikan);
            }
        })->findOrFail($id);

        DB::beginTransaction();
        try {
            // Save document validation decisions
            foreach ($request->input('dokumen', []) as $dokId => $status) {
                $dok = \App\Models\DokumenPersyaratan::findOrFail($dokId);
                $dok->status_dokumen = $status;
                $dok->keterangan = $request->input("keterangan_dokumen.{$dokId}");
                $dok->save();
            }

            $oldStatus = $permohonan->status;
            $catatan = $request->input('catatan');

            if ($role === 'verifikator1') {
                $permohonan->status_verifikator1 = 'disetujui';
                $permohonan->catatan_verifikator1 = $catatan;
                $permohonan->status = 'Menunggu Verifikasi Verifikator 2';
            } elseif ($role === 'verifikator2') {
                $permohonan->status_verifikator2 = 'disetujui';
                $permohonan->catatan_verifikator2 = $catatan;
                $permohonan->status = 'Menunggu Verifikasi Verifikator 3';
            } elseif ($role === 'verifikator3') {
                $permohonan->status_verifikator3 = 'disetujui';
                $permohonan->catatan_verifikator3 = $catatan;
                $permohonan->status = 'Menunggu Verifikasi Verifikator 4';
            } elseif ($role === 'verifikator4') {
                $permohonan->status_verifikator4 = 'disetujui';
                $permohonan->catatan_verifikator4 = $catatan;
                $permohonan->status = 'Menentukan Jadwal Sumpah';
            }

            foreach ($permohonan->pemohons as $pemohon) {
                $hasInvalid = $pemohon->dokumenPersyaratan()->where('status_dokumen', 'Tidak Valid')->exists();
                if (!$hasInvalid || $role === 'verifikator4') {
                    $pemohon->status_verifikasi = 'Disetujui';
                    $pemohon->save();
                }
            }

            $permohonan->save();

            RiwayatStatus::create([
                'permohonan_id' => $permohonan->id,
                'status_lama' => $oldStatus,
                'status_baru' => $permohonan->status,
                'keterangan' => $catatan ?? 'Disetujui oleh ' . Auth::user()->name,
                'changed_by' => Auth::id(),
            ]);

            DB::commit();
            return redirect()->route($role . '.permohonan.index')->with('success', 'Permohonan berhasil disetujui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function reject(Request $request, $id)
    {
        $role = Auth::user()->role;
        $targetStatus = $this->getTargetStatusForRole($role);

        if (!$targetStatus) {
            abort(403, 'Akses ditolak.');
        }

        $permohonan = Permohonan::where('status', $targetStatus)->findOrFail($id);
        $permohonan->load(['pemohons.dokumenPersyaratan.masterPersyaratan', 'organisasi']);

        DB::beginTransaction();
        try {
            // 1. Simpan status dokumen per dokumen
            foreach ($request->input('dokumen', []) as $dokId => $statusDok) {
                $dok = \App\Models\DokumenPersyaratan::findOrFail($dokId);
                $dok->status_dokumen = $statusDok;
                $dok->keterangan = $request->input("keterangan_dokumen.{$dokId}");
                $dok->save();
            }

            // 2. Update status_verifikasi per anggota berdasarkan dokumen yang tidak valid
            foreach ($permohonan->pemohons as $pemohon) {
                $invalidDoks = $pemohon->dokumenPersyaratan()
                    ->where('status_dokumen', 'Tidak Valid')
                    ->with('masterPersyaratan')
                    ->get();

                if ($invalidDoks->count() > 0) {
                    $reasons = $invalidDoks->map(function ($d) {
                        return $d->masterPersyaratan->nama_persyaratan . ($d->keterangan ? ': ' . $d->keterangan : '');
                    })->toArray();

                    $pemohon->update([
                        'status_verifikasi' => 'Ditolak',
                        'catatan_penolakan' => implode('; ', $reasons),
                    ]);

                    // 3. Kirim email ke email_organisasi DAN email anggota yang dokumennya tidak valid
                    $rejectedList    = $invalidDoks->map(fn($d) => $d->masterPersyaratan->nama_persyaratan)->toArray();
                    $rejectedDetails = $invalidDoks->pluck('keterangan', 'masterPersyaratan.nama_persyaratan')->toArray();

                    try {
                        // Email ke organisasi
                        if ($permohonan->email_organisasi) {
                            \Illuminate\Support\Facades\Mail::to($permohonan->email_organisasi)
                                ->send(new \App\Mail\MemberRejectedMail($pemohon, $permohonan, $rejectedList, $rejectedDetails));
                        }

                        // Email ke anggota yang bersangkutan
                        if ($pemohon->email) {
                            \Illuminate\Support\Facades\Mail::to($pemohon->email)
                                ->send(new \App\Mail\MemberRejectedMail($pemohon, $permohonan, $rejectedList, $rejectedDetails));
                        }
                    } catch (\Exception $e) {
                        \Illuminate\Support\Facades\Log::error('Gagal kirim email penolakan verifikator: ' . $e->getMessage());
                    }
                }
            }

            // 4. Tentukan status baru "Menunggu Perbaikan Dokumen Verifikator N"
            $statusPerbaikanMap = [
                'verifikator1' => 'Menunggu Perbaikan Dokumen Verifikator 1',
                'verifikator2' => 'Menunggu Perbaikan Dokumen Verifikator 2',
                'verifikator3' => 'Menunggu Perbaikan Dokumen Verifikator 3',
                'verifikator4' => 'Menunggu Perbaikan Dokumen Verifikator 4',
            ];
            $statusPerbaikan = $statusPerbaikanMap[$role];

            $oldStatus = $permohonan->status;
            $catatan   = $request->input('catatan');

            $statusFieldMap = [
                'verifikator1' => 'status_verifikator1',
                'verifikator2' => 'status_verifikator2',
                'verifikator3' => 'status_verifikator3',
                'verifikator4' => 'status_verifikator4',
            ];
            $catatanFieldMap = [
                'verifikator1' => 'catatan_verifikator1',
                'verifikator2' => 'catatan_verifikator2',
                'verifikator3' => 'catatan_verifikator3',
                'verifikator4' => 'catatan_verifikator4',
            ];
            $permohonan->{$statusFieldMap[$role]}  = 'ditolak';
            $permohonan->{$catatanFieldMap[$role]} = $catatan;
            $permohonan->status  = $statusPerbaikan;
            $permohonan->catatan = $catatan;
            $permohonan->save();

            RiwayatStatus::create([
                'permohonan_id' => $permohonan->id,
                'status_lama'   => $oldStatus,
                'status_baru'   => $statusPerbaikan,
                'keterangan'    => 'Terdapat dokumen tidak valid. Menunggu perbaikan dari pemohon. Oleh: ' . Auth::user()->name . ($catatan ? ' - ' . $catatan : ''),
                'changed_by'    => Auth::id(),
            ]);

            DB::commit();
            return redirect()->route($role . '.permohonan.index')
                ->with('success', 'Dokumen tidak valid. Email notifikasi telah dikirim ke organisasi dan anggota. Permohonan menunggu perbaikan dokumen.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function memberShow($permohonan_id, $pemohon_id)
    {
        $permohonan = Permohonan::with(['organisasi'])->findOrFail($permohonan_id);
        $pemohon    = \App\Models\Pemohon::with(['dokumenPersyaratan.masterPersyaratan'])
                        ->where('id', $pemohon_id)
                        ->where('permohonan_id', $permohonan_id)
                        ->firstOrFail();

        $role = Auth::user()->role;
        return view('verifikator.permohonan.member-show', compact('permohonan', 'pemohon', 'role'));
    }

    public function verifikasiMember(Request $request, $permohonan_id, $pemohon_id)
    {
        $role       = Auth::user()->role;
        $permohonan = Permohonan::with(['pemohons.dokumenPersyaratan.masterPersyaratan', 'organisasi'])
                        ->findOrFail($permohonan_id);
        $pemohon    = \App\Models\Pemohon::where('id', $pemohon_id)
                        ->where('permohonan_id', $permohonan_id)
                        ->firstOrFail();

        DB::beginTransaction();
        try {
            // Simpan hasil validasi ulang dokumen
            foreach ($request->input('dokumen', []) as $dokId => $statusDok) {
                $dok = \App\Models\DokumenPersyaratan::findOrFail($dokId);
                $dok->status_dokumen = $statusDok;
                $dok->keterangan     = $request->input("keterangan_dokumen.{$dokId}");
                $dok->save();
            }

            // Cek apakah masih ada dokumen tidak valid untuk anggota ini
            $invalidDoks = $pemohon->dokumenPersyaratan()
                ->where('status_dokumen', 'Tidak Valid')->count();

            if ($invalidDoks > 0) {
                $pemohon->update(['status_verifikasi' => 'Ditolak']);
            } else {
                $pemohon->update(['status_verifikasi' => 'Disetujui', 'catatan_penolakan' => null]);
            }

            // Cek apakah SEMUA anggota di permohonan ini sudah Valid (setelah revalidasi)
            $allApproved = $permohonan->pemohons()
                ->get()->every(fn($m) => $m->status_verifikasi === 'Disetujui');

            if ($allApproved) {
                // Kembalikan status ke "Menunggu Verifikasi Verifikator N" agar verifikator bisa approve
                $statusFieldMap = [
                    'verifikator1' => ['status_field' => 'status_verifikator1', 'next_status' => 'Menunggu Verifikasi Verifikator 1'],
                    'verifikator2' => ['status_field' => 'status_verifikator2', 'next_status' => 'Menunggu Verifikasi Verifikator 2'],
                    'verifikator3' => ['status_field' => 'status_verifikator3', 'next_status' => 'Menunggu Verifikasi Verifikator 3'],
                    'verifikator4' => ['status_field' => 'status_verifikator4', 'next_status' => 'Menunggu Verifikasi Verifikator 4'],
                ];
                $map    = $statusFieldMap[$role];
                $oldSt  = $permohonan->status;

                $permohonan->{$map['status_field']} = 'pending'; // reset, siap approve
                $permohonan->status = $map['next_status'];
                $permohonan->save();

                RiwayatStatus::create([
                    'permohonan_id' => $permohonan->id,
                    'status_lama'   => $oldSt,
                    'status_baru'   => $map['next_status'],
                    'keterangan'    => 'Semua dokumen perbaikan sudah Valid. Siap diverifikasi ulang oleh ' . Auth::user()->name,
                    'changed_by'    => Auth::id(),
                ]);
            }

            DB::commit();
            return redirect()->route($role . '.permohonan.show', $permohonan_id)
                ->with('success', 'Hasil validasi ulang dokumen berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function bukuRegistrasiIndex(Request $request)
    {
        $query = BukuRegistrasiAdvokat::with(['pemohon.organisasi', 'permohonan'])
            ->whereNotNull('nomor_bas');

        // Search by name
        if ($request->filled('search_name')) {
            $query->whereHas('pemohon', function ($q) use ($request) {
                $q->where('nama_lengkap', 'like', '%' . $request->search_name . '%');
            });
        }

        $registrasi = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();
        $role = Auth::user()->role;

        return view('verifikator.buku-registrasi.index', compact('registrasi', 'role'));
    }

    public function showBukuMember($id)
    {
        $reg = BukuRegistrasiAdvokat::with(['pemohon.organisasi', 'pemohon.dokumenPersyaratan.masterPersyaratan', 'permohonan.verifikasi'])->findOrFail($id);
        $role = Auth::user()->role;

        return view('verifikator.buku-registrasi.show', compact('reg', 'role'));
    }
}

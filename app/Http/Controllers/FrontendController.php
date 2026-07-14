<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Berita;
use App\Models\Faq;
use App\Models\Permohonan;
use App\Models\MasterPersyaratan;
use App\Models\PengaturanWebsite;
use App\Models\Organization;
use App\Services\PermohonanService;

class FrontendController extends Controller
{
    public function index()
    {
        $stats = [
            'total' => Permohonan::count(),
            'disetujui' => Permohonan::where('status', 'Disetujui')->count(),
            'dijadwalkan' => Permohonan::where('status', 'Dijadwalkan Sumpah')->count(),
            'selesai' => Permohonan::where('status', 'Selesai')->count(),
        ];
        $beritaTerbaru = Berita::orderBy('created_at', 'desc')->take(3)->get();
        return view('landing.index', compact('stats', 'beritaTerbaru'));
    }

    public function showBerita($slug)
    {
        $berita = Berita::where('slug', $slug)->firstOrFail();
        return view('landing.berita-show', compact('berita'));
    }

    public function informasi()
    {
        return view('landing.informasi');
    }

    public function persyaratan()
    {
        $persyaratan = MasterPersyaratan::all();
        return view('landing.persyaratan', compact('persyaratan'));
    }

    public function alur()
    {
        return view('landing.alur');
    }

    public function faq()
    {
        $faqs = Faq::all();
        return view('landing.faq', compact('faqs'));
    }

    public function kontak()
    {
        $pengaturan = PengaturanWebsite::first();
        return view('landing.kontak', compact('pengaturan'));
    }

    public function permohonan()
    {
        $organisasi = Organization::where('status', 'Aktif')->get();
        $persyaratan = MasterPersyaratan::all();
        return view('landing.permohonan', compact('organisasi', 'persyaratan'));
    }

    public function storePermohonan(Request $request, PermohonanService $service)
    {
        $request->validate([
            'organization_id' => 'required|exists:organizations,id',
            'nomor_sk' => 'required|string|max:255',
            'tanggal_sk' => 'required|date',
            'no_hp_organisasi' => 'required|string|max:255',
            'email_organisasi' => 'required|email|max:255',
            'members' => 'required|array|min:1',
            'members.*.nik' => 'required|numeric|digits:16|distinct|unique:pemohons,nik',
            'members.*.nama_lengkap' => 'required|string|max:255',
            'members.*.tempat_lahir' => 'required|string|max:255',
            'members.*.tanggal_lahir' => 'required|date',
            'members.*.jenis_kelamin' => 'required|in:L,P',
            'members.*.email' => 'required|email|max:255|distinct|unique:pemohons,email',
            'members.*.alamat' => 'required|string',
        ], [
            'members.min' => 'Jumlah anggota minimal 1 orang.',
            'members.*.nik.distinct' => 'Tidak boleh ada NIK yang sama dalam satu permohonan.',
            'members.*.nik.unique' => 'NIK :input sudah terdaftar di sistem.',
            'members.*.email.distinct' => 'Tidak boleh ada Email yang sama dalam satu permohonan.',
            'members.*.email.unique' => 'Email :input sudah terdaftar di sistem.',
        ]);

        \Illuminate\Support\Facades\DB::beginTransaction();
        try {
            $permohonanRepo = app(\App\Repositories\Interfaces\PermohonanRepositoryInterface::class);
            $nomorPermohonan = $permohonanRepo->generateNomorRegistrasi();
 
            $permohonan = Permohonan::create([
                'organization_id' => $request->organization_id,
                'nomor_sk' => $request->nomor_sk,
                'tanggal_sk' => $request->tanggal_sk,
                'no_hp_organisasi' => $request->no_hp_organisasi,
                'email_organisasi' => $request->email_organisasi,
                'nomor_permohonan' => $nomorPermohonan,
                'tanggal_pengajuan' => date('Y-m-d'),
                'status' => 'Draft',
            ]);
 
            foreach ($request->members as $memberData) {
                $pemohon = \App\Models\Pemohon::create([
                    'permohonan_id' => $permohonan->id,
                    'organization_id' => $request->organization_id,
                    'nik' => $memberData['nik'],
                    'nama_lengkap' => $memberData['nama_lengkap'],
                    'tempat_lahir' => $memberData['tempat_lahir'],
                    'tanggal_lahir' => $memberData['tanggal_lahir'],
                    'jenis_kelamin' => $memberData['jenis_kelamin'],
                    'email' => $memberData['email'],
                    'alamat' => $memberData['alamat'],
                    'status_verifikasi' => 'Pending',
                ]);
                \App\Models\BukuRegistrasiAdvokat::create([
                    'pemohon_id' => $pemohon->id,
                    'permohonan_id' => $permohonan->id,
                ]);
            }
 
            \Illuminate\Support\Facades\DB::commit();
 
            return redirect()->route('permohonan.dokumen-list', $permohonan->nomor_permohonan)
                ->with('success', 'Permohonan berhasil didaftarkan. Silakan unggah dokumen persyaratan.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return back()->with('error', 'Gagal mendaftarkan permohonan: ' . $e->getMessage())->withInput();
        }
    }

    public function proposeOrganization(Request $request)
    {
        try {
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
                            $fail('Nama organisasi tersebut sudah terdaftar.');
                        }
                    }
                ],
            ]);

            $org = Organization::create([
                'nama_organisasi' => trim($request->nama_organisasi),
                'status' => 'Menunggu Persetujuan',
            ]);

            return response()->json([
                'success' => true,
                'id' => $org->id,
                'nama_organisasi' => $org->nama_organisasi,
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->validator->errors()->first('nama_organisasi')
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
            ], 500);
        }
    }

    public function dokumenList($nomor_permohonan)
    {
        $permohonan = Permohonan::with(['pemohons.dokumenPersyaratan', 'organisasi'])->where('nomor_permohonan', $nomor_permohonan)->firstOrFail();
        $persyaratan = MasterPersyaratan::all();
        $requiredCount = $persyaratan->where('is_required', true)->count();

        foreach ($permohonan->pemohons as $pemohon) {
            $uploadedRequiredCount = $pemohon->dokumenPersyaratan()
                ->whereIn('persyaratan_id', $persyaratan->where('is_required', true)->pluck('id'))
                ->where('status_dokumen', '!=', 'Tidak Valid')
                ->count();
            
            $pemohon->is_complete = ($uploadedRequiredCount >= $requiredCount);
        }

        return view('landing.dokumen-list', compact('permohonan', 'persyaratan'));
    }

    public function dokumenUpload($nomor_permohonan, $pemohon_id)
    {
        $permohonan = Permohonan::where('nomor_permohonan', $nomor_permohonan)->firstOrFail();
        $pemohon = \App\Models\Pemohon::with('dokumenPersyaratan')->where('id', $pemohon_id)->where('permohonan_id', $permohonan->id)->firstOrFail();
        $persyaratan = MasterPersyaratan::all();

        return view('landing.dokumen-upload', compact('permohonan', 'pemohon', 'persyaratan'));
    }

    public function storeDokumenUpload(Request $request, $nomor_permohonan, $pemohon_id)
    {
        $permohonan = Permohonan::where('nomor_permohonan', $nomor_permohonan)->firstOrFail();
        $pemohon = \App\Models\Pemohon::where('id', $pemohon_id)->where('permohonan_id', $permohonan->id)->firstOrFail();

        $rules = [];
        $persyaratan = MasterPersyaratan::all();
        foreach ($persyaratan as $p) {
            $exists = $pemohon->dokumenPersyaratan()->where('persyaratan_id', $p->id)->exists();
            $isPasFoto = (strpos(strtolower($p->nama_persyaratan), 'pas foto') !== false);
            $mimes = $isPasFoto ? 'jpg,jpeg,png' : 'pdf';
            
            if ($p->is_required && !$exists) {
                $rules["dokumen.{$p->id}"] = "required|file|mimes:{$mimes}|max:2048";
            } else {
                $rules["dokumen.{$p->id}"] = "nullable|file|mimes:{$mimes}|max:2048";
            }
        }

        $request->validate($rules);

        \Illuminate\Support\Facades\DB::beginTransaction();
        try {
            $dokumenData = $request->file('dokumen');
            if ($dokumenData) {
                foreach ($dokumenData as $persyaratanId => $file) {
                    if (!$file) continue;

                    $oldDokumen = \App\Models\DokumenPersyaratan::where('pemohon_id', $pemohon->id)
                        ->where('persyaratan_id', $persyaratanId)
                        ->first();

                    if ($oldDokumen && \Illuminate\Support\Facades\Storage::disk('public')->exists($oldDokumen->file_path)) {
                        \Illuminate\Support\Facades\Storage::disk('public')->delete($oldDokumen->file_path);
                    }

                    $path = $file->store('permohonan/dokumen/' . $nomor_permohonan . '/' . $pemohon->id, 'public');

                    \App\Models\DokumenPersyaratan::updateOrCreate(
                        [
                            'pemohon_id' => $pemohon->id,
                            'persyaratan_id' => $persyaratanId,
                        ],
                        [
                            'permohonan_id' => $permohonan->id,
                            'file_path' => $path,
                            'status_dokumen' => 'Pending',
                        ]
                    );
                }
            }

            if ($pemohon->status_verifikasi === 'Ditolak') {
                $pemohon->update([
                    'status_verifikasi' => 'Pending',
                ]);
            }

            if ($permohonan->status === 'Siap Penjadwalan Pengecekan Berkas Fisik' || $permohonan->status === 'Verifikasi Berkas Fisik') {
                $permohonan->update([
                    'status' => 'Menunggu Verifikasi'
                ]);
            }

            \Illuminate\Support\Facades\DB::commit();

            if ($permohonan->status !== 'Draft') {
                return redirect('/tracking')
                    ->with('nomor_permohonan', $permohonan->nomor_permohonan)
                    ->with('success', 'Dokumen perbaikan untuk ' . $pemohon->nama_lengkap . ' berhasil diunggah.');
            }

            return redirect()->route('permohonan.dokumen-list', $permohonan->nomor_permohonan)
                ->with('success', 'Dokumen untuk ' . $pemohon->nama_lengkap . ' berhasil diunggah.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function submitPermohonan($nomor_permohonan)
    {
        $permohonan = Permohonan::with(['pemohons.dokumenPersyaratan'])->where('nomor_permohonan', $nomor_permohonan)->firstOrFail();
        $persyaratan = MasterPersyaratan::all();
        $requiredCount = $persyaratan->where('is_required', true)->count();

        foreach ($permohonan->pemohons as $pemohon) {
            $uploadedRequiredCount = $pemohon->dokumenPersyaratan()
                ->whereIn('persyaratan_id', $persyaratan->where('is_required', true)->pluck('id'))
                ->where('status_dokumen', '!=', 'Tidak Valid')
                ->count();
            
            if ($uploadedRequiredCount < $requiredCount) {
                return back()->with('error', 'Semua anggota wajib melengkapi dokumen persyaratan sebelum dikirim.');
            }
        }

        \Illuminate\Support\Facades\DB::beginTransaction();
        try {
            $permohonan->update([
                'status' => 'Menunggu Verifikasi',
            ]);

            \App\Models\RiwayatStatus::create([
                'permohonan_id' => $permohonan->id,
                'status_lama' => 'Draft',
                'status_baru' => 'Menunggu Verifikasi',
                'keterangan' => 'Permohonan lengkap diajukan oleh Organisasi.',
                'changed_by' => null,
            ]);

            try {
                \Illuminate\Support\Facades\Mail::to($permohonan->email_organisasi, $permohonan->organisasi->nama_organisasi ?? 'Organisasi')
                    ->send(new \App\Mail\PermohonanDiajukanMail($permohonan));
                \Illuminate\Support\Facades\Mail::to('adminadvokat@gmail.com', 'Admin EVOKAT')
                    ->send(new \App\Mail\NotifikasiAdminMail($permohonan));
            } catch (\Throwable $mailException) {
                \Illuminate\Support\Facades\Log::error('Gagal mengirim email permohonan diajukan: ' . $mailException->getMessage());
            }

            \Illuminate\Support\Facades\DB::commit();

            return redirect('/tracking')
                ->with('success', 'Permohonan berhasil dikirim ke Pengadilan Tinggi. Nomor Registrasi Anda: ' . $permohonan->nomor_permohonan)
                ->with('open_survey', true)
                ->with('nomor_permohonan', $permohonan->nomor_permohonan);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan saat submit: ' . $e->getMessage());
        }
    }

    public function tracking(Request $request, PermohonanService $service)
    {
        $permohonan = null;
        if (session('nomor_permohonan')) {
            $permohonan = $service->getPermohonanByNomor(session('nomor_permohonan'));
        }
        return view('landing.tracking', compact('permohonan'));
    }

    public function cekTracking(Request $request, PermohonanService $service)
    {
        $request->validate([
            'nomor_permohonan' => 'required|string'
        ]);

        $permohonan = $service->getPermohonanByNomor($request->nomor_permohonan);

        if (!$permohonan) {
            return back()->with('error', 'Nomor registrasi tidak ditemukan.');
        }

        return view('landing.tracking', compact('permohonan'));
    }

    public function downloadFinalSurat($nomor_permohonan)
    {
        $permohonan = Permohonan::where('nomor_permohonan', $nomor_permohonan)->firstOrFail();
        if (!in_array($permohonan->status, ['Surat Selesai', 'Selesai'])) {
            abort(403, 'Surat final belum tersedia.');
        }
        if (!$permohonan->file_surat || !\Illuminate\Support\Facades\Storage::disk('public')->exists($permohonan->file_surat)) {
            abort(404, 'File surat tidak ditemukan.');
        }
        $safeName = 'Surat_Final_' . str_replace('/', '_', $permohonan->nomor_permohonan) . '.pdf';
        return \Illuminate\Support\Facades\Storage::disk('public')->download($permohonan->file_surat, $safeName);
    }
}

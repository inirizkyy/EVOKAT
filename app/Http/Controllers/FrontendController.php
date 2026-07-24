<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
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
            'disetujui' => Permohonan::whereIn('status', ['Siap Penjadwalan Pengecekan Berkas Fisik', 'Menentukan Jadwal Verifikasi', 'Disetujui'])->count(),
            'dijadwalkan' => Permohonan::whereIn('status', ['Menentukan Jadwal Sumpah', 'Proses Pembuatan Surat', 'Surat Selesai', 'Dijadwalkan Sumpah'])->count(),
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
        // Hanya bersihkan temp files jika buka halaman baru (bukan redirect dari validation error)
        if (!session()->has('errors') && !session()->has('error') && !old('_token') && session()->has('temp_permohonan_files')) {
            $tempFiles = session('temp_permohonan_files', []);
            foreach ($tempFiles as $fileData) {
                $path = is_array($fileData) ? ($fileData['path'] ?? null) : $fileData;
                if ($path && Storage::disk('public')->exists($path)) {
                    Storage::disk('public')->delete($path);
                }
            }
            session()->forget('temp_permohonan_files');
        }

        $organisasi = Organization::where('status', 'Aktif')->get();
        $persyaratan = MasterPersyaratan::all();
        return view('landing.permohonan', compact('organisasi', 'persyaratan'));
    }

    public function storePermohonan(Request $request, PermohonanService $service)
    {
        // Simpan file ke temp storage SEBELUM validasi agar tidak hilang saat error
        $tempFiles = session('temp_permohonan_files', []);

        $fileFields = ['file_surat_pengantar', 'file_sk_pendirian', 'file_sk_kepengurusan'];
        foreach ($fileFields as $field) {
            if ($request->hasFile($field) && $request->file($field)->isValid()) {
                // Hapus file temp lama jika ada
                $oldPath = is_array($tempFiles[$field] ?? null) ? ($tempFiles[$field]['path'] ?? null) : ($tempFiles[$field] ?? null);
                if (!empty($oldPath) && Storage::disk('public')->exists($oldPath)) {
                    Storage::disk('public')->delete($oldPath);
                }
                $storedPath = $request->file($field)->store('permohonan/temp', 'public');
                $tempFiles[$field] = [
                    'path' => $storedPath,
                    'name' => $request->file($field)->getClientOriginalName(),
                ];
            }
        }
        session(['temp_permohonan_files' => $tempFiles]);
        session()->save();

        $tempSuratPath = is_array($tempFiles['file_surat_pengantar'] ?? null) ? ($tempFiles['file_surat_pengantar']['path'] ?? null) : ($tempFiles['file_surat_pengantar'] ?? null);
        $tempPendirianPath = is_array($tempFiles['file_sk_pendirian'] ?? null) ? ($tempFiles['file_sk_pendirian']['path'] ?? null) : ($tempFiles['file_sk_pendirian'] ?? null);
        $tempKepengurusanPath = is_array($tempFiles['file_sk_kepengurusan'] ?? null) ? ($tempFiles['file_sk_kepengurusan']['path'] ?? null) : ($tempFiles['file_sk_kepengurusan'] ?? null);

        $hasTempSurat = !empty($tempSuratPath) && Storage::disk('public')->exists($tempSuratPath);
        $hasTempPendirian = !empty($tempPendirianPath) && Storage::disk('public')->exists($tempPendirianPath);
        $hasTempKepengurusan = !empty($tempKepengurusanPath) && Storage::disk('public')->exists($tempKepengurusanPath);

        $request->validate([
            'organization_id' => [
                'required',
                function ($attribute, $value, $fail) {
                    if (is_numeric($value)) {
                        if (!Organization::where('id', $value)->whereNull('deleted_at')->exists()) {
                            $fail('Organisasi yang dipilih tidak valid.');
                        }
                    } elseif (is_string($value) && str_starts_with($value, 'new:')) {
                        $name = trim(substr($value, 4));
                        if (empty($name) || strlen($name) > 255) {
                            $fail('Nama usulan organisasi tidak boleh kosong dan maksimal 255 karakter.');
                        }
                    } else {
                        $fail('Pilihan organisasi tidak valid.');
                    }
                }
            ],
            'nomor_sk' => 'nullable|string|max:255',
            'nomor_sk_kepengurusan' => 'nullable|string|max:255',
            'tanggal_sk' => 'nullable|date',
            'no_hp_organisasi' => 'required|string|max:255',
            'email_organisasi' => 'required|email|max:255',
            'nomor_surat_pengantar' => 'required|string|max:255',
            'tanggal_surat_pengantar' => 'required|date',
            'perihal_surat_pengantar' => 'required|string|max:500',
            'file_surat_pengantar' => ($hasTempSurat ? 'nullable' : 'required') . '|file|mimes:pdf|max:2048',
            'file_sk_pendirian' => ($hasTempPendirian ? 'nullable' : 'required') . '|file|mimes:pdf|max:2048',
            'file_sk_kepengurusan' => ($hasTempKepengurusan ? 'nullable' : 'required') . '|file|mimes:pdf|max:2048',
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

        // Inisialisasi variabel file path sebelum DB transaction & try block
        $pathSuratPengantar = null;
        $pathSkPendirian = null;
        $pathSkKepengurusan = null;

        \Illuminate\Support\Facades\DB::beginTransaction();
        try {
            // Resolusi Organization ID (Buat baru atau gunakan yang ada secara case-insensitive)
            $orgInput = $request->organization_id;
            $finalOrganizationId = null;

            if (is_numeric($orgInput)) {
                $finalOrganizationId = (int)$orgInput;
            } elseif (is_string($orgInput) && str_starts_with($orgInput, 'new:')) {
                $proposedName = trim(substr($orgInput, 4));

                $existingOrg = Organization::whereRaw('LOWER(nama_organisasi) = ?', [strtolower($proposedName)])
                    ->whereNull('deleted_at')
                    ->first();

                if ($existingOrg) {
                    $finalOrganizationId = $existingOrg->id;
                } else {
                    $newOrg = Organization::create([
                        'nama_organisasi' => $proposedName,
                        'status' => 'Menunggu Persetujuan',
                    ]);
                    $finalOrganizationId = $newOrg->id;
                }
            }

            $permohonanRepo = app(\App\Repositories\Interfaces\PermohonanRepositoryInterface::class);
            $nomorPermohonan = $permohonanRepo->generateNomorRegistrasi();

            // Store / move uploaded files from temp
            if (!empty($tempSuratPath) && Storage::disk('public')->exists($tempSuratPath)) {
                $target = 'permohonan/surat_pengantar/' . basename($tempSuratPath);
                Storage::disk('public')->move($tempSuratPath, $target);
                $pathSuratPengantar = $target;
            }

            if (!empty($tempPendirianPath) && Storage::disk('public')->exists($tempPendirianPath)) {
                $target = 'permohonan/sk_pendirian/' . basename($tempPendirianPath);
                Storage::disk('public')->move($tempPendirianPath, $target);
                $pathSkPendirian = $target;
            }

            if (!empty($tempKepengurusanPath) && Storage::disk('public')->exists($tempKepengurusanPath)) {
                $target = 'permohonan/sk_kepengurusan/' . basename($tempKepengurusanPath);
                Storage::disk('public')->move($tempKepengurusanPath, $target);
                $pathSkKepengurusan = $target;
            }

            $permohonan = Permohonan::create([
                'organization_id' => $finalOrganizationId,
                'nomor_sk' => $request->nomor_sk ?? $request->nomor_surat_pengantar,
                'nomor_sk_kepengurusan' => $request->nomor_sk_kepengurusan,
                'tanggal_sk' => $request->tanggal_sk ?? $request->tanggal_surat_pengantar,
                'no_hp_organisasi' => $request->no_hp_organisasi,
                'email_organisasi' => $request->email_organisasi,
                'nomor_surat_pengantar' => $request->nomor_surat_pengantar,
                'tanggal_surat_pengantar' => $request->tanggal_surat_pengantar,
                'perihal_surat_pengantar' => $request->perihal_surat_pengantar,
                'file_surat_pengantar' => $pathSuratPengantar,
                'file_sk_pendirian' => $pathSkPendirian,
                'file_sk_kepengurusan_pdf' => $pathSkKepengurusan,
                'nomor_permohonan' => $nomorPermohonan,
                'tanggal_pengajuan' => date('Y-m-d'),
                'status' => 'Draft',
            ]);

            foreach ($request->members as $memberData) {
                $pemohon = \App\Models\Pemohon::create([
                    'permohonan_id' => $permohonan->id,
                    'organization_id' => $finalOrganizationId,
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

            // Clear temp session after success
            session()->forget('temp_permohonan_files');

            try {
                $permohonan->load('organisasi');
                \Illuminate\Support\Facades\Mail::to($permohonan->email_organisasi, $permohonan->organisasi->nama_organisasi ?? 'Organisasi')
                    ->send(new \App\Mail\PermohonanDiajukanMail($permohonan));
            } catch (\Throwable $mailException) {
                \Illuminate\Support\Facades\Log::error('Gagal mengirim email permohonan didaftarkan: ' . $mailException->getMessage());
            }

            return redirect()->route('permohonan.dokumen-list', $permohonan->nomor_permohonan)
                ->with('success', 'Permohonan berhasil didaftarkan. Nomor Registrasi Anda: ' . $permohonan->nomor_permohonan)
                ->with('nomor_permohonan', $permohonan->nomor_permohonan)
                ->with('email_terkirim', $permohonan->email_organisasi);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();

            // Hapus / kembalikan file yang terlanjur dipindahkan jika transaksi gagal
            if (!empty($pathSuratPengantar) && Storage::disk('public')->exists($pathSuratPengantar)) {
                if (!empty($tempSuratPath)) {
                    Storage::disk('public')->move($pathSuratPengantar, $tempSuratPath);
                } else {
                    Storage::disk('public')->delete($pathSuratPengantar);
                }
            }
            if (!empty($pathSkPendirian) && Storage::disk('public')->exists($pathSkPendirian)) {
                if (!empty($tempPendirianPath)) {
                    Storage::disk('public')->move($pathSkPendirian, $tempPendirianPath);
                } else {
                    Storage::disk('public')->delete($pathSkPendirian);
                }
            }
            if (!empty($pathSkKepengurusan) && Storage::disk('public')->exists($pathSkKepengurusan)) {
                if (!empty($tempKepengurusanPath)) {
                    Storage::disk('public')->move($pathSkKepengurusan, $tempKepengurusanPath);
                } else {
                    Storage::disk('public')->delete($pathSkKepengurusan);
                }
            }

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

        $sessionKey = "temp_dokumen_upload.{$pemohon_id}";
        $tempDokumen = session($sessionKey, []);

        if ($request->hasFile('dokumen')) {
            foreach ($request->file('dokumen') as $persyaratanId => $file) {
                if ($file && $file->isValid()) {
                    if (!empty($tempDokumen[$persyaratanId]) && Storage::disk('public')->exists($tempDokumen[$persyaratanId])) {
                        Storage::disk('public')->delete($tempDokumen[$persyaratanId]);
                    }
                    $tempDokumen[$persyaratanId] = $file->store('permohonan/temp_dokumen/' . $nomor_permohonan . '/' . $pemohon_id, 'public');
                }
            }
        }
        session([$sessionKey => $tempDokumen]);
        session()->save();

        $rules = [];
        $messages = [
            'dokumen.*.max'   => 'Ukuran file untuk :attribute melebihi batas maksimum 2MB.',
            'dokumen.*.mimes' => 'Format file :attribute tidak valid. Gunakan PDF untuk dokumen, atau JPG/PNG untuk pas foto.',
        ];
        $attributes = [];

        $persyaratan = MasterPersyaratan::all();
        foreach ($persyaratan as $p) {
            $existsInDb = $pemohon->dokumenPersyaratan()->where('persyaratan_id', $p->id)->exists();
            $hasTemp = !empty($tempDokumen[$p->id]) && Storage::disk('public')->exists($tempDokumen[$p->id]);
            $isPasFoto = (strpos(strtolower($p->nama_persyaratan), 'pas foto') !== false);
            $mimes = $isPasFoto ? 'jpg,jpeg,png' : 'pdf';
            
            $attributes["dokumen.{$p->id}"] = '"' . $p->nama_persyaratan . '"';

            if ($p->is_required && !$existsInDb && !$hasTemp) {
                $rules["dokumen.{$p->id}"] = "required|file|mimes:{$mimes}|max:2048";
            } else {
                $rules["dokumen.{$p->id}"] = "nullable|file|mimes:{$mimes}|max:2048";
            }
        }

        $request->validate($rules, $messages, $attributes);

        \Illuminate\Support\Facades\DB::beginTransaction();
        try {
            if (!empty($tempDokumen)) {
                foreach ($tempDokumen as $persyaratanId => $tempPath) {
                    if (!Storage::disk('public')->exists($tempPath)) continue;

                    $oldDokumen = \App\Models\DokumenPersyaratan::where('pemohon_id', $pemohon->id)
                        ->where('persyaratan_id', $persyaratanId)
                        ->first();

                    if ($oldDokumen && Storage::disk('public')->exists($oldDokumen->file_path)) {
                        Storage::disk('public')->delete($oldDokumen->file_path);
                    }

                    $finalPath = 'permohonan/dokumen/' . $nomor_permohonan . '/' . $pemohon->id . '/' . basename($tempPath);
                    Storage::disk('public')->move($tempPath, $finalPath);

                    \App\Models\DokumenPersyaratan::updateOrCreate(
                        [
                            'pemohon_id' => $pemohon->id,
                            'persyaratan_id' => $persyaratanId,
                        ],
                        [
                            'permohonan_id' => $permohonan->id,
                            'file_path' => $finalPath,
                            'status_dokumen' => 'Pending',
                            'keterangan' => null,
                        ]
                    );
                }
            }

            $pemohon->update([
                'status_verifikasi' => $pemohon->status_verifikasi === 'Ditolak' ? 'Pending' : $pemohon->status_verifikasi,
                'catatan_penolakan' => null,
            ]);

            $statusPerbaikanVerifikatorMap = [
                'Menunggu Perbaikan Dokumen Verifikator 1' => 'Menunggu Verifikasi Verifikator 1',
                'Menunggu Perbaikan Dokumen Verifikator 2' => 'Menunggu Verifikasi Verifikator 2',
                'Menunggu Perbaikan Dokumen Verifikator 3' => 'Menunggu Verifikasi Verifikator 3',
                'Menunggu Perbaikan Dokumen Verifikator 4' => 'Menunggu Verifikasi Verifikator 4',
            ];

            if (array_key_exists($permohonan->status, $statusPerbaikanVerifikatorMap)) {
                $permohonan->update([
                    'status' => $statusPerbaikanVerifikatorMap[$permohonan->status]
                ]);
            } elseif (in_array($permohonan->status, ['Menentukan Jadwal Berkas Fisik', 'Menunggu Verifikasi Admin', 'Verifikasi Berkas Fisik'])) {
                $permohonan->update(['status' => 'Menunggu Verifikasi Admin']);
            }

            \Illuminate\Support\Facades\DB::commit();

            session()->forget($sessionKey);

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
                'status' => 'Menunggu Verifikasi Admin',
            ]);

            \App\Models\RiwayatStatus::create([
                'permohonan_id' => $permohonan->id,
                'status_lama' => 'Draft',
                'status_baru' => 'Menunggu Verifikasi Admin',
                'keterangan' => 'Permohonan lengkap diajukan oleh Organisasi.',
                'changed_by' => null,
            ]);

            try {
                $permohonan->load('organisasi');
                \Illuminate\Support\Facades\Mail::to($permohonan->email_organisasi, $permohonan->organisasi->nama_organisasi ?? 'Organisasi')
                    ->send(new \App\Mail\PermohonanDiajukanMail($permohonan));
                \Illuminate\Support\Facades\Mail::to('adminadvokat@gmail.com', 'Admin EVOKAT')
                    ->send(new \App\Mail\NotifikasiAdminMail($permohonan));
            } catch (\Throwable $mailException) {
                \Illuminate\Support\Facades\Log::error('Gagal mengirim email permohonan diajukan: ' . $mailException->getMessage());
            }

            \Illuminate\Support\Facades\DB::commit();

            return redirect('/tracking?nomor_permohonan=' . urlencode($permohonan->nomor_permohonan))
                ->with('success', 'Permohonan berhasil dikirim ke Pengadilan Tinggi. Nomor Registrasi Anda: ' . $permohonan->nomor_permohonan)
                ->with('nomor_permohonan', $permohonan->nomor_permohonan);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan saat submit: ' . $e->getMessage());
        }
    }

    public function tracking(Request $request, PermohonanService $service)
    {
        $permohonan = null;
        $nomor = $request->query('nomor_permohonan') ?? session('nomor_permohonan');
        if ($nomor) {
            $permohonan = $service->getPermohonanByNomor($nomor);
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

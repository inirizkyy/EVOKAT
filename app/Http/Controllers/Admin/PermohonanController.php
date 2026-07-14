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
            $data = Permohonan::with(['organisasi', 'pemohons.dokumenPersyaratan'])->select('permohonans.*');
            return DataTables::eloquent($data)
                ->addIndexColumn()
                ->addColumn('organisasi', function($row){
                    return $row->organisasi->nama_organisasi ?? '-';
                })
                ->addColumn('jumlah_anggota', function($row){
                    return $row->pemohons->count() . ' Orang';
                })
                ->addColumn('status_dokumen', function($row){
                    $allDocumentsComplete = true;
                    $hasInvalidDocuments = false;
                    $requiredCount = \App\Models\MasterPersyaratan::where('is_required', true)->count();
                    
                    if ($row->pemohons->count() === 0) {
                        $allDocumentsComplete = false;
                    } else {
                        foreach ($row->pemohons as $pemohon) {
                            $uploadedCount = $pemohon->dokumenPersyaratan
                                ->whereIn('persyaratan_id', \App\Models\MasterPersyaratan::where('is_required', true)->pluck('id'))
                                ->where('status_dokumen', 'Valid')
                                ->count();
                                
                            $invalidCount = $pemohon->dokumenPersyaratan
                                ->where('status_dokumen', 'Tidak Valid')
                                ->count();
                                
                            if ($invalidCount > 0) {
                                $hasInvalidDocuments = true;
                            }
                            
                            if ($uploadedCount < $requiredCount) {
                                $allDocumentsComplete = false;
                            }
                        }
                    }
                    
                    if ($allDocumentsComplete) {
                        return '<span class="inline-flex items-center text-success font-bold text-sm"><i class="fa-solid fa-circle-check mr-1"></i> Lengkap</span>';
                    } elseif ($hasInvalidDocuments) {
                        return '<span class="inline-flex items-center text-fg-danger-strong font-bold text-sm"><i class="fa-solid fa-circle-xmark mr-1"></i> Kurang/Ditolak</span>';
                    } else {
                        return '<span class="inline-flex items-center text-fg-warning font-bold text-sm"><i class="fa-regular fa-clock mr-1"></i> Menunggu</span>';
                    }
                })
                ->addColumn('status_badge', function($row){
                    if($row->status == 'Draft') return '<span class="inline-flex items-center px-2 py-0.5 rounded-default text-xs font-medium bg-neutral-secondary-medium border border-border-default text-heading">Draft</span>';
                    if($row->status == 'Menunggu Verifikasi') return '<span class="inline-flex items-center px-2 py-0.5 rounded-default text-xs font-medium bg-warning-soft border border-border-warning-subtle text-fg-warning"><i class="fa-regular fa-clock mr-1"></i>Menunggu Verifikasi</span>';
                    if($row->status == 'Verifikasi Berkas Fisik') return '<span class="inline-flex items-center px-2 py-0.5 rounded-default text-xs font-medium bg-warning-soft border border-border-warning-subtle text-fg-warning"><i class="fa-solid fa-folder-open mr-1"></i>Verifikasi Fisik</span>';
                    if($row->status == 'Menentukan Jadwal Verifikasi') return '<span class="inline-flex items-center px-2 py-0.5 rounded-default text-xs font-medium bg-info-soft border border-border-info-subtle text-fg-info"><i class="fa-regular fa-clock mr-1"></i>Jadwal Verifikasi</span>';
                    if($row->status == 'Menentukan Jadwal Sumpah') return '<span class="inline-flex items-center px-2 py-0.5 rounded-default text-xs font-medium bg-info-soft border border-border-info-subtle text-fg-info"><i class="fa-regular fa-calendar-alt mr-1"></i>Jadwal Sumpah</span>';
                    if($row->status == 'Proses Pembuatan Surat') return '<span class="inline-flex items-center px-2 py-0.5 rounded-default text-xs font-medium bg-info-soft border border-border-info-subtle text-fg-info"><i class="fa-solid fa-spinner fa-spin mr-1"></i>Pembuatan Surat</span>';
                    if($row->status == 'Surat Selesai') return '<span class="inline-flex items-center px-2 py-0.5 rounded-default text-xs font-medium bg-success-soft border border-border-success-subtle text-fg-success-strong"><i class="fa-regular fa-file-pdf mr-1"></i>Surat Selesai</span>';
                    if($row->status == 'Selesai') return '<span class="inline-flex items-center px-2 py-0.5 rounded-default text-xs font-medium bg-brand-softer border border-border-brand-subtle text-fg-brand-strong"><i class="fa-solid fa-flag-checkered mr-1"></i>Selesai</span>';
                    return '<span class="inline-flex items-center px-2 py-0.5 rounded-default text-xs font-medium bg-warning-soft border border-border-warning-subtle text-fg-warning">'.$row->status.'</span>';
                })
                ->addColumn('action', function($row){
                    $detailUrl = route('admin.permohonan.show', $row->id);
                    return '<a href="'.$detailUrl.'" class="inline-flex items-center px-3 py-1.5 rounded-base text-sm font-medium bg-neutral-primary-soft text-brand shadow-sm hover:shadow-md hover:text-brand-strong active:shadow-inset transition-all border border-brand"><i class="fa-solid fa-eye mr-2"></i> Detail</a>';
                })
                ->rawColumns(['status_dokumen', 'status_badge', 'action'])
                ->make(true);
        }
        return view('admin.permohonan.index');
    }

    public function create() {}
    public function store(Request $request) {}
    public function show(string $id) {
        $permohonan = Permohonan::with([
            'pemohon.organisasi',
            'pemohons.organisasi',
            'pemohons.dokumenPersyaratan.masterPersyaratan',
            'riwayatStatus',
            'verifikasi',
            'jadwalSumpah',
            'organisasi'
        ])->findOrFail($id);
        return view('admin.permohonan.show', compact('permohonan'));
    }

    public function penjadwalan(string $id)
    {
        $permohonan = Permohonan::with([
            'pemohons.organisasi',
            'pemohons.dokumenPersyaratan.masterPersyaratan',
            'riwayatStatus',
            'verifikasi',
            'jadwalSumpah',
            'organisasi'
        ])->findOrFail($id);

        if ($permohonan->status !== 'Siap Penjadwalan Pengecekan Berkas Fisik') {
            return redirect()->route('admin.permohonan.show', $id)
                ->with('error', 'Status permohonan tidak mendukung penjadwalan berkas fisik saat ini.');
        }

        return view('admin.permohonan.penjadwalan', compact('permohonan'));
    }

    public function edit(string $id) {}
    public function update(Request $request, string $id) {}
    public function destroy(string $id) {}

    public function memberShow($pemohon_id)
    {
        $pemohon = \App\Models\Pemohon::with(['permohonan.organisasi', 'dokumenPersyaratan.masterPersyaratan'])->findOrFail($pemohon_id);
        return view('admin.permohonan.member-show', compact('pemohon'));
    }

    public function verifikasiMember(Request $request, $pemohon_id)
    {
        $request->validate([
            'status_verifikasi' => 'required|in:Disetujui,Ditolak',
            'dokumen' => 'required|array',
            'dokumen.*' => 'required|in:Valid,Tidak Valid',
            'keterangan_dokumen' => 'nullable|array',
            'keterangan_dokumen.*' => 'nullable|string',
        ]);

        $pemohon = \App\Models\Pemohon::with('permohonan.pemohons')->findOrFail($pemohon_id);
        $permohonan = $pemohon->permohonan;

        $persyaratan = \App\Models\MasterPersyaratan::all();
        $requiredCount = $persyaratan->where('is_required', true)->count();
        $uploadedRequiredCount = $pemohon->dokumenPersyaratan()
            ->whereIn('persyaratan_id', $persyaratan->where('is_required', true)->pluck('id'))
            ->count();

        if ($uploadedRequiredCount < $requiredCount) {
            return back()->with('error', 'Tidak dapat memverifikasi anggota karena dokumen persyaratan belum lengkap.');
        }

        \Illuminate\Support\Facades\DB::beginTransaction();
        try {
            if ($request->dokumen) {
                foreach ($request->dokumen as $dokumenId => $statusDokumen) {
                    $dokumen = \App\Models\DokumenPersyaratan::find($dokumenId);
                    if ($dokumen) {
                        $dokumen->update([
                            'status_dokumen' => $statusDokumen,
                            'keterangan' => $request->keterangan_dokumen[$dokumenId] ?? null,
                        ]);
                    }
                }
            }

            // Calculate status automatically based on whether any document is Tidak Valid
            $invalidDocs = $pemohon->dokumenPersyaratan()
                ->where('status_dokumen', 'Tidak Valid')
                ->with('masterPersyaratan')
                ->get();

            $statusVerifikasi = $invalidDocs->count() > 0 ? 'Ditolak' : 'Disetujui';

            $pemohon->update([
                'status_verifikasi' => $statusVerifikasi,
                'catatan_penolakan' => null,
            ]);

            if ($statusVerifikasi === 'Ditolak') {
                $rejectedList = $invalidDocs->map(fn($d) => $d->masterPersyaratan->nama_persyaratan)->toArray();
                try {
                    \Illuminate\Support\Facades\Mail::to($permohonan->email_organisasi)
                        ->send(new \App\Mail\MemberRejectedMail($pemohon, $permohonan, $rejectedList));
                    
                    \Illuminate\Support\Facades\Mail::to($pemohon->email)
                        ->send(new \App\Mail\MemberRejectedMail($pemohon, $permohonan, $rejectedList));
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::error('Gagal mengirim email penolakan anggota: ' . $e->getMessage());
                }
            }

            // Sync permohonan status: if all approved, change to Siap Penjadwalan Pengecekan Berkas Fisik
            $allMembers = $permohonan->pemohons()->get();
            $allApproved = $allMembers->every(fn($m) => $m->status_verifikasi === 'Disetujui');

            $oldStatus = $permohonan->status;
            if ($allApproved) {
                if ($oldStatus === 'Verifikasi Berkas Fisik' || $oldStatus === 'Draft' || $oldStatus === 'Menunggu Verifikasi') {
                    $permohonan->update(['status' => 'Siap Penjadwalan Pengecekan Berkas Fisik']);
                    
                    \App\Models\RiwayatStatus::create([
                        'permohonan_id' => $permohonan->id,
                        'status_lama' => $oldStatus,
                        'status_baru' => 'Siap Penjadwalan Pengecekan Berkas Fisik',
                        'keterangan' => 'Seluruh anggota disetujui, siap menentukan jadwal.',
                        'changed_by' => auth()->id() ?? 1,
                    ]);
                }
            } else {
                if ($oldStatus === 'Siap Penjadwalan Pengecekan Berkas Fisik' || $oldStatus === 'Menunggu Verifikasi') {
                    $permohonan->update(['status' => 'Verifikasi Berkas Fisik']);
                    
                    \App\Models\RiwayatStatus::create([
                        'permohonan_id' => $permohonan->id,
                        'status_lama' => $oldStatus,
                        'status_baru' => 'Verifikasi Berkas Fisik',
                        'keterangan' => 'Terdapat berkas anggota yang ditolak/pending. Status direset.',
                        'changed_by' => auth()->id() ?? 1,
                    ]);
                }
            }

            \Illuminate\Support\Facades\DB::commit();

            return redirect()->route('admin.permohonan.show', $pemohon->permohonan_id)
                ->with('success', 'Status verifikasi anggota ' . $pemohon->nama_lengkap . ' berhasil diperbarui secara otomatis.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    public function verifikasi(Request $request, string $id) {
        $permohonan = Permohonan::findOrFail($id);
        $currentStatus = $permohonan->status;

        $validTransitions = [
            'Verifikasi Berkas Fisik' => 'Siap Penjadwalan Pengecekan Berkas Fisik',
            'Siap Penjadwalan Pengecekan Berkas Fisik' => 'Menentukan Jadwal Verifikasi',
            'Menentukan Jadwal Verifikasi' => 'Menentukan Jadwal Sumpah',
            'Menentukan Jadwal Sumpah' => 'Proses Pembuatan Surat',
            'Proses Pembuatan Surat' => 'Surat Selesai',
            'Surat Selesai' => 'Selesai',
        ];

        if ($currentStatus === 'Draft' || $currentStatus === 'Menunggu Verifikasi') {
            $expectedNextStatus = 'Verifikasi Berkas Fisik';
        } else {
            $expectedNextStatus = $validTransitions[$currentStatus] ?? null;
        }

        if ($request->status !== $expectedNextStatus) {
            return back()->with('error', 'Transisi status tidak valid. Status harus berubah berurutan dari ' . $currentStatus . ' ke ' . $expectedNextStatus . '.');
        }

        // Programmatic business validation
        if ($request->status === 'Menentukan Jadwal Verifikasi') {
            $allApproved = $permohonan->pemohons()->get()->every(fn($m) => $m->status_verifikasi === 'Disetujui');
            if (!$allApproved) {
                return back()->with('error', 'Penentuan tanggal pengecekan berkas fisik hanya dapat dilakukan apabila seluruh anggota berstatus Disetujui.');
            }
        }

        $request->validate([
            'status'                   => 'required|in:Verifikasi Berkas Fisik,Siap Penjadwalan Pengecekan Berkas Fisik,Menentukan Jadwal Verifikasi,Menentukan Jadwal Sumpah,Proses Pembuatan Surat,Surat Selesai,Selesai',
            'catatan'                  => 'nullable|string',
            'hari_verifikasi_fisik'    => 'required_if:status,Menentukan Jadwal Verifikasi|nullable|string',
            'tanggal_verifikasi_fisik' => 'required_if:status,Menentukan Jadwal Verifikasi|nullable|date',
            'surat_bertanda_tangan'    => 'required_if:status,Surat Selesai|nullable|file|mimes:pdf|max:2048',
            'tanggal_sumpah'           => 'required_if:status,Menentukan Jadwal Sumpah|nullable|date',
            'jam_sumpah'               => 'required_if:status,Menentukan Jadwal Sumpah|nullable',
            'lokasi_sumpah'            => 'required_if:status,Menentukan Jadwal Sumpah|nullable|string',
        ]);

        \Illuminate\Support\Facades\DB::beginTransaction();
        try {
            $statusLama = $permohonan->status;
            $permohonan->status  = $request->status;
            $permohonan->catatan = $request->catatan;

            if ($request->status === 'Menentukan Jadwal Verifikasi') {
                $tanggal = $request->tanggal_verifikasi_fisik;
                $permohonan->tanggal_verifikasi_fisik = $tanggal;
                $permohonan->hari_verifikasi_fisik = $tanggal
                    ? \Carbon\Carbon::parse($tanggal)->locale('id')->isoFormat('dddd')
                    : $request->hari_verifikasi_fisik;
            }

            if ($request->status === 'Menentukan Jadwal Sumpah') {
                if ($request->tanggal_sumpah && $request->jam_sumpah) {
                    \App\Models\JadwalSumpah::updateOrCreate(
                        ['permohonan_id' => $permohonan->id],
                        [
                            'tanggal' => $request->tanggal_sumpah,
                            'jam' => $request->jam_sumpah,
                            'lokasi' => $request->lokasi_sumpah ?? 'Ruang Sidang Utama Pengadilan Tinggi Tanjungkarang',
                            'keterangan' => $request->catatan,
                        ]
                    );
                    $permohonan->load('jadwalSumpah');
                }
            }

            if ($request->status === 'Proses Pembuatan Surat') {
                $activeTemplate = \App\Models\SuratTemplate::where('is_active', true)->first();
                if ($activeTemplate && \Illuminate\Support\Facades\Storage::disk('public')->exists($activeTemplate->file_path)) {
                    try {
                        $path = $this->generateWordDraft($permohonan, $activeTemplate);
                        $permohonan->file_surat = $path;
                    } catch (\Exception $e) {
                        return back()->with('error', 'Gagal memproses draf Word: ' . $e->getMessage())->withInput();
                    }
                } else {
                    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.permohonan.surat_pdf', compact('permohonan'));
                    $fileName = 'surat_pengantar_' . $permohonan->nomor_permohonan . '.pdf';
                    $path = 'permohonan/surat/' . $fileName;
                    \Illuminate\Support\Facades\Storage::disk('public')->put($path, $pdf->output());
                    $permohonan->file_surat = $path;
                }
            }

            if ($request->status === 'Surat Selesai') {
                if ($request->hasFile('surat_bertanda_tangan')) {
                    $file = $request->file('surat_bertanda_tangan');
                    $oldPath = $permohonan->file_surat;
                    $fileName = 'surat_final_' . $permohonan->nomor_permohonan . '.pdf';
                    $newPath = $file->storeAs('permohonan/surat', $fileName, 'public');
                    $permohonan->file_surat = $newPath;
                    if ($oldPath && $oldPath !== $newPath && \Illuminate\Support\Facades\Storage::disk('public')->exists($oldPath)) {
                        \Illuminate\Support\Facades\Storage::disk('public')->delete($oldPath);
                    }
                } else {
                    return back()->with('error', 'Surat bertanda tangan wajib diunggah untuk status Surat Selesai.')->withInput();
                }
            }

            $permohonan->save();

            \App\Models\RiwayatStatus::create([
                'permohonan_id' => $permohonan->id,
                'status_lama' => $statusLama,
                'status_baru' => $request->status,
                'keterangan' => $request->catatan,
                'changed_by' => auth()->id() ?? 1,
            ]);

            \App\Models\Verifikasi::create([
                'permohonan_id' => $permohonan->id,
                'admin_id' => auth()->id() ?? 1,
                'status_verifikasi' => $request->status,
                'catatan' => $request->catatan,
            ]);

            if ($request->status === 'Surat Selesai' || $request->status === 'Selesai') {
                try {
                    \Illuminate\Support\Facades\Mail::to($permohonan->email_organisasi, $permohonan->organisasi->nama_organisasi ?? 'Organisasi')
                        ->send(new \App\Mail\StatusVerifikasiMail($permohonan));
                } catch (\Exception $mailException) {
                    \Illuminate\Support\Facades\Log::error('Gagal mengirim email update status: ' . $mailException->getMessage());
                }
            }

            \Illuminate\Support\Facades\DB::commit();
            return redirect()->route('admin.permohonan.show', $permohonan->id)->with('success', 'Status permohonan berhasil diperbarui.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return back()->with('error', 'Gagal memperbarui status: ' . $e->getMessage())->withInput();
        }
    }

    public function downloadSurat(Request $request, string $id) {
        $permohonan = Permohonan::findOrFail($id);

        if ($permohonan->status === 'Proses Pembuatan Surat') {
            $request->validate([
                'jabatan' => 'required|in:PANITERA,PLH. PANITERA,PLT. PANITERA',
            ], [
                'jabatan.required' => 'Silakan pilih jabatan penandatangan terlebih dahulu.',
                'jabatan.in' => 'Jabatan penandatangan tidak valid.',
            ]);
        }

        if ($permohonan->status === 'Proses Pembuatan Surat') {
            $activeTemplate = \App\Models\SuratTemplate::where('is_active', true)->first();
            if ($activeTemplate && \Illuminate\Support\Facades\Storage::disk('public')->exists($activeTemplate->file_path)) {
                try {
                    $path = $this->generateWordDraft($permohonan, $activeTemplate, $request->jabatan);
                    $permohonan->file_surat = $path;
                    $permohonan->save();
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::error('Gagal regenerasi draf Word: ' . $e->getMessage());
                }
            } else {
                $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.permohonan.surat_pdf', compact('permohonan'));
                $fileName = 'surat_pengantar_' . $permohonan->nomor_permohonan . '.pdf';
                $path = 'permohonan/surat/' . $fileName;
                \Illuminate\Support\Facades\Storage::disk('public')->put($path, $pdf->output());
                $permohonan->file_surat = $path;
                $permohonan->save();
            }
        }

        if (!$permohonan->file_surat || !\Illuminate\Support\Facades\Storage::disk('public')->exists($permohonan->file_surat)) {
            return back()->with('error', 'File surat tidak ditemukan.');
        }
        
        $extension = pathinfo($permohonan->file_surat, PATHINFO_EXTENSION);
        $safeNomor = str_replace('/', '_', $permohonan->nomor_permohonan);
        $displayName = (in_array($permohonan->status, ['Surat Selesai', 'Selesai']))
            ? 'Surat_Final_' . $safeNomor . '.pdf'
            : 'Draft_Surat_' . $safeNomor . '.' . $extension;
            
        return \Illuminate\Support\Facades\Storage::disk('public')->download($permohonan->file_surat, $displayName);
    }

    /**
     * Helper to generate Word draft from template.
     */
    private function generateWordDraft($permohonan, $activeTemplate, $jabatan = null)
    {
        $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor(storage_path('app/public/' . $activeTemplate->file_path));
        
        $templateProcessor->setValue('jabatan', $jabatan ?? '-');
        
        $pemohon = $permohonan->pemohon;
        $jadwal = $permohonan->jadwalSumpah;

        // General info
        $templateProcessor->setValue('nomor_permohonan', $permohonan->nomor_permohonan ?? '-');
        $templateProcessor->setValue('tanggal_registrasi', $permohonan->tanggal_pengajuan ? \Carbon\Carbon::parse($permohonan->tanggal_pengajuan)->translatedFormat('d F Y') : '-');
        $templateProcessor->setValue('tanggal_hari_ini', \Carbon\Carbon::now()->translatedFormat('d F Y'));
        $templateProcessor->setValue('tanggal_cetak', \Carbon\Carbon::now()->translatedFormat('d F Y'));
        $templateProcessor->setValue('tanggal_surat_balasan', \Carbon\Carbon::now()->translatedFormat('d F Y'));

        // Current Month (Roman Numeral) and Year for custom letter numbering
        $romanMonths = [
            1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV', 5 => 'V', 6 => 'VI',
            7 => 'VII', 8 => 'VIII', 9 => 'IX', 10 => 'X', 11 => 'XI', 12 => 'XII'
        ];
        $currentMonthNum = (int)date('n');
        $bulanRomawi = $romanMonths[$currentMonthNum] ?? 'I';
        $templateProcessor->setValue('bulan_romawi', $bulanRomawi);
        $templateProcessor->setValue('tahun', date('Y'));
        $templateProcessor->setValue('tahun_sekarang', date('Y'));

        // Generate dynamic nomor_balasan with leading spaces for sequence number
        $nomorBalasan = '        /PAN.W9-U/HM2.1.3/' . $bulanRomawi . '/' . date('Y');
        $templateProcessor->setValue('nomor_balasan', $nomorBalasan);
        $templateProcessor->setValue('nomot_balasan', $nomorBalasan);

        // Process multiline notes to preserve spacing and automatically prefix bullets
        $catatanText = $permohonan->catatan ?? '-';
        $lines = explode("\n", str_replace("\r", "", $catatanText));
        $formattedLines = [];
        foreach ($lines as $index => $line) {
            $trimmed = trim($line);
            if ($trimmed === '') continue;
            
            // If the line already starts with a list marker (e.g. -, *, •, or 1., 2.), leave it as is
            if (preg_match('/^([\-\*•]|\d+\.|\w\.)\s/', $trimmed)) {
                $formattedLines[] = $trimmed;
            } else {
                // Otherwise, prepend a dash if it's not the first line
                $formattedLines[] = ($index > 0 ? '- ' : '') . $trimmed;
            }
        }
        $formattedCatatan = count($formattedLines) > 0 ? implode('</w:t><w:br/><w:t>', $formattedLines) : '-';
        $templateProcessor->setValue('catatan', $formattedCatatan);

        // Applicant info (Backward Compatibility for single placeholders)
        $pemohons = $permohonan->pemohons;
        $firstPemohon = $pemohons->first() ?? $pemohon;
        
        if ($firstPemohon) {
            $templateProcessor->setValue('nama_lengkap', $firstPemohon->nama_lengkap ?? '-');
            $templateProcessor->setValue('nama', $firstPemohon->nama_lengkap ?? '-');
            $templateProcessor->setValue('nik', $firstPemohon->nik ?? '-');
            $templateProcessor->setValue('tempat_lahir', $firstPemohon->tempat_lahir ?? '-');
            $templateProcessor->setValue('tanggal_lahir', $firstPemohon->tanggal_lahir ? \Carbon\Carbon::parse($firstPemohon->tanggal_lahir)->translatedFormat('d F Y') : '-');
            $templateProcessor->setValue('jenis_kelamin', ($firstPemohon->jenis_kelamin ?? '') === 'L' ? 'Laki-laki' : (($firstPemohon->jenis_kelamin ?? '') === 'P' ? 'Perempuan' : '-'));
            $templateProcessor->setValue('alamat', $firstPemohon->alamat ?? '-');
            $templateProcessor->setValue('email', $firstPemohon->email ?? '-');
            $templateProcessor->setValue('no_hp', $firstPemohon->no_hp ?? '-');
        }
        
        $templateProcessor->setValue('organisasi', $permohonan->organisasi->nama_organisasi ?? '-');

        // Map SK details to nomor_sk / nomor_surat / tanggal_sk / tanggal_surat
        $templateProcessor->setValue('nomor_sk', $permohonan->nomor_sk ?? '-');
        $templateProcessor->setValue('nomor_surat', $permohonan->nomor_sk ?? '-');
        
        $formattedTanggalSk = $permohonan->tanggal_sk ? \Carbon\Carbon::parse($permohonan->tanggal_sk)->translatedFormat('d F Y') : '-';
        $templateProcessor->setValue('tanggal_sk', $formattedTanggalSk);
        $templateProcessor->setValue('tanggal_surat', $formattedTanggalSk);
        $templateProcessor->setValue('tanggal_pengajuan', $formattedTanggalSk);

        // Multi-applicant row cloning for tables
        if ($pemohons->count() > 0) {
            try {
                $templateProcessor->cloneRow('nama_anggota', $pemohons->count());
                foreach ($pemohons as $index => $p) {
                    $rowNum = $index + 1;
                    $templateProcessor->setValue('nama_anggota#' . $rowNum, $p->nama_lengkap ?? '-');
                    $templateProcessor->setValue('nik_anggota#' . $rowNum, $p->nik ?? '-');
                    $templateProcessor->setValue('ttl_anggota#' . $rowNum, ($p->tempat_lahir ?? '-') . ', ' . ($p->tanggal_lahir ? \Carbon\Carbon::parse($p->tanggal_lahir)->translatedFormat('d F Y') : '-'));
                    $templateProcessor->setValue('gender_anggota#' . $rowNum, ($p->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan'));
                    $templateProcessor->setValue('email_anggota#' . $rowNum, $p->email ?? '-');
                }
            } catch (\Exception $e) {
                // Ignore if template doesn't have multi-applicant table row placeholders
            }
        }

        // Sumpah schedule info
        if ($jadwal) {
            $formattedTanggal = \Carbon\Carbon::parse($jadwal->tanggal)->translatedFormat('d F Y');
            $formattedHari = \Carbon\Carbon::parse($jadwal->tanggal)->translatedFormat('l');
            $templateProcessor->setValue('hari', $formattedHari);
            $templateProcessor->setValue('tanggal_sumpah', $formattedTanggal);
            $templateProcessor->setValue('hari_tanggal', $formattedHari . ', ' . $formattedTanggal);
            $templateProcessor->setValue('jam', \Carbon\Carbon::parse($jadwal->jam)->format('H:i') . ' WIB');
            $templateProcessor->setValue('pukul', \Carbon\Carbon::parse($jadwal->jam)->format('H:i') . ' WIB');
            $templateProcessor->setValue('lokasi', $jadwal->lokasi ?? '-');
            $templateProcessor->setValue('tempat', $jadwal->lokasi ?? '-');
        } else {
            // Default placeholder values if not scheduled yet
            $templateProcessor->setValue('hari', '-');
            $templateProcessor->setValue('tanggal_sumpah', '-');
            $templateProcessor->setValue('hari_tanggal', '-');
            $templateProcessor->setValue('jam', '-');
            $templateProcessor->setValue('pukul', '-');
            $templateProcessor->setValue('lokasi', '-');
            $templateProcessor->setValue('tempat', '-');
        }

        $fileName = 'surat_pengantar_' . $permohonan->nomor_permohonan . '.docx';
        $path = 'permohonan/surat/' . $fileName;
        $fullPath = storage_path('app/public/' . $path);
        
        if (!file_exists(dirname($fullPath))) {
            mkdir(dirname($fullPath), 0755, true);
        }
        
        $templateProcessor->saveAs($fullPath);
        return $path;
    }
}

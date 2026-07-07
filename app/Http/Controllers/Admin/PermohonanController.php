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
                    if($row->status == 'Dijadwalkan Sumpah') return '<span class="inline-flex items-center px-2 py-0.5 rounded-default text-xs font-medium bg-success-soft border border-border-success-subtle text-fg-success-strong"><i class="fa-regular fa-calendar-check mr-1"></i>Dijadwalkan Sumpah</span>';
                    if($row->status == 'Selesai') return '<span class="inline-flex items-center px-2 py-0.5 rounded-default text-xs font-medium bg-brand-softer border border-border-brand-subtle text-fg-brand-strong">Selesai</span>';
                    if($row->status == 'Ditolak') return '<span class="inline-flex items-center px-2 py-0.5 rounded-default text-xs font-medium bg-danger-soft border border-border-danger-subtle text-fg-danger-strong">Ditolak</span>';
                    if($row->status == 'Verifikasi Berkas Fisik') return '<span class="inline-flex items-center px-2 py-0.5 rounded-default text-xs font-medium bg-warning-soft border border-border-warning-subtle text-fg-warning"><i class="fa-solid fa-folder-open mr-1"></i>Verifikasi Fisik</span>';
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
        $permohonan = Permohonan::with(['pemohon.organisasi', 'dokumenPersyaratan.masterPersyaratan', 'riwayatStatus', 'verifikasi', 'jadwalSumpah'])->findOrFail($id);
        return view('admin.permohonan.show', compact('permohonan'));
    }
    public function edit(string $id) {}
    public function update(Request $request, string $id) {}
    public function destroy(string $id) {}
    public function verifikasi(Request $request, string $id) {
        $request->validate([
            'status'                   => 'required|in:Disetujui,Ditolak,Selesai,Verifikasi Berkas Fisik,Diproses',
            'catatan'                  => 'nullable|string',
            'hari_verifikasi_fisik'    => 'required_if:status,Verifikasi Berkas Fisik|nullable|string',
            'tanggal_verifikasi_fisik' => 'required_if:status,Verifikasi Berkas Fisik|nullable|date',
            'surat_bertanda_tangan'    => 'required_if:status,Disetujui|nullable|file|mimes:pdf|max:2048',
            'tanggal_sumpah'           => 'required_if:status,Diproses|nullable|date',
            'jam_sumpah'               => 'required_if:status,Diproses|nullable',
            'lokasi_sumpah'            => 'required_if:status,Diproses|nullable|string',
        ]);

        $permohonan = Permohonan::with(['pemohon.organisasi'])->findOrFail($id);

        \Illuminate\Support\Facades\DB::beginTransaction();
        try {
            $permohonan->status  = $request->status;
            $permohonan->catatan = $request->catatan;

            if ($request->status === 'Verifikasi Berkas Fisik') {
                $tanggal = $request->tanggal_verifikasi_fisik;
                $permohonan->tanggal_verifikasi_fisik = $tanggal;
                // Hitung hari otomatis dari tanggal
                $permohonan->hari_verifikasi_fisik = $tanggal
                    ? \Carbon\Carbon::parse($tanggal)->locale('id')->isoFormat('dddd')
                    : $request->hari_verifikasi_fisik;
            }

            if ($request->status === 'Diproses') {
                // Save/update Jadwal Sumpah if inputted
                if ($request->tanggal_sumpah && $request->jam_sumpah) {
                    $jadwal = \App\Models\JadwalSumpah::updateOrCreate(
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

                $activeTemplate = \App\Models\SuratTemplate::where('is_active', true)->first();

                if ($activeTemplate && \Illuminate\Support\Facades\Storage::disk('public')->exists($activeTemplate->file_path)) {
                    try {
                        $path = $this->generateWordDraft($permohonan, $activeTemplate);
                        $permohonan->file_surat = $path;
                    } catch (\Exception $e) {
                        return back()->with('error', 'Gagal memproses draf Word: ' . $e->getMessage())->withInput();
                    }
                } else {
                    // Fallback to PDF
                    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.permohonan.surat_pdf', compact('permohonan'));
                    $fileName = 'surat_pengantar_' . $permohonan->nomor_permohonan . '.pdf';
                    $path = 'permohonan/surat/' . $fileName;
                    
                    \Illuminate\Support\Facades\Storage::disk('public')->put($path, $pdf->output());
                    $permohonan->file_surat = $path;
                }
            }

            if ($request->status === 'Disetujui') {
                if ($request->hasFile('surat_bertanda_tangan')) {
                    $file = $request->file('surat_bertanda_tangan');
                    
                    $oldPath = $permohonan->file_surat;
                    
                    $fileName = 'surat_final_' . $permohonan->nomor_permohonan . '.pdf';
                    $newPath = $file->storeAs('permohonan/surat', $fileName, 'public');
                    
                    $permohonan->file_surat = $newPath;
                    
                    // Delete old file if upload succeeded and file was different
                    if ($oldPath && $oldPath !== $newPath && \Illuminate\Support\Facades\Storage::disk('public')->exists($oldPath)) {
                        \Illuminate\Support\Facades\Storage::disk('public')->delete($oldPath);
                    }

                    // Keep DB status as Diproses until syncStatusAndNotify evaluates it
                    $permohonan->status = 'Diproses';
                } else {
                    return back()->with('error', 'Surat bertanda tangan wajib diunggah untuk status Disetujui.')->withInput();
                }
            }

            $permohonan->save();

            // Record verification in verifikasis table
            \App\Models\Verifikasi::create([
                'permohonan_id' => $permohonan->id,
                'admin_id' => auth()->id() ?? 1,
                'status_verifikasi' => $request->status,
                'catatan' => $request->catatan,
            ]);

            // Sync status and send notification email if both schedule & final letter are ready
            $permohonan->syncStatusAndNotify();

            // Kirim email notifikasi ke pemohon untuk status selain 'Diproses' dan 'Disetujui'
            if (!in_array($request->status, ['Diproses', 'Disetujui'])) {
                try {
                    \Illuminate\Support\Facades\Mail::to($permohonan->pemohon->email, $permohonan->pemohon->nama_lengkap)
                        ->send(new \App\Mail\StatusVerifikasiMail($permohonan));
                } catch (\Exception $mailException) {
                    \Illuminate\Support\Facades\Log::error('Gagal mengirim email update status: ' . $mailException->getMessage());
                }
            }

            \Illuminate\Support\Facades\DB::commit();
            
            if ($request->status === 'Diproses') {
                return redirect()->route('admin.permohonan.show', $permohonan->id)
                    ->with('success', 'Status permohonan berhasil diperbarui dan draf surat berhasil dibuat.');
            }

            return redirect()->route('admin.permohonan.index')->with('success', 'Status permohonan berhasil diperbarui.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return back()->with('error', 'Gagal memperbarui status: ' . $e->getMessage())->withInput();
        }
    }

    public function downloadSurat(Request $request, string $id) {
        $permohonan = Permohonan::findOrFail($id);

        // Validate the signatory position selection only for draft letters in 'Diproses' state
        if ($permohonan->status === 'Diproses') {
            $request->validate([
                'jabatan' => 'required|in:PANITERA,PLH. PANITERA,PLT. PANITERA',
            ], [
                'jabatan.required' => 'Silakan pilih jabatan penandatangan terlebih dahulu.',
                'jabatan.in' => 'Jabatan penandatangan tidak valid.',
            ]);
        }

        // Regenerate draft on the fly if status is Diproses to use the active template
        if ($permohonan->status === 'Diproses') {
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
                // Generate draft PDF fallback
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
        $displayName = (in_array($permohonan->status, ['Disetujui', 'Dijadwalkan Sumpah', 'Selesai']))
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

        // Applicant info
        $templateProcessor->setValue('nama_lengkap', $pemohon->nama_lengkap ?? '-');
        $templateProcessor->setValue('nama', $pemohon->nama_lengkap ?? '-');
        $templateProcessor->setValue('nik', $pemohon->nik ?? '-');
        $templateProcessor->setValue('tempat_lahir', $pemohon->tempat_lahir ?? '-');
        $templateProcessor->setValue('tanggal_lahir', $pemohon->tanggal_lahir ? \Carbon\Carbon::parse($pemohon->tanggal_lahir)->translatedFormat('d F Y') : '-');
        $templateProcessor->setValue('jenis_kelamin', ($pemohon->jenis_kelamin ?? '') === 'L' ? 'Laki-laki' : (($pemohon->jenis_kelamin ?? '') === 'P' ? 'Perempuan' : '-'));
        $templateProcessor->setValue('alamat', $pemohon->alamat ?? '-');
        $templateProcessor->setValue('email', $pemohon->email ?? '-');
        $templateProcessor->setValue('no_hp', $pemohon->no_hp ?? '-');
        $templateProcessor->setValue('organisasi', $pemohon->organisasi->nama_organisasi ?? '-');

        // Map SK details to nomor_surat / tanggal_surat / tanggal_pengajuan as requested by user
        $templateProcessor->setValue('nomor_sk', $pemohon->nomor_sk ?? '-');
        $templateProcessor->setValue('nomor_surat', $pemohon->nomor_sk ?? '-');
        
        $formattedTanggalSk = $pemohon->tanggal_sk ? \Carbon\Carbon::parse($pemohon->tanggal_sk)->translatedFormat('d F Y') : '-';
        $templateProcessor->setValue('tanggal_sk', $formattedTanggalSk);
        $templateProcessor->setValue('tanggal_surat', $formattedTanggalSk);
        $templateProcessor->setValue('tanggal_pengajuan', $formattedTanggalSk);

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

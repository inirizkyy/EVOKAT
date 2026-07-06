<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Berita;
use App\Models\Faq;
use App\Models\Permohonan;
use App\Models\MasterPersyaratan;
use App\Models\PengaturanWebsite;
use App\Models\OrganisasiAdvokat;
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
        $organisasi = OrganisasiAdvokat::all();
        $persyaratan = MasterPersyaratan::all();
        return view('landing.permohonan', compact('organisasi', 'persyaratan'));
    }

    public function storePermohonan(Request $request, PermohonanService $service)
    {
        $rules = [
            'nik' => 'required|numeric|digits:16|unique:pemohons',
            'nama_lengkap' => 'required|string|max:255',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:L,P',
            'alamat' => 'required|string',
            'email' => 'required|email|max:255',
            'no_hp' => 'required|string|max:20',
            'nama_organisasi' => 'required|string|max:255',
            'nomor_sk' => 'required|string|max:255',
            'tanggal_sk' => 'required|date',
            'foto' => 'required|file|mimes:jpg,jpeg,png|max:2048',
        ];

        // Validasi dokumen persyaratan secara dinamis berdasarkan status is_required di database
        $persyaratan = MasterPersyaratan::all();
        foreach ($persyaratan as $p) {
            if ($p->is_required) {
                $rules["dokumen.{$p->id}"] = 'required|file|mimes:pdf|max:2048';
            } else {
                $rules["dokumen.{$p->id}"] = 'nullable|file|mimes:pdf|max:2048';
            }
        }

        $request->validate($rules);

        try {
            $pemohonData = $request->except(['_token', 'dokumen', 'nama_organisasi']);
            $dokumenData = $request->file('dokumen');
            
            $organisasi = OrganisasiAdvokat::firstOrCreate(['nama_organisasi' => $request->nama_organisasi]);
            $pemohonData['organisasi_id'] = $organisasi->id;
            
            $permohonan = $service->submitPermohonan($pemohonData, $dokumenData);
            
            try {
                // Kirim email ke pemohon
                \Illuminate\Support\Facades\Mail::to($permohonan->pemohon->email, $permohonan->pemohon->nama_lengkap)->send(new \App\Mail\PermohonanDiajukanMail($permohonan));
                // Kirim notifikasi ke admin
                \Illuminate\Support\Facades\Mail::to('adminadvokat@gmail.com', 'Admin EVOKAT')->send(new \App\Mail\NotifikasiAdminMail($permohonan));
            } catch (\Exception $mailException) {
                // Log or ignore email error so it doesn't break the submission
                \Illuminate\Support\Facades\Log::error('Gagal mengirim email permohonan: ' . $mailException->getMessage());
            }
            
            return redirect('/tracking')
                ->with('success', 'Permohonan berhasil diajukan. Nomor Registrasi Anda: ' . $permohonan->nomor_permohonan)
                ->with('nomor_permohonan', $permohonan->nomor_permohonan);
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
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
        if ($permohonan->status !== 'Disetujui' && $permohonan->status !== 'Selesai') {
            abort(403, 'Surat final belum tersedia.');
        }
        if (!$permohonan->file_surat || !\Illuminate\Support\Facades\Storage::disk('public')->exists($permohonan->file_surat)) {
            abort(404, 'File surat tidak ditemukan.');
        }
        return \Illuminate\Support\Facades\Storage::disk('public')->download($permohonan->file_surat, 'Surat_Final_' . $permohonan->nomor_permohonan . '.pdf');
    }
}

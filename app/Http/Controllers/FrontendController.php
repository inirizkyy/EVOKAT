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
        $request->validate([
            'nik' => 'required|numeric|digits:16|unique:pemohons',
            'nama_lengkap' => 'required|string|max:255',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:L,P',
            'alamat' => 'required|string',
            'email' => 'required|email|max:255',
            'no_hp' => 'required|string|max:20',
            'nama_organisasi' => 'required|string|max:255',
            'foto' => 'required|file|mimes:pdf|max:2048',
            'dokumen.*' => 'required|file|mimes:pdf|max:2048',
        ]);

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
                \Illuminate\Support\Facades\Mail::to('adminadvokat@gmail.com', 'Admin E-Advokat')->send(new \App\Mail\NotifikasiAdminMail($permohonan));
            } catch (\Exception $mailException) {
                // Log or ignore email error so it doesn't break the submission
                \Illuminate\Support\Facades\Log::error('Gagal mengirim email permohonan: ' . $mailException->getMessage());
            }
            
            return redirect('/tracking')->with('success', 'Permohonan berhasil diajukan. Nomor Registrasi Anda: ' . $permohonan->nomor_permohonan);
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    public function tracking()
    {
        return view('landing.tracking');
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
}

<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Permohonan;
use App\Models\Pemohon;
use App\Models\OrganisasiAdvokat;
use App\Mail\StatusVerifikasiMail;
use App\Mail\JadwalSumpahMail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PermohonanVerifikasiTest extends TestCase
{
    use RefreshDatabase;

    private $admin;
    private $permohonan;
    private $pemohon;

    protected function setUp(): void
    {
        parent::setUp();
        Mail::fake();
        Storage::fake('public');

        $this->admin = User::factory()->create(['role' => 'admin']);
        
        $organisasi = OrganisasiAdvokat::create([
            'nama_organisasi' => 'PERADI',
            'alamat' => 'Jl. Test No. 1',
            'email' => 'peradi@test.com',
            'no_telp' => '021-123456',
        ]);
        
        $this->pemohon = Pemohon::create([
            'nik' => '1234567890123456',
            'nama_lengkap' => 'John Doe',
            'tempat_lahir' => 'Bandar Lampung',
            'tanggal_lahir' => '1990-01-01',
            'jenis_kelamin' => 'L',
            'alamat' => 'Jl. Test No. 1',
            'email' => 'johndoe@example.com',
            'no_hp' => '08123456789',
            'organisasi_id' => $organisasi->id,
            'nomor_sk' => 'SK/123/2026',
            'tanggal_sk' => '2026-01-01',
            'foto' => 'foto.jpg',
        ]);

        $this->permohonan = Permohonan::create([
            'pemohon_id' => $this->pemohon->id,
            'nomor_permohonan' => 'REG/2026/001',
            'tanggal_pengajuan' => '2026-07-03',
            'status' => 'Menunggu Verifikasi',
        ]);
    }

    public function test_admin_can_verify_and_send_status_verifikasi_mail()
    {
        $response = $this->actingAs($this->admin)
            ->post(route('admin.permohonan.verifikasi', $this->permohonan->id), [
                'status' => 'Verifikasi Berkas Fisik',
                'catatan' => 'Silakan bawa berkas asli',
                'hari_verifikasi_fisik' => 'Senin',
                'tanggal_verifikasi_fisik' => '2026-07-06',
            ]);

        $response->assertRedirect(route('admin.permohonan.index'));
        
        $this->assertDatabaseHas('permohonans', [
            'id' => $this->permohonan->id,
            'status' => 'Verifikasi Berkas Fisik',
        ]);

        Mail::assertSent(StatusVerifikasiMail::class, function ($mail) {
            return $mail->hasTo($this->pemohon->email) &&
                   $mail->permohonan->status === 'Verifikasi Berkas Fisik' &&
                   $mail->permohonan->catatan === 'Silakan bawa berkas asli';
        });
    }

    public function test_admin_can_approve_permohonan_and_send_approved_status_mail()
    {
        $file = UploadedFile::fake()->create('surat_final.pdf', 500, 'application/pdf');

        $response = $this->actingAs($this->admin)
            ->post(route('admin.permohonan.verifikasi', $this->permohonan->id), [
                'status' => 'Disetujui',
                'catatan' => 'Selamat permohonan disetujui',
                'surat_bertanda_tangan' => $file,
            ]);

        $response->assertRedirect(route('admin.permohonan.index'));

        $this->assertDatabaseHas('permohonans', [
            'id' => $this->permohonan->id,
            'status' => 'Disetujui',
        ]);

        Mail::assertSent(StatusVerifikasiMail::class, function ($mail) {
            return $mail->hasTo($this->pemohon->email) &&
                   $mail->permohonan->status === 'Disetujui';
        });
    }

    public function test_admin_sends_jadwal_sumpah_mail_on_diproses_status()
    {
        $response = $this->actingAs($this->admin)
            ->post(route('admin.permohonan.verifikasi', $this->permohonan->id), [
                'status' => 'Diproses',
                'catatan' => 'Jadwal sumpah telah ditetapkan',
                'tanggal_sumpah' => '2026-07-10',
                'jam_sumpah' => '09:00',
                'lokasi_sumpah' => 'Gedung PT',
            ]);

        $response->assertRedirect(route('admin.permohonan.index'));

        $this->assertDatabaseHas('permohonans', [
            'id' => $this->permohonan->id,
            'status' => 'Diproses',
        ]);

        Mail::assertSent(JadwalSumpahMail::class, function ($mail) {
            return $mail->hasTo($this->pemohon->email);
        });

        Mail::assertNotSent(StatusVerifikasiMail::class);
    }
}

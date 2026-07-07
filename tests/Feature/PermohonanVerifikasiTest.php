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

    public function test_admin_can_approve_permohonan_but_status_remains_diproses_if_no_schedule()
    {
        $this->permohonan->update(['status' => 'Diproses']);
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
            'status' => 'Diproses', // remains Diproses because there's no schedule yet
        ]);

        Mail::assertNotSent(StatusVerifikasiMail::class);
        Mail::assertNotSent(JadwalSumpahMail::class);
    }

    public function test_admin_can_schedule_but_status_remains_diproses_if_no_signed_letter()
    {
        $response = $this->actingAs($this->admin)
            ->post(route('admin.permohonan.verifikasi', $this->permohonan->id), [
                'status' => 'Diproses',
                'catatan' => 'Jadwal sumpah telah ditetapkan',
                'tanggal_sumpah' => '2026-07-10',
                'jam_sumpah' => '09:00',
                'lokasi_sumpah' => 'Gedung PT',
            ]);

        $response->assertRedirect(route('admin.permohonan.show', $this->permohonan->id));

        $this->assertDatabaseHas('permohonans', [
            'id' => $this->permohonan->id,
            'status' => 'Diproses', // remains Diproses because there's no final signed letter yet
        ]);

        Mail::assertNotSent(JadwalSumpahMail::class);
        Mail::assertNotSent(StatusVerifikasiMail::class);
    }

    public function test_status_transitions_to_dijadwalkan_sumpah_when_both_schedule_and_letter_are_ready()
    {
        // 1. Create schedule first
        $this->actingAs($this->admin)
            ->post(route('admin.permohonan.verifikasi', $this->permohonan->id), [
                'status' => 'Diproses',
                'catatan' => 'Jadwal sumpah telah ditetapkan',
                'tanggal_sumpah' => '2026-07-10',
                'jam_sumpah' => '09:00',
                'lokasi_sumpah' => 'Gedung PT',
            ]);

        $this->assertDatabaseHas('permohonans', [
            'id' => $this->permohonan->id,
            'status' => 'Diproses',
        ]);
        
        Mail::assertNotSent(JadwalSumpahMail::class);

        // 2. Upload signed letter
        $file = UploadedFile::fake()->create('surat_final.pdf', 500, 'application/pdf');

        $response = $this->actingAs($this->admin)
            ->post(route('admin.permohonan.verifikasi', $this->permohonan->id), [
                'status' => 'Disetujui',
                'catatan' => 'Selamat permohonan disetujui',
                'surat_bertanda_tangan' => $file,
            ]);

        $response->assertRedirect(route('admin.permohonan.index'));

        // Status should now automatically update to 'Dijadwalkan Sumpah'
        $this->assertDatabaseHas('permohonans', [
            'id' => $this->permohonan->id,
            'status' => 'Dijadwalkan Sumpah',
        ]);

        // Email notification should be sent
        Mail::assertSent(JadwalSumpahMail::class, function ($mail) {
            return $mail->hasTo($this->pemohon->email);
        });
    }

    public function test_applicant_can_download_final_letter_when_status_is_dijadwalkan_sumpah()
    {
        $filePath = 'permohonan/surat/surat_final_' . str_replace('/', '_', $this->permohonan->nomor_permohonan) . '.pdf';

        $this->permohonan->update([
            'status' => 'Dijadwalkan Sumpah',
            'file_surat' => $filePath,
        ]);

        Storage::disk('public')->put($filePath, 'dummy pdf content');

        $response = $this->get(route('permohonan.download-final', $this->permohonan->nomor_permohonan));

        $response->assertStatus(200);
        $response->assertDownload('Surat_Final_' . str_replace('/', '_', $this->permohonan->nomor_permohonan) . '.pdf');
    }

    public function test_admin_cannot_download_draft_if_jabatan_is_missing()
    {
        $this->permohonan->update(['status' => 'Diproses']);

        $response = $this->actingAs($this->admin)
            ->get(route('admin.permohonan.download-surat', $this->permohonan->id));

        $response->assertSessionHasErrors('jabatan');
    }

    public function test_admin_can_download_draft_with_valid_jabatan()
    {
        $this->permohonan->update(['status' => 'Diproses']);

        $response = $this->actingAs($this->admin)
            ->get(route('admin.permohonan.download-surat', $this->permohonan->id) . '?jabatan=PANITERA');

        $response->assertStatus(200);
        $response->assertDownload('Draft_Surat_' . str_replace('/', '_', $this->permohonan->nomor_permohonan) . '.pdf');
    }
}

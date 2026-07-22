<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Permohonan;
use App\Models\Pemohon;
use App\Models\Organization;
use App\Models\MasterPersyaratan;
use App\Models\DokumenPersyaratan;
use App\Mail\StatusVerifikasiMail;
use App\Mail\MemberRejectedMail;
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
    private $organisasi;

    protected function setUp(): void
    {
        parent::setUp();
        Mail::fake();
        Storage::fake('public');

        $this->admin = User::factory()->create(['role' => 'admin']);
        
        $this->organisasi = Organization::create([
            'nama_organisasi' => 'PERADI',
            'alamat' => 'Jl. Test No. 1',
            'email' => 'peradi@test.com',
            'no_telp' => '021-123456',
        ]);
        
        $this->permohonan = Permohonan::create([
            'organization_id' => $this->organisasi->id,
            'nomor_permohonan' => 'REG/2026/001',
            'tanggal_pengajuan' => '2026-07-03',
            'status' => 'Verifikasi Berkas Fisik',
            'nomor_sk' => 'SK/123/2026',
            'tanggal_sk' => '2026-01-01',
            'no_hp_organisasi' => '08123456789',
            'email_organisasi' => 'peradi@test.com',
        ]);

        $this->pemohon = Pemohon::create([
            'permohonan_id' => $this->permohonan->id,
            'organization_id' => $this->organisasi->id,
            'nik' => '1234567890123456',
            'nama_lengkap' => 'John Doe',
            'tempat_lahir' => 'Bandar Lampung',
            'tanggal_lahir' => '1990-01-01',
            'jenis_kelamin' => 'L',
            'email' => 'johndoe@example.com',
        ]);
    }

    public function test_admin_can_verify_member_automatically_approved_if_all_valid()
    {
        $req1 = MasterPersyaratan::where('nama_persyaratan', 'Pas Foto Pemohon')->first();
        if (!$req1) {
            $req1 = MasterPersyaratan::create(['nama_persyaratan' => 'Pas Foto Pemohon', 'is_required' => true]);
        }
        $req2 = MasterPersyaratan::create(['nama_persyaratan' => 'KTP', 'is_required' => true]);

        $dok1 = DokumenPersyaratan::create([
            'pemohon_id' => $this->pemohon->id,
            'permohonan_id' => $this->permohonan->id,
            'persyaratan_id' => $req1->id,
            'file_path' => 'file1.jpg',
            'status_dokumen' => 'Pending'
        ]);

        $dok2 = DokumenPersyaratan::create([
            'pemohon_id' => $this->pemohon->id,
            'permohonan_id' => $this->permohonan->id,
            'persyaratan_id' => $req2->id,
            'file_path' => 'file2.pdf',
            'status_dokumen' => 'Pending'
        ]);

        $response = $this->actingAs($this->admin)
            ->post(route('admin.permohonan.verifikasi-member', $this->pemohon->id), [
                'status_verifikasi' => 'Disetujui',
                'dokumen' => [
                    $dok1->id => 'Valid',
                    $dok2->id => 'Valid'
                ],
                'keterangan_dokumen' => [
                    $dok1->id => 'Foto OK',
                    $dok2->id => 'KTP OK'
                ]
            ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('admin.permohonan.show', $this->permohonan->id));

        $this->pemohon->refresh();
        $this->assertEquals('Disetujui', $this->pemohon->status_verifikasi);

        $this->permohonan->refresh();
        $this->assertEquals('Menentukan Jadwal Berkas Fisik', $this->permohonan->status);
        Mail::assertNotSent(MemberRejectedMail::class);
    }

    public function test_admin_can_verify_member_automatically_rejected_if_any_invalid_and_queues_email()
    {
        $req1 = MasterPersyaratan::where('nama_persyaratan', 'Pas Foto Pemohon')->first();
        if (!$req1) {
            $req1 = MasterPersyaratan::create(['nama_persyaratan' => 'Pas Foto Pemohon', 'is_required' => true]);
        }
        $req2 = MasterPersyaratan::create(['nama_persyaratan' => 'KTP', 'is_required' => true]);

        $dok1 = DokumenPersyaratan::create([
            'pemohon_id' => $this->pemohon->id,
            'permohonan_id' => $this->permohonan->id,
            'persyaratan_id' => $req1->id,
            'file_path' => 'file1.jpg',
            'status_dokumen' => 'Pending'
        ]);

        $dok2 = DokumenPersyaratan::create([
            'pemohon_id' => $this->pemohon->id,
            'permohonan_id' => $this->permohonan->id,
            'persyaratan_id' => $req2->id,
            'file_path' => 'file2.pdf',
            'status_dokumen' => 'Pending'
        ]);

        $response = $this->actingAs($this->admin)
            ->post(route('admin.permohonan.verifikasi-member', $this->pemohon->id), [
                'status_verifikasi' => 'Ditolak',
                'dokumen' => [
                    $dok1->id => 'Tidak Valid',
                    $dok2->id => 'Valid'
                ],
                'keterangan_dokumen' => [
                    $dok1->id => 'Foto tidak jelas/blur',
                    $dok2->id => 'KTP OK'
                ]
            ]);

        $response->assertSessionHasNoErrors();
        
        $this->pemohon->refresh();
        $this->assertEquals('Ditolak', $this->pemohon->status_verifikasi);

        $this->permohonan->refresh();
        $this->assertEquals('Verifikasi Berkas Fisik', $this->permohonan->status);

        Mail::assertSent(MemberRejectedMail::class, function($mail) {
            return $mail->hasTo($this->permohonan->email_organisasi) &&
                   in_array('Pas Foto Pemohon', $mail->rejectedList);
        });

        Mail::assertSent(MemberRejectedMail::class, function($mail) {
            return $mail->hasTo($this->pemohon->email) &&
                   in_array('Pas Foto Pemohon', $mail->rejectedList);
        });
    }

    public function test_admin_can_transition_to_menunggu_verifikasi_verifikator_1()
    {
        $this->pemohon->update(['status_verifikasi' => 'Disetujui']);
        $this->permohonan->update(['status' => 'Menentukan Jadwal Berkas Fisik']);

        $response = $this->actingAs($this->admin)
            ->post(route('admin.permohonan.verifikasi', $this->permohonan->id), [
                'status' => 'Menunggu Verifikasi Verifikator 1',
                'catatan' => 'Jadwal penyerahan berkas fisik asli',
                'hari_verifikasi_fisik' => 'Senin',
                'tanggal_verifikasi_fisik' => '2026-07-06',
            ]);

        $response->assertRedirect(route('admin.permohonan.show', $this->permohonan->id));
        
        $this->assertDatabaseHas('permohonans', [
            'id' => $this->permohonan->id,
            'status' => 'Menunggu Verifikasi Verifikator 1',
            'hari_verifikasi_fisik' => 'Senin',
            'tanggal_verifikasi_fisik' => '2026-07-06',
        ]);

        Mail::assertSent(\App\Mail\JadwalBerkasFisikMail::class, function ($mail) {
            return $mail->hasTo($this->permohonan->email_organisasi);
        });
    }

    public function test_admin_can_transition_to_proses_pembuatan_surat_by_scheduling_oath()
    {
        $this->permohonan->update(['status' => 'Menentukan Jadwal Sumpah']);

        $response = $this->actingAs($this->admin)
            ->post(route('admin.permohonan.verifikasi', $this->permohonan->id), [
                'status' => 'Proses Pembuatan Surat',
                'catatan' => 'Jadwal pelaksanaan sumpah',
                'tanggal_sumpah' => '2026-07-10',
                'jam_sumpah' => '09:00',
                'lokasi_sumpah' => 'Gedung Pengadilan Tinggi',
            ]);

        $response->assertRedirect(route('admin.permohonan.show', $this->permohonan->id));

        $this->assertDatabaseHas('permohonans', [
            'id' => $this->permohonan->id,
            'status' => 'Proses Pembuatan Surat',
        ]);

        $this->assertDatabaseHas('jadwal_sumpahs', [
            'permohonan_id' => $this->permohonan->id,
            'tanggal' => '2026-07-10',
            'jam' => '09:00:00',
            'lokasi' => 'Gedung Pengadilan Tinggi',
        ]);
    }

    public function test_admin_can_transition_to_surat_selesai_with_upload()
    {
        $this->permohonan->update(['status' => 'Proses Pembuatan Surat']);
        $file = UploadedFile::fake()->create('surat_final.pdf', 500, 'application/pdf');

        $response = $this->actingAs($this->admin)
            ->post(route('admin.permohonan.verifikasi', $this->permohonan->id), [
                'status' => 'Surat Selesai',
                'catatan' => 'Surat pengantar selesai ditandatangani',
                'surat_bertanda_tangan' => $file,
            ]);

        $response->assertRedirect(route('admin.permohonan.show', $this->permohonan->id));

        $this->assertDatabaseHas('permohonans', [
            'id' => $this->permohonan->id,
            'status' => 'Surat Selesai',
        ]);

        Mail::assertSent(StatusVerifikasiMail::class, function ($mail) {
            return $mail->hasTo($this->permohonan->email_organisasi) &&
                   $mail->permohonan->status === 'Surat Selesai';
        });
    }

    public function test_applicant_can_download_final_letter_when_status_is_surat_selesai()
    {
        $filePath = 'permohonan/surat/surat_final_' . str_replace('/', '_', $this->permohonan->nomor_permohonan) . '.pdf';

        $this->permohonan->update([
            'status' => 'Surat Selesai',
            'file_surat' => $filePath,
        ]);

        Storage::disk('public')->put($filePath, 'dummy pdf content');

        $response = $this->get(route('permohonan.download-final', $this->permohonan->nomor_permohonan));

        $response->assertStatus(200);
        $response->assertDownload('Surat_Final_' . str_replace('/', '_', $this->permohonan->nomor_permohonan) . '.pdf');
    }

    public function test_admin_cannot_download_draft_if_jabatan_is_missing()
    {
        $this->permohonan->update(['status' => 'Proses Pembuatan Surat']);

        $response = $this->actingAs($this->admin)
            ->get(route('admin.permohonan.download-surat', $this->permohonan->id));

        $response->assertSessionHasErrors('jabatan');
    }

    public function test_admin_can_download_draft_with_valid_jabatan()
    {
        $this->permohonan->update(['status' => 'Proses Pembuatan Surat']);
        $filePath = 'permohonan/surat/surat_pengantar_' . $this->permohonan->nomor_permohonan . '.pdf';
        $this->permohonan->update(['file_surat' => $filePath]);
        Storage::disk('public')->put($filePath, 'dummy draft pdf');

        $response = $this->actingAs($this->admin)
            ->get(route('admin.permohonan.download-surat', $this->permohonan->id) . '?jabatan=PANITERA');

        $response->assertStatus(200);
        $response->assertDownload('Draft_Surat_' . str_replace('/', '_', $this->permohonan->nomor_permohonan) . '.pdf');
    }

    public function test_admin_can_download_draft_with_blank_signatory()
    {
        $this->permohonan->update(['status' => 'Proses Pembuatan Surat']);
        $filePath = 'permohonan/surat/surat_pengantar_' . $this->permohonan->nomor_permohonan . '.pdf';
        $this->permohonan->update(['file_surat' => $filePath]);
        Storage::disk('public')->put($filePath, 'dummy draft pdf');

        $response = $this->actingAs($this->admin)
            ->get(route('admin.permohonan.download-surat', $this->permohonan->id) . '?jabatan=PANITERA&nama_penandatangan=[KOSONG]');

        $response->assertStatus(200);
        $response->assertDownload('Draft_Surat_' . str_replace('/', '_', $this->permohonan->nomor_permohonan) . '.pdf');
    }

    public function test_tracking_page_shows_sedang_diproses_when_no_schedule()
    {
        $this->permohonan->update([
            'status' => 'Verifikasi Berkas Fisik',
            'tanggal_verifikasi_fisik' => null
        ]);

        $response = $this->post('/tracking', [
            'nomor_permohonan' => $this->permohonan->nomor_permohonan
        ]);

        $response->assertStatus(200);
        $response->assertSee('Sedang Diproses');
    }

    public function test_uploading_new_document_clears_previous_keterangan()
    {
        // Setup existing required documents so they don't fail validation
        $pasFotoReq = MasterPersyaratan::where('nama_persyaratan', 'Pas Foto Pemohon')->first();
        if ($pasFotoReq) {
            DokumenPersyaratan::create([
                'pemohon_id' => $this->pemohon->id,
                'permohonan_id' => $this->permohonan->id,
                'persyaratan_id' => $pasFotoReq->id,
                'file_path' => 'pas_foto.jpg',
                'status_dokumen' => 'Valid'
            ]);
        }

        $req = MasterPersyaratan::create(['nama_persyaratan' => 'Ijazah', 'is_required' => true]);
        
        $dok = DokumenPersyaratan::create([
            'pemohon_id' => $this->pemohon->id,
            'permohonan_id' => $this->permohonan->id,
            'persyaratan_id' => $req->id,
            'file_path' => 'file_old.pdf',
            'status_dokumen' => 'Tidak Valid',
            'keterangan' => 'File tidak terbaca'
        ]);

        $file = UploadedFile::fake()->create('file_new.pdf', 500);

        $response = $this->post(route('permohonan.store-dokumen-upload', [
            'nomor_permohonan' => $this->permohonan->nomor_permohonan,
            'pemohon_id' => $this->pemohon->id
        ]), [
            'dokumen' => [
                $req->id => $file
            ]
        ]);

        $response->assertSessionHasNoErrors();
        
        $this->assertDatabaseHas('dokumen_persyaratans', [
            'id' => $dok->id,
            'status_dokumen' => 'Pending',
            'keterangan' => null
        ]);
    }

    public function test_admin_validation_page_hides_keterangan_for_pending_documents()
    {
        $req = MasterPersyaratan::create(['nama_persyaratan' => 'Ijazah', 'is_required' => true]);
        
        $dok = DokumenPersyaratan::create([
            'pemohon_id' => $this->pemohon->id,
            'permohonan_id' => $this->permohonan->id,
            'persyaratan_id' => $req->id,
            'file_path' => 'file.pdf',
            'status_dokumen' => 'Pending',
            'keterangan' => 'File tidak terbaca'
        ]);

        $response = $this->actingAs($this->admin)
            ->get(route('admin.permohonan.member-show', $this->pemohon->id));

        $response->assertStatus(200);
        $response->assertDontSee('value="File tidak terbaca"');
    }
}

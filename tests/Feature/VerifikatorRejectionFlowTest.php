<?php

namespace Tests\Feature;

use App\Models\DokumenPersyaratan;
use App\Models\MasterPersyaratan;
use App\Models\Organization;
use App\Models\Pemohon;
use App\Models\Permohonan;
use App\Models\User;
use App\Mail\MemberRejectedMail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class VerifikatorRejectionFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_verifikator_rejection_triggers_perbaikan_status_and_sends_emails()
    {
        Mail::fake();
        Storage::fake('public');

        // Reset any seeded master persyaratan requirements for isolated test
        MasterPersyaratan::query()->update(['is_required' => false]);

        $verifikator1 = User::factory()->create([
            'role' => 'verifikator1',
            'email_verified_at' => now(),
        ]);

        $organisasi = Organization::create(['nama_organisasi' => 'Persatuan Advokat Test']);
        $masterDoc  = MasterPersyaratan::create(['nama_persyaratan' => 'KTP', 'is_required' => true]);

        $permohonan = Permohonan::create([
            'organisasi_id'     => $organisasi->id,
            'nomor_permohonan'  => 'ADV-2026-TEST1',
            'email_organisasi'  => 'org@test.com',
            'tanggal_pengajuan' => now(),
            'status'            => 'Menunggu Verifikasi Verifikator 1',
        ]);

        $pemohon = Pemohon::create([
            'permohonan_id' => $permohonan->id,
            'nama_lengkap'  => 'Member One',
            'nik'           => '1234567890123456',
            'tempat_lahir'  => 'Jakarta',
            'tanggal_lahir' => '1990-01-01',
            'jenis_kelamin' => 'L',
            'email'         => 'member1@test.com',
            'status_verifikasi' => 'Pending',
        ]);

        $dokumen = DokumenPersyaratan::create([
            'pemohon_id'     => $pemohon->id,
            'permohonan_id'  => $permohonan->id,
            'persyaratan_id' => $masterDoc->id,
            'file_path'      => 'dokumen/ktp.pdf',
            'status_dokumen' => 'Pending',
        ]);

        // Verifikator 1 rejects the document
        $response = $this->actingAs($verifikator1)->post(route('verifikator1.permohonan.reject', $permohonan->id), [
            'dokumen' => [
                $dokumen->id => 'Tidak Valid',
            ],
            'keterangan_dokumen' => [
                $dokumen->id => 'Foto KTP buram',
            ],
            'catatan' => 'Mohon perbaiki KTP',
        ]);

        $response->assertRedirect(route('verifikator1.permohonan.index'));

        // Assert database updates
        $this->assertDatabaseHas('permohonans', [
            'id' => $permohonan->id,
            'status' => 'Menunggu Perbaikan Dokumen Verifikator 1',
            'status_verifikator1' => 'ditolak',
        ]);

        $this->assertDatabaseHas('pemohons', [
            'id' => $pemohon->id,
            'status_verifikasi' => 'Ditolak',
        ]);

        // Assert emails sent to BOTH org@test.com and member1@test.com
        Mail::assertSent(MemberRejectedMail::class, function ($mail) {
            return $mail->hasTo('org@test.com');
        });

        Mail::assertSent(MemberRejectedMail::class, function ($mail) {
            return $mail->hasTo('member1@test.com');
        });

        // Test applicant uploading fix document
        $file = UploadedFile::fake()->createWithContent('ktp_fix.pdf', "%PDF-1.4\n1 0 obj\n<<>>\nendobj\ntrailer\n<<>>\n%%EOF");
        $uploadResponse = $this->post(
            route('permohonan.store-dokumen-upload', [$permohonan->nomor_permohonan, $pemohon->id]),
            [
                'pemohon_id' => $pemohon->id,
                'dokumen'    => [
                    $masterDoc->id => $file,
                ],
            ]
        );

        $uploadResponse->assertSessionHasNoErrors();
        $uploadResponse->assertRedirect();

        // Status should now revert back to "Menunggu Verifikasi Verifikator 1"
        $this->assertDatabaseHas('permohonans', [
            'id' => $permohonan->id,
            'status' => 'Menunggu Verifikasi Verifikator 1',
        ]);

        // Verifikator 1 re-validates and approves
        $revalResponse = $this->actingAs($verifikator1)->post(route('verifikator1.permohonan.member-verifikasi', [$permohonan->id, $pemohon->id]), [
            'dokumen' => [
                $dokumen->id => 'Valid',
            ],
        ]);

        $revalResponse->assertRedirect(route('verifikator1.permohonan.show', $permohonan->id));

        // Permohonan status resets to Menunggu Verifikasi Verifikator 1 with pending status_verifikator1 so it can be approved
        $this->assertDatabaseHas('permohonans', [
            'id' => $permohonan->id,
            'status' => 'Menunggu Verifikasi Verifikator 1',
            'status_verifikator1' => 'pending',
        ]);
    }
}

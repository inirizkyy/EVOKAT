<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Pemohon;
use App\Models\Permohonan;
use App\Models\Organization;
use App\Models\MasterPersyaratan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PermohonanSubmitTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_submit_permohonan_with_optional_document_empty()
    {
        Storage::fake('public');

        // Create active organization
        $org = Organization::create([
            'nama_organisasi' => 'PERADI',
            'status' => 'Aktif',
        ]);

        // Ensure "Pas Foto Pemohon" requirement exists
        $pasFotoReq = MasterPersyaratan::where('nama_persyaratan', 'Pas Foto Pemohon')->first();
        if (!$pasFotoReq) {
            $pasFotoReq = MasterPersyaratan::create([
                'nama_persyaratan' => 'Pas Foto Pemohon',
                'deskripsi' => 'Pas Foto terbaru',
                'is_required' => true,
            ]);
        }

        // Create other test requirements
        $reqRequired = MasterPersyaratan::create([
            'nama_persyaratan' => 'Syarat Wajib',
            'deskripsi' => 'Deskripsi wajib',
            'is_required' => true,
        ]);
        
        $reqOptional = MasterPersyaratan::create([
            'nama_persyaratan' => 'Syarat Opsional',
            'deskripsi' => 'Deskripsi opsional',
            'is_required' => false,
        ]);

        // Step 1: Create Permohonan Draft with 1 Member
        $response = $this->post('/permohonan', [
            'organization_id' => $org->id,
            'nomor_sk' => 'SK/456/2026',
            'tanggal_sk' => '2026-05-05',
            'no_hp_organisasi' => '081234567890',
            'email_organisasi' => 'peradi@example.com',
            'members' => [
                [
                    'nik' => '1234567890123456',
                    'nama_lengkap' => 'Jane Doe',
                    'tempat_lahir' => 'Bandar Lampung',
                    'tanggal_lahir' => '1995-05-05',
                    'jenis_kelamin' => 'P',
                    'email' => 'janedoe@example.com',
                    'alamat' => 'Jl. Mawar No. 10, Bandar Lampung',
                ]
            ]
        ]);

        $response->assertSessionHasNoErrors();
        
        $permohonan = Permohonan::first();
        $this->assertNotNull($permohonan);
        $this->assertEquals('Draft', $permohonan->status);
        $response->assertRedirect('/permohonan/' . $permohonan->nomor_permohonan . '/dokumen');

        $pemohon = $permohonan->pemohons->first();
        $this->assertNotNull($pemohon);

        $fileWajib = UploadedFile::fake()->create('wajib.pdf', 100, 'application/pdf');
        $fileFoto = UploadedFile::fake()->image('pas_foto.jpg');

        // Step 2: Upload Documents for the member (Pas foto and required file present, Optional file empty)
        $responseUpload = $this->post("/permohonan/{$permohonan->nomor_permohonan}/dokumen/{$pemohon->id}", [
            'dokumen' => [
                $pasFotoReq->id => $fileFoto,
                $reqRequired->id => $fileWajib,
                $reqOptional->id => null, // Optional document left blank
            ]
        ]);

        $responseUpload->assertSessionHasNoErrors();
        $responseUpload->assertRedirect("/permohonan/{$permohonan->nomor_permohonan}/dokumen");

        // Step 3: Final submit
        $responseSubmit = $this->post("/permohonan/{$permohonan->nomor_permohonan}/submit");

        $responseSubmit->assertSessionHasNoErrors();
        $responseSubmit->assertRedirectContains('/tracking');
        $this->assertTrue(session()->has('success'));
        $this->assertTrue(session()->has('open_survey'));
        $this->assertTrue(session()->has('nomor_permohonan'));

        $permohonan->refresh();
        $this->assertEquals('Menunggu Verifikasi', $permohonan->status);
    }
}

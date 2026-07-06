<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Pemohon;
use App\Models\Permohonan;
use App\Models\OrganisasiAdvokat;
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

        // Create requirements
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

        $foto = UploadedFile::fake()->image('avatar.jpg');
        $fileWajib = UploadedFile::fake()->create('wajib.pdf', 100, 'application/pdf');

        // We simulate a multipart form submit where optional file is null/empty
        $response = $this->post('/permohonan', [
            'nik' => '1234567890123456',
            'nama_lengkap' => 'Jane Doe',
            'tempat_lahir' => 'Bandar Lampung',
            'tanggal_lahir' => '1995-05-05',
            'jenis_kelamin' => 'P',
            'alamat' => 'Jl. Test No. 2',
            'email' => 'janedoe@example.com',
            'no_hp' => '08123456789',
            'nama_organisasi' => 'PERADI',
            'nomor_sk' => 'SK/456/2026',
            'tanggal_sk' => '2026-05-05',
            'foto' => $foto,
            'dokumen' => [
                $reqRequired->id => $fileWajib,
                $reqOptional->id => null, // Optional document left blank
            ]
        ]);

        // Check if there are validation errors
        $response->assertSessionHasNoErrors();
        $response->assertRedirect('/tracking');
    }
}

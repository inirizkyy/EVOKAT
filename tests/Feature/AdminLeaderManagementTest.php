<?php

namespace Tests\Feature;

use App\Models\BukuRegistrasiAdvokat;
use App\Models\Leader;
use App\Models\Organization;
use App\Models\Pemohon;
use App\Models\Permohonan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminLeaderManagementTest extends TestCase
{
    use RefreshDatabase;

    private $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['role' => 'admin']);
    }

    public function test_admin_can_add_leader_manually()
    {
        $this->actingAs($this->admin)
            ->post(route('admin.leader.store'), [
                'name' => 'Dr. H. Pemimpin Sumpah, S.H., M.H.',
            ])
            ->assertRedirect(route('admin.leader.index'));

        $this->assertDatabaseHas('leaders', [
            'name' => 'Dr. H. Pemimpin Sumpah, S.H., M.H.',
        ]);
    }

    public function test_proposed_leader_is_auto_saved_when_buku_registrasi_is_updated()
    {
        $organisasi = Organization::create([
            'nama_organisasi' => 'PERADI',
            'alamat' => 'Jl. Test No. 1',
            'email' => 'peradi@test.com',
            'no_telp' => '021-123456',
        ]);

        $permohonan = Permohonan::create([
            'organization_id' => $organisasi->id,
            'nomor_permohonan' => 'REG/2026/001',
            'tanggal_pengajuan' => '2026-07-03',
            'status' => 'Verifikasi Berkas Fisik',
            'no_hp_organisasi' => '08123456789',
            'email_organisasi' => 'org@test.com',
        ]);

        $pemohon = Pemohon::create([
            'permohonan_id' => $permohonan->id,
            'nama_lengkap' => 'Ani Test',
            'nik' => '1234567890123456',
            'tempat_lahir' => 'Bandung',
            'tanggal_lahir' => '1995-05-15',
            'jenis_kelamin' => 'P',
            'email' => 'ani@test.com',
        ]);

        $bukuReg = BukuRegistrasiAdvokat::create([
            'permohonan_id' => $permohonan->id,
            'pemohon_id' => $pemohon->id,
        ]);

        $customLeaderName = 'Dr. H. Ketua Pengadilan Baru, S.H., M.H.';

        $response = $this->actingAs($this->admin)
            ->put(route('admin.buku-registrasi.update', $bukuReg->id), [
                'nomor_bas' => 'W9.U1/123/HK.06.2/7/2026',
                'tanggal_disumpah' => '2026-07-23',
                'ketua_pengadilan_tinggi' => $customLeaderName,
                'saksi_1' => 'Saksi Satu, S.H.',
                'saksi_2' => 'Saksi Dua, S.H.',
            ]);

        $response->assertRedirect(route('admin.buku-registrasi.show', $permohonan->id));

        $this->assertDatabaseHas('leaders', [
            'name' => $customLeaderName,
        ]);
    }
}

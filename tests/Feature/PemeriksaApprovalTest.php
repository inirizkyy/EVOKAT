<?php

namespace Tests\Feature;

use App\Models\BukuRegistrasiAdvokat;
use App\Models\Organization;
use App\Models\Pemohon;
use App\Models\Permohonan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PemeriksaApprovalTest extends TestCase
{
    use RefreshDatabase;

    private $admin;
    private $pemeriksa;
    private $permohonan;
    private $pemohon;
    private $bukuReg;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create(['role' => 'admin']);
        $this->pemeriksa = User::factory()->create(['role' => 'pemeriksa']);

        $organisasi = Organization::create([
            'nama_organisasi' => 'PERADI',
            'alamat' => 'Jl. Test No. 1',
            'email' => 'peradi@test.com',
            'no_telp' => '021-123456',
        ]);
        
        $this->permohonan = Permohonan::create([
            'organization_id' => $organisasi->id,
            'nomor_permohonan' => 'REG/2026/001',
            'tanggal_pengajuan' => '2026-07-03',
            'status' => 'Selesai',
            'nomor_sk' => 'SK/123/2026',
            'tanggal_sk' => '2026-01-01',
            'no_hp_organisasi' => '08123456789',
            'email_organisasi' => 'peradi@test.com',
        ]);

        $this->pemohon = Pemohon::create([
            'permohonan_id' => $this->permohonan->id,
            'organization_id' => $organisasi->id,
            'nama_lengkap' => 'Advokat Test',
            'nik' => '1234567890123456',
            'tempat_lahir' => 'Bandung',
            'tanggal_lahir' => '1990-01-01',
            'jenis_kelamin' => 'L',
            'email' => 'advokat@test.com',
            'no_hp' => '081234567890',
            'status_verifikasi' => 'Disetujui',
        ]);

        $this->bukuReg = BukuRegistrasiAdvokat::create([
            'pemohon_id' => $this->pemohon->id,
            'permohonan_id' => $this->permohonan->id,
            'nomor_bas' => 'W9.U1/123/HK.06.2/7/2026',
            'tanggal_disumpah' => '2026-07-16',
            'ketua_pengadilan_tinggi' => 'Ketua',
            'saksi' => 'Saksi 1;Saksi 2',
            'status_pemeriksa' => 'Pending',
        ]);
    }

    public function test_admin_and_pemeriksa_dashboards_are_role_protected()
    {
        // Admin trying to access Pemeriksa dashboard
        $this->actingAs($this->admin)
            ->get(route('pemeriksa.dashboard'))
            ->assertStatus(403);

        // Pemeriksa trying to access Admin dashboard
        $this->actingAs($this->pemeriksa)
            ->get(route('dashboard'))
            ->assertStatus(403);

        // Pemeriksa accessing Pemeriksa dashboard
        $this->actingAs($this->pemeriksa)
            ->get(route('pemeriksa.dashboard'))
            ->assertStatus(200);

        // Admin accessing Admin dashboard
        $this->actingAs($this->admin)
            ->get(route('dashboard'))
            ->assertStatus(200);
    }

    public function test_pemeriksa_can_approve_buku_registrasi()
    {
        $this->actingAs($this->pemeriksa)
            ->post(route('pemeriksa.buku-registrasi.approve', $this->bukuReg->id))
            ->assertStatus(302);

        $this->assertEquals('Disetujui', $this->bukuReg->fresh()->status_pemeriksa);
    }

    public function test_approved_buku_registrasi_is_locked_in_admin_panel()
    {
        // Approve first
        $this->bukuReg->update(['status_pemeriksa' => 'Disetujui']);

        // Admin tries to update
        $response = $this->actingAs($this->admin)
            ->put(route('admin.buku-registrasi.update', $this->bukuReg->id), [
                'nomor_bas' => 'NEW-BAS-NO',
                'tanggal_disumpah' => '2026-07-17',
                'ketua_pengadilan_tinggi' => 'Wakil',
                'saksi_1' => 'Saksi Baru 1',
                'saksi_2' => 'Saksi Baru 2',
            ]);

        $response->assertRedirect(route('admin.buku-registrasi.show', $this->permohonan->id));
        $response->assertSessionHas('error', 'Data ini sudah disetujui oleh Pemeriksa dan dikunci.');

        // Verify database did not change
        $this->assertEquals('W9.U1/123/HK.06.2/7/2026', $this->bukuReg->fresh()->nomor_bas);
    }

    public function test_pemeriksa_can_unlock_approved_buku_registrasi()
    {
        // Approve first
        $this->bukuReg->update(['status_pemeriksa' => 'Disetujui']);

        // Pemeriksa tries to unlock
        $response = $this->actingAs($this->pemeriksa)
            ->post(route('pemeriksa.buku-registrasi.unlock', $this->bukuReg->id));

        $response->assertStatus(302);
        
        // Verify database status is back to Pending
        $this->assertEquals('Pending', $this->bukuReg->fresh()->status_pemeriksa);
    }

    public function test_admin_cannot_unlock_approved_buku_registrasi()
    {
        // Approve first
        $this->bukuReg->update(['status_pemeriksa' => 'Disetujui']);

        // Admin tries to unlock (should get 403)
        $response = $this->actingAs($this->admin)
            ->post(route('pemeriksa.buku-registrasi.unlock', $this->bukuReg->id));

        $response->assertStatus(403);
    }
}

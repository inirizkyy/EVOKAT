<?php

namespace Tests\Feature;

use App\Models\Signatory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminSignatoryManagementTest extends TestCase
{
    use RefreshDatabase;

    private $admin;
    private $pemeriksa;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create(['role' => 'admin']);
        $this->pemeriksa = User::factory()->create(['role' => 'pemeriksa']);
    }

    public function test_non_admin_cannot_access_signatory_management()
    {
        // Guest
        $this->get(route('admin.signatory.index'))->assertRedirect(route('login'));

        // Pemeriksa
        $this->actingAs($this->pemeriksa)
            ->get(route('admin.signatory.index'))
            ->assertStatus(403);
    }

    public function test_admin_can_view_signatories_list()
    {
        Signatory::create(['name' => 'Dr. H. Herri Swantoro, S.H., M.H.']);

        $this->actingAs($this->admin)
            ->get(route('admin.signatory.index'))
            ->assertStatus(200)
            ->assertSee('Dr. H. Herri Swantoro, S.H., M.H.');
    }

    public function test_admin_can_add_signatory()
    {
        $this->actingAs($this->admin)
            ->post(route('admin.signatory.store'), [
                'name' => 'Dr. H. Herri Swantoro, S.H., M.H. Baru',
            ])
            ->assertRedirect(route('admin.signatory.index'));

        $this->assertDatabaseHas('signatories', [
            'name' => 'Dr. H. Herri Swantoro, S.H., M.H. Baru',
        ]);
    }

    public function test_admin_cannot_add_duplicate_signatory()
    {
        Signatory::create(['name' => 'Dr. H. Herri Swantoro, S.H., M.H.']);

        $response = $this->actingAs($this->admin)
            ->post(route('admin.signatory.store'), [
                'name' => 'Dr. H. Herri Swantoro, S.H., M.H.',
            ]);

        $response->assertSessionHasErrors('name');
    }

    public function test_admin_can_delete_signatory()
    {
        $signer = Signatory::create(['name' => 'Signer Sementara']);

        $this->actingAs($this->admin)
            ->delete(route('admin.signatory.destroy', $signer->id))
            ->assertRedirect(route('admin.signatory.index'));

        $this->assertDatabaseMissing('signatories', [
            'id' => $signer->id,
        ]);
    }
}

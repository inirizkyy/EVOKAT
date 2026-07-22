<?php

namespace Tests\Feature;

use App\Models\Room;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminRoomManagementTest extends TestCase
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

    public function test_non_admin_cannot_access_room_management()
    {
        // Guest
        $this->get(route('admin.room.index'))->assertRedirect(route('login'));

        // Pemeriksa
        $this->actingAs($this->pemeriksa)
            ->get(route('admin.room.index'))
            ->assertStatus(403);
    }

    public function test_admin_can_view_rooms_list()
    {
        Room::create(['name' => 'Ruang Sidang Utama']);

        $this->actingAs($this->admin)
            ->get(route('admin.room.index'))
            ->assertStatus(200)
            ->assertSee('Ruang Sidang Utama');
    }

    public function test_admin_can_add_room()
    {
        $this->actingAs($this->admin)
            ->post(route('admin.room.store'), [
                'name' => 'Ruang Sidang Cakra Baru',
            ])
            ->assertRedirect(route('admin.room.index'));

        $this->assertDatabaseHas('rooms', [
            'name' => 'Ruang Sidang Cakra Baru',
        ]);
    }

    public function test_admin_cannot_add_duplicate_room()
    {
        Room::create(['name' => 'Ruang Sidang Utama']);

        $response = $this->actingAs($this->admin)
            ->post(route('admin.room.store'), [
                'name' => 'Ruang Sidang Utama',
            ]);

        $response->assertSessionHasErrors('name');
    }

    public function test_admin_can_delete_room()
    {
        $room = Room::create(['name' => 'Ruang Sidang Sementara']);

        $this->actingAs($this->admin)
            ->delete(route('admin.room.destroy', $room->id))
            ->assertRedirect(route('admin.room.index'));

        $this->assertDatabaseMissing('rooms', [
            'id' => $room->id,
        ]);
    }
}

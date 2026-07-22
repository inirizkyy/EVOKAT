<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class VerifikatorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Verifikator 1',
                'email' => 'verifikator1@evokat.id',
                'password' => Hash::make('password'),
                'role' => 'verifikator1',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Verifikator 2',
                'email' => 'verifikator2@evokat.id',
                'password' => Hash::make('password'),
                'role' => 'verifikator2',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Verifikator 3',
                'email' => 'verifikator3@evokat.id',
                'password' => Hash::make('password'),
                'role' => 'verifikator3',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Verifikator 4',
                'email' => 'verifikator4@evokat.id',
                'password' => Hash::make('password'),
                'role' => 'verifikator4',
                'email_verified_at' => now(),
            ],
        ];

        foreach ($users as $user) {
            User::updateOrCreate(
                ['email' => $user['email']],
                $user
            );
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PegawaiUserSeeder extends Seeder
{
    public function run(): void
    {
        $pegawai = User::updateOrCreate(
            [
                'email' => 'pegawai@kms.local',
            ],
            [
                'name' => 'Pegawai User',
                'password' => Hash::make('pegawai123'),
            ]
        );

        $pegawai->assignRole('Pegawai');
    }
}

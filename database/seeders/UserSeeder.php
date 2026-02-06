<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Usuario administrador
        User::factory()
            ->count(1)
            ->withProfilePhoto()
            ->create([
                'email' => 'admin@admin.com',
                'name'  => 'Administrador',
            ])
            ->each(function ($user) {
                $user->roles()->attach(1);
            });
    }
}

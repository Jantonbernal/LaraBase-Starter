<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()
            ->count(1)
            ->withProfilePhoto()
            ->create()
            ->each(function ($user) {
                $user->roles()->attach(1);
            });

        // Usuarios normales
        User::factory()
            ->count(5)
            ->withProfilePhoto()
            ->create()
            ->each(function ($user) {
                $user->roles()->attach(2);
            });
    }
}

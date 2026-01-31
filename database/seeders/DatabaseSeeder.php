<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            CompanySeeder::class,       // Crea las companies
            PermissionSeeder::class,    // Crea los permisos
            RoleSeeder::class,          // Crea los roles
            UserSeeder::class,          // Crea los usuarios (depende de roles y permisos)
            MenuSeeder::class,          // Crea los menús depende de permisos
        ]);
    }
}

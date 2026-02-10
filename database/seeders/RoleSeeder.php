<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Rol de administrador
        $role = Role::create([
            'name' => 'Administrador',
        ]);

        $permissionsCount = Permission::all()->count();
        $collection = collect([]);

        for ($i = 1; $i <= $permissionsCount; $i++) {
            $collection->push($i);
        }

        $role->permissions()->attach($collection);
    }
}

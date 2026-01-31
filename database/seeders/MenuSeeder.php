<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $menus = [
            ['menu' => 'Seguridad', 'hierarchy' => 1, 'parent' => null, 'permission_id' => null, 'icon' => 'mdi mdi-fingerprint'],
            ['menu' => 'Logs', 'hierarchy' => 2, 'parent' => 1, 'permission_id' => 2, 'icon' => 'mdi mdi-math-log'],
            ['menu' => 'Permisos', 'hierarchy' => 2, 'parent' => 1, 'permission_id' => 3, 'icon' => 'mdi mdi-key-chain'],
            ['menu' => 'Roles', 'hierarchy' => 2, 'parent' => 1, 'permission_id' => 7, 'icon' => 'mdi mdi-lock-check'],
            ['menu' => 'Usuarios', 'hierarchy' => 2, 'parent' => 1, 'permission_id' => 12, 'icon' => 'mdi mdi-account'],

            ['menu' => 'Configuracion', 'hierarchy' => 1, 'parent' => null, 'permission_id' => null, 'icon' => 'mdi mdi-cog'],
            ['menu' => 'Menu', 'hierarchy' => 2, 'parent' => 6, 'permission_id' => 20, 'icon' => 'mdi mdi-menu-open'],
            ['menu' => 'Empresa', 'hierarchy' => 2, 'parent' => 6, 'permission_id' => 25, 'icon' => 'mdi mdi-office-building'],
        ];

        collect($menus)->each(function ($menu) {
            Menu::create($menu);
        });
    }
}

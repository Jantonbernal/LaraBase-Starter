<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            ['name' => 'Home', 'slug' => 'Home'],

            ['name' => 'Navegar Logs', 'slug' => 'log.listar'],

            ['name' => 'Navegar Permisos', 'slug' => 'permiso.listar'],
            ['name' => 'Ver Permiso', 'slug' => 'permiso.ver'],
            ['name' => 'Crear Permiso', 'slug' => 'permiso.crear'],
            ['name' => 'Editar Permiso', 'slug' => 'permiso.editar'],
            ['name' => 'Eliminar Permiso', 'slug' => 'permiso.eliminar'],

            ['name' => 'Navegar Roles', 'slug' => 'rol.listar'],
            ['name' => 'Ver Rol', 'slug' => 'rol.ver'],
            ['name' => 'Crear Rol', 'slug' => 'rol.crear'],
            ['name' => 'Editar Rol', 'slug' => 'rol.editar'],
            ['name' => 'Eliminar Rol', 'slug' => 'rol.eliminar'],

            ['name' => 'Navegar Usuarios', 'slug' => 'usuario.listar'],
            ['name' => 'Crear Usuario', 'slug' => 'usuario.crear'],
            ['name' => 'Editar Usuario', 'slug' => 'usuario.editar'],
            ['name' => 'Ver Usuario', 'slug' => 'usuario.ver'],
            ['name' => 'Activar Usuario', 'slug' => 'usuario.activar'],
            ['name' => 'Desactivar Usuario', 'slug' => 'usuario.desactivar'],
            ['name' => 'Permiso Usuario', 'slug' => 'usuario.permiso'],
            ['name' => 'Perfil Usuario', 'slug' => 'usuario.perfil'],

            ['name' => 'Navegar Menus', 'slug' => 'menu.listar'],
            ['name' => 'Crear Menu', 'slug' => 'menu.crear'],
            ['name' => 'Editar Menu', 'slug' => 'menu.editar'],
            ['name' => 'Ver Menu', 'slug' => 'menu.ver'],
            ['name' => 'Ver SubMenu', 'slug' => 'sub.menu.ver'],

            ['name' => 'Navegar Compañia', 'slug' => 'compania.listar'],
            ['name' => 'Administrar Compañia', 'slug' => 'compania.createOrUpdate'],
        ];

        collect($permissions)->each(function ($permission) {
            Permission::create($permission);
        });
    }
}

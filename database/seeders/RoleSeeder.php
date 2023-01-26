<?php

namespace Wepa\Core\Database\seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Wepa\Core\Models\Permission;
use Wepa\Core\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            [
                'name' => 'admin',
                'es' => [
                    'description' => 'Administrador del sistema',
                ],
                'en' => [
                    'description' => 'System administrator',
                ],
            ],
            [
                'name' => 'user',
                'es' => [
                    'description' => 'Usuario',
                ],
                'en' => [
                    'description' => 'User',
                ],
            ],
            [
                'name' => 'traductor',
                'es' => [
                    'description' => 'Traductor',
                ],
                'en' => [
                    'description' => 'Translator',
                ],
            ],
            [
                'name' => 'seo',
                'es' => [
                    'description' => 'SEO',
                ],
                'en' => [
                    'description' => 'SEO',
                ],
            ],
            [
                'name' => 'super.admin',
                'es' => [
                    'description' => 'Tareas del administrador',
                ],
                'en' => [
                    'description' => 'Administrator task',
                ],
            ],
        ];

        foreach ($roles as $role) {
            Role::create(array_merge($role, ['guard_name' => 'web']));
        }

        $permissions = [
            // Users permissions
            [
                'name' => 'admin.users.index',
                'es' => [
                    'description' => 'Ver usuarios',
                ],
                'en' => [
                    'description' => 'View users',
                ],
            ],
            [
                'name' => 'admin.users.create',
                'es' => [
                    'description' => 'Crear usuarios',
                ],
                'en' => [
                    'description' => 'Create users',
                ],
            ],
            [
                'name' => 'admin.users.edit',
                'es' => [
                    'description' => 'Editar usuarios',
                ],
                'en' => [
                    'description' => 'Edit users',
                ],
            ],
            [
                'name' => 'admin.users.destroy',
                'es' => [
                    'description' => 'Eliminar usuarios',
                ],
                'en' => [
                    'description' => 'Delete users',
                ],
            ],

            // Roles permissions
            [
                'name' => 'admin.roles',
                'es' => [
                    'description' => 'Administrar roles y permisos',
                ],
                'en' => [
                    'description' => 'Roles and permissions administration',
                ],
            ],

            // Tranlator user action
            [
                'name' => 'admin.translations',
                'es' => [
                    'description' => 'Traducciones',
                ],
                'en' => [
                    'description' => 'Translations',
                ],
            ],

            // Auth user action
            [
                'name' => 'admin.auth',
                'es' => [
                    'description' => 'Usuarios autenticados',
                ],
                'en' => [
                    'description' => 'Authenticated users',
                ],
            ],
        ];

        foreach ($permissions as $permission) {
            Permission::create(array_merge($permission, ['guard_name' => 'web']));
        }

        // Asign super admin role to super admin user
        $superAdmin = User::first();
        $superAdmin->assignRole('super.admin');

        // Asign permissions to roles
        /* @var Role $role */

        // Admin role
        $role = Role::where(['name' => 'admin'])->first();
        $allPermissions = Permission::select(['name'])->get()->toArray();
        $role->syncPermissions($allPermissions);

        // User role
        $role = Role::where(['name' => 'user'])->first();
        $authPermissions = Permission::select(['name'])->where(['name' => 'admin.auth'])->pluck('name')->toArray();
        $role->syncPermissions($authPermissions);

        // Translator Role
        $role = Role::where(['name' => 'traductor'])->first();
        $tralationsPermissions = Permission::select(['name'])->where(['name' => 'admin.translations'])->pluck('name')->toArray();
        $role->syncPermissions(array_merge($tralationsPermissions, $authPermissions));
    }
}

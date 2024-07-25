<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create Admin user if not already exists
        $admin = User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Admin',
                'password' => bcrypt('password'),
                'profile' => 'user.avif'
            ]
        );

        // Create Regular user if not already exists
        $writer = User::firstOrCreate(
            ['email' => 'user@gmail.com'],
            [
                'name' => 'User',
                'password' => bcrypt('password')
            ]
        );

        // Create roles if not already exists
        $admin_role = Role::firstOrCreate(['name' => 'admin']);
        $writer_role = Role::firstOrCreate(['name' => 'user']);

        // Define permissions (create if not already exists)
        $permissions = [
            'Post access', 'Post edit', 'Post create', 'Post delete',
            'Role access', 'Role edit', 'Role create', 'Role delete',
            'User access', 'User edit', 'User create', 'User delete',
            'Permission access', 'Permission edit', 'Permission create', 'Permission delete',
            'Mail access', 'Mail edit'
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Assign roles and permissions to admin
        $admin->assignRole($admin_role);
        $admin_role->givePermissionTo(Permission::all());

        // Assign role to writer
        $writer->assignRole($writer_role);
    }
}

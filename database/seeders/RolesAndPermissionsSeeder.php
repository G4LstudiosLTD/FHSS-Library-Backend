<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Create roles
        $superadminRole = Role::create(['name' => 'superadmin']);
        $adminRole = Role::create(['name' => 'admin']);
        $teacherRole = Role::create(['name' => 'teacher']);
        $studentRole = Role::create(['name' => 'student']);

        // Create permissions
        $createSchoolPermission = Permission::create(['name' => 'create-school']);
        // Add more permissions as needed

        // Assign permissions to roles
        $superadminRole->givePermissionTo($createSchoolPermission);
        // Assign more permissions to roles as needed
    }
}

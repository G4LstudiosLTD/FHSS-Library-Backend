<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Spatie\Permission\Models\Role;

class AssignRoleCommand extends Command
{
    protected $signature = 'assign-role';

    protected $description = 'Assign a role to a user manually';

    public function handle()
    {
        $userId = $this->ask('Enter user ID:');
        $roleId = $this->ask('Enter role ID or name:');

        $user = User::find($userId);
        $role = Role::where('id', $roleId)->orWhere('name', $roleId)->first();

        if ($user && $role) {
            $user->assignRole($role);
            $this->info('Role assigned successfully.');
        } else {
            $this->error('User or role not found.');
        }
    }
}

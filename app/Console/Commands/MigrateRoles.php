<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Role;
use App\Models\User;
use Spatie\Permission\Models\Role as SpatieRole;

class MigrateRoles extends Command
{
    protected $signature = 'migrate:roles';
    protected $description = 'Migrate existing roles and assign to users';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // Создание ролей
        $roles = ['ADMIN', 'OPERATOR', 'ANALYST'];
        foreach ($roles as $role) {
            SpatieRole::findOrCreate($role);
        }

        // Перенос существующих ролей пользователей
        $users = User::with('roles')->get();
        foreach ($users as $user) {
            foreach ($user->roles as $role) {
                $user->assignRole($role->name);
            }
        }

        $this->info('Roles migrated successfully.');
    }
}

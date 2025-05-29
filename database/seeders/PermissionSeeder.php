<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */use WithoutModelEvents;
    public function run(): void
    {
        Permission::firstOrCreate(['name' => 'get.roles', 'guard_name' => 'api']);
        Permission::firstOrCreate(['name' => 'post.roles', 'guard_name' => 'api']);
        Permission::firstOrCreate(['name' => 'put.roles', 'guard_name' => 'api']);
        Permission::firstOrCreate(['name' => 'delete.roles', 'guard_name' => 'api']);
        Permission::firstOrCreate(['name' => 'sync.permissions', 'guard_name' => 'api']);
        Permission::firstOrCreate(['name' => 'get.permissions', 'guard_name' => 'api']);
        Permission::firstOrCreate(['name' => 'post.permissions', 'guard_name' => 'api']);
        Permission::firstOrCreate(['name' => 'put.permissions', 'guard_name' => 'api']);
        Permission::firstOrCreate(['name' => 'delete.permissions', 'guard_name' => 'api']);
        Permission::firstOrCreate(['name' => 'assign.role', 'guard_name' => 'api']);
        Permission::firstOrCreate(['name' => 'assign.permission', 'guard_name' => 'api']);
        Permission::firstOrCreate(['name' => 'revoke.permission', 'guard_name' => 'api']);

        $user = User::find(1);
        $user->givePermissionTo('get.roles');
        $user->givePermissionTo('post.roles');
        $user->givePermissionTo('put.roles');
        $user->givePermissionTo('delete.roles');

        $user->givePermissionTo('get.permissions');
        $user->givePermissionTo('post.permissions');
        $user->givePermissionTo('put.permissions');
        $user->givePermissionTo('delete.permissions');
        $user->givePermissionTo('assign.role');
        $user->givePermissionTo('assign.permission');
        $user->givePermissionTo('revoke.permission');
    }
}

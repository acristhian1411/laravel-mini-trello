<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\User;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */use WithoutModelEvents;
    public function run(): void
    {
        Permission::firstOrCreate(['name' => 'board.index', 'guard_name' => 'api']);
        Permission::firstOrCreate(['name' => 'board.create', 'guard_name' => 'api']);
        Permission::firstOrCreate(['name' => 'board.show', 'guard_name' => 'api']);
        Permission::firstOrCreate(['name' => 'board.edit', 'guard_name' => 'api']);
        Permission::firstOrCreate(['name' => 'board.delete', 'guard_name' => 'api']);

        Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'api']);
        $role = Role::where('name', 'admin')->first();
        $role->givePermissionTo('board.index');
        $role->givePermissionTo('board.create');
        $role->givePermissionTo('board.show');
        $role->givePermissionTo('board.edit');
        $role->givePermissionTo('board.delete');

        $user = User::find(1);
        $user->assignRole('admin');
    }
}

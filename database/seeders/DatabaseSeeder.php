<?php

namespace Database\Seeders;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;



class DatabaseSeeder extends Seeder
{
    public function run()
    {

        Permission::create(['name' => 'manage products']);
        $role = Role::findByName('Admin');
        $role->givePermissionTo('manage products');

        $user = \App\Models\User::find(5);
        $user->assignRole('Admin');
    }
}

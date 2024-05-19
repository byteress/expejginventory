<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Str;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $user = new User();
        $user->id = Str::uuid()->toString();
        $user->first_name = 'Admin';
        $user->last_name = 'Admin';
        $user->email = 'admin@mailinator.com';
        $user->password = Hash::make('password');

        $user->save();

        Role::create(['name' => 'admin']);
        Role::create(['name' => 'inventory_head']);
        Role::create(['name' => 'sales_rep']);
        Role::create(['name' => 'cashier']);
        Role::create(['name' => 'delivery']);

        $user->assignRole('admin');
    }
}

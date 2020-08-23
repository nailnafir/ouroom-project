<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Model\User\User;

class RoleTableSeeder extends Seeder{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(){
        // Get First User at first time 
        $user = User::all()->first();
        if ($user != null) {
            // Reset cached roles and permissions
            app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
            // Create permissions
            $this->createPermission();
            // create roles and assign created permissions
            $role_creator   = Role::create(['name' => 'Creator']);
            $role_admin     = Role::create(['name' => 'Administrator']);
            $role_guru      = Role::create(['name' => 'Guru']);
            $role_siswa     = Role::create(['name' => 'Siswa']);
            // Assigning User to ROLE first time
            $user->assignRole('Creator');
            $user->assignRole('Administrator');

            // Assign All Permission to creator role
            $role_creator->givePermissionTo(Permission::all());
            $role_admin->givePermissionTo(Permission::all());

            // Assign Default Permission to Teacher
            $role_guru->givePermissionTo('index home');
            $role_guru->givePermissionTo('index profile');
            $role_guru->givePermissionTo('update profile');
            $role_guru->givePermissionTo('change password');
            $role_guru->givePermissionTo('index class');
            $role_guru->givePermissionTo('view class');
            $role_guru->givePermissionTo('create class');
            $role_guru->givePermissionTo('update class');
            $role_guru->givePermissionTo('delete class');

            // Assign Default Permission to Siswa
            $role_siswa->givePermissionTo('index home');
            $role_siswa->givePermissionTo('index profile');
            $role_siswa->givePermissionTo('update profile');
            $role_siswa->givePermissionTo('change password');
            $role_siswa->givePermissionTo('index class');
            $role_siswa->givePermissionTo('view class');
        }
    }

    /**
     * 
     */
    private function createPermission(){
        // ------ Home ----- 
        Permission::create(['name' => 'index home']);

        // --------- User ---------------------
        Permission::create(['name' => 'index user']);
        Permission::create(['name' => 'view user']);
        Permission::create(['name' => 'create user']);
        Permission::create(['name' => 'update user']);
        Permission::create(['name' => 'delete user']);
        Permission::create(['name' => 'change password']);

        // --------- Class ---------------------
        Permission::create(['name' => 'index class']);
        Permission::create(['name' => 'view class']);
        Permission::create(['name' => 'create class']);
        Permission::create(['name' => 'update class']);
        Permission::create(['name' => 'delete class']);

        // --------- Siswa ---------------------
        Permission::create(['name' => 'index siswa']);
        Permission::create(['name' => 'view siswa']);
        Permission::create(['name' => 'create siswa']);
        Permission::create(['name' => 'update siswa']);
        Permission::create(['name' => 'delete siswa']);

        // --------- Role ---------------------
        Permission::create(['name' => 'index role']);
        Permission::create(['name' => 'update role']);

        // --------- Profile ---------------------
        Permission::create(['name' => 'index profile']);
        Permission::create(['name' => 'update profile']);

        // ----------- Notification -----------------
        Permission::create(['name' => 'index notification']);
        Permission::create(['name' => 'create notification']);
    }
}

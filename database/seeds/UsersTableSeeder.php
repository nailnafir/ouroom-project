<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tbl_user')->insert([
            'username' => 'creator',
            'email' => 'creator@gmail.com',
            'password' => Hash::make('creatoratm'),
            'full_name' => 'Super Admin',
            'status' => 10,
            'account_type' => 'Creator'
        ]);
        DB::table('tbl_user')->insert([
            'username' => 'guru1',
            'email' => 'guru@gmail.com',
            'password' => Hash::make('guruatm'),
            'full_name' => 'Rifa al-Afghani',
            'status' => 10,
            'account_type' => 'Guru'
        ]);
        DB::table('tbl_user')->insert([
            'username' => 'guru2',
            'email' => 'g@gmail.com',
            'password' => Hash::make('guruatm'),
            'full_name' => 'Yayang Kurnia',
            'status' => 10,
            'account_type' => 'Guru'
        ]);
    }
}
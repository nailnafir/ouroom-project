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
    }
}
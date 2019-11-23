<?php

use Illuminate\Database\Seeder;

class AdminsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->truncate();
        DB::table('users')->insert([
            [
                'name' => 'Admin',
                'email' => 'test@admin',
                'password' => bitcastle_hash('123123'),
                'active' => 1,
                'activation_token' => '',
                'avatar' => 'avatar.png',
                'role_id' => \App\Consts::$ROLE_ADMIN,
            ]
        ]);
    }
}

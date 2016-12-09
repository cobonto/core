<?php

use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('admins')->insert([
                [
                    'firstname'=>'Admin',
                    'lastname'=>'Admin',
                    'email' =>'demo@cobonto.com',
                    'active' =>'1',
                    'lang'=>'en',
                    'password' =>Hash::make('demodemo'),
                    'role_id'=>'1',
                    'created_at' =>date('YYYY-MM-DD H:i:s'),
                    'updated_at' =>date('YYYY-MM-DD H:i:s'),
                ],
            ]
        );
    }
}
